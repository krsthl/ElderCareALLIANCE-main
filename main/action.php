<?php 
		session_start(); // creates session
        $databaseHost = 'localhost';
        $databaseUsername = 'root';
        $databasePassword = '';
        $dbname = "webapp_db";
    
        // Connect to the webapp_db database
        $conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $dbname);
    

		// assigns value from the edit admin credentials
		if (isset($_POST['UpdateCredentials'])) {
			$oldUser = $_POST['oldUser'];
			$user = $_POST['username'];
			$oldPass = $_POST['old_password'];
			$newPass = $_POST['newpassword'];
			$rePass = $_POST['repass'];

			// performs a query
			$result = mysqli_query($conn, "SELECT * FROM users");
			$res = mysqli_fetch_array($result);

			// checks if the username is matches the old username
			if ($oldUser == $res['email']) {
				// checks if the old password matches the input
				if ($oldPass == $res['password']) {
					if ($newPass == "" && $rePass == "") {
						$result = mysqli_query($conn, "UPDATE users SET email = '$user' WHERE email='$oldUser'");

						if ($result) {
							echo '<script>alert("New Credentials Updated. Logging Out")</script>';
							echo "<script>window.location.href='/ElderCareALLIANCE/main/login.php'</script>";
						} else {
							echo '<script>alert("Error. Please Try Again.")</script>';
						}
					}

					// checks if the new pass and and re enter password is the same and performs the query
					else if ($newPass == $rePass) {

						$result = mysqli_query($conn, "UPDATE users SET email = '$user', password = '$rePass' WHERE email='$oldUser'");

						if ($result) {
							echo '<script>alert("New Credentials Updated. Logging Out")</script>';
							echo "<script>window.location.href='/ElderCareALLIANCE/main/login.php'</script>";
						} else {
							echo '<script>alert("Error. Please Try Again.")</script>';
						}
					} else {
						echo '<script>alert("New Password and Re-Password doesn\'t match. Please try again.")</script>';
					}
				} else {
					echo '<script>alert("Old password is incorrect.")</script>';
				}
			} 
		}
	?>