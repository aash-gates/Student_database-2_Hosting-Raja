<?php
session_start(); // Start session to access session variables

// Check if the user is not logged in, then redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6);
            color: #fff;
        }

        .container {
            margin-top: 20px;
            opacity: 0;
            animation: fadeIn 2s forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
            color: #333;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.5);
            color: #333;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .table {
            background-color: rgba(255, 255, 255, 0.7);
        }

        .table th, .table td {
            border: none;
        }

        .btn {
            margin-top: 10px;
        }

        .time-panel {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 20px;
            margin-bottom: 20px;
        }

        .logout-btn {
            margin-top: 20px;
        }

        .pie-chart {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="logout-btn text-right">
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
                <div class="text-center mb-4">
                    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2></br>
                    <h4>A Program created by Aashik</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="time-panel card mb-4">
                    <h5 class="card-header">Time Panel</h5>
                    <div class="card-body" id="time-info">
                        <!-- Time information goes here -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <h2 class="card-header">Students with Dust Allergies</h2>
                    <h6 class="class-header">Not Real Time data just a placeholder</h6>
                    <div class="card-body">
                        <div class="pie-chart">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h2 class="card-header">List of Students</h2>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Student ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Include your db_connect.php file to establish a database connection
                                include 'db_connect.php';

                                // Retrieve full name and student ID of all students
                                $sql_students = "SELECT full_name, student_id FROM StudentRecords";
                                $result_students = $connection->query($sql_students);

                                if ($result_students->num_rows > 0) {
                                    while ($row = $result_students->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td><a href='student_details.php?id=" . $row['student_id'] . "'>" . $row['full_name'] . "</a></td>";
                                        echo "<td>" . $row['student_id'] . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='2'>No students found.</td></tr>";
                                }

                                // Close database connection
                                $connection->close();
                                ?>
                            </tbody>
                        </table>
                        <a href="add_student.php" class="btn btn-primary">Add Student</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom JS -->
    <script>
        // Get current time
        setInterval(() => {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('time-info').textContent = `Current Time: ${timeString}`;
        }, 1000);

        // Pie chart data
        var pieData = {
            labels: ['With Dust Allergies', 'Without Dust Allergies'],
            datasets: [{
                data: [10, 05], // Example data, replace with actual data
                backgroundColor: ['#FF6384', '#36A2EB']
            }]
        };

        // Get the pie chart canvas
        var pieChartCanvas = document.getElementById('pieChart').getContext('2d');

        // Draw the pie chart
        var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData
        });
    </script>
</body>
</html>
