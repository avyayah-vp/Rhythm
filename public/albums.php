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
    <title>Albums</title>
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <table class="table table-dark">
            <thead>
                <tr>
                    <th scope="col">Album Name</th>
                    <th scope="col">Album Art</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Prepare a select statement to get the albums from the database
                $sql = "SELECT DISTINCT album_name, image_path FROM tracks";

                $result = mysqli_query($link, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // Output data of each row
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row["album_name"] . '</td>';
                        echo '<td><img src="' . $row["image_path"] . '" alt="' . $row["album_name"] . '" width="100" height="100"></td>';
                        echo '</tr>';
                    }
                } else {
                    echo "No albums found";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
// Close connection
mysqli_close($link);
?>
