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

    // Fetch user data
    $stmt = $conn->prepare("SELECT * FROM `tutor` WHERE tutor_id = ? LIMIT 1");
    $stmt->bind_param("s", $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = "image/" .$row['image']; 
        $name = $row['name']; 
        $profession = $row['profession'];
    } else {
      
        $image = "image.jpg";
        $name = "Guest";
    }
} else {
    $student_id = '';
    $image = "default-profile-image.jpg";
    $name = "Guest";
}
?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

<title>
   LearnWiz
</title>
</head>


<body>
<header class="header">
     <section class="flex">
        <a href="" class="logo">LearnWiz.</a>

        <form action="" method="post" class="search-form">
            <input type="text" name="search" placeholder="search here" required maxlength="100">
            <button type="submit" class="fas fa-search" name="search_btn"></button>
        </form>

        <div class="icons">
            <div id="search-btn" class="fas fa-search"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile">
            <img src="<?php echo $image; ?>" alt="">
            <h3><?php echo $name; ?></h3>
            <span><?php echo $profession; ?></span>
            <a href="tutors_Profile.php" class="btn">view profile</a>

            <div class="flex-btn">
              <a href="log_reg.php" class="option-btn">login</a>
                <a href="log_reg.php" class="option-btn">register</a>
            </div>   
            <a href="tutorlogout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
        </div>
    </section>


<h1 class="heading"></h1>

<nav class="navbar">
   <a href="Dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
   <a href="adminPlaylis.php"><i class="fa-solid fa-bars-staggered"></i><span>playlists</span></a>
   <a href="adminContentUpload.php"><i class="fas fa-graduation-cap"></i><span>contents</span></a>
   <a href="tutorDocument.php"><i class="fa fa-edit"></i><span>Documents</span></a>
   <a href="userComments.php"><i class="fas fa-comment"></i><span>comments</span></a>
   <a href="tutorlogout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
</nav>
</header>

<section class="dashboard">
    <h1 class="heading">dashboard</h1>
    <div class="box-container">
        <div class="box">
            <h3>welcome</h3>
            <a href="tutors_Profile.php" class="btn">View Profile</a>
        </div>

        <div class="box">
            <h3>contents</h3>            
            <a href="uploadContent.php" class="btn">add new content</a>
        </div>

        <div class="box">
            <h3>playlists</h3>
            <a href="createPlaylist.php" class="btn">add new playlist</a>
        </div>

        <div class="box">
            <h3>contents</h3>
            <a href="adminContentUpload.php" class="btn">view contents</a>
        </div>

        <div class="box">
            <h3>comments</h3>
            <a href="userComments.php" class="btn">view comments</a>
        </div>
        
        <div class="box">
            <h3>Document</h3>
            <a href="tutorDocument.php" class="btn">view Document</a>
        </div>

        

     <div class="box">
        <h3>quick select</h3>
        <div class="flex-btn">
           <a href="log_reg.php"  class="option-btn">login</a>
           <a href="log_reg.php"  class="option-btn">register</a>
        </div>
     </div>
    </div>
<footer class="footer">
    <P >
    Emil  - EDUCATE@gmail.com   &nbsp &nbsp
    PHONE   -  077456270        &nbsp &nbsp
    ADDRESS  -2nd, colombo
    </P>
</footer>
<script src="script.js"></script>
</body>
</html>