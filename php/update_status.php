<?php
// update_status.php

// Database connection
$servername = "localhost";
$usernameDB = "root";
$passwordDB = "";
$dbname = "authentication"; // Name of your database

// Create connection
$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the customer's email and the new status
    $email = $_POST['email'];  // Customer's email to update
    $newStatus = $_POST['status'];  // New status selected by the engineer

    // Ensure that the email and status are not empty
    if (empty($email) || empty($newStatus)) {
        die("Email or status cannot be empty.");
    }

    // Prepare the SQL query to update the status using the email
    $sql = "UPDATE contact SET status = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind the parameters ('s' for string, 's' for string)
    $stmt->bind_param("ss", $newStatus, $email);

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
                alert('Complaint status updated successfully.');
                window.location.href = '/Ocs/routes-page/engineer-profile.php'; // Redirect back to the profile page
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
