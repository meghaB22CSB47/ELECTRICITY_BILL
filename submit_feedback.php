<?php 
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root","","login");
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['feedback'])) {
    $complaintId = intval($_POST['id']);
    $feedback = $conn->real_escape_string($_POST['feedback']);

    // Update complaint with feedback and set status to "Resolved"
    $stmt = $conn->prepare("UPDATE complaints SET action = ?, status = 'Resolved' WHERE id = ?");
    $stmt->bind_param('si', $feedback, $complaintId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]); // Ensure this is sent on success
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating feedback: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
