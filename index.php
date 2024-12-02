<?php
header("Location: auth/index.php");
// Define office coordinates here
$school_latn = 22.986757; // Coordinates of my school
$school_long = 87.854975;

// Function to calculate distance
function calculate_distance($user_lat, $user_long, $school_lat, $school_lon)
{
    $earth_radius = 6371; // In kms

    // Convert degree to radians
    $user_lat = deg2rad($user_lat);
    $user_long = deg2rad($user_long);
    $school_lat = deg2rad($school_lat);
    $school_lon = deg2rad($school_lon);

    // Haversine formula
    $dlat = $school_lat - $user_lat;
    $dlong = $school_lon - $user_long;

    $a = sin($dlat / 2) * sin($dlat / 2) + cos($user_lat) * cos($school_lat) * sin($dlong / 2) * sin($dlong / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    // Calculating distance in kms
    $dst = $earth_radius * $c;

    return $dst;
}

// For submission check
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user latitude and longitude
    $user_latn = filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT);
    $user_longn = filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT);

    if ($user_latn === false || $user_longn === false) {
        echo "Invalid latitude or longitude.";
        exit;
    }

    // Calculate the distance between the user location and the school
    $distance = calculate_distance($user_latn, $user_longn, $school_latn, $school_long);

    if ($distance <= 2) {
        header("Location: ./auth/index.php");
        exit;
    } else {
        echo "Permission denied. You are $distance km away from the school.";
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Location Based Access</title>
    </head>

    <body>
        <h1 style="text-decoration: dotted;">Location Verification</h1>

        <script>
            window.onload = function () {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(sendLocation, showError);
                } else {
                    alert("Geolocation Not Supported In this Browser");
                }
            }

            // Function to handle successful location retrieval
            function sendLocation(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;

                // Creating a form element to submit location
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "index.php";

                // Adding hidden input for latitude
                var latInput = document.createElement("input");
                latInput.type = "hidden";
                latInput.name = "latitude";
                latInput.value = latitude;
                form.appendChild(latInput);

                // Adding hidden input for longitude
                var longInput = document.createElement("input");
                longInput.type = "hidden";
                longInput.name = "longitude";
                longInput.value = longitude;
                form.appendChild(longInput);

                // Append form to body and submit
                document.body.appendChild(form);
                form.submit(); // Auto submit
            }

            // Error Handling
            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("You have to allow Location Access.");
                        break;

                    case error.POSITION_UNAVAILABLE:
                        alert("Location information unavailable.");
                        break;

                    case error.TIMEOUT:
                        alert("Timeout! Please try again.");
                        break;

                    case error.UNKNOWN_ERROR:
                        alert("Unknown error occurred.");
                        break;
                }
            }
        </script>
    </body>

    </html>
    <?php
}
?>