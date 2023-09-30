<?php
session_start();
	$databaseHost = 'localhost';
	$databaseUsername = 'root';
	$databasePassword = '';
    $dbname = "webapp_db";

// Create a connection
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword,$dbname );

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // You should perform proper validation and sanitization here

    $sql = "SELECT user_id FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        session_start();
        // Successful login, retrieve the user ID
        $row = $result->fetch_assoc();
        $userID = $row["user_id"];
        
        // Set the user ID in a session variable
        $_SESSION['user_id'] = $userID;
        // Successful login, redirect to a dashboard page
        header("Location: /ElderCareALLIANCE/main/dashboard.php");
        exit();
    } else {
        echo "Invalid email or password.";
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ElderCareALLIANCE/main/login.css">
	
	<title>Login Page</title>
     
</head>
<body>

    <header>
    </header>
    <div class ="banner">
        <div class="wrapper">
            <div class="form-box">
                <h1 class="logo">
                    <img src="blogo.png" alt="Logo"></h1> 
                    <h3>Elder Care <br>ALLIANCE</h3 >
                <h2>Sta. Ana - San Joaquin Bahay Ampunan <br>Foundation Inc.</h2>
                <form action="login.php" method="POST">
                <div class="input-box">
                    <input type="email" name="email" required placeholder="Email Address or Name">
                 </div>
                <div class="input-box">
                     <input type="password" name="password" required placeholder="Enter Password">
                </div>
                    <div class="remember-forgot">
                        <label>
                                 </label>
                        <a href="#">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn">Sign In</button>
                    <div class="login-register">
                        <p>Don't have an account? <a href="signup.php"
                        class="register-link">Register</a></p>
                    </div>
                </form>
            </div>
            
        </div>

    </div>  
    
</body> 
</html>