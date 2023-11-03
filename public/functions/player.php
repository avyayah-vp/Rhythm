<?php

// Include your database configuration file
require_once __DIR__ . '/../../includes/db_config.php';
// Get the id of the song to be played from the GET parameters
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    // Handle the case where no id is provided
    // Prepare a select statement to get a random song from the database
    $sql = "SELECT id FROM tracks ORDER BY RAND() LIMIT 1";

    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $id = $row['id'];
        } else {
            echo "No songs in the database.";
            exit;
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
        exit;
    }
}
// Prepare a select statement to get the song details from the database
$sql = "SELECT name, artist_name, album_name, image_path, mp3_path FROM tracks WHERE id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = $id;

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if song exists, if yes then get the details
        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $name, $artist_name, $album_name, $image_path, $mp3_path);
            if (mysqli_stmt_fetch($stmt)) {
                // Now you have the details of the song to be played
            }
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Now Playing</title>
</head>

<body>
    <div class="container mt-5">
        <!-- Now Playing Bar -->
        <div class="fixed-bottom bg-dark text-white p-3 d-flex justify-content-between align-items-center">
            <!-- Album Art and Track Info -->
            <div class="d-flex align-items-center">
                <img src="<?php echo $image_path; ?>" alt="Album Art" width="60" height="60">
                <div class="ml-3">
                    <h6 class="mb-0"><?php echo $name; ?></h6>
                    <small><?php echo $artist_name; ?></small>
                </div>
            </div>

            <!-- Playback Controls and Progress Bar -->
            <div>
                <button class="btn btn-outline-light btn-sm" id="backward"><i class="fas fa-step-backward"></i></button>
                <button class="btn btn-outline-light btn-sm" id="play"><i class="fas fa-play"></i></button>
                <button class="btn btn-outline-light btn-sm" id="forward"><i class="fas fa-step-forward"></i></button>
                <input type="range" class="custom-range ml-3" style="width: 200px;" id="progressBar" min="0" max="100" value="0">
            </div>

            <!-- Volume Control -->
            <div>
                <i id="volumeIcon" class="fas fa-volume-up text-white"></i>
                <input type="range" min="0" max="100" value="100" id="volumeControl" class="custom-range ml-3" style="width: 100px;">
            </div>
            <!-- Loader -->
            <div id="loader" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 9999;">Loading...</div><!--  --> <!-- Audio Player -->
            <audio id="audioPlayer" src="<?php echo $mp3_path; ?>"></audio>

        </div>

        <!-- Include Bootstrap JS and FontAwesome for icons -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

        <!-- Include jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Playback Controls Script -->
        <script>
            $(document).ready(function() {
                // Define player as a global variable
                var player = document.getElementById('audioPlayer');

                // Define progressBar as a global variable
                var progressBar = document.getElementById('progressBar');

                // Define volumeControl as a global variable
                var volumeControl = document.getElementById('volumeControl');

                // Play/Pause button
                document.getElementById('play').addEventListener('click', function() {
                    console.log('Play/Pause button clicked');
                    var icon = this.querySelector('i');
                    if (player.paused) {
                        player.play();
                        icon.classList.remove('fa-play');
                        icon.classList.add('fa-pause');
                    } else {
                        player.pause();
                        icon.classList.remove('fa-pause');
                        icon.classList.add('fa-play');
                    }
                });

                // Step forward button  
                document.getElementById('forward').addEventListener('click', function() {
                    console.log('Step forward button clicked');
                    player.currentTime += 10;
                });

                // Step backward button
                document.getElementById('backward').addEventListener('click', function() {
                    console.log('Step backward button clicked');
                    player.currentTime -= 10;
                });

                // Seek to new time when progress bar is clicked
                progressBar.addEventListener('input', function() {
                    player.currentTime = (progressBar.value / 100) * player.duration;
                });

                // Update progress bar value when time updates
                player.addEventListener('timeupdate', function() {
                    progressBar.value = (player.currentTime / player.duration) * 100;
                });

                // Seek to new time when progress bar is clicked
                progressBar.addEventListener('input', function() {
                    player.currentTime = (progressBar.value / 100) * player.duration;
                });

                // Reference to the volume icon
                var volumeIcon = document.getElementById('volumeIcon');

                // Toggle mute when volume icon is clicked
                volumeIcon.addEventListener('click', function() {
                    if (player.muted) {
                        player.muted = false;
                        volumeIcon.className = 'fas fa-volume-up text-white';
                    } else {
                        player.muted = true;
                        volumeIcon.className = 'fas fa-volume-mute text-white';
                    }
                });
            });
        </script>
</body>

</html>

<?php
// Close connection
mysqli_close($link);
?>