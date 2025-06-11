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
$successMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userId'], $_POST['month'], $_POST['consumption'], $_POST['amount'])) {
    $userId = $_POST['userId'];
    $month = $_POST['month'];
    $consumption = (int) $_POST['consumption'];
    $amount = (float) $_POST['amount'];

    // Insert new bill into the bills table
    $sql = "INSERT INTO bills (Userid, Month, Consumption, Amount, Status) VALUES (?, ?, ?, ?, 'Unpaid')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $userId, $month, $consumption, $amount);

    if ($stmt->execute()) {
        $successMessage = "New bill added successfully for User ID: " . htmlspecialchars($userId);
    } else {
        $successMessage = "Error: Could not add bill.";
    }
}

// Fetch all users to display in the dropdown
$sqlUsers = "SELECT userid, firstname, lastname FROM users";
$resultUsers = $conn->query($sqlUsers);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Add New Bill</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .form-container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px #aaa; max-width: 400px; margin: auto; }
        .form-container h2 { text-align: center; }
        .input-field, select, .submit-button { width: 100%; padding: 10px; margin-bottom: 10px; }
        .submit-button { background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .submit-button:hover { background-color: #45a049; }
        .message { color: green; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Bill</h2>

    <form method="POST" action="">
        <label for="userId">Select User:</label>
        <select name="userId" required>
            <option value="">-- Select User --</option>
            <?php while ($row = $resultUsers->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($row['userid']); ?>">
                    <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . " (" . htmlspecialchars($row['userid']) . ")"; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="month">Month (e.g., 2024-10):</label>
        <input type="text" name="month" class="input-field" placeholder="Enter Month" required>

        <label for="consumption">Units Consumed:</label>
        <input type="number" name="consumption" class="input-field" placeholder="Enter Units Consumed" required>

        <label for="amount">Amount (e.g., 150.50):</label>
        <input type="text" name="amount" class="input-field" placeholder="Enter Amount" required>

        <button type="submit" class="submit-button">Add Bill</button>
    </form>

    <?php if ($successMessage): ?>
        <p class="message"><?php echo $successMessage; ?></p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
