	<nav class="navbar navbar-expand-sm navbar-dark bg-primary" data-bs-theme"dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">FooBar Portal</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
		</button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
	        <li class="nav-item">
		      <a class="nav-link" href="index.php">Home</a>
	        </li>
			<?php 
				if ($loggedIn) { 
					echo '            <li class="nav-item">
		      <a class="nav-link" href="courses.php">Courses</a>
			</li>
			<li class="nav-item">
		      <a class="nav-link" href="notifications.php">Notifications</a>
	        </li>
			<li class="nav-item">
		      <a class="nav-link" href="logout.php">Logout</a>
	        </li>
';	
				} else {
					echo '            <li class="nav-item">
		      <a class="nav-link" href="login.php">Login</a>
	        </li>
	        <li class="nav-item">
		      <a class="nav-link" href="register.php">Register</a>
	        </li>
';	
				
				}
		    ?>
	      </ul>
        </div>
      </div>
    </nav>
