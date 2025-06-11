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
$feedbackMessage = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get userId and feedback from form input
    $userId = $_POST['userId'];
    $feedbackText = $_POST['feedbackText'];

    if (!empty($userId) && !empty($feedbackText)) {
        // Insert feedback into the database
        $sql = "INSERT INTO feedback (userId, feedbackText) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userId, $feedbackText);
        
        if ($stmt->execute()) {
            $feedbackMessage = "Thank you for your feedback!";
        } else {
            $error = "Error: Could not save feedback.";
        }
    } else {
        $error = "Please provide a valid User ID and feedback.";
    }
}

// Retrieve the latest feedback for the user if userId is set
$feedbacks = [];
if (!empty($userId)) {
    $sql = "SELECT feedbackText, feedbackDate FROM feedback WHERE userId = ? ORDER BY feedbackDate DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>User Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .form-container, .feedback-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container h2, .feedback-container h2 {
            margin-bottom: 20px;
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
        .feedback-list {
            list-style-type: none;
            padding: 0;
        }
        .feedback-item {
            background: #e9e9e9;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Submit Feedback</h2>
        <form method="POST" action="">
            <input type="text" name="userId" class="input-field" placeholder="Enter User ID" value="<?php echo htmlspecialchars($userId); ?>" required>
            <textarea name="feedbackText" class="input-field" placeholder="Enter your feedback here..." required></textarea>
            <button type="submit" class="submit-button">Submit Feedback</button>
        </form>
        <?php if ($feedbackMessage): ?>
            <p class="success-message"><?php echo htmlspecialchars($feedbackMessage); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>

    <?php if (!empty($feedbacks)): ?>
    <div class="feedback-container">
        <h2>Your Recent Feedback</h2>
        <ul class="feedback-list">
            <?php foreach ($feedbacks as $feedback): ?>
                <li class="feedback-item">
                    <p><?php echo htmlspecialchars($feedback['feedbackText']); ?></p>
                    <small><?php echo htmlspecialchars($feedback['feedbackDate']); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
