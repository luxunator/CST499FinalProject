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
		$email = $_POST['email'];
		$user_id = $_POST['user_id'];
		$password = $_POST['password'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$phone = $_POST['phone'];

		// create a salt to use for the password encryption using blowfish 
		$salt = '$2a$10$' . substr(md5(uniqid(rand(), true)), 0, 22);

		// hash the password using blowfish based on the salt prefix 
		$hashedPassword = crypt($password, $salt);

		// get sql string 
		$sql = "INSERT INTO users (email, user_id, password, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?, ?)";

		// exec the query 
		$result = $dbHandler->executeQuery($con, $sql, $email, $user_id, $hashedPassword, $first_name, $last_name, $phone);

		// handle response 
		if ($result) {
			$alert = '      <div class="alert alert-success" role="alert">
	    Registration Successful 
	  </div>
';
		} else {
			$alert = '      <div class="alert alert-danger" role="alert">
		Registration Failed 
	  </div>
';
		}
	}

    mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses Portal - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
<?php require 'nav.php'; ?>
	<div class="container mt-5">
<?php if ($alert) { echo $alert; } ?>
	  <h2 class="text-center">Registration Page</h2>
	  <form action="#" method="post">
		<div class="mb-3">
		  <label for="email" class="form-label">Email:</label>
		  <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
		</div>
	    <div class="mb-3">
		  <label for="user_id" class="form-label">User ID:</label>
		  <input class="form-control" id="user_id" name="user_id" placeholder="User ID">
		</div>	
        <div class="mb-3">
		  <label for="password" class="form-label">Password:</label>
		  <input class="form-control" type="password" id="password" name="password" placeholder="Enter password">
		</div>
		<div class="mb-3">
		  <label for="first_name" class="form-label">First Name:</label>
		  <input class="form-control" id="first_name" name="first_name" placeholder="First name">
		</div>
        <div class="mb-3">
		  <label for="last_name" class="form-label">Last Name:</label>
		  <input class="form-control" id="last_name" name="last_name" placeholder="Last name">
		</div>
        <div class="mb-3">
		  <label for="phone" class="form-label">Phone:</label>
		  <input class="form-control" id="phone" name="phone" placeholder="Phone">
		</div>
		<button type="submit" class="btn btn-primary">Register</button>
	  </form>
	</div>
<?php require 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
