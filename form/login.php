<?php
session_start();

// Database connection settings
$servername = "localhost";
$username = "root";  // Adjust if your MySQL username is different
$password = "";  // Adjust if your MySQL password is different
$dbname = "login_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database to find the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
        
        // Verify the password (assuming passwords are hashed using password_hash)
        if (password_verify($password, $user['password'])) {
            // Password is correct, start the session
            $_SESSION['email'] = $user['email'];
            echo "Login successful! Welcome " . $user['email'];
            // Redirect to a dashboard or another page (optional)
            // header('Location: dashboard.php');
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email!";
    }
}
?>
