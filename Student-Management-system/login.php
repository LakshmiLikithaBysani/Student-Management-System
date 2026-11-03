<?php

include "db.php";

$message = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users 
            WHERE email='$email' 
            AND password='$password'";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {

        header("Location: dashboard.html");
        exit();

    } else {

        $message = "Invalid Email or Password";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - Student Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-page">

    <div class="login-container">


        <!-- LEFT SIDE -->

        <div class="login-info">

            <div class="big-icon">
                🎓
            </div>

            <h1>
                Student<br>
                Management<br>
                System
            </h1>

            <p>
                Manage student information,
                marks and attendance easily
                in one place.
            </p>

            <div class="login-features">

                <p>✔ Manage Student Details</p>

                <p>✔ Manage Marks</p>

                <p>✔ Manage Attendance</p>

                <p>✔ Easy and Organized System</p>

            </div>

        </div>


        <!-- RIGHT SIDE -->

        <div class="login-box">



            <h2>
                Welcome Back!
            </h2>

            <p class="login-subtitle">
                Login to your account
            </p>


            <!-- ERROR MESSAGE -->

            <?php

            if ($message != "") {

                echo "<p style='color:red; margin-bottom:15px;'>
                        $message
                      </p>";

            }

            ?>


            <!-- LOGIN FORM -->

            <form method="POST">

                <!-- EMAIL -->

                <div class="input-group">

                    <label>
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="input-group">

                    <label>
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >

                </div>


                <!-- OPTIONS -->

                <div class="login-options">

                    <label>
                        <input type="checkbox">
                        Remember me
                    </label>

                    <a href="#">
                        Forgot Password?
                    </a>

                </div>


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    name="login"
                    class="login-submit"
                >
                    Login
                </button>

            </form>


            <!-- REGISTER -->

            <p class="register-text">

                Don't have an account?

                <a href="#">
                    Contact Administrator
                </a>

            </p>


            <!-- BACK HOME BUTTON -->

            <a
                href="index.html"
                class="back-home-btn"
            >
                Back to Home
            </a>

        </div>

    </div>

</div>

</body>

</html>