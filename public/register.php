<?php
// Include your database configuration file
require_once "../includes/db_config.php";

// Define variables and initialize with empty values
$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = trim($_POST["name"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before inserting in database
    if (empty($name_err) && empty($email_err) && empty($password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_email, $param_password);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
</head>

<body>
    <div id="background">
        <video autoplay loop muted playsinline>
            <source src="assets/images/herov.mp4" type="video/mp4">
        </video>
        <div id="loginContainer">

            <div id="inputContainer">
                <h1>Rhythm</h1>
                <form id="registerForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Create your free account</h2>
                    <p>
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <label for="username">Username</label>
                        <input id="username" name="name" type="text" placeholder="e.g. Vishnu" value="<?php echo $name; ?>" required class="form-control">
                        <span class="help-block"><?php echo $name_err; ?></span>
                    </div>
                    </p>
                    <p>
                    <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="e.g. vishnu@gmail.com" value="<?php echo $email; ?>" required class="form-control">
                        <span class="help-block"><?php echo $email_err; ?></span>
                    </div>
                    </p>
                    <p>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" placeholder="Your password" value="<?php echo $password; ?>" required class="form-control">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>
                    </p>
                    <button type="submit" name="registerButton">SIGN UP</button>

                    <div class="hasAccountText">
                        <span id="hideRegister" style="cursor: pointer;" onclick="window.location.href='login.php'">Already have an account? Log in here.</span>
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

</html>