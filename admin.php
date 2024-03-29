<?php
session_start(); // Start session to access session variables

// Include your db_connect.php file to establish a database connection
include 'db_connect.php';

// Initialize variables to store user input
$username = $password = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare SQL statement to retrieve user data
    $sql = "SELECT * FROM LoginCredentials WHERE username = ? AND password_hash = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists and credentials are correct
    if ($result->num_rows > 0) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Redirect to dashboard upon successful login
        header("Location: dashboard.php");
        exit; // Make sure to exit after redirection
    } else {
        // Display error message for invalid credentials
        $login_error = "Invalid username or password";
    }
}

// Check if user is not logged in and add user to LoginCredentials table
if (!isset($_SESSION['loggedin'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate input for adding user
        $new_username = $_POST["new_username"];
        $new_password = $_POST["new_password"];

        // Check if username already exists
        $check_username_sql = "SELECT * FROM LoginCredentials WHERE username = ?";
        $check_stmt = $connection->prepare($check_username_sql);
        $check_stmt->bind_param("s", $new_username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $login_error = "Username already exists";
        } else {
            // Insert new user into LoginCredentials table
            $insert_sql = "INSERT INTO LoginCredentials (username, password_hash) VALUES (?, ?)";
            $insert_stmt = $connection->prepare($insert_sql);
            $insert_stmt->bind_param("ss", $new_username, $new_password);
            $insert_stmt->execute();

            // Display success message
            $login_error = "New user added successfully";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Custom CSS styles here */
        body {
            color: #000;
            overflow-x: hidden;
            height: 100%;
            background-image: linear-gradient(to right, #D500F9, #FFD54F);
            background-repeat: no-repeat;
        }

        .container {
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

        input, textarea {
            background-color: #F3E5F5;
            border-radius: 50px !important;
            padding: 12px 15px 12px 15px !important;
            width: 100%;
            box-sizing: border-box;
            border: none !important;
            border: 1px solid #F3E5F5 !important;
            font-size: 16px !important;
            color: #000 !important;
            font-weight: 400;
            margin-bottom: 20px;
        }

        input:focus, textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border: 1px solid #D500F9 !important;
            outline-width: 0;
            font-weight: 400;
        }

        .btn-color {
            border-radius: 50px;
            color: #fff;
            background-image: linear-gradient(to right, #FFD54F, #D500F9);
            padding: 15px;
            cursor: pointer;
            border: none !important;
        }

        .btn-color:hover {
            color: #fff;
            background-image: linear-gradient(to right, #D500F9, #FFD54F);
        }

        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            text-align: center;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h3>Login</h3>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="text" id="username" name="username" placeholder="Username" class="form-control" required>
                <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                <button type="submit" class="btn-color">Login</button>
            </form>
            <?php if(isset($login_error)) { ?>
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php } ?>

            <!-- Add new user form -->
            <?php if (!isset($_SESSION['loggedin'])) { ?>
                <h3>Create Account</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="text" id="new_username" name="new_username" placeholder="New Username" class="form-control" required>
                    <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control" required>
                    <button type="submit" class="btn-color">Create Account</button>
                </form>
                <?php if(isset($login_error)) { ?>
                    <p class="error-message"><?php echo $login_error; ?></p>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</body>
</html>
