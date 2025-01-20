<?php
session_start();
include 'db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT score, stress_level, date_taken FROM quiz_results WHERE user_id = ? ORDER BY date_taken DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($latest_score, $latest_stress_level, $latest_date);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body { font-family: cursive; margin: 0; padding: 20px; background-color: #f0f4f8; }
        .dashboard-container { max-width: 800px; margin: auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.1); }
        h1 { font-size: 32px; color: #333; }
        .section { margin: 20px 0; }
        .section-title { font-size: 24px; color: #0984e3; margin-bottom: 10px; }
        .button { background-color: #0984e3; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-size: 16px; }
        .button:hover { background-color: #74b9ff; }
        .result { font-size: 18px; color: #555; margin-top: 5px; }
        .history-item { padding: 10px; border-bottom: 1px solid #ccc; font-size: 16px; }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Your History</h1>

        <!-- Latest Quiz Result -->
        <div class="section">
            <h2 class="section-title">Your Latest Stress Level</h2>
            <?php if (isset($latest_score)): ?>
                <p class="result">Stress Level: <strong><?php echo htmlspecialchars($latest_stress_level); ?></strong></p>
                <p class="result">Score: <strong><?php echo htmlspecialchars($latest_score); ?></strong></p>
                <p class="result">Date Taken: <strong><?php echo htmlspecialchars($latest_date); ?></strong></p>
            <?php else: ?>
                <p class="result">No quiz results available. Take your first quiz below!</p>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="section">
            <h2 class="section-title">Quick Links</h2>
            <a href="submit_quiz.php" class="button">Take a New Quiz</a>
            <a href="healthtips.html?stress_level=<?php echo urlencode($latest_stress_level); ?>" class="button">View Health Tips</a>
            <a href="about.html" class="button">Learn About This Quiz</a>
        </div>

        <!-- Quiz History -->
        <div class="section">
            <h2 class="section-title">Your Quiz History</h2>
            <?php
            $query = "SELECT score, stress_level, date_taken FROM quiz_results WHERE user_id = ? ORDER BY date_taken DESC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($score, $stress_level, $date_taken);

            while ($stmt->fetch()):
            ?>
                <div class="history-item">
                    <strong><?php echo htmlspecialchars($date_taken); ?></strong>: Stress Level - <?php echo htmlspecialchars($stress_level); ?>, Score - <?php echo htmlspecialchars($score); ?>
                </div>
            <?php endwhile; ?>
            <?php $stmt->close(); ?>
        </div>
    </div>
</body>
</html>
