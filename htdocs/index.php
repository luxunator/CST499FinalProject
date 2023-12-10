<?php
	// check for existing session
	session_start();
	$loggedIn = isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true;
?>	
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses Portal - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
<?php require 'nav.php'; ?>
    <div class="container mt-5">
      <div class="row justify-content-center text-center">
        <h1>Welcome to the FooBar Student Portal</h1>
        <p>This is the homepage of the <span class="fw-bold">FooBar School</span> student portal.</p>
      </div>
    </div>
<?php require 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
