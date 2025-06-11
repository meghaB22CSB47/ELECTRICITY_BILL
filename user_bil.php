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

// Initialize variables
$userId = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // If the payment button is clicked, mark the selected bills as paid
    if (!empty($_POST['billIds'])) {
        $billIds = $_POST['billIds'];
        $billIdsPlaceholder = implode(',', array_fill(0, count($billIds), '?'));

        // Update the status of the selected bills to "Paid"
        $sqlUpdate = "UPDATE bills SET status = 'Paid' WHERE id IN ($billIdsPlaceholder)";
        $stmtUpdate = $conn->prepare($sqlUpdate);

        // Bind bill IDs dynamically
        $stmtUpdate->bind_param(str_repeat('i', count($billIds)), ...$billIds);
        if ($stmtUpdate->execute()) {
            $successMessage = "Payment successful!";
        } else {
            $successMessage = "Error: Could not process payment.";
        }
    }

    // Fetch bills for the specific user
    $sql = "SELECT * FROM bills WHERE userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Pay Bills</title>
    <style>
        /* General Layout */
        body {
            font-family: Arial, sans-serif;
            background-color: #eaf4ea;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #2d502d;
        }

        h2 {
            color: #2d502d;
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        /* Form Styles */
        form {
            width: 100%;
            max-width: 500px;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #a0cda0;
            border-radius: 5px;
            background-color: #f8fcf8;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #43a047;
        }

        /* Table Styles */
        table {
            width: 100%;
            max-width: 800px;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #a0cda0;
        }

        th {
            background-color: #4caf50;
            color: white;
            font-weight: bold;
        }

        td input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #4caf50; /* Green accent color */
        }

        /* Success Message */
        .message {
            color: #4caf50;
            font-weight: bold;
            margin-top: 20px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>

<h2>Enter User ID to View Bills</h2>

<form method="POST" action="">
    <input type="text" name="userId" class="input-field" placeholder="Enter User ID" required>
    <button type="submit" class="submit-button">View Bills</button>
</form>

<?php if (!empty($userId) && $result): ?>
    <h2>Bill Summary for User: <?php echo htmlspecialchars($userId); ?></h2>

    <form method="POST" action="">
        <input type="hidden" name="userId" value="<?php echo htmlspecialchars($userId); ?>">
        <table>
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Month</th>
                    <th>Units Consumed</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if ($row['status'] == 'Unpaid'): ?>
                            <input type="checkbox" name="billIds[]" value="<?php echo $row['id']; ?>">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['month']); ?></td>
                    <td><?php echo htmlspecialchars($row['consumption']); ?></td>
                    <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <button type="submit" class="submit-button">Pay Selected Bills</button>
    </form>

<?php endif; ?>

<?php if ($successMessage): ?>
    <p class="message"><?php echo $successMessage; ?></p>
<?php endif; ?>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
