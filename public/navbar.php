<!DOCTYPE html>
<html lang="en">
<head>
    <title>Navbar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="home.php"><img src="assets/images/logo.png" alt="Logo" style="width:70px; padding:0px;"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
    <div class="navbar-nav mr-auto">
      <a class="nav-item nav-link active" href="albums.php">Albums</a>
      <a class="nav-item nav-link" href="artists.php">Artists</a>
      <a class="nav-item nav-link" href="home.php">Tracks</a>
    </div>
    <div class="navbar-nav">
      <a class="nav-item nav-link" href="functions/logout.php" style="color:red;">Logout</a>
    </div>
  </div>
</nav>



<!-- rest of your content -->

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html> 
