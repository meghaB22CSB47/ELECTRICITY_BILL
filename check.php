<?php
session_start(); // Start the session to access user ID

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in


$userid = $_SESSION['userid']; // Get the user ID from session

// Connect to the database
$conn = new mysqli("localhost", "root", "", "login");

// Check the database connection
if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

// Prepare the SQL query to fetch complaints for the logged-in user
$sql = "SELECT id, userid, status, action, created_at FROM complaints WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $userid); // 's' for string (user ID)
$stmt->execute();
$result = $stmt->get_result(); // Get the result set

// Store complaints in an array
$previousComplaints = [];
while ($row = $result->fetch_assoc()) {
    $previousComplaints[] = $row;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Previous Complaints</title>
</head>
<body>
    <h1>Your Previous Complaints</h1>

    <?php if (count($previousComplaints) > 0): ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <th>userid</th>
                <th>Status</th>
                <th>Action</th>
                <th>Date Submitted</th>
            </tr>
            <?php foreach ($previousComplaints as $complaint): ?>
                <tr>
                    <td><?php echo htmlspecialchars($complaint['id']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['userid']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['status']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['action']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No previous complaints found.</p>
    <?php endif; ?>
</body>
</html>
