<?php
// Capture the score, stress level, and explanation from the URL
$score = isset($_GET['score']) ? (int)$_GET['score'] : 0;
$stress_level = isset($_GET['stress_level']) ? $_GET['stress_level'] : 'Normal';
$explanation = isset($_GET['explanation']) ? $_GET['explanation'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calm or Chaos - Your Stress Level</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
            text-align: center;
            padding: 20px;
        }

        /* Container for the Result */
        .result-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2d3436;
            font-size: 36px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 20px;
            color: #0a0d0f;
        }

        /* Stress Level Display */
        .stress-level {
            font-size: 28px;
            font-weight: bold;
            padding: 10px;
            border-radius: 10px;
            display: inline-block;
            margin: 20px 0;
        }

        .normal {
            background-color: #3dc256;
            color: #2d3436;
        }

        .moderate {
            background-color: orange;
            color: #2d3436;
        }

        .high {
            background-color: rgb(214, 98, 3);
            color: #f9fdfe;
        }

        /* Explanation */
        .explanation {
            font-size: 18px;
            color: #060708;
            margin: 30px 0;
        }

        /* Button Styling */
        .tips-btn, .back-btn, .about-btn {
            background-color: #0984e3;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 18px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin: 10px;
        }

        .tips-btn:hover, .back-btn:hover, .about-btn:hover {
            background-color: #74b9ff;
        }

    </style>
</head>
<body>

    <div class="result-container">
        <h1>Your Stress Level</h1>

        <!-- Display the user's score -->
        <p>You scored: <strong><?php echo $score; ?></strong></p>

        <!-- Stress Level Display with dynamic class based on stress level -->
        <div class="stress-level <?php echo strtolower($stress_level); ?>">
            <?php echo htmlspecialchars($stress_level); ?>
        </div>

        <!-- Explanation of what the score means -->
        <p class="explanation"><?php echo htmlspecialchars($explanation); ?></p>

        <!-- Links to additional resources -->
        <a href="health_tips.php?stress_level=<?php echo urlencode($stress_level); ?>" class="tips-btn">View Health Tips</a>
        <a href="about.html" class="about-btn">Learn More About This Quiz</a>
        <a href="quiz_reg.html" class="back-btn">Retake Quiz</a>
    </div>

</body>
</html>
