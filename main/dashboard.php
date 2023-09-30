<?php
function getLastUpdatedTime($userID, $conn) {
  $lastUpdatedQuery = "SELECT MAX(date_created) AS last_updated FROM payments WHERE user_id = $userID";
  $lastUpdatedResult = $conn->query($lastUpdatedQuery);
  $lastUpdatedRow = $lastUpdatedResult->fetch_assoc();

  if ($lastUpdatedRow['last_updated']) {
      $lastUpdatedTime = strtotime($lastUpdatedRow['last_updated']);
      $currentTime = time();
      $timeDifference = $currentTime - $lastUpdatedTime;

      if ($timeDifference < 60) {
          // Less than a minute ago
          return  "few seconds ago";
      } elseif ($timeDifference < 3600) {
          // Less than an hour ago
          $minutesAgo = floor($timeDifference / 60);
          return $minutesAgo . " minute" . ($minutesAgo > 1 ? "s" : "") . " ago";
      } elseif ($timeDifference < 86400) {
          // Less than a day ago
          $hoursAgo = floor($timeDifference / 3600);
          return $hoursAgo . " hour" . ($hoursAgo > 1 ? "s" : "") . " ago";
      } else {
          // More than a day ago
          $daysAgo = floor($timeDifference / 86400);
          return $daysAgo . " day" . ($daysAgo > 1 ? "s" : "") . " ago";
      }
  } else {
      return 'N/A'; // No donations found
  }
}


session_start();
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

// Check if the user is logged in (user ID is set in the session)
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or perform other actions for non-logged-in users
    header("Location: /ElderCareALLIANCE/main/login.php");
    exit();
}

// Retrieve the user ID from the session
$userID = $_SESSION['user_id'];

// Query to fetch user's data
$sql = "SELECT img, fname, lname FROM users WHERE user_id = $userID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profilePic = $row["img"];
    $fullName = $row["fname"] . ' ' . $row["lname"];
} else {
    // Handle the case where the user data is not found
    $profilePic = "/path/to/default/profile.png"; // Provide a default profile picture path
    $fullName = "User Not Found";
}
$credResults = mysqli_query($conn, "SELECT email FROM users WHERE user_id = $userID");

if (!$credResults) {
    die("Error: " . mysqli_error($conn)); // Display the error message
}

$credRes = mysqli_fetch_array($credResults);

$query = "SELECT DATE_FORMAT(date_created, '%M %d, %Y %h:%i%p') AS formatted_date, amount, donate_for FROM payments WHERE user_id = $userID";
$paymentResult = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/ElderCareALLIANCE/main/try.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>User Dashboard</title>
</head>
<body>
<header class="header">
    <h2 class="logo">
        <img src="/ElderCareALLIANCE/main/logo.png" alt="Logo">
    </h2>
    <div class="address">Sta. Ana - San Joaquin Bahay Ampunan Foundation Inc.
        <div class="sub">Barangay Altura Bata, Tanauan, 4232</div>
    </div>
    <div class="profile">
        <img src="<?php echo $profilePic; ?>" alt="Profile Icon">
        <span id="username">Hi <?php echo strtoupper($fullName); ?>!</span>
    </div>
</header>
  <div class="banner">
    <aside class="sidebar">
      <ul class="tabs">
        <li class="tab active" data-tab="donate">
          <i class="fas fa-donate"></i> Donate Now
        </li>
        <li class="tab" data-tab="dashboard">
          <i class="fas fa-chart-line"></i> Dashboard
        </li>
        <li class="tab" data-tab="settings">
          <i class="fas fa-cog"></i> Settings
        </li>
        <li class="tab" data-tab="support">
          <i class="fas fa-headset"></i> Support
        </li>
      </ul>
      <button class="logout-button" onclick="confirmLogout()">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </aside>
    <main class="main-content">
      <div class="tab-content">
        <div class="tab-pane" id="donate">
          <div class="donate-tab">
          <form method="POST" action="post.php">
            <div class="top">
              <div class="donation-options">
                <img src="blogo.png" alt="Profile Icon">
                <label></label>
                <p>Donate For:
                <input type="radio" name="donation-type" value="Services" id="services-option">
                <label for="services-option" class="choice">Services</label>
                <input type="radio" name="donation-type" value="Programs" id="programs-option">
                <label for="programs-option" class="choice">Programs</label>
                </p>
              </div>
            </div>
            <div class="amount-input">
              <label for="donation-amount">Enter amount (PHP):</label>
              <input type="number" id="donation-amount" name="donation-amount" step="none" placeholder="Enter amount">
              <div class="preset-amounts">
                <button class="preset">500</button>
                <button class="preset">1000</button>
                <button class="preset">3000</button>
                <button class="preset">5000</button>
              </div>
            </div>
            <div class="secure">
              Choose Payment   <span class="lock-symbol">&#128274;</span>  Secure
            </div>
            <div class="payment-buttons">
              <button class="payment-button" id="credit-card-payment">
                <i class="far fa-credit-card"></i>  Card 
              </button>
              <button class="payment-button" id="gcash-payment">
                <i class="fab fa-cc-paypal"></i> GCash 
              </button>
            </div>
          </form>
            <div class="bottom-text">
              <h5> By using trusted payment gateways and web APIs, we can guarantee that your 
                donation will be processed efficiently and effectively, giving you the peace of mind 
                that your contribution is making a real difference in the lives of those in need. <br>
                <br>Rest assured that your donation is in good hands. Thank you for your generosity and kindness.</h5>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="dashboard">
            <!-- Start of Dashboard Tab Content -->
            <div class="wrapper">
            <div class="dashboard-box">
                <h3 class="box-title">Total Donations</h3>
                <?php
                $totalDonationsQuery = "SELECT SUM(amount) AS total_donations FROM payments WHERE user_id = $userID";
                $totalDonationsResult = $conn->query($totalDonationsQuery);
                $totalDonationsRow = $totalDonationsResult->fetch_assoc();
                $totalDonations = $totalDonationsRow['total_donations'];
                ?>
                <p class="box-content">₱ <?= number_format($totalDonations, 2) ?></p>
                <!-- Add the last updated time if available -->
                <p class="box-footer">Updated <?php echo getLastUpdatedTime($userID, $conn); ?></p>
            </div>

            <div class="dashboard-box">
                <h3 class="box-title">Donations Today</h3>
                <?php
                $today = date('Y-m-d');
                $donationsTodayQuery = "SELECT SUM(amount) AS donations_today FROM payments WHERE user_id = $userID AND DATE(date_created) = '$today'";
                $donationsTodayResult = $conn->query($donationsTodayQuery);
                $donationsTodayRow = $donationsTodayResult->fetch_assoc();
                $donationsToday = $donationsTodayRow['donations_today'];
                ?>
                <p class="box-content">₱ <?= number_format($donationsToday, 2) ?></p>
                <!-- Add the last updated time if available -->
                <p class="box-footer">Updated <?php echo getLastUpdatedTime($userID, $conn); ?></p>
            </div>
                <div class="box-container">
                    <div class="box-a">
                    <?php if ($paymentResult->num_rows > 0) : ?>
                        <div class="scrollable-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Donation For</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php while ($row = $paymentResult->fetch_assoc()):?>
                                      <tr>
                                      <td><?= $row['formatted_date']?></td>
                                      <td>₱<?=$row['amount']?></td>
                                      <td><?=$row['donate_for']?></td>
                                      </tr>
                                  <?php endwhile; ?>
                                </tbody>
                            </table>
                            <?php else : ?>
                            <p>No donation found.</p>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Dashboard Tab Content -->
        </div>
        <div class="tab-pane"  id="settings">
          <!-- Settings Tab Content -->
          <div id="credModal" class="modal">
            <div class="modalContent">
              <form class="form2" action="/ElderCareALLIANCE/main/action.php" method="post">
                <h2 style="color:#103d24;">Change Credentials</h2><br>
                <label for="username">Email: </label>
                <input type="text" name="username" id="User" value="<?php echo $credRes['email']?>"><br>
                <label for="old_password">Old Password: </label>
                <input type="password" name="old_password" id="Password"><br>
                <label for="newpassword">New Password: </label>
                <input type="password" name="newpassword" id="newPassword"><br>
                <label for="repass">Re-enter New Password: </label>
                <input type="password" name="repass" id="rePassword"><br>
                <input type="hidden" name="oldUser" value="<?php echo $credRes['email']?>">
                <div class="btn-block margin-side">
                  <input type="submit" name="UpdateCredentials" class="submit-btn" value="Update Credentials">
                </div>
              </form>
            </div>
          </div>
          <!-- End   of Settings Tab Content -->
        </div>
        <div class="tab-pane" id="support">
              <div class="prod">
                <iframe src="/ElderCareALLIANCE/Customer%20Support/" width="1400" height="700"></iframe>
               </div>  
        </div>
      </div>
    </main>
  </div>
 
  <script>
    // Logout button function
    function confirmLogout() {
        // Display a confirmation dialog
        const confirmation = confirm("Are you sure you want to log out?");

        // Check if the user confirmed the logout
        if (confirmation) {
            // Redirect the user to the logout PHP script
            window.location.href = "/ElderCareALLIANCE/main/logout.php";
        }
    }
  </script>
  <script>
              const donationAmountInput = document.getElementById('donation-amount');
              const presetButtons = document.querySelectorAll('.preset');
            
              presetButtons.forEach(button => {
                button.addEventListener('click', () => {
                  const value = button.textContent;
                  donationAmountInput.value = value;
            
                  presetButtons.forEach(btn => btn.classList.remove('selected'));
                  button.classList.add('selected');
                });
              });
  </script>
  <script>
              const creditCardButton = document.getElementById('credit-card-payment');
              const gcashButton = document.getElementById('gcash-payment');
            
              creditCardButton.addEventListener('click', () => {
                if (!hasSelectedChoice() || !hasEnteredAmount()) {
                  window.alert("Please choose a donation option OR enter an amount before proceeding.");
                } else {
                  // Handle credit card payment
                }
              });
            
              gcashButton.addEventListener('click', () => {
                if (!hasSelectedChoice() || !hasEnteredAmount()) {
                  window.alert("Please choose a donation option OR enter an amount before proceeding.");
                } else {
                  // Handle GCash payment
                }
              });
            
              function hasSelectedChoice() {
                return Array.from(choices).some(choice => choice.classList.contains('active'));
              }
            
              function hasEnteredAmount() {
                return donationAmountInput.value.trim() !== '';
              }
            
              choices.forEach(choice => {
                choice.addEventListener('click', () => {
                  choices.forEach(c => c.classList.remove('active'));
                  choice.classList.add('active');
                });
              });
  </script>
  <script>
                  const choices = document.querySelectorAll('.choice');
                
                  choices.forEach(choice => {
                    choice.addEventListener('click', () => {
                      choices.forEach(c => c.classList.remove('active'));
                      choice.classList.add('active');
                    });
                  });
  </script>
<script>
        document.addEventListener('DOMContentLoaded', function () {
    const dashboardTab = document.querySelector('.tab[data-tab="donate"]');
    const dashboardTabPane = document.getElementById('donate');

   
    dashboardTab.classList.add('active');
    dashboardTabPane.style.display = 'block';
  });
    //tab active display script
    const tabs = document.querySelectorAll('.tab');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(tab => tab.classList.remove('active'));
        tabPanes.forEach(pane => pane.style.display = 'none');
        tab.classList.add('active');
        const tabId = tab.getAttribute('data-tab');
        document.getElementById(tabId).style.display = 'block';
      });
    });;
</script>


</body>
</html>
