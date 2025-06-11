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

// Initialize a variable to hold the success message
$successMessage = '';

// Handle form submission for updating bill status to "Paid"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['billId'])) {
    $billId = (int)$_POST['billId'];

    // Update the status of the selected bill to "Paid"
    $sqlUpdate = "UPDATE bills SET status = 'Paid' WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("i", $billId);

    if ($stmtUpdate->execute()) {
        $successMessage = "Bill ID $billId has been marked as Paid.";
    } else {
        $successMessage = "Error: Could not update the bill status.";
    }
}

// Fetch all bills from the bills table
$sql = "SELECT * FROM bills";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - View and Update Bills</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .submit-button { padding: 5px 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .submit-button:hover { background-color: #45a049; }
        .message { color: green; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>

<h2>Admin - View Bills and Mark as Paid</h2>

<?php if ($successMessage): ?>
    <p class="message"><?php echo $successMessage; ?></p>
<?php endif; ?>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Month</th>
                <th>Units Consumed</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['userid']); ?></td>
                    <td><?php echo htmlspecialchars($row['month']); ?></td>
                    <td><?php echo htmlspecialchars($row['consumption']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php if ($row['status'] == 'Unpaid'): ?>
                            <form method="POST" action="" style="display:inline;">
                                <input type="hidden" name="billId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="submit-button">Mark as Paid</button>
                            </form>
                        <?php else: ?>
                            <span>Paid</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p class="message">No bills found.</p>
<?php endif; ?>

<?php
// Close connection
$conn->close();
?>

</body>
</html>
