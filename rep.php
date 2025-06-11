<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get report data based on filters
function getBillReport($year = null, $month = null, $userId = null) {
    global $conn;
    
    $sql = "SELECT * FROM bill WHERE 1=1";
    
    if ($year) {
        $sql .= " AND year = " . intval($year);
    }
    if ($month) {
        $sql .= " AND month = '" . $conn->real_escape_string($month) . "'";
    }
    if ($userId) {
        $sql .= " AND userId = '" . $conn->real_escape_string($userId) . "'";
    }
    
    $sql .= " ORDER BY year DESC, month DESC";
    
    $result = $conn->query($sql);
    return $result;
}

// Get filter values from POST
$year = isset($_POST['year']) ? $_POST['year'] : null;
$month = isset($_POST['month']) ? $_POST['month'] : null;
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;

// Get report data
$reportData = getBillReport($year, $month, $userId);

// Initialize arrays for chart data
$months = [];
$unitsData = [];
$amountData = [];
$energyChargesData = [];
$taxesData = [];

// Calculate totals and prepare chart data
$totalBills = 0;
$totalUnits = 0;
$totalAmount = 0;

// Store the result data for both table and charts
$resultArray = [];
if ($reportData) {
    while ($row = $reportData->fetch_assoc()) {
        $resultArray[] = $row;
        $totalBills++;
        $totalUnits += $row['unitsConsumed'];
        $totalAmount += $row['totalAmount'];
        
        // Prepare data for charts
        $months[] = $row['month'] . ' ' . $row['year'];
        $unitsData[] = $row['unitsConsumed'];
        $amountData[] = $row['totalAmount'];
        $energyChargesData[] = $row['energyCharges'];
        $taxesData[] = $row['taxes'];
    }
    // Reset pointer for table display
    $reportData->data_seek(0);
}

// Convert data arrays to JSON for JavaScript
$chartData = [
    'months' => json_encode($months),
    'units' => json_encode($unitsData),
    'amounts' => json_encode($amountData),
    'energyCharges' => json_encode($energyChargesData),
    'taxes' => json_encode($taxesData)
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Bill Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .filters {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .chart-wrapper {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 5px;
        }
        .btn {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        @media print {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Electricity Bill Report</h1>
        
        <!-- Filters -->
        <div class="filters">
            <form method="POST" action="">
                <label>Year:
                    <select name="year">
                        <option value="">All Years</option>
                        <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                            $selected = ($year == $y) ? 'selected' : '';
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </label>
                
                <label>Month:
                    <select name="month">
                        <option value="">All Months</option>
                        <?php
                        $months = [
                            'January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'
                        ];
                        foreach ($months as $m) {
                            $selected = ($month == $m) ? 'selected' : '';
                            echo "<option value='$m' $selected>$m</option>";
                        }
                        ?>
                    </select>
                </label>
                
                <label>User ID:
                    <input type="text" name="userId" value="<?php echo htmlspecialchars($userId ?? ''); ?>">
                </label>
                
                <button type="submit" class="btn">Generate Report</button>
            </form>
        </div>

        <!-- Charts Section -->
        <div class="charts-container">
            <div class="chart-wrapper">
                <canvas id="consumptionChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="amountChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="breakdownChart"></canvas>
            </div>
            <div class="chart-wrapper">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Report Table -->
        <table>
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>User ID</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Units Consumed</th>
                    <th>Rate/Unit</th>
                    <th>Energy Charges</th>
                    <th>Taxes</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($reportData && $reportData->num_rows > 0) {
                    while ($row = $reportData->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['billId']); ?></td>
                            <td><?php echo htmlspecialchars($row['userId']); ?></td>
                            <td><?php echo htmlspecialchars($row['month']); ?></td>
                            <td><?php echo htmlspecialchars($row['year']); ?></td>
                            <td><?php echo number_format($row['unitsConsumed'], 2); ?></td>
                            <td><?php echo number_format($row['ratePerUnit'], 2); ?></td>
                            <td><?php echo number_format($row['energyCharges'], 2); ?></td>
                            <td><?php echo number_format($row['taxes'], 2); ?></td>
                            <td><?php echo number_format($row['totalAmount'], 2); ?></td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary">
            <h3>Report Summary</h3>
            <p>Total Bills: <?php echo $totalBills; ?></p>
            <p>Total Units Consumed: <?php echo number_format($totalUnits, 2); ?></p>
            <p>Total Amount: <?php echo number_format($totalAmount, 2); ?></p>
        </div>
    </div>

    <script>
        // Chart data from PHP
        const months = <?php echo $chartData['months']; ?>;
        const unitsData = <?php echo $chartData['units']; ?>;
        const amountData = <?php echo $chartData['amounts']; ?>;
        const energyChargesData = <?php echo $chartData['energyCharges']; ?>;
        const taxesData = <?php echo $chartData['taxes']; ?>;

        // Consumption Chart
        new Chart(document.getElementById('consumptionChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Units Consumed',
                    data: unitsData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Power Consumption'
                    }
                }
            }
        });

        // Amount Chart
        new Chart(document.getElementById('amountChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Total Amount',
                    data: amountData,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Bill Amount Trend'
                    }
                }
            }
        });

        // Breakdown Chart
        new Chart(document.getElementById('breakdownChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Energy Charges',
                    data: energyChargesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Taxes',
                    data: taxesData,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Bill Breakdown'
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                    },
                    y: {
                        stacked: true
                    }
                }
            }
        });

        // Trend Chart
        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Units',
                    data: unitsData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    yAxisID: 'y-units',
                },
                {
                    label: 'Amount',
                    data: amountData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    yAxisID: 'y-amount',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Consumption vs Amount Trend'
                    }
                },
                scales: {
                    'y-units': {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Units'
                        }
                    },
                    'y-amount': {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Amount'
                        }
                    }
                }
            }
        });

        // Add print functionality
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>