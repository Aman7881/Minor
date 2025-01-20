<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$score = 0;

for ($i = 1; $i <= 10; $i++) {
    $score += isset($_POST["q$i"]) ? (int)$_POST["q$i"] : 0;
}

if ($score <= 13) {
    $stress_level = "Normal";
    $explanation = "Your stress level is within a normal range. Keep up the good habits!";
} elseif ($score <= 26) {
    $stress_level = "Moderate";
    $explanation = "You have a moderate level of stress. Consider some stress-management practices.";
} else {
    $stress_level = "High";
    $explanation = "Your stress level is high. It might be helpful to explore strategies for managing stress.";
}

// Insert query without `date_taken`, as MySQL will handle it automatically
$stmt = $conn->prepare("INSERT INTO quiz_results (user_id, score, stress_level) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $user_id, $score, $stress_level);

if ($stmt->execute()) {
    header("Location: display_results.php?score=$score&stress_level=" . urlencode($stress_level) . "&explanation=" . urlencode($explanation));
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
