<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PowerSphere</title>
    <link rel="stylesheet" href="admin.css"> <!-- Link to your admin CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
  <header  class="main-header">
        <h1>PowerSphere</h1>
    </header>
    <header class="sub-header">
        <nav>
            <ul>
		<li><a href="#about">HOME</a></li>                 
                <li><a href="#contact">CONTACT</a></li> 
		<li><a href="#about">ABOUT</a></li>
            </ul>
        </nav>
    </header>
      <div class="start">

         <p>We're glad to have you back. Here’s a quick overview of the system:</p>
     </div>
      
		
    <main>
        <h1>Facilities</h1>
        <div class="fac-main">

        <section id="billing-management">
            <h2>Billing Management</h2>
            <button onclick="location.href='admin_bill_opt.php'">Bill Menu</button>
        </section>
        <section id="complaint-management">
            <h2>Complaint Management</h2>
            <button onclick="viewComplaints()">View Complaints</button>
      
        </section>
        <section id="reports">
            <h2>Reports</h2>
            <button onclick="location.href='admin_report_opt.php'">View Reports</button>
            <div>
                <!-- Report summaries will go here -->
            </div>
        </section>
        <section id="feedback">
            <h2>Feedback</h2>
            <button onclick="location.href='admin_view_feedback.php'">View feedback</button>
            <div>
                <!-- Report summaries will go here -->
            </div>
        </section>
	</div>
 </main>
        <div class="settings">
            <h2>Settings</h2>
            <form>
                <label for="billing-cycle">Billing Cycle:</label>
                <input type="text" id="billing-cycle" name="billing-cycle">
                <button type="submit">Save Settings</button>
            </form>
        </div>
   

    <footer>
        <h2>&copy; 2024 PowerSphere. All rights reserved.</h2>
    </footer>

    <script>
        function viewComplaints() {
            // Redirect to the admin complaints management page
            window.location.href = 'admin_page.php'; // Change this path if needed
        }
        
        function generateInvoice() {
            // Functionality to generate an invoice
            alert('Generate Invoice functionality to be implemented.');
        }
        
        function viewReports() {
            // Functionality to view reports
            alert('View Reports functionality to be implemented.');
        }
    </script>
</body>
</html>
