
<?php
// Define the correct answers for each question
$correctAnswers = array(
    'q1' => 'Output',
    'q2' => ' binary',
    'q3' => ' ++',
    'q4' => 'Void function',
    'q5' => 'variables',
    'q6' => '4',
    'q7' => 'Enumerated',
    'q8' => 'Unary',
    'q9' => '15',
    'q10' => 'address'
);

// Initialize a variable to count the number of correct answers
$numCorrectAnswers = 0;

// Check each question's answer against the correct answer
foreach ($correctAnswers as $question => $correctAnswer) {
    if (isset($_POST[$question]) && $_POST[$question] === $correctAnswer) {
        $numCorrectAnswers++;
    }
}

// Check if the user passed the quiz 
if ($numCorrectAnswers >= 5) {
    // Redirect to the login page if they passed the quiz
    header("Location: tutorRegistration.php");
    exit; // Ensure that the script stops executing after the redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   
    <link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
</head>
<body>
    
<div class="flex">

    <h1>Quiz Results</h1>
<div class="box">
    <p>You answered <?php echo $numCorrectAnswers; ?> out of 10 questions correctly.</p>

    <?php
    if ($numCorrectAnswers >= 5) {
        echo "<p>Congratulations! You passed the quiz.</p>";
        echo "<p>You will now be redirected to the login page.</p>";
    } else {
        echo "<p>Sorry, you did not pass the quiz. Please try again.</p>";
        echo "<p><a href='quiz.php'>Back to Quiz</a></p>";
    }
    ?>

</div>
</div>
</body>
<script src="script.js"></script>
</html>
