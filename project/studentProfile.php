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

    // Fetch user data
    $stmt = $conn->prepare("SELECT * FROM `student` WHERE student_id = ? LIMIT 1");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = "image/" .$row['image'];
        $name = $row['name']; 
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


        <title>studentProfile</title>
    </head>

<body>
      <header class="header">
        <section class="flex" >
           <a href="#" class="logo">Educate.</a>
            <form action="#" method="post" class="search-form">
                <input type="text" name="search_course" placeholder="search courses" required maxlength="100">
                <button type="submit" class="fas fa-search" name="search_course_btn"></button>
            </form>
        
           <div class="icons">
              <div id="search-btn" class="fas fa-search"></div>
              <div id="user-btn" class="fas fa-user"></div>
              <div id="toggle-btn" class="fas fa-sun"></div>
           </div>

         <div class="profile">
            <img src="<?php echo $image; ?>"alt="student profile">
            <h3>student</h3>
            <h3><?php echo $name; ?></h3>
            <a href="studentProfile.php" class="btn">view profile</a>
            <div class="flex-btn">
               <a href="login.php" class="option-btn">login</a>
               <a href="registration.php" class="option-btn">register</a>
            </div>
            <a href="studentLogout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>     
        </div>
</section>
      <h1 class="heading"></h1>
            <nav class="navbar">
                <div class="flex-btn" style="padding-top: .5rem;"></div>
                <a href="HOME.php"><i class="fas fa-home"></i><span>Home</span></a>
                <a href="about.php"><i class="fas fa-question"></i><span>About us</span></a>
                <a href="cources.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
                <a href="tutor.php"><i class="fas fa-chalkboard-user"></i><span>Tutors</span></a>
            </nav>   
</header>

<section class="profile">
    <h1 class="Profile Details"></h1>

    <div class="Details">
        <div class="user">
            <img src="<?php echo $image; ?>" alt="">
            <h3><?php echo $name; ?></h3>
            <p>Student</p>
            <a href="updateProfile.php" class="inline-btn">Update Profile</a>
        </div>

    <div class="box-container">
        <div class="box">
            <div class="flex">
                <i class="fas fa-bookmark"></i>
                <div>
                    <h3>3</h3>
                    <span>Saved Playlist</span>
                </div>
            </div>
                <a href="#" class="inline-btn"> View Playlist</a>
        </div>

        <div class="box">
            <div class="flex">
                <i class="fas fa-heart"></i>
                <div>
                    <h3>5</h3>
                    <span>Liked Tutorials</span>
                </div>
            </div>
                <a href="#" class="inline-btn">View Likes</a>
        </div>

        <div class="box">
            <div class="flex">
                <i class="fas fa-comments"></i>
                <div>
                    <h3>5</h3>
                    <span>Comments</span>
                </div>
            </div>
                <a href="#" class="inline-btn">View Coments</a>
        </div>
    </div>
    </div>
</section>

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