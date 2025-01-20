<?php
session_start();
include 'db_connect.php';

if (isset($_GET['registered']) && $_GET['registered'] == 'success') {
    echo "<p class='success-message'>Registration successful! Please log in.</p>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if password is exactly 8 characters long
    if (strlen($password) !== 8) {
        echo "<p class='error-message'>Password must be exactly 8 characters long.</p>";
        exit();
    }

    $stmt = $conn->prepare("SELECT id, password FROM registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $stored_password);
        $stmt->fetch();

        // Compare plain text password (no hashing)
        if ($password === $stored_password) {
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            header("Location: quiz_reg.html");
            exit();
        } else {
            echo "<p class='error-message'>Incorrect password. Please try again.</p>";
        }
    } else {
        echo "<p class='error-message'>No account found with this email. Please register first.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        .success-message { color: green; }
        .error-message { color: red; }
    </style>
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="password" placeholder="Enter your password" minlength="8" maxlength="8" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
