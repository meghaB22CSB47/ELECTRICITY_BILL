<?php
session_start(); // Start the session
header('Content-Type: application/json'); // Set the content type to JSON

$conn = new mysqli("localhost", "root", "", "login");

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['Userid'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Get the logged-in user's userid
$userid = $_SESSION['Userid'];

// Prepare the SQL query to fetch complaints for the logged-in user
$sql = "SELECT * FROM complaints WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $userid);
$stmt->execute();
$result = $stmt->get_result();

$complaints = []; // Initialize an array to hold the complaints

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Push each complaint into the array
        $complaints[] = [
            "id" => $row["id"],
            "userid" => $row["userid"],
            "name" => $row["name"],
            "info" => $row["complaint_info"],
            "status" => $row["status"],
            "action" => $row["action"] // Assuming 'action' column exists
        ];
    }
}

// Return the complaints as a JSON response
echo json_encode($complaints);

$stmt->close();
$conn->close();
?>
