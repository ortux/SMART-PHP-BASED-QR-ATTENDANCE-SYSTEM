<?php
include("../conn/conn.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["qr_code"])) {
        $qrcode = $_POST['qr_code'];
        $selectStmt = $conn->prepare('SELECT tbl_student_id FROM tbl_students WHERE genrated_code = :generated_code');
        $selectStmt->bindParam(':generated_code', $qrcode, PDO::PARAM_STR);
        if( $selectStmt->execute()) {
            $result = $selectStmt->fetch();
            if ($result !== false) {
                $student_id = $result["tbl_student_id"];
                $time = date("Y-m-d H:i:s");

                //checking for the data entry interva to prevnt duplication of data 

                $checkStmt = $conn->prepare("SELECT time_in FROM tbl_attendance WHERE tbl_student_id = :tbl_student_id ORDER BY time_in DESC LIMIT 1");
                $checkStmt->bindParam(":tbl_student_id", $student_id, PDO::PARAM_INT);

                if( $checkStmt->execute()) {
                    $last = $checkStmt->fetch();
                    if ($last !== false) {
                        $lasttimein = strtotime($last["time_in"]);
                        $currenttime = strtotime($time);
                        $diff = $currenttime - $lasttimein;
                        if ($diff < 86400) {
                            echo "<script>alert('Attendance Recorded Once');window.location.href = '../dash/dash.php'</script>";
                            exit();
                        }
                    }else{
                        echo "Failed To Execute";
                    }
                    try{
                        $stmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_student_id,time_in) VALUES (:tbl_student_id, :time_in)");
                        $stmt->bindParam(":tbl_student_id", $student_id, PDO::PARAM_INT);
                        $stmt->bindParam(":time_in", $time, PDO::PARAM_STR);

                        $stmt -> execute();
                        header("Location: ../dash/dash.php");
                        exit();
                    }catch (PDOException $e) {
                        echo "Error". $e->getMessage();
                    }
                }else{
                    echo "No Student Found";
                }
            }else{
                echo "Failed With Unexpexted Error";
            }
        }else{
            echo "<script>alrt('Unexpexted Error');window.location.href = '../dash/dash.php'</script>";
        }
    }

}
?>