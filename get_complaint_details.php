<?php
session_start(); // Start the session
header('Content-Type: application/json'); // Set the content type to JSON

$conn = new mysqli("localhost", "root","", "login");

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and parameters are set
if (!isset($_SESSION['Userid']) || !isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit();
}

// Get the logged-in user's userid and complaint id
$userid = $_SESSION['Userid'];
$complaintId = $_GET['id'];

// Prepare the SQL query to fetch the specific complaint
$sql = "SELECT * FROM complaints WHERE userid = ? AND id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('si', $userid, $complaintId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $complaint = $result->fetch_assoc();
    echo json_encode(['success' => true, 'complaint' => $complaint]);
} else {
    echo json_encode(['success' => false, 'message' => 'Complaint not found or access denied.']);
}

$stmt->close();
$conn->close();
?>
