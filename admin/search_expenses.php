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

if (isset($_POST['search2'])) {
    $search2 = $_POST['search2'];

    // SQL query to fetch expenses data based on the search input (ID)
    $sql = "SELECT id, date, description, amount FROM expenses WHERE id LIKE '%$search2%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Start building the HTML table
        echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["date"] . "</td>
                <td>" . $row["description"] . "</td>
                <td>" . $row["amount"] . "</td>
            </tr>";
        }

        echo "</table>";
    } else {
        echo "No expenses found";
    }
}

$conn->close();
?>
