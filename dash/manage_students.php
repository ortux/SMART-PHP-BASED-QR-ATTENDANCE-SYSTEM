<?php
include("../conn/conn.php");
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    header('Location: ../auth/index.php');
    exit;
}
?>
<?php
include('../conn/db_config.php');

// Assuming you have a session or other means of getting the username
$username = $_COOKIE['username']; // Make sure $username is set before using it

// SQL query to fetch class and section
$sql = "SELECT class, section FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Fetch the data
    $row = $result->fetch_assoc();
    $class = isset($row['class']) ? $row['class'] : 'N/A';  // Default to 'N/A' if class is not set
    $section = isset($row['section']) ? $row['section'] : 'N/A'; // Default to 'N/A' if section is not set

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
    <title>MANAGE STUDENTS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css">
</head>

<body style="background-image: url('../auth/images/bg-01.jpg');">
    <nav class="bg-gray-800 p-4">
        <div class="flex items-center justify-between">
            <a href="dash.php" class="text-white text-lg font-bold">QR BASED ATTENDANCE SYSTEM</a>
            <!-- navbar toggler for mobile (intially hidden) -->
            <div class="lg:hidden flex items-center">
                <button id="navbar-toggle" class="text-white focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currenColor" viewbox="0 0 24 24"
                        xmlns="http://w3.org/200/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M412h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!--nav for pc-->
            <ul class="hidden lg:flex space-x-4 text-white">
                <li><a href="dash.php" class="hover:underline">Home</a></li>
                <li><a href="#" class="hover:underline">Print Attendance</a></li>
                <li><a href="manage_students.php" class="hover:underline">Add Student</a></li>
                <li><a href="../auth/logout.php" class="hover:underline">Logout</a></li>
            </ul>

            <!--navbar for mobile -->
            <ul id="navbar-links" class="lg:hidden bg-gray-800 p-4 space-y-4 text-white hidden">
                <li><a href="dash.php" class="hover:underline">Home</a></li>
                <li><a href="#" class="hover:underline">Print Attendance</a></li>
                <li><a href="manage_students.php" class="hover:underline">Add Student</a></li>
                <li><a href="../auth/logout.php" class="hover:underline">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-5xl">
            <div class="flex space-x-8">
                <div class="flex-grow">
                    <h4 class="text-l font-semibold mb-0">Welcome, <?php echo $username ?>,</h4>
                    <h4 class="text-2xl font-bold mb-4">Manage Students of Class <?php echo "$class" ?> Section
                        <?php echo "$section"; ?>
                    </h4>
                    <button type="button" data-toggle="modal" data-target="#addStudentModal" onclick="openAdder()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 ml-auto">
                        Add Student
                    </button>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-center border-collapse">
                            <thead class="bg-gray-700 text-white">
                                <tr>
                                    <th class="px-4 py-2 border">#</th>
                                    <th class="px-4 py-2 border">Name</th>
                                    <th class="px-4 py-2 border">Student ID</th>
                                    <th class="px-4 py-2 border">Roll. NO</th>
                                    <th class="px-4 py-2 border">Action</th>
                                </tr>
                            </thead>
                            <!--demo content -->
                            <tbody>
                                <?php
                                include('../conn/conn.php');
                                $sql = "SELECT * FROM tbl_students WHERE class='$class' AND section = '$section'";
                                $stmt = $conn->prepare($sql);
                                $stmt->execute();
                                $result = $stmt->fetchAll();
                                foreach ($result as $row) {
                                    $student_serial_id = $row["tbl_student_id"];
                                    $student_name = $row["student_name"];
                                    $student_id = $row["student_id"];
                                    $roll = $row["roll"];
                                    $qrCode = $row['genrated_code'];
                                    ?>
                                    <tr>
                                        <td class="border px-4 py-2"><?= $student_serial_id ?></td>
                                        <td class="border px-4 py-2"><?= $student_name ?></td>
                                        <td class="border px-4 py-2"><?= $student_id ?></td>
                                        <td class="border px-4 py-2"><?= $roll ?></td>
                                        <td>
                                            <button
                                                class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded"
                                                onclick="openModal('qrCodeModal<?= $student_serial_id ?>')">
                                                Show QR Code
                                            </button>
                                            <!--QR CODE MODAL -->
                                            <div id="qrCodeModal<?= $student_serial_id ?>"
                                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                                                    <div class="flex justify-between items-center mb-4">
                                                        <h3 class="text-lg font-semibold text-gray-800">QR Code</h3>
                                                        <button class="text-gray-500 hover:text-gray-700"
                                                            onclick="closeModal('qrCodeModal<?= $student_serial_id ?>')">
                                                            &times;
                                                        </button>
                                                    </div>
                                                    <div class="flex justify-center items-center">
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode($qrCode) ?>"
                                                            alt="QR Code for Student <?= $student_name ?>"
                                                            class="w-48 h-48 object-contain">
                                                    </div>
                                                    <div class="mt-4 flex justify-end">
                                                        <button
                                                            class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded"
                                                            onclick="closeModal('qrCodeModal<?= $student_serial_id ?>')">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="./controller/idcard.php?id=<?= base64_encode($student_id) ?>"
                                                class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded shadow-md transition duration-200">
                                                ID CARD
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="addStudentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-auto p-6 overflow-y-auto max-h-screen">
            <div class="flex justify-between items-center bg-blue-500 text-white rounded-t-lg p-4">
                <h2 class="text-lg font-semibold">Add Students</h2>
                <button onclick="closeAdder()"
                    class="text-white hover:text-gray-200 focus:outline-none">&times;</button>
            </div>
            <div class="p-6 space-y-4">
                <form action="./controller/add_student.php" method="POST" class="space-y-4" enctype="multipart/form-data">
                    <div>
                        <label for="student_name" class="block text-sm font-medium text-gray-700">Student Name</label>
                        <input type="text" name="student_name" id="student_name"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" name="student_id" id="student_id"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700">Class</label>
                        <input type="text" name="class" id="class" value="<?=$class?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            readonly >
                    </div>
                    <div>
                        <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                        <input type="text" name="section" id="section"  value="<?=$section?>"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            readonly>
                    </div>
                    <div>
                        <label for="roll" class="block text-sm font-medium text-gray-700">Roll Number</label>
                        <input type="text" name="roll" id="roll"
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>
                    <div>
                        <label for="student_photo" class="block text-sm font-medium text-gray-700">Upload Photo</label>
                        <div class="mt-2 flex items-center">
                            <!-- Upload Button -->
                            <input type="file" name="student_photo" id="student_photo"  accept="image/*">
                        </div>
                        <!-- Optional message -->
                        <p class="mt-1 text-sm text-gray-500">Only JPG, PNG, or GIF formats are allowed.</p>
                    </div>
                    <div class="text-right">
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                            Add Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Functions to open and close the modal
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        function openAdder() {
            document.getElementById('addStudentModal').classList.remove('hidden');
        }

        // Function to close the modal
        function closeAdder() {
            document.getElementById('addStudentModal').classList.add('hidden');
        }
    </script>


</body>

</html>