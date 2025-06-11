<?php
// Database connection details
$host = 'localhost';
$dbname = 'login';
$username = 'root';
$password = "";

// Create database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the User ID from the request (via GET or POST)
$userid = $_GET['userid'];

// SQL query to retrieve the user's billing history
$sql = "SELECT Id, Userid, Month, Consumption, Amount, Status FROM bills WHERE Userid = '$userid'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Set headers to trigger file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="billing_history.csv"');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output column headings if needed
    fputcsv($output, array('Id', 'User ID', 'Month', 'Consumption', 'Amount', 'Status'));

    // Output each row of the data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close file pointer
    fclose($output);
} else {
    echo "No billing records found for this user.";
}

// Close the database connection
$conn->close();
?>
