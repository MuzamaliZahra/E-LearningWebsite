<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educate";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_COOKIE['student_id'])) {
    $student_id = $_COOKIE['student_id'];
} else {
    $student_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    $stmt = $conn->prepare("SELECT * FROM `student` WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['pass']) {
            setcookie('student_id', $row  //used to store the student's id for further identification
            ['student_id'], time() + 60*60*24*30, '/'); //expiration time 30 days
            header('location: HOME.php');
            exit();
            
        }
    }
}
?>

<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
        <link rel="stylesheet" href="style.css">

        <title>LearnWiz</title>
    </head>

<body>
<section class="form-container">

    <form action="" method="post" class="login" >
        <h3>WELCOME BACK</h3>

        <?php if (!empty($message)) : ?>
            <p class="error-message"><?php echo $message; ?></p>
            <?php endif; ?>

        <p>Your Email </p>
        <input type="email" name="email" placeholder="enter Your E-mail" maxlength="20" required class="box">
        <p>Your password </p>
        <input type="password" name="pass" placeholder="enter Your password" maxlength="20" required class="box">

        <p class="">Don't have an account? 
        <a href="log_reg.php">Register now</a></p>
        <input type="submit" name="submit" value="login now" class="btn">



    
    </form>

</section>


 <script src="script.js"></script>
    </body>

</html>