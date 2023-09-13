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
        $image = "image/" .$row['image']; // Assuming the column name for profile image is 'profile_image'
        $name = $row['name']; // Assuming the column name for user's name is 'name'
        $profession = $row['profession'];
    } else {
        // User data not found, handle accordingly
        $image = "image.jpg"; // Set a default profile image
        $name = "Guest"; // Set a default name for guest users
    }
    
} else {
    $student_id = '';
    $image = "default-profile-image.jpg"; // Set a default profile image
    $name = "Guest"; // Set a default name for guest users
}





$select_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
$select_playlists->bind_param("s", $tutor_id);
$select_playlists->execute();
$result_playlists = $select_playlists->get_result();
$total_playlists = $result_playlists->num_rows;

$select_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
$select_contents->bind_param("s", $tutor_id);
$select_contents->execute();
$result_contents = $select_contents->get_result();
$total_contents = $result_contents->num_rows;

$select_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
$select_likes->bind_param("s", $tutor_id);
$select_likes->execute();
$result_likes = $select_likes->get_result();
$total_likes = $result_likes->num_rows;

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
$select_comments->bind_param("s", $tutor_id);
$select_comments->execute();
$result_comments = $select_comments->get_result();
$total_comments = $result_comments->num_rows;



?>







<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
        <link rel="stylesheet" href="style.css">


        <title>LearnWiz</title>
    </head>

    <body>

        


    <header class="header">

<section class="flex">

    <a href="#" class="logo">Admin.</a>

    <form action="#" method="post" class="search-form">
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
   <a href="tutorDocument.php"><i class="fas fa-graduation-cap"></i><span>Documents</span></a>
   <a href="userComments.php"><i class="fas fa-comment"></i><span>comments</span></a>
   <a href="tutor.php"><i class="fa fa-clone"></i><span>Quizes</span></a>
   <a href="tutorlogout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
</nav>

</header>








<section class="tutor-profile">

<h1 class="heading">Profile Details</h1>



<div class="details">
    <div class="tutor">
        <img src="<?php echo $image; ?>" alt="">
        <h3> <?php echo $name; ?> </h3>
        <h3><?php echo $profession; ?></h3>
        <a href="updateProileTutor.php" class="inline-btn">Update Profile</a>
    </div>

    <div class="flex">
        <p>Total Playlist : <span><?php echo $total_playlists; ?></span></p>
        <p>Total Videos : <span><?php echo $total_contents;?></span></p>
        <p>Total Likes : <span><?php echo $total_likes; ?></span></p>
        <p>Total Comments : <span><?php echo $total_comments; ?></span></p>    
    </div>

</div>

</section>



<footer class="footer">

    <P >
    Emil  - EDUCATE@gmail.com        &nbsp &nbsp &nbsp &nbsp &nbsp
    PHONE   -  077456270        &nbsp &nbsp &nbsp &nbsp &nbsp
    ADDRESS  -2nd, colombo
    </P>

</footer>



 <script src="script.js"></script>
    </body>

</html>