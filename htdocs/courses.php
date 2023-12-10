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
		$sql = "SELECT * FROM courses";
		$result = $dbHandler->executeSelectQuery($con, $sql);
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['course_id'])) {
		$user_id = $_SESSION['user_id'];
		$course_id = $_POST['course_id'];

		$action = $_POST['action'];

		if ($action === 'enroll') {
	        $sql = "INSERT INTO courses_registration (user_id, course_id) VALUES (?, ?)";
		} elseif ($action === 'unenroll') {
			$sql = "DELETE FROM courses_registration WHERE user_id = (?) AND course_id = (?)";
		} elseif ($action === 'waitlist') {
			$sql = "INSERT INTO courses_waitlist (user_id, course_id) VALUES (?, ?)";
		} elseif ($action === 'unwaitlist') {
			$sql = "DELETE FROM courses_waitlist WHERE user_id = (?) AND course_id = (?)";
		}

		$result = $dbHandler->executeQuery($con, $sql, $user_id, $course_id);
	}

	mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Courses Portal - Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
<?php require 'nav.php'; ?>
  <div class="container mt-5">
	<h1 class="text-center">Course Enrollment</h1>
    <div class="row">
      <div class="col-auto">
        <p class="mt-2">Select Semester:</p>
      </div>
      <div class="col-auto">
        <div class="btn-group" role="group" aria-label="Options">
          <button type="button" class="btn btn-primary" onclick="showTable('springTableEnrolled')">Spring</button>
          <button type="button" class="btn btn-primary" onclick="showTable('summerTableEnrolled')">Summer</button>
          <button type="button" class="btn btn-primary" onclick="showTable('fallTableEnrolled')">Fall</button>
        </div>
      </div>
	</div>
<?php
$semesters = ['spring', 'summer', 'fall'];

foreach ($semesters as $semester) {
	$con = mysqli_connect('db', 'myuser', 'mypassword', 'courses_portal');
	if (!$con) {
		die('Failed to connect to the db: ' . mysqli_connect_error());
	}

	$userEnrolledCoursesQuery = "SELECT COUNT(*) AS enrolled_courses 
								 FROM courses_registration cr 
								 JOIN courses c ON cr.course_id = c.course_id 
								 WHERE cr.user_id = '" . $_SESSION['user_id'] . "' 
								 AND c.semester = '" . $semester . "'";
	$userEnrolledCoursesResult = $dbHandler->executeSelectQuery($con, $userEnrolledCoursesQuery);
	$userEnrolledCount = $userEnrolledCoursesResult[0]['enrolled_courses'];
	
	$semesterCourses = array_filter($result, function ($row) use ($semester) {
        return $row['semester'] === $semester;
	});
	
	mysqli_close($con);

    if (!empty($semesterCourses)) {
        echo "    <table class='table mt-3' id='" . $semester . "TableEnrolled' style='display: None;'>
	  <thead>
	    <tr>
		  <th>ID #</th>
		  <th>Name</th>
		  <th># of Students Enrolled</th>
		  <th># of Students Max</th>
		  <th>Actions</th>
		</tr>
	  </thead>
	  <tbody>";

        foreach ($semesterCourses as $course) {
			$con = mysqli_connect('db', 'myuser', 'mypassword', 'courses_portal');
			if (!$con) {
				die('Failed to connect to the db: ' . mysqli_connect_error());
			}

			$enrollmentQuery = "SELECT COUNT(*) AS enrolled FROM courses_registration WHERE course_id = " . $course['course_id'];
			$enrollmentResult = $dbHandler->executeSelectQuery($con, $enrollmentQuery);

            $isEnrolledQuery = "SELECT COUNT(*) AS enrolled FROM courses_registration WHERE course_id = " . $course['course_id'] . " AND user_id = '" . $_SESSION['user_id'] . "'";
			$isEnrolledResult = $dbHandler->executeSelectQuery($con, $isEnrolledQuery);
			$isUserEnrolled = $isEnrolledResult[0]['enrolled'];

			$isUserWaitlistedQuery = "SELECT COUNT(*) AS waitlisted FROM courses_waitlist WHERE course_id = " . $course['course_id'] . " AND user_id = '" . $_SESSION['user_id'] . "'";
			$isUserWaitlistedResult = $dbHandler->executeSelectQuery($con, $isUserWaitlistedQuery);
			$isUserWaitlisted = $isUserWaitlistedResult[0]['waitlisted'];
	
			$waitlistQuery = "SELECT COUNT(*) AS waitlisted FROM courses_waitlist WHERE course_id = " . $course['course_id'];
			$waitlistResult = $dbHandler->executeSelectQuery($con, $waitlistQuery);
			$usersOnWaitlist = $waitlistResult[0]['waitlisted'];	
				
			$isFirstOnWaitlistQuery = "SELECT CASE
    WHEN EXISTS (
        SELECT 1
        FROM courses_portal.courses_waitlist cw2
        WHERE cw2.course_id = " . $course['course_id'] . "
        AND cw2.user_id = '" . $_SESSION['user_id'] . "'
    ) THEN
        CASE
            WHEN (
                SELECT MIN(cw3.waitlisted_at)
                FROM courses_portal.courses_waitlist cw3
                WHERE cw3.course_id = " . $course['course_id'] . "
            ) = (
                SELECT cw4.waitlisted_at
                FROM courses_portal.courses_waitlist cw4
                WHERE cw4.course_id = " . $course['course_id'] . "
                AND cw4.user_id = '" . $_SESSION['user_id'] . "'
            ) THEN 1
            ELSE 0
        END
    ELSE 0
END AS waitlist_status;";
			$isFirstOnWaitlistResult = $dbHandler->executeSelectQuery($con, $isFirstOnWaitlistQuery);
			$isFirstOnWaitlist = $isFirstOnWaitlistResult[0]['waitlist_status'];	

			mysqli_close($con);

			$enrolledStudents = $enrollmentResult[0]['enrolled'];
			echo "
        <tr>
          <td>" . $course['course_id'] . "</td>
		  <td>" . $course['course_name'] . "</td>
		  <td>" . $enrolledStudents . "</td>
		  <td>" . $course['max_student_limit'] . "</td>
		  <td>";
			if ($isUserEnrolled > 0) {
				echo "<button class='btn btn-danger' onclick='unenrollCourse($course[course_id])'>Unenroll</button>";
			} elseif ($userEnrolledCount >= 4) {
				echo "<button class='btn btn-secondary' disabled>Max Courses Reached</button>";
			} elseif ($enrolledStudents < $course['max_student_limit'] && $usersOnWaitlist > 0 && $enrolledStudents < 12 && $isFirstOnWaitlist == 1) {
				echo "<button class='btn btn-success' onclick='enrollCourse($course[course_id])'>Enroll</button>";
				echo "<button class='btn btn-warning' onclick='unwaitlistCourse($course[course_id])'>Unwaitlist</button>";
			} elseif ($isUserWaitlisted > 0 && $isFirstOnWaitlist == 0) {
				echo "<button class='btn btn-warning' onclick='unwaitlistCourse($course[course_id])'>Unwaitlist</button>";
			} elseif ($enrolledStudents >= $course['max_student_limit']) {
				echo "<button class='btn btn-warning' onclick='waitlistCourse($course[course_id])'>Waitlist</button>";
			} elseif ($enrolledStudents < $course['max_student_limit'] && $usersOnWaitlist > 0 && $enrolledStudents < 12 && $isFirstOnWaitlist == 0) {
				echo "<button class='btn btn-warning' onclick='waitlistCourse($course[course_id])'>Waitlist</button>";
			} else {
				echo "<button class='btn btn-success' onclick='enrollCourse($course[course_id])'>Enroll</button>";
			}
			
			echo "</td>
		</tr>";        
		}
	echo "
	  </tbody>
    </table>";
	} 
}

?>

  </div>
<?php require 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script>
      function showTable(tableId) {
        const tables = document.querySelectorAll('.table');
        tables.forEach(table => {
          if (table.id === tableId) {
            table.style.display = 'table';
          } else {
            table.style.display = 'none';
          }
        });
	  }
      function enrollCourse(courseId) {
		courseAction(courseId, 'enroll');
	  }
	  function unenrollCourse(courseId) {
		courseAction(courseId, 'unenroll');
	  }
	  function waitlistCourse(courseId) {
		courseAction(courseId, 'waitlist');
	  }
	  function unwaitlistCourse(courseId) {
		courseAction(courseId, 'unwaitlist');
	  }
	  function courseAction(courseId, action) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'courses.php');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
            if (xhr.status === 200) {
                window.location.reload();
            }
        };
        xhr.send(`action=${action}&course_id=${courseId}`);
      }
    </script> 
  </body>
</html>
