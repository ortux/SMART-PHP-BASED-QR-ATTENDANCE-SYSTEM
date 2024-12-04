<?php
$student_id = base64_decode($_GET['id']);
include('../../conn/db_config.php');


// SQL query to fetch class and section
$sql = "SELECT student_name, class, section, roll FROM tbl_students WHERE student_id = '$student_id'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Fetch the data
    $row = $result->fetch_assoc();
    $name = isset($row['student_name']) ? $row['student_name'] : 'N/A';
    $class = isset($row['class']) ? $row['class'] : 'N/A';  // Default to 'N/A' if class is not set
    $section = isset($row['section']) ? $row['section'] : 'N/A';
    $roll = isset($row['roll']) ? $row['roll'] : 'N/A'; // Default to 'N/A' if section is not set

    // Display class and section
} else {
    // If no result found, output a message or handle accordingly
    echo "User not found or error in query.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDENT ID CARD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div>
        <div id="idCard" class="w-80 bg-white shadow-lg rounded-lg overflow-hidden border border-gray-300">
            <!--header wil logo -->
            <div class="bg-blue-600 p-4 flex items-center">
                <img src="assest/logo.jpg" alt="Logo" class="w-12 h-12 rounded-full mr-3">   
                <div class="text-white">
                    <h1 class="text-lg font-bold">Vidyasagar Shishu Niketan</h1>
                    <p class="text-sm justify-center items-center">STUDENT ID CARD</p>
                </div>             
            </div>
            <div class="p-4">
                <div class="flex justify-center mb-4">
                    <img src="images/<?=$student_id?>.png" alt="Student" class="w-24 h-24 rounded-full shadow-md">
                </div>
                <div class="text-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800"><?=$name?></h2>
                    <p class="text-sm font-semibold text-gray-800">Grade: <?=$class?></p>
                    <p class="text-sm font-semibold text-gray-800">Section: <?=$section?></p>
                    <p class="text-sm font-semibold text-gray-800">Roll No: <?=$roll?></p>
                </div>
                <div class="flex justify-center">
                    <img src="qrcodes/<?=$student_id?>.png" alt="QR Code" class="w-24 h-24">
                </div>
            </div>
            <div class="bg-gray-200 p-2 text-center">
                <p class="text-s text-gray-600">VALID FOR ACADEMIC YEAR 2024-2025</p>
            </div>
        </div>
        <div class="mt-4 text-center">
            <button id="downloadButton" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                DOWNLOAD ID CARD
            </button>
            <button id="returnHome" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-blue-700">
                GO BACK
            </button>
        </div>
    </div>
    <script>
        document.getElementById('returnHome').addEventListener('click',function(){
            location.replace("../manage_students.php");
        });
        document.getElementById('downloadButton').addEventListener('click', function () {
            const idCard = document.getElementById('idCard');
            html2canvas(idCard).then((canvas) => {
                const link = document.createElement('a');
                link.download = '<?=$name?>_id_card.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        });
    </script>
    <!--programme complete -->
    
</body>
</html>