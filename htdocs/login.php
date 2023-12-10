<?php
	// check for existing session
	session_start();
	if (isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true) {
		header("Location: index.php");
		exit; 	
	}
	
	// include the db handler class and its methods
    require_once 'handler.php';

	// connect to the db
	$con = mysqli_connect('db', 'myuser', 'mypassword', 'courses_portal');
	if (!$con) {
		die('Failed to connect to the db: ' . mysqli_connect_error());
	}

	// get new db handler class instance
	$dbHandler = new DBHandler();

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// get form data
		$user_id = $_POST['user_id'];
		$password = $_POST['password'];
	

		// escape user id input to prevent injections 
		$user_id = mysqli_real_escape_string($con, $user_id);

		// construct query 
		$sql = "SELECT * FROM users WHERE user_id = '$user_id'";

		// exec query to get user 
		$result = $dbHandler->executeSelectQuery($con, $sql);

		// check if login is successful based on db results
		if (!empty($result)) {
			if (crypt($password, $result[0]["password"]) === $result[0]["password"]) {
				// attach variable to verify in pages if user is authenticated
				session_start();
				$_SESSION['logged_in'] = true;
				$_SESSION['user_id'] = $user_id;

				header("Location: index.php");
				exit; 			
			}
		}  
		
		// failure if reached here	
		$alert = '      <div class="alert alert-danger" role="alert">
		Login Failed 
	  </div>
';
	}

    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses Portal - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
<?php require 'nav.php'; ?>
	<div class="container mt-5">
<?php if ($alert) { echo $alert; } ?>
	  <h2 class="text-center">Login Page</h2>
	  <form action="#" method="post">
		<div class="mb-3">
		  <label for="user_id" class="form-label">User ID:</label>
		  <input class="form-control" id="user_id" name="user_id" placeholder="Enter user id">
		</div>
		<div class="mb-3">
		  <label for="password" class="form-label">Password:</label>
		  <input class="form-control" type="password" id="password" name="password" placeholder="Enter password">
		</div>
		<button type="submit" class="btn btn-primary">Login</button>
	  </form>
	</div> 
<?php require 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
