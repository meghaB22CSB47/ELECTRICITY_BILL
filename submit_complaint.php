<?php
session_start();

// Database connection parameters
$conn = new mysqli("localhost", "root","", "login");

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['Userid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the complaint details
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $complaint_info = $conn->real_escape_string($_POST['complaint_info']);
    $userid = $_SESSION['Userid']; // Get the logged-in user's userid

    // SQL query to insert the complaint
    $sql = "INSERT INTO complaints (userid, name, email, complaint_info, status) VALUES ('$userid', '$name', '$email', '$complaint_info', 'Pending')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Complaint submitted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>
