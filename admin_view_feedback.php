<?php
// Database connection details
$host = 'localhost';
$dbname = 'login';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all feedback from the database
$sql = "SELECT userId, feedbackText, feedbackDate FROM feedback ORDER BY feedbackDate DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - View Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            background-image: url('bckg12.png');
            background-size: cover;
        }
        .feedback-container {
            max-width: 800px;
            margin: auto;
            margin-top: 150px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: rgb(6, 6, 129);
            color: white;
        }
        .feedback-item {
            background: #e9e9e9;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .feedback-text {
            font-style: italic;
        }
        .feedback-date {
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="feedback-container">
    <h2>User Feedback</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Feedback</th>
            <th>Date</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['userId']); ?></td>
                    <td class="feedback-text"><?php echo htmlspecialchars($row['feedbackText']); ?></td>
                    <td class="feedback-date"><?php echo htmlspecialchars($row['feedbackDate']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No feedback available.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
