<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Billing History</title>
    <link rel="stylesheet" href="export.css">
    <script>
        function exportToCSV() {
            const userid = document.getElementById('userid').value.trim();

            if (userid) {
                // Construct the URL for the PHP export script
                const url = `export_to_csv.php?userid=${encodeURIComponent(userid)}`;
                
                // Open the URL in a new tab to trigger the download
                window.open(url, '_blank');
            } else {
                alert('Please enter a valid User ID.');
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Export Billing History</h1>
        <div class="form-group">
            <label for="userid">User ID:</label>
            <input type="text" id="userid" name="userid" placeholder="Enter User ID" required>
        </div>
        <button class="btn" onclick="exportToCSV()">Download CSV</button>
    </div>
</body>
</html>