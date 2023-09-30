<?php
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$dbname = "webapp_db";

// Create a connection
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"]; // Add a new input field for role selection in your HTML form

    // You should perform proper validation and sanitization here

    if ($role === "admin") {
        $sql = "SELECT user_id FROM admin WHERE username = '$email' AND password = '$password'";
    } elseif ($role === "staff") {
        $sql = "SELECT user_id FROM staff WHERE username = '$email' AND password = '$password'";
    } else {
        echo "Invalid role selection.";
        exit();
    }

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Successful login, set user role in session
        session_start();
        $_SESSION["user_role"] = $role;

        // Redirect based on user role
        if ($role === "admin") {
            header("Location: /ElderCareALLIANCE/admin/dashboard.php");
        } elseif ($role === "staff") {
            header("Location: /ElderCareALLIANCE/admin/dashboard2.php");
        }
        exit();
    } else {
        echo "Invalid username or password.";
    }
}

$conn->close();
include 'dbcreation.php';
include 'dbcreation2.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ElderCareALLIANCE/admin/style.css">
	
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

                <form action="/ElderCareALLIANCE/admin/index.php" method="POST">
                <div class="sbox">
                        <select name="role" required style="border-width: 5px; width: 170px; height: 60px; font-size: 18px; font-weight: bolder; margin-left: 75px; margin-bottom: -220px; margin-top: 40px; color: #062506; border-radius: 12px;border-color: #C0C0C0; padding-left: 10px; ">
                             <option value="admin">Admin</option>
                             <option value="staff">Staff</option>
                        </select>
                    </div>
                <div class="input-box">
                    <input type="username" name="username" required placeholder="Enter Username">
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
                </form>
            </div>
            
        </div>

    </div>  
    
</body> 
</html>