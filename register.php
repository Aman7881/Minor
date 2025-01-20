<?php
session_start();
include 'db_connect.php'; // Include the database connection file

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $Name = trim($_POST['Name']);
    $gender = $_POST['gender'];
    $email = trim($_POST['email']);
    $phonenumber = trim($_POST['phonenumber']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Basic validations
    if (empty($Name) || empty($gender) || empty($email) || empty($phonenumber) || empty($password)) {
        echo "Error: All fields are required.";
        exit();
    }

    // Validate that passwords match
    if ($password !== $confirmPassword) {
        echo "Error: Passwords do not match.";
        exit();
    }

    // Additional validation: Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT id FROM registration WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo "Error: An account with this email already exists.";
        $checkEmailStmt->close();
        $conn->close();
        exit();
    }
    $checkEmailStmt->close();

    // Store password as plain text (not recommended in production)
    $plain_password = $password;

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO registration (Name, gender, email, phonenumber, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $Name, $gender, $email, $phonenumber, $plain_password);

    if ($stmt->execute()) {
        // Send welcome email to the user
        $to = $email; // Recipient email
        $subject = "Welcome to Calm or Chaos!";
        $message = "
            <html>
            <head>
                <title>Welcome to Calm or Chaos</title>
            </head>
            <body>
                <h2>Hi $Name,</h2>
                <p>Thank you for registering with <strong>Calm or Chaos</strong>. We're thrilled to have you on board!</p>
                <p>Start exploring by logging into your account <a href='http://yourwebsite.com/login.html'>here</a>.</p>
                <p>If you have any questions or need assistance, feel free to reach out to our support team.</p>
                <p>Best regards,<br>The Calm or Chaos Team</p>
            </body>
            </html>
        ";
        
        // Set headers for HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@yourwebsite.com" . "\r\n";

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "Registration successful! A welcome email has been sent.";
        } else {
            echo "Registration successful, but we couldn't send the welcome email.";
        }

        // Redirect to the login page
        header("Location: login.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
