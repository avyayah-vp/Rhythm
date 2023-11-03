<?php
// Start the session
session_start();

// Include your database configuration file
require_once "../includes/db_config.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if email exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;

                            // Redirect user to home page
                            header("location: index.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<html>

<head>
    <title>Ryhthm</title>
    <style>
        html,
        body {
            padding: 0;
            margin: 0;
            height: 100%;
        }

        #background {
            background-color: #000;
            display: table;
            height: 100%;
            width: 100%;
        }

        #background video {
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
        }

        #loginContainer * {
            color: #fff;
            font-family: "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            font-weight: normal;
            line-height: 1em;
            box-sizing: border-box;
        }

        #loginContainer {
            width: 80%;
            margin: 0 auto;
            position: relative;
            max-width: 1024px;
            margin-top: 10%;
        }

        ::-webkit-input-placeholder {
            /* WebKit, Blink, Edge */
            color: #fff;
        }

        :-moz-placeholder {
            /* Mozilla Firefox 4 to 18 */
            color: #fff;
            opacity: 1;
        }

        ::-moz-placeholder {
            /* Mozilla Firefox 19+ */
            color: #fff;
            opacity: 1;
        }

        :-ms-input-placeholder {
            /* Internet Explorer 10-11 */
            color: #fff;
        }

        ::-ms-input-placeholder {
            /* Microsoft Edge */
            color: #fff;
        }

        #inputContainer {
            width: 400px;
            padding: 45px;
            float: left;
            border-right: 1.5px solid #f7f7f7;
        }

        #inputContainer h2 {
            text-align: center;
        }

        #inputContainer input[type="text"],
        #inputContainer input[type="email"],
        #inputContainer input[type="password"] {
            display: block;
            background-color: transparent;
            border: 0;
            border-bottom: 1px solid #fff;
            height: 27px;
            line-height: 27px;
            width: 100%;
            opacity: 0.4;
        }

        #inputContainer input[type="text"]:focus,
        #inputContainer input[type="email"]:focus,
        #inputContainer input[type="password"]:focus {
            display: block;
            background-color: transparent;
            border: 0;
            border-bottom: 1px solid #fff;
            height: 27px;
            line-height: 27px;
            width: 100%;
        }

        #inputContainer label {
            font-size: 13px;
            margin-top: 15px;
            display: block;
        }

        #inputContainer button {
            background-color: transparent;
            border: 2px solid #fff;
            border-radius: 250px;
            color: #fff;
            display: block;
            font-size: 14px;
            letter-spacing: 1px;
            margin: 20px auto;
            height: 41px;
            width: 100%;
            transition: background-color 1s;
            /* Add this line */
        }

        #inputContainer button:hover {
            cursor: pointer;
            background-color: lightgreen;
            /* Add this line */
        }


        .hasAccountText span {
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
        }

        .hasAccountText {
            text-align: center;
        }

        #loginText {
            padding: 45px;
            display: table-cell;
        }

        #loginText h1 {
            color: #3f6a8496;
            font-size: 60px;
            font-weight: bold;
        }

        #inputContainer h1 {
            color: white;
            font-size: 60px;
            font-weight: bold;
        }

        #loginText h2 {
            margin: 35px 0;
        }

        #loginText ul {
            padding: 0;
        }

        #loginText li {
            font-size: 20px;
            list-style-type: none;
            padding: 5px 30px;
            background: url(assets/images/checkmark.png) no-repeat 0 0;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="assets/js/register.js"></script> -->
</head>

<body>
    <div id="background">
        <video autoplay loop muted playsinline>
            <source src="assets/images/herov.mp4" type="video/mp4">
        </video>
        <div id="loginContainer">

            <div id="inputContainer">
                <h1>Rhythm</h1>
                <form id="loginForm" action="login.php" method="POST">
                    <h2>Login to your account</h2>
                    <p>
                        <label for="loginEmail">Email</label>
                        <input id="loginEmail" name="email" type="email" placeholder="e.g. vishnu@gmail.com" required class="form-control">
                    </p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input id="loginPassword" name="password" type="password" placeholder="Your password" required class="form-control">
                    </p>
                    <!-- <p>
                        <label for="termsConditions">
                            <input type="checkbox" required> I agree to the terms and conditions
                        </label>
                    </p> -->
                    <button type="submit" name="loginButton" class="btn btn-light">LOG IN</button>
                    <div class="hasAccountText">
                        <span id="hideLogin" style="cursor: pointer;" onclick="window.location.href='register.php'">Don't have an account yet? Signup here.</span>
                    </div>
                </form>

            </div>
            <div id="loginText">
                <h1>Get great music, right now</h1>
                <h2>Listen to loads of songs for free</h2>
                <ul>
                    <li>Discover music you'll fall in love with</li>
                    <li>Create your own playlists</li>
                    <li>Follow artists to keep up to date</li>
                </ul>
            </div>
        </div>
    </div>
</body>
<html>