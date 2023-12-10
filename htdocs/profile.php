<?php
	// check for existing session
	session_start();
	$loggedIn = isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true;
	if (!$loggedIn) {
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

	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		$user_id = $_SESSION['user_id'];

		// escape user id input to prevent injections 
		$user_id = mysqli_real_escape_string($con, $user_id);

		// construct query 
		$sql = "SELECT * FROM users WHERE user_id = '$user_id'";

		// exec query to get user 
		$result = $dbHandler->executeSelectQuery($con, $sql);

		// set variables to display in page	
		$email = $result[0]["email"];
		$user_id = $result[0]["user_id"];
		$first_name = $result[0]["first_name"];
		$last_name = $result[0]["last_name"];
		$phone = $result[0]["phone"];
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
    <h2 class="text-center">Profile Details</h2>
      <div class="card-body">
        <ul class="list-group">
          <li class="list-group-item"><b>Email:</b> <?php echo $email; ?></li>
          <li class="list-group-item"><b>User ID:</b> <?php echo $user_id; ?></li>
          <li class="list-group-item"><b>First Name:</b> <?php echo $first_name; ?></li>
          <li class="list-group-item"><b>Last Name:</b> <?php echo $last_name; ?></li>
          <li class="list-group-item"><b>Phone:</b> <?php echo $phone; ?></li>
        </ul>
      </div>
  </div>
<?php require 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
