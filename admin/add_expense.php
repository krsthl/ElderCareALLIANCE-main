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
    // $id will be auto-incremented, so no need to specify it
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    
    date_default_timezone_set('Asia/Manila');
    $currentDateTime = date('Y-m-d H:i:s');
    echo $currentDateTime;
    
    // SQL query to insert a new expense
    $sql = "INSERT INTO expenses (date, description, amount) VALUES ('$currentDateTime', '$description', '$amount')";
    
    if ($conn->query($sql) === TRUE) {
        // Fetch and display the updated expenses table
        include 'fetch_expenses.php';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


?>
