<!--Login Page-->
<!--if user already loged in redirect to dashboard-->
<?php 
if(isset($_COOKIE['username']))
{
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--Importing Boostrap Stylesheets And Vendors-->
    <link rel="icon" href="images/icons/favicon.ico">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="fonts/iconic/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="vendor/animate/animate.css">
    <link rel="stylesheet" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" href="vendor/animsition/css/animsition.min.css">
    <link rel="stylesheet" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" href="vendor/daterangepicker/daterangepicker.css">
    <!--End Of Inbuilt Style Importing -->
    <!--start of importing custom css link in description -->
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/util.css">
    <!--End Of Importing Custom Style Sheet-->
</head>
<body>
    <!--main box start-->
    <div class="limiter">
        <!--login container-->
        <!--page background gradient-->
        <div class="container-login100" style="background-image: url('images/bg-01.jpg');">
            <div class="wrap-login100 p-l-5 p-r-55 p-t-65 p-b-54">
                <!--form designing-->
                <form class="login100-form validate-form" action="login_handeler.php" method="post">
                    <!--heading of login page-->
                    <span class="login100-form-title p-b-50">
                        Login
                    </span>
                    <!--end of heading-->

                    <!--Start of username Input-->
                    <div class="wrap-input100 validate-input m-b-24" data-validate="Username Is Requred">
                        <span class="label-input100">
                            Username
                        </span>

                        <input class="input100" type="text" name="username" placeholder="Enter Username">
                        <span class="focus-input100" data-symbol="&#xf206;"></span>
                    </div>
                    <!--End Of Username Input-->

                    <!--Start of password field-->
                    <div class="wrap-input100 validate-input m-b-25" data-validate="Password Requred" >
                        <span class="label-input100">Password</span>
                        <input class="input100" type="password" name="password" placeholder="Enter Password">
                        <span class="focus-input100" data-symbol="&#xf190;"></span>
                    </div>
                    <!--end of password field -->

                    <!--Login Button Start-->
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Login
                            </button>
                        </div>
                    </div>
                    <!--Login Button End -->
                </form>
                <!--Form End Here-->
            </div>
        </div>
        <!--page background gradient end-->
    </div>
    <!--main container end-->
    <!--importing js files-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/animsition/js/animsition.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/daterangepicker/moment.min.js"></script>
    <script src="vendor/daterangepicker/daterangepicker.js"></script>
    <script src="vendor/countdowntime/countdowntime.js"></script>
    <!--end of importing js-->
    <!--importing custom js-->
    <script src="js/main.js"></script>
    <!--End Import-->
    <!--End Of Code Hope You liked it-->
    
</body>
</html>