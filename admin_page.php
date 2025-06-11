<?php 
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "login");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all complaints that are not cancelled and include those with status 'Pending'
$sql = "SELECT * FROM complaints WHERE status != 'Cancelled' AND status != 'Feedback Provided'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="admin_styles.css"> <!-- Include your CSS file for Admin -->
</head>
<body>

<div id="admin-container">
    <h1>Admin Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Complaint Info</th>
                <th>Status</th>
                <th>Action</th>
                <th>Feedback</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['complaint_info']; ?></td>
                        <td class="status-cell"><?php echo $row['status']; ?></td>
                        <td class="action-cell"><?php echo $row['action']; ?></td>
                        <td>
                            <input type="text" class="feedback" data-id="<?php echo $row['id']; ?>" placeholder="Enter feedback" 
                                   <?php if ($row['status'] == 'Resolved') echo 'disabled'; ?>>
                            <button class="submit-feedback" data-id="<?php echo $row['id']; ?>" 
                                    <?php if ($row['status'] == 'Resolved') echo 'style="display:none;"'; ?>>Submit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No complaints found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="admin_script.js"></script> <!-- Include your JavaScript file -->
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>
