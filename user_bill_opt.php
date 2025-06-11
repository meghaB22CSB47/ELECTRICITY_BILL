<?php 
include 'connect.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Management Interface</title>
    <link rel="stylesheet" href="user_bill_opt.css">
</head>
<body>
    <div class="container">
        <h1>Bill Management</h1>
        <div class="button-container">
            <button class="view-bills" onclick="window.location.href='user_bil.php'">View and Pay</button>
            <button class="add-bill" onclick="window.location.href='bill_summary.php'">Detailed Bill</button>
            <button class="mark-bill" onclick="window.location.href='export_billing.php'">Download Complete Bill</button>
        </div>
    </div>
</body>
</html>
