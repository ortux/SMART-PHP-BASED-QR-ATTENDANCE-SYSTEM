<?php
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
    //echo 'Hello Mr.' . $_COOKIE['username'] . '/t welcome cack to the bashboard';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR CODE ATTENDANCE</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <li><a href="./index.php" class="hover:underline">Home</a></li>
                <li><a href="print/attendence.php" class="hover:underline">Print Attendance</a></li>
                <li><a href="./masterlist.php" class="hover:underline">List of Students</a></li>
                <li><a href="./take_atten.php" class="hover:underline">Take Attendance</a></li>
                <li><a href="auth/logout.php" class="hover:underline">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-5xl">
            <div class="flex space-x-8">
                <div class="flex-none w-1/3">
                    <div class="text-center mb-4">
                        <h4 class="text-xl font-bold">SCAN YOUR QR CODE HERE</h4>
                        <!-- scanner -->
                        <video id="scanner" class="w-full rounded-md" width="100%" src=""></video>
                    </div>
                </div>
                <div class="flex-grow">
                    <h4 class="text-l font-semibold mb-0">Welcome, <?php echo $username ?>,</h4>
                    <h4 class="text-2xl font-bold mb-4">Manage Attendance</h4>
                    <div class="overflow-x-auto">
                        <table class="table-auto w-full text-center border-collapse">
                            <thead class="bg-gray-700 text-white">
                                <tr>
                                    <th class="px-4 py-2 border">#</th>
                                    <th class="px-4 py-2 border">Name</th>
                                    <th class="px-4 py-2 border">Class</th>
                                    <th class="px-4 py-2 border">Roll. NO</th>
                                    <th class="px-4 py-2 border">Section</th>
                                    <th class="px-4 py-2 border">In Time</th>
                                </tr>
                            </thead>
                            <!--demo content -->
                            <tbody>
                                <?php
                                // Include the database configuration file
                                include '../conn/db_config.php';

                                // Username to search for
                                $username = $_COOKIE['username'];

                                // SQL query to retrieve class and section based on username
                                $sql = "SELECT class, section FROM users WHERE username = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param('s', $username);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $class = $row['class'];
                                $sec = $row['section'];
                                // SQL query to retrieve attendance based on class and section
                                $stmt = $conn->prepare("SELECT * FROM tbl_attendance INNER JOIN tbl_students ON tbl_students.tbl_student_id = tbl_attendance.tbl_student_id WHERE tbl_students.class = ? AND tbl_students.section = ?");
                                $stmt->bind_param('ss', $class, $sec);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                while ($row = $result->fetch_assoc()) {
                                    $attendanceID = $row["tbl_attendance_id"];
                                    $studentName = $row["student_name"];
                                    $class = $row['class'];
                                    $sec = $row['section'];
                                    $roll = $row['roll'];
                                    $timeIn = $row["time_in"];
                                ?>
                                    <tr class="hover:bg-gray-100">
                                        <td class="border px-4 py-2"><?= $attendanceID ?></td>
                                        <td class="border px-4 py-2"><?= $studentName ?></td>
                                        <td class="border px-4 py-2"><?= $class ?></td>
                                        <td class="border px-4 py-2"><?= $sec ?></td>
                                        <td class="border px-4 py-2"><?= $roll ?></td>
                                        <td class="border px-4 py-2"><?= $timeIn ?></td>
                                    </tr>
                                <?php
                                }

                                $stmt->close();
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- QR CODE SCANNER -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
        let scanner;

        function startScanner() {
            scanner = new Instascan.Scanner({
                video: document.getElementById('scanner')
            });

            scanner.addListener('scan', function (content) {
                console.log(content);
                sendAttendance(content);
            });

            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]);
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function (err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        function sendAttendance(qrCode) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../endpoint/add-attendance.php';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'qr_code';
            input.value = qrCode;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', startScanner);

        function deleteAttendance(id) {
            if (confirm("Do you want to remove this attendance?")) {
                window.location = "./endpoint/delete-attendance.php?attendance=" + id;
            }
        }

        // Toggle mobile navbar visibility
        document.getElementById('navbar-toggle').addEventListener('click', function () {
            const navbarLinks = document.getElementById('navbar-links');
            navbarLinks.classList.toggle('hidden');
        });
    </script>



</body>

</html>