<?php

//define office cordinates here

$school_latn = 25.0930682;//cordinates of my school
$school_long = 84.0378894;

//function to calculate diatance
function claculate_distance($user_lat,$user_long,$school_lat,$school_lon){
    $earth_radius = 6371;//in kms

    //covert degree to radians
    $user_lat = deg2rad($user_lat);
    $user_long = deg2rad($user_long);
    $school_lat = deg2rad($school_lat);
    $school_lon = deg2rad($school_lon);

    //Haversin formula
    $dlat = $school_lat - $user_lat;
    $dlong = $school_lon - $user_long;

    $a = sin($dlat/2)*sin($dlat/2) + cos($user_lat) * cos($school_lat) * sin($dlong/2) * sin($dlong/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    //calculating distance in kms
    $dst = $earth_radius*$c;

    return $dst;

}

// for submison check
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    //get the user latitude and longitude
    $user_latn = $_POST['latitude'];
    $user_longn = $_POST['longitude'];

    //calculate the distance between the user location and the school
    $distance = claculate_distance($user_latn,$user_longn,$school_latn,$school_long);

    if($distance <= 2)
    {
        header("Location: ./auth/index.php");
        exit;
    }
    else{
        echo "permisson denied" ;
    }
}else{
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Loaction Based Acess</title>
        <script>
            window.onload = function(){
                if(navigator.geolocation){
                    navigator.geolocation.getCurrentPosition(sendLocation, showError);

                }
                else{
                    ("Geoloacation Not Supported In this Browser");
                }
            }
            //function to handel sucess ful location retrival
            function sendLocation(position){
                var latitude = position.coords.latitude;
                var longitude =position.coords.longitude;

                //creating a form element to submit location
                var form = document.createElement("form");
                form.method = "POST";
                form.action = "index.php";

                //adding hidden input for lat
                var latInput = document.createElement("input");
                latInput.type = "hidden";
                latInput.name = "latitude";
                latInput.value = latitude;
                form.appendChild(latInput);
                
                //adding hidden input for long
                var longInput = document.createElement("input");
                longInput.type = "hidden";
                longInput.name = "longitude";
                longInput.value = longitude;
                form.appendChild(longInput);

                //append form to body and submit
                document.body.appendChild(form);
                form.submit();//auto submit
            }

                //Error Handeling 
                function showError(error)
                {
                    switch(error.code)
                    {
                        case error.PERMISSION_DENIED:
                            alert("You Have to allow Location Acess");
                            break;
                        
                        case error.POSITION_UNAVAILABLE:
                            alert("Location Information Unavailable");
                            break;

                        case error.TIMEOUT:
                            alert("Time Out! PLS TRY AGAIN ")
                            break;
                        
                        case error.UNKNOWN_ERROR:
                            alert("UNKNOWN ERROR !!")
                            break;
                    }
                }
        </script>
    </head>
    <body>
        <h1 style="text-decoration: dotted;">Location Verification</h1>

        
    </body>
    </html>
    <?php
}
?>
    <!--code complete -->