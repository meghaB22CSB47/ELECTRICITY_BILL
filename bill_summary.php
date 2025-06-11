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

// Initialize userId variable
$userId = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Retrieve bill details for the user
    $sql = "SELECT month, year, unitsConsumed, ratePerUnit, energyCharges, taxes, totalAmount
            FROM bill
            WHERE userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bill Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container, .bill-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container h2, .bill-container h2 {
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
            background-color: #4CAF50;
            color: white;
        }
        .total {
            font-weight: bold;
            background-color: #f4f4f4;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        .submit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Enter User ID</h2>
        <form method="POST" action="">
            <input type="text" name="userId" class="input-field" placeholder="Enter User ID" required>
            <button type="submit" class="submit-button">View Bill Summary</button>
        </form>
    </div>

    <?php if (!empty($userId)): ?>
    <div class="bill-container">
        <h2>Monthly Bill Summary for User: <?php echo htmlspecialchars($userId); ?></h2>
        <table>
            <tr>
                <th>Month</th>
                <th>Year</th>
                <th>Units Consumed</th>
                <th>Rate/Unit</th>
                <th>Energy Charges</th>
                <th>Taxes</th>
                <th>Total Amount</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['month']); ?></td>
                    <td><?php echo htmlspecialchars($row['year']); ?></td>
                    <td><?php echo htmlspecialchars($row['unitsConsumed']); ?></td>
                    <td><?php echo htmlspecialchars($row['ratePerUnit']); ?></td>
                    <td><?php echo htmlspecialchars($row['energyCharges']); ?></td>
                    <td><?php echo htmlspecialchars($row['taxes']); ?></td>
                    <td class="total"><?php echo htmlspecialchars($row['totalAmount']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <?php endif; ?>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
