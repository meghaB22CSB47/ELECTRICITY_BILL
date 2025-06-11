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
$result = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'])) {
    $userId = $_POST['userId'];

    // Fetch billing records for the specified user ID
    $sql = "SELECT userId, month, year, SUM(unitsConsumed) as unitsConsumed, 
            AVG(ratePerUnit) as ratePerUnit, SUM(energyCharges) as energyCharges, 
            SUM(taxes) as taxes, SUM(totalAmount) as totalAmount 
            FROM bill
            WHERE userId = ?
            GROUP BY userId, year, month 
            ORDER BY year, month";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Monthly Consumption Report - PowerSphere</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Monthly Consumption Report for User</h1>
    </header>
    
    <main>
        <!-- Form to request userId -->
        <form method="POST" action="">
            <label for="userId">Enter User ID:</label>
            <input type="text" id="userId" name="userId" required placeholder="Enter User ID">
            <button type="submit">Generate Report</button>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <h2>Report for User ID: <?php echo htmlspecialchars($userId); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Units Consumed</th>
                        <th>Rate per Unit</th>
                        <th>Energy Charges</th>
                        <th>Taxes</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['month']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo htmlspecialchars($row['unitsConsumed']); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['ratePerUnit'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['energyCharges'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['taxes'], 2)); ?></td>
                            <td><?php echo htmlspecialchars(number_format($row['totalAmount'], 2)); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($userId): ?>
            <p>No records found for User ID: <?php echo htmlspecialchars($userId); ?></p>
        <?php endif; ?>
    </main>
    
    <footer>
        <p>&copy; 2024 PowerSphere. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
