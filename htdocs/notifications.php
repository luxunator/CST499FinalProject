<?php
// check for existing session
	session_start();
	$loggedIn = isset($_SESSION['logged_in']) || $_SESSION['logged_in'] === true;
	if (!$loggedIn) {
		header("Location: index.php");
		exit; 	
	}

	// Include necessary files and establish database connection
	require_once 'handler.php';

	// Connect to the database
	$con = mysqli_connect('db', 'myuser', 'mypassword', 'courses_portal');
	if (!$con) {
		die('Failed to connect to the database: ' . mysqli_connect_error());
	}

	// get new db handler class instance
	$dbHandler = new DBHandler();


	$userID = $_SESSION['user_id'];
	$availableCoursesQuery = "SELECT c.course_name 
							   FROM courses_waitlist cw 
							   JOIN courses c ON cw.course_id = c.course_id 
							   WHERE cw.user_id = '$userID' 
							   AND c.max_student_limit > (
								   SELECT COUNT(*) FROM courses_registration cr 
								   WHERE cr.course_id = c.course_id
							   )";

	$availableCourses = $dbHandler->executeSelectQuery($con, $availableCoursesQuery);

	mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses Portal - Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
<body>
<?php require 'nav.php'; ?>
  <div class="container mt-5">
	<h1 class="text-center">Notifications</h1>
	<?php if (!empty($availableCourses)) : ?>
        <p>You can now join the following classes:</p>
        <ul>
            <?php foreach ($availableCourses as $course) : ?>
                <li><?php echo $course['course_name']; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No notifications available at the moment.</p>
    <?php endif; ?>
  </div>
</body>
</html>

