<?php
	$databaseHost = 'localhost';
	$databaseUsername = 'root';
	$databasePassword = '';
$dbname = "webapp_db";

// Connect to the webapp_db database
$conn = new mysqli($databaseHost, $databaseUsername, $databasePassword, $dbname);

// Create the admin table
$sql = "CREATE TABLE IF NOT EXISTS staff(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo " ";
} else {
    echo "Error creating table: " . $conn->error;
}


// Define the username and password you want to insert
$username = "staff";
$password = "staff";

// Check if the username already exists
$check_query = "SELECT username FROM staff WHERE username = '$username'";
$check_result = $conn->query($check_query);

if ($check_result->num_rows == 0) {
    // The username doesn't exist, so insert it
    $insert_query = "INSERT INTO staff (username, password) VALUES ('$username', '$password')";

    if ($conn->query($insert_query) === TRUE) {
        echo " ";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
}
$conn->close();
?>