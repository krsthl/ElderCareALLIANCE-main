<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit; // You can choose how to handle this case (e.g., redirect to a login page)
}

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

// Retrieve the user ID from the session
$userId = $_SESSION['user_id']; // Use the user ID from the session

// Query to fetch user data including 'contact_number' and 'address'
$query = "SELECT email, fname, lname, address, contact_number FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);

if ($stmt) {
    $stmt->bind_param("i", $userId);
    if ($stmt->execute()) {
        $stmt->bind_result($email, $fname, $lname, $address, $contactNumber);

        // Fetch user data
        if ($stmt->fetch()) {
            // Now you have the user's data, including 'contact_number' and 'address', in these variables:
            // $email, $fname, $lname, $address, $contactNumber

            // Proceed with the rest of your code, including inserting the donation information into the database

            // Check if the form was submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get the values from the form
                $donationType = $_POST["donation-type"];
                $donationAmount = $_POST["donation-amount"];
                // Add two zeros to the donationAmount
                $donationAmount *= 100;
                $payment = "gcash";

                $url = "https://api4wrd-v1.kpa.ph/paymongo/v1/create"; // you will need an app_key, get it from -> https://api4wrd.kpa.ph/register

                $redirect = [
                    "success" => "/ElderCareALLIANCE/main/success.php",
                    "failed" => "/ElderCareALLIANCE/main/failed.php"
                ];

                // User data fetched from the database
                $billing = [
                    "email" => "emjhay.celstino20@gmail.com",
                    "name" =>  "Michael Jeffrey Celestino",
                    "phone" =>  "09161265126",
                    "address" => [
                        "line1" =>  "Purok Silangan",
                        "line2" =>  "Barangay Wawa",
                        "city" =>  "Batangas City",
                        "state" =>  "Batangas",
                        "postal_code" =>  "4200",
                        "country" =>  "Philippines"
                    ]
                ];

                $attributes = [
                    "livemode" => false,
                    "type" => "gcash",
                    "amount" => 10000,
                    "currency" => "PHP",
                    "redirect" => $redirect,
                    "billing" => $billing,
                ];

                // FYI = You can use the PAYMONGO secret key & password below;
                // "secret_key" => "sk_test_HL7BiubdGVbXHXCt2nhf8fNE"
                // "password" => "your-paymongo-password" 
                // sample

                $source = [
                    "app_key" => "23f72b4dda45e9e1bd05a592ac36c65722d5da27", // get it from -> https://api4wrd.kpa.ph/register
                    "secret_key" => "sk_test_1EdYfEZEC1xuXEcZ7BJhuuXp", // secret key from paymongo - be sure your account is fully activated
                    "password" => "003001Cath@", // your paymongo account password - be sure your account is fully activated
                    "data" => [
                        "attributes" => $attributes
                    ]
                ];
                

                $jsonData = json_encode($source);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Disable SSL verification for development/testing (consider enabling it in production)
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $result = curl_exec($ch);
                $resData = json_decode($result, true);

                if ($resData["status"] == 200) {
                    header("Location: " . $resData["url_redirect"]);
                } else {
                    header("Location: index.php");
                    echo $result;
                }

                }

                // Close the cURL session
                curl_close($ch);
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();



// Close the database connection
$conn->close();
?>
