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

if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = $_COOKIE['tutor_id'];
} else {
    $tutor_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];

    // Assuming you have already established the database connection ($conn)
    $stmt = $conn->prepare("SELECT * FROM `tutor` WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['pass']) {
            setcookie('tutor_id', $row['tutor_id'], time() + 60*60*24*30, '/');
            header('location: Dashboard.php');
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
        <h3>WELCOME BACK ADMIN</h3>

        <?php if (!empty($message)) : ?>
            <p class="error-message"><?php echo $message; ?></p>
            <?php endif; ?>

        <p>Your Email <span>-------</span></p>
        <input type="email" name="email" placeholder="enter Your E-mail" maxlength="20" required class="box">
        <p>Your password <span>-------</span></p>
        <input type="password" name="pass" placeholder="enter Your password" maxlength="20" required class="box">

        <p class="">Don't have an account? 
        <a href=quiz.php>Register now</a></p>
        <input type="submit" name="submit" value="login now" class="btn">



    
    </form>

</section>


 <script src="script.js"></script>
    </body>

</html>