<?php
include("../../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_name'], $_POST['student_id'], $_POST['class'], $_POST['section'], $_POST['roll'])) {
        
        // Retrieve student data from the form
        $student_name = $_POST['student_name'];
        $student_id = $_POST['student_id'];
        $generated_code = $student_id;  // Fix: consistency in variable names
        $class = $_POST['class'];
        $section = $_POST['section'];
        $roll = $_POST['roll'];

        // Student image upload handling
        if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == 0) {
            $target_dir = "images/";
            $custom_name = $student_id;
            $file_extension = strtolower(pathinfo($_FILES["student_photo"]['name'], PATHINFO_EXTENSION));
            $file_name = $custom_name . ".png" ;//. $file_extension;
            $target_file = $target_dir . $file_name;

            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Validate file type (image only)
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_extension, $allowed_extensions)) {
                die("Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.");
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["student_photo"]["tmp_name"], $target_file)) {
                echo "Image uploaded successfully.<br>";
            } else {
                die("Failed to upload image.");
            }
        } else {
            die("No file uploaded or an error occurred during file upload.");
        }

        // QR code generation and saving
        $qr_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($generated_code);
        $custom_qr_name = $student_id . ".png";
        $save_directory = './qrcodes/';

        if (!file_exists($save_directory)) {
            mkdir($save_directory, 0777, true);
        }

        $image_data = file_get_contents($qr_url);
        if ($image_data !== false) {
            $file_path = $save_directory . $custom_qr_name;
            if (file_put_contents($file_path, $image_data) === false) {
                die('Failed to save QR code.');
            } else {
                echo "QR code generated and saved.<br>";
            }
        } else {
            die('Failed to generate QR code from API.');
        }

        // Insert student data into the database
        $sql = "INSERT INTO tbl_students (student_name, student_id, genrated_code, class, section, roll)
                VALUES ('$student_name', '$student_id', '$generated_code', '$class', '$section', '$roll')";

        if ($conn->query($sql) === TRUE) {
            header("Location: ../manage_students.php");
            exit();  // Always exit after header redirection
        } else {
            die("Error: ");
        }
    } else {
        die("Required fields are missing.");
    }
}
?>
