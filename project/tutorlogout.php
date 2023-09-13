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
// Remove the 'tutor_id' cookie by setting its expiration time to a past value
setcookie('tutor_id', '', time() - 3600, '/');

// Redirect the user to the login page
header('Location:tutorLogin.php');
exit;
?>