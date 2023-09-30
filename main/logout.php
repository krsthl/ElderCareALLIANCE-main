<?php
// Start the session
session_start();

// Destroy the session data
session_destroy();

// Redirect the user to the login page or any other desired page
header("Location: /ElderCareALLIANCE/main/index.php");
exit();
?>