<?php

$host="localhost";
$user="root";
$pass="";
$db="login";
$conn=new mysqli($host,$user,$pass,$db);
if($conn->connect_error){
    echo "Failed to connect DB".$conn->connect_error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>About PowerSphere</title>
    <link rel="stylesheet" href="about.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1>PowerSphere</h1>
            </div>
            <ul class="nav-links">
                <li><a href="start.php">Home</a></li>
                <li><a href="#">Features</a></li>
            </ul>
        </nav>
    </header>

    <section class="about-section">
        <div class="about-content">
            <h2>About PowerSphere</h2>
            <p>PowerSphere is an innovative platform designed to simplify the way you manage your electricity bills. We provide a one-stop solution for tracking, paying, and analyzing your electricity consumption, all from one convenient dashboard.</p>
            
            <h3>Our Mission</h3>
            <p>Our mission is to empower individuals and businesses to take control of their energy usage, reduce unnecessary costs, and simplify the process of managing electricity bills. We aim to bring transparency and ease into your monthly bill management.</p>

            <h3>Key Features</h3>
            <ul>
                <li>Easy Bill Tracking</li>
                <li>Automatic Payments Setup</li>
                <li>Energy Usage Analytics</li>
                <li>Customizable Alerts and Reminders</li>
                <li>Cost-Saving Insights</li>
            </ul>

            <h3>Why Choose PowerSphere?</h3>
            <p>With PowerSphere, you’ll never have to worry about missed payments, confusing energy bills, or excessive electricity costs again. We give you real-time insights and control over your energy consumption, helping you save both time and money.</p>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 PowerSphere. All rights reserved.</p>
    </footer>
</body>
</html>
