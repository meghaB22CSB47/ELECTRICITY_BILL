<?php
session_start();
header('Content-Type: application/json');

// Database connection parameters
$conn = new mysqli("localhost", "root","", "login");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['Userid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$complaintId = $_POST['id'];
$userid = $_POST['userid'];

// Ensure that only the logged-in user can cancel their own complaints
$sql = "UPDATE complaints SET status = 'Cancelled', action = 'Complaint Cancelled' WHERE id = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $complaintId, $userid);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['success' => true, 'message' => 'Complaint successfully cancelled.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel complaint.']);
}

$stmt->close();
$conn->close();
?>