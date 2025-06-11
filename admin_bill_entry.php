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
$userId = $month = $year = $unitsConsumed = $ratePerUnit = $energyCharges = $taxes = $totalAmount = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $unitsConsumed = $_POST['unitsConsumed'];
    $ratePerUnit = $_POST['ratePerUnit'];
    $energyCharges = $_POST['energyCharges'];
    $taxes = $_POST['taxes'];
    $totalAmount = $_POST['totalAmount'];

    // Insert the bill details into the database
    $sql = "INSERT INTO bill (userId, month, year, unitsConsumed, ratePerUnit, energyCharges, taxes, totalAmount)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiiiddi", $userId, $month, $year, $unitsConsumed, $ratePerUnit, $energyCharges, $taxes, $totalAmount);

    if ($stmt->execute()) {
        $successMessage = "Bill details successfully entered for User ID: " . htmlspecialchars($userId);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Enter Bill Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            background-image:url('bill.png');
        }
        .form-container {
            max-width: 600px;
            margin: auto;
            background:#228cba ;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        .submit-button {
            background-color: #026393;
            color: white;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-left: 230px;
        }
        .submit-button:hover {
            background-color: rgb(6, 76, 147);
        }
        .message {
            color: green;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Admin - Enter Bill Details</h2>
    <?php if ($successMessage): ?>
        <p class="message"><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="userId" class="input-field" placeholder="Enter User ID" required>
        <input type="text" name="month" class="input-field" placeholder="Enter Month (e.g. January)" required>
        <input type="number" name="year" class="input-field" placeholder="Enter Year (e.g. 2024)" required>
        <input type="number" name="unitsConsumed" class="input-field" placeholder="Enter Units Consumed" required>
        <input type="number" step="0.01" name="ratePerUnit" class="input-field" placeholder="Enter Rate Per Unit" required>
        <input type="number" step="0.01" name="energyCharges" class="input-field" placeholder="Enter Energy Charges" required>
        <input type="number" step="0.01" name="taxes" class="input-field" placeholder="Enter Taxes" required>
        <input type="number" step="0.01" name="totalAmount" class="input-field" placeholder="Enter Total Amount" required>
        <button type="submit" class="submit-button">Submit Bill Details</button>
    </form>
</div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
