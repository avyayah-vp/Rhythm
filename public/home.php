<?php
// Start the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include your database configuration file
require_once "../includes/db_config.php";

// Prepare a select statement to get the user's name from the database
$sql = "SELECT name FROM users WHERE id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $_SESSION["id"];

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if user exists, if yes then get the name
        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $name);
            if (mysqli_stmt_fetch($stmt)) {
                // Now $name contains the name of the user
                $_SESSION["name"] = $name;  // Store the name in session variable
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}
date_default_timezone_set('Asia/Kolkata');  // Set the timezone to your location
$hour = date('H');
$greeting = ($hour > 17) ? 'Good evening' : 'Good morning';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .card-link {
            cursor: pointer;
        }
        .artist-image {
        border-radius: 50%;
        width: 150px; /* specify the width */
        height: 150px; /* specify the height */
        object-fit: cover; /* this will prevent distortion of images */
        padding: 10px;
    }
    </style>
    <title>Rhythm</title>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="page-header">

        <div class="d-flex justify-content-between align-items-center">
            <h1><?php echo $greeting; ?>, <b><?php echo htmlspecialchars($_SESSION["name"]); ?></b>. </h1>
        </div>
        <div class="container mt-5">
            <div class="row">
                <?php
                // Prepare a select statement to get the songs from the database
                $sql = "SELECT id, name, image_path, artist_name, album_name FROM tracks";

                $result = mysqli_query($link, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-sm-4 d-flex align-items-stretch" onclick="loadSong(' . $row["id"] . ')">';
                        echo '<div class="card bg-dark text-white mb-4 card-link" style="width: 18rem;">';
                        echo '<img src="' . $row["image_path"] . '" class="card-img-top rounded-circle p-2" alt="Album Cover">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row["name"] . '</h5>';
                        echo '<p class="card-text">' . $row["artist_name"] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </div>
        </div>
    </div>
    <div id="player">
        <?php include 'functions/player.php'; ?>
    </div>
    <script src="AJAX/loadSong.js"></script>

</body>


</html>

<?php
// Close connection
mysqli_close($link);
?>