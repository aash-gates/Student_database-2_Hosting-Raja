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
    <title>Edit Student Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #FF416C, #FF4B2B);
            color: #fff;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card-header {
            background-color: rgba(255, 255, 255, 0.5);
            color: #333;
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        .btn-save, .btn-back {
            background-color: #fff;
            color: #333;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            margin-right: 10px;
            text-decoration: none;
        }

        .btn-save:hover, .btn-back:hover {
            background-color: #eee;
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php 
        // Include your db_connect.php file to establish a database connection
        include 'db_connect.php';

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate the student ID
            if (isset($_POST['student_id'])) {
                $student_id = $_POST['student_id'];

                // Retrieve existing student details
                $sql_student_details = "SELECT * FROM StudentRecords WHERE student_id = $student_id";
                $result_student_details = $connection->query($sql_student_details);
                
                if ($result_student_details->num_rows > 0) {
                    $student_details = $result_student_details->fetch_assoc();

                    // Validate other form inputs
                    // For simplicity, assuming all fields are required
                    $full_name = $_POST['full_name'];
                    $phone_number = $_POST['phone_number'];
                    $dob = $_POST['dob'];
                    $mother_tongue = $_POST['mother_tongue'];
                    $blood_group = $_POST['blood_group'];
                    $known_dust_allergies = $_POST['known_dust_allergies'];
                    $mother_name = $_POST['mother_name'];
                    $father_name = $_POST['father_name'];
                    $nationality = $_POST['nationality'];

                    // Update the student details in the database
                    $sql_update = "UPDATE StudentRecords SET 
                                    full_name = '$full_name', 
                                    phone_number = '$phone_number', 
                                    dob = '$dob', 
                                    mother_tongue = '$mother_tongue', 
                                    blood_group = '$blood_group', 
                                    known_dust_allergies = '$known_dust_allergies', 
                                    mother_name = '$mother_name', 
                                    father_name = '$father_name', 
                                    nationality = '$nationality' 
                                WHERE student_id = $student_id";

                    if ($connection->query($sql_update) === TRUE) {
                        echo "<div class='alert alert-success' role='alert'>Student details updated successfully.</div>";
                    } else {
                        echo "<div class='alert alert-danger' role='alert'>Error updating student details: " . $connection->error . "</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger' role='alert'>Student not found.</div>";
                }
            } else {
                echo "<div class='alert alert-danger' role='alert'>Invalid request.</div>";
            }
        }

        // Retrieve student details based on the ID passed through GET parameter
        if (isset($_GET['id'])) {
            $student_id = $_GET['id'];

            // Retrieve existing student details
            $sql_student_details = "SELECT * FROM StudentRecords WHERE student_id = $student_id";
            $result_student_details = $connection->query($sql_student_details);

            if ($result_student_details->num_rows > 0) {
                $student_details = $result_student_details->fetch_assoc();
        ?>
        <div class="card">
            <div class="card-header">
                <h2>Edit Student Details</h2>
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
                    <!-- Input fields for editing student details -->
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $student_details['full_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number:</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $student_details['phone_number']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $student_details['dob']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mother_tongue">Mother Tongue:</label>
                        <input type="text" class="form-control" id="mother_tongue" name="mother_tongue" value="<?php echo $student_details['mother_tongue']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="blood_group">Blood Group:</label>
                        <input type="text" class="form-control" id="blood_group" name="blood_group" value="<?php echo $student_details['blood_group']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="known_dust_allergies">Known Dust Allergies:</label>
                        <input type="text" class="form-control" id="known_dust_allergies" name="known_dust_allergies" value="<?php echo $student_details['known_dust_allergies']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Mother Name:</label>
                        <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?php echo $student_details['mother_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="father_name">Father Name:</label>
                        <input type="text" class="form-control" id="father_name" name="father_name" value="<?php echo $student_details['father_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Nationality:</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" value="<?php echo $student_details['nationality']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-save">Save</button>
                    <a href="student_details.php?id=<?php echo $student_id; ?>" class="btn btn-back">Back to Student Details</a>
                </form>
            </div>
        </div>
        <?php } else {
            echo "<div class='alert alert-danger' role='alert'>Student not found.</div>";
        }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid request.</div>";
        }

        // Close database connection
        $connection->close();
        ?>
    </div>
</body>
</html>
