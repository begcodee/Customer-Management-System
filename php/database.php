<?php
// Database credentials
$servername = "localhost";
$username = "root";  // Default XAMPP MySQL username
$password = "";      // Default XAMPP MySQL password (empty)
$dbname = "authentication"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    $role = $_POST['role'];

    // Create connection
    $servername = "localhost";
    $usernameDB = "root";
    $passwordDB = "";
    $dbname = "authentication";
    
    $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute insert query
    $sql = "INSERT INTO user (name, password, email, role) VALUES ('$username', '$password', '$email', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('User created successfully! Redirecting to login...');
              </script>";
        header("Location: /Ocs/auth/login.html");
        exit();        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
