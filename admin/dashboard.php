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

function getLastUpdatedTime( $conn) {
  $lastUpdatedQuery = "SELECT MAX(date_created) AS last_updated FROM payments";
  $lastUpdatedResult = $conn->query($lastUpdatedQuery);
  $lastUpdatedRow = $lastUpdatedResult->fetch_assoc();

  if ($lastUpdatedRow['last_updated']) {
      $lastUpdatedTime = strtotime($lastUpdatedRow['last_updated']);
      $currentTime = time();
      $timeDifference = $currentTime - $lastUpdatedTime;

      if ($timeDifference < 60) {
          // Less than a minute ago
          return  " a few seconds ago";
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
function getLastUpdatedTimeForDonors($conn) {
  $lastUpdatedQuery = "SELECT MAX(date_registered) AS last_updated FROM users";
  $lastUpdatedResult = $conn->query($lastUpdatedQuery);
  $lastUpdatedRow = $lastUpdatedResult->fetch_assoc();
  
  if ($lastUpdatedRow['last_updated']) {
      $lastUpdatedTime = strtotime($lastUpdatedRow['last_updated']);
      $currentTime = time();
      $timeDifference = $currentTime - $lastUpdatedTime;

      if ($timeDifference < 60) {
          // Less than a minute ago
          return" a few seconds ago";
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
      return 'N/A'; // No donor data found
  }
}


session_start();

if (!isset($_SESSION["user_role"])) {
    // Redirect to the login page if the user is not logged in
    header("Location: /ElderCareALLIANCE/admin/index.php");
    exit();
}

$userRole = $_SESSION["user_role"];

if ($userRole === "admin") {
    // Display admin dashboard content
    // ...
} elseif ($userRole === "staff") {
    // Display staff dashboard content
    // ...
}
$credResults = mysqli_query($conn, "SELECT username FROM admin");
$credRes = mysqli_fetch_array($credResults); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/ElderCareALLIANCE/admin/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Admin Dashboard</title>
</head>
<body>
  <header class="header">
    <h2 class="logo">
      <img src="logo.png" alt="Logo">
</h2> 
<div class="address">Sta. Ana -  San Joaquin Bahay Ampunan Foundation Inc. 
  <div class="sub">Barangay Altura Bata, Tanauan, 4232</div> 
</div>

    <div class="profile">
      <img src="/admin/profile.png" alt="Profile Icon">
      <span id="username">Welcome Admin!</span>
    </div>
  </header>
  <div class="banner">
    <aside class="sidebar">
      <ul class="tabs">
      <li class="tab" data-tab="dashboard">
          <i class="fas fa-chart-line"></i> Dashboard
        </li>
        <li class="tab " data-tab="donor-info">
          <i class="fas fa-user"></i> Donor Info
        </li>
        <li class="tab" data-tab="finance-management">
          <i class="fas fa-money-bill-wave"></i> Finance 
        </li>
        <li class="tab" data-tab="settings">
          <i class="fas fa-cog"></i> Settings
        </li>
      </ul>
      <button class="logout-button" onclick="confirmLogout()">
        <i class="fas fa-sign-out-alt"></i> Log Out
      </button>
    </aside>
    <main class="main-content">
      <div class="tab-content">
        <div class="tab-pane" id="dashboard">
        <div class="wrapper">
        <div class="dashboard-box">
                <h3 class="box-title">Total Donations</h3>
                <?php
                $totalDonationsQuery = "SELECT SUM(amount) AS total_donations FROM payments";
                $totalDonationsResult = $conn->query($totalDonationsQuery);
                $totalDonationsRow = $totalDonationsResult->fetch_assoc();
                $totalDonations = $totalDonationsRow['total_donations'];
                ?>
                <p class="box-content">₱ <?= number_format($totalDonations, 2) ?></p>
                <!-- Add the last updated time if available -->
                <p class="box-footer">Updated <?php echo getLastUpdatedTime($conn); ?></p>
            </div>

            <div class="dashboard-box">
                <h3 class="box-title">Donations Today</h3>
                <?php
                $today = date('Y-m-d');
                $donationsTodayQuery = "SELECT SUM(amount) AS donations_today FROM payments WHERE DATE(date_created) = '$today'";
                $donationsTodayResult = $conn->query($donationsTodayQuery);
                $donationsTodayRow = $donationsTodayResult->fetch_assoc();
                $donationsToday = $donationsTodayRow['donations_today'];
                ?>
                <p class="box-content">₱ <?= number_format($donationsToday, 2) ?></p>
                <!-- Add the last updated time if available -->
                <p class="box-footer">Updated <?php echo getLastUpdatedTime($conn); ?></p>
            </div>
          <div class="dashboard-box">
          <h3 class="box-title">Total Donors</h3>
            <?php
            $totalDonorsQuery = "SELECT COUNT(*) AS total_donors FROM users";
            $totalDonorsResult = $conn->query($totalDonorsQuery);
            $totalDonorsRow = $totalDonorsResult->fetch_assoc();
            $totalDonors = $totalDonorsRow['total_donors'];
            ?>
            <p class="box-content"><?= $totalDonors ?></p>
            <p class="box-footer">Updated <?php echo getLastUpdatedTimeForDonors($conn); ?></p>
          </div>
          <div class="dashboard-box">
              <h3 class="box-title">New Donors</h3>
              <?php
              $today = date('Y-m-d');
              $newDonorsQuery = "SELECT COUNT(*) AS new_donors FROM users WHERE DATE(date_registered) = '$today'";
              $newDonorsResult = $conn->query($newDonorsQuery);
              $newDonorsRow = $newDonorsResult->fetch_assoc();
              $newDonors = $newDonorsRow['new_donors'];
              ?>
              <p class="box-content"><?= $newDonors ?></p>
              <p class="box-footer">Updated <?php echo getLastUpdatedTimeForDonors($conn); ?></p>
          </div>
          <div class="box-container">
              <div class="box-a">
                <div id="curve_chart" name="curve_chart" style="width: 550px; height: 300px; margin-top:20px;"></div> 
              </div>
          </div>  
          <div class="right-container">
              <div class="low-right">
                <div class="title">Income & Expenses</div>
                  <button class="view-button">View more Details</button>
                  <br>
                    <div class="income">  ₱ 54,490.00</div>
                    <div class="expenses">₱ 33,200.00</div>
                    <div class="legend">
                      <div class="legend-item">
                        <span class="dot income-dot"></span> Income
                      </div>
                      <div class="legend-item">
                        <span class="dot expenses-dot"></span> Expenses
                      </div>
                  </div>
              </div>
              <div class="low-right2">
                <div class="title">Top Donors</div>
                  <table class="top-donors-table">
                    <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>Count</th>
                      <th>Last D..</th>
                    </tr>
                    <tr class="top-donor-row">    
                      <td class="top-donor-cell">1</td>
                      <td class="top-donor-cell"><div class="profile-image"></div></td>
                      <td class="top-donor-cell">
                        <div>Juan Dela Cruz</div>
                        <div class="location-icon">Ilo-ilo City</div>
                      </td>
                      <td class="top-donor-cell">42</td>
                      <td class="top-donor-cell">
                          <div>₱500.00</div>
                        <div> 1 hour ago</div>
                      </td>
                    </tr>
                    <tr class="top-donor-row">
                      <td class="top-donor-cell">2</td>
                      <td class="top-donor-cell"><div class="profile-image"></div></td>
                      <td class="top-donor-cell">
                        <div>Jose Marie Chan</div>
                        <div class="location-icon">Batangas CIty</div>
                      </td>
                      <td class="top-donor-cell">30</td>
                      <td class="top-donor-cell">
                          <div>₱800.00</div>
                        <div> 1 hour ago</div>
                      </td>
                      </tr>
                  </table>
                </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="donor-info">
          <h2>Donor's Information</h2>
          <input type="text" id="searchInput" placeholder="Search by User ID">
          <div id="resultTable">
              <?php include 'fetch_users.php'; ?>
          </div>
        </div>


        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function filterTable(searchValue) {
                $.ajax({
                    type: "GET",
                    url: "search_users.php",
                    data: { search: searchValue },
                    success: function (response) {
                        $("#resultTable").html(response);
                    }
                });
            }

            $("#searchInput").on("keyup", function () {
                var searchValue = $(this).val();
                filterTable(searchValue);
            });
        </script>

        <div class="tab-pane" id="finance-management">
          <h1>Expenses</h1>
          <input type="text" id="searchExpensesInput" placeholder="Search by ID">
          <button id="addExpenseButton" class="save">Add Expense</button>

          <!-- Display expenses table here -->
          <div id="expensesTable">
              <?php include 'fetch_expenses.php'; ?>
          </div>

          <!-- Add a modal for adding expenses -->
          <div id="addExpenseModal" class="modal2" style="display: none;">
              <!-- Modal content -->
              <div class="modal-content2">
                  <!-- Close button -->
                  <span class="close" id="closeModalButton">&times;</span>
                  <h2>Add New Expense</h2>
                  <div class="modal-body">
                           <!-- Date field with the current date automatically populated -->
                           <label for="date">Date:</label>
                          <input type="text" id="date" name="date" value="<?php echo date('Y-m-d H:i:s'); ?>" disabled><br>  
                          <!-- No 'id' input field since it's auto-incremented -->
                          <label for="description">Description:</label>
                          <input type="text" id="description" name="description" required><br>
                          <label for="amount">Amount:</label>
                          <input type="text" id="amount" name="amount" required><br>

                          <button type="submit" class="save">Save Expense</button>
                
                  </div>
              </div>
          </div>
          <script>
              // Handle search input for expenses
              $("#searchExpensesInput").keyup(function () {
                  var searchValue = $(this).val();
                  console.log("Search value: " + searchValue); // Add this line for debugging
                  $.ajax({
                      type: "POST",
                      url: "search_expenses.php",
                      data: { search2: searchValue },
                      success: function (response) {
                          $("#expensesTable").html(response);
                      }
                  });
              });
          </script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
          <script src="scripts.js"></script>
        </div>
        <div class="tab-pane" id="settings">
         <!-- Settings Tab Content -->
            <div id="credModal" class="modal">
            <div class="modalContent">
              <form action="/ElderCareALLIANCE/admin/action2.php" method="post">
                <h2 style="color:#103d24;">Change Credentials</h2><br>
                <label for="username">Username: </label>
                <input type="text" name="username" id="User" value="<?php echo $credRes['username']?>"><br>
                <label for="old_password">Old Password: </label>
                <input type="password" name="old_password" id="Password"><br>
                <label for="newpassword">New Password: </label>
                <input type="password" name="newpassword" id="newPassword"><br>
                <label for="repass">Re-enter New Password: </label>
                <input type="password" name="repass" id="rePassword"><br>
                <input type="hidden" name="oldUser" value="<?php echo $credRes['username']?>">
                <div class="btn-block margin-side">
                  <input type="submit" name="UpdateCredentials" class="submit-btn" value="Update Credentials">
                </div>
              </form>
            </div>
          </div>
          <!-- End   of Settings Tab Content -->
        </div>
      </div>
    </main>
  </div>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);

        var options = {
          title: 'Donation Analytics',
          curveType: 'function',
          legend: { position: 'bottom' },
          colors: ['green', '#ffe603'] 
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  <script>

  // variable declaration
  var addModal = document.getElementById("addModal");
  var credModal = document.getElementById("credModal");
  var editModal = document.getElementById("editModal");

  // Get the button that opens the modal
  var addBtn = document.getElementById("addBtn");
  var editCredBtn = document.getElementById("editCredBtn");

  // Get the <span> element that closes the modal
  var addSpan = document.getElementsByClassName("close")[0];
  var editSpan = document.getElementsByClassName("close")[1];
  var credSpan = document.getElementsByClassName("close")[2];

  // When the user clicks the button, open the modal 
  addBtn.onclick = function() {
    addModal.style.display = "block";
  }

  editCredBtn.onclick = function() {
    credModal.style.display = "block";
  }

  // When the user clicks on <span> (x), close the modal
  addSpan.onclick = function() {
    addModal.style.display = "none";
  }

  editSpan.onclick = function() {
    editModal.style.display = "none";
  }

  credSpan.onclick = function() {
    credModal.style.display = "none";
  }

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == addModal || event.target == editModal || event.target == credModal) {
      addModal.style.display = "none";
      editModal.style.display = "none";
      credModal.style.display = "none";
    }
  }

  // creates a trigger to access the edit modal with the correct values
  var tableBody = document.getElementById("tableBody");

  tableBody.onclick = function() {
    editModal.style.display = "block";
  };	

  // assigns the value of the row to the edit modal
  for (let index = 0; index < tableBody.rows.length; index++) {

    tableBody.rows[index].onclick = function() {
      document.getElementById("editPlatenumber").value = this.cells[0].innerHTML;

      if (this.cells[8].innerHTML == "Processing") {
        document.getElementById("editProcessing").checked = true;
      } else if (this.cells[8].innerHTML == "Completed") {
        document.getElementById("editCompleted").checked = true;
      } else {
        document.getElementById("editCancelled").checked = true;
      }

      document.getElementById("total").value = Number(this.cells[6].innerHTML) +  Number(this.cells[7].innerHTML);
      document.getElementById("Parts").value = this.cells[6].innerHTML;
      document.getElementById("Labor").value = this.cells[7].innerHTML;
      document.getElementById("editID").value = this.cells[9].innerHTML;
    };
  };

  // variable to get the current day in which the add data is triggered
  var today = new Date();
  document.getElementById('StartDate').valueAsDate = today;

  // imports the api from the dataTables
  $(document).ready(function () {
    $('#dataTable').DataTable({
      initComplete: function () {
        this.api()
          .columns()
          .every(function () {
            var column = this;
            var select = $('<select><option value=""></option></select>')
              .appendTo($(column.footer()).empty())
              .on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                column.search(val ? '^' + val + '$' : '', true, false).draw();
              });

            column
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>');
              });
          });
      },
    });
  });

  //autosummation code
  $(document).ready(function(){
    $("#Parts, #Labor").keyup(function(){
      
      var total = 0;
      var part = Number($("#Parts").val()); // gets the value of the field by using ID selectors
      var labor = Number($("#Labor").val());
      var total = part + labor;

      $("#total").val(total); // assigns the total to the input field with the ID total
    });
  });


  </script>

  <script>
        document.addEventListener('DOMContentLoaded', function () {
    const dashboardTab = document.querySelector('.tab[data-tab="dashboard"]');
    const dashboardTabPane = document.getElementById('dashboard');

   
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
    });
    //logout button function
    function confirmLogout() {
    // Display a confirmation dialog
     const confirmation = confirm("Are you sure you want to log out?");

    // Check if the user confirmed the logout
    if (confirmation) {
        // Redirect the user to the logout URL or perform any other logout action
        // For example, you can redirect to a logout PHP script:
        window.location.href = "/ElderCareALLIANCE/admin/index.php";
    }
}
  </script>
</body>
</html>