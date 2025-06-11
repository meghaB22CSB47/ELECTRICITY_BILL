# ⚡ Electricity Bill Management System

This is a web-based Electricity Bill Management System developed using **PHP**, **HTML**, **CSS**, and **MySQL**, running on **XAMPP**. It allows users to manage electricity billing data such as customer information, meter readings, and monthly bills in a structured and automated way.

## 🚀 Features

- User-friendly dashboard for admins
- Add, update, and delete customer details
- Record meter readings
- Generate and manage electricity bills
- View billing history
- Login/Logout functionality for security
- Responsive UI with basic styling using CSS

## 🛠️ Tech Stack

- **Frontend:** HTML, CSS
- **Backend:** PHP
- **Database:** MySQL
- **Local Server:** XAMPP (Apache + MySQL)


## 🖥️ How to Run Locally

1. **Install XAMPP**  
   [Download XAMPP](https://www.apachefriends.org/index.html) and install it.

2. **Start Apache and MySQL** from the XAMPP Control Panel.

3. **Clone this repository** or copy the project folder into:
C:\xampp\htdocs\

4. **Create the database:**
- Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
- Create a new database, e.g., `electricity_db`
- Import the provided `.sql` file (if available) to set up tables

5. **Update DB config (if needed)**  
Open the PHP file for DB connection (e.g., `includes/db.php`) and check:
```php
$conn = mysqli_connect("localhost", "root", "", "electricity_db");

6.Run the project in browser:
Visit: http://localhost/logins

📄 License
This project is for educational purposes. Feel free to use or modify it.
