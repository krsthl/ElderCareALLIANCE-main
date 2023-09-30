<?php
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

// SQL query to fetch user data
$sql = "SELECT unique_id, fname, lname, email,address,contact_number FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Start building the HTML table
    echo "<table border='1'>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Address</th>
            <th>Contact No.</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr style=\"order-bottom: 1px solid #ccc;\">
            <td>" . $row["unique_id"] . "</td>
            <td>" . $row["fname"] . "</td>
            <td>" . $row["lname"] . "</td>
            <td>" . $row["email"] . "</td>
            <td>" . $row["address"] . "</td>
            <td>" . $row["contact_number"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "No users found";
}

$conn->close();

?>