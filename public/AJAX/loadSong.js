    function loadSong(id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("player").innerHTML = this.responseText;
                // Update player and progressBar to reference the new audio element and progress bar
                player = document.getElementById('audioPlayer');
                progressBar = document.querySelector('.custom-range');
                // Update max attribute of progress bar when metadata is loaded
                player.addEventListener('loadedmetadata', function () {
                    progressBar.max = player.duration;
                });
            }
        };
        // Add a unique parameter to the URL
        xhttp.open("GET", "functions/player.php?id=" + id + "&nocache=" + new Date().getTime(), true);
        xhttp.send();
    }
