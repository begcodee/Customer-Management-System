<?php
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $identifier = $_POST['identifier']; // This could be email or username
    $password = $_POST['password'];     // Plain text password

    // Create a connection to the database
    $servername = "localhost";
    $username = "root";  // Default MySQL username
    $passwordDB = "";    // Default MySQL password
    $dbname = "authentication"; // Your database name

    $conn = new mysqli($servername, $username, $passwordDB, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to find the user by identifier (email or username)
    $sql = "SELECT name, email, password, role FROM user WHERE email = ? OR name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $identifier, $identifier); // Bind parameters to prevent SQL injection

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        // User found, verify the password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, set up session
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect to dashboard or home page based on role
            if ($user['role'] == 'enginer') {
                header("Location: /Ocs/routes-page/engineer-profile.html");
            } else {
                header("Location: /Ocs/routes-page/user-profile.html");
            }
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href = 'login.html';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('No user found with this identifier. Please check your details.'); window.location.href = 'login.html';</script>";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>
