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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <title>Artists</title>
    <style>
        .artist-image {
            width: 100%;
            height: auto;
        }
        .card {
            width: 80%;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <?php
            // Prepare a select statement to get the artists from the database
            $sql = "SELECT DISTINCT artist_name FROM tracks";

            $result = mysqli_query($link, $sql);

            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4">';
                    echo '<div class="card">';
                    echo '<img src="assets/images/artists/' . $row["artist_name"] . '.jpg" class="card-img-top artist-image" style="object-fit: cover; width: 100%; height: 200px;">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["artist_name"] . '</h5>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                
            } else {
                echo "No artists found";
            }
            ?>
        </div>
    </div>
</body>

</html>

<?php
// Close connection
mysqli_close($link);
?>
