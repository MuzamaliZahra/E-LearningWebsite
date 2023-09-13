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
}   else {
    $student_id = '';
    $image = "default-profile-image.jpg"; 
    $name = "Guest"; 
}
?>

<html>
<head>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Cources-EDUCATE</title>
</head>

<body>
    <header class="header">
<section class="flex" >
                <a href="" class="logo">LearnWiz.</a>  
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
                            <img src="<?php echo $image; ?>"  alt="student profile">
                            <h3>student</h3>
                            <h3><?php echo $name; ?></h3>
                            <a href="studentProfile.php" class="btn">view profile</a>
                    <div class="flex-btn">
                            <a href="log_reg.php" class="option-btn">login</a>
                            <a href="log_reg.php" class="option-btn">register</a>
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
                <a href="studentDocument.php"><i class="fa fa-edit"></i><span>Documents</span></a>
               
        </nav>
</header>

<section class="courses">  
    <h1 class="heading">OUR COURSES</h1>
    <div class="box-container">

    <?php
        $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC");
        $status = 'active';
        $select_courses->bind_param("s", $status);
        $select_courses->execute();
        $result_courses = $select_courses->get_result();

        if ($result_courses !== false && $result_courses->num_rows > 0) {
            while ($fetch_course = $result_courses->fetch_assoc()) {
                $course_id = $fetch_course['playlist_id'];

                $select_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE tutor_id = ?");
                $select_tutor->bind_param("i", $fetch_course['tutor_id']);
                $select_tutor->execute();
                $result_tutor = $select_tutor->get_result();
                $fetch_tutor = $result_tutor->fetch_assoc();

    ?>
            <div class="box">
                <div class="tutor">
                    <img src="image/<?php echo $fetch_tutor['image']; ?>" alt="">
                    <div>
                        <h3><?php echo $fetch_tutor['name']; ?></h3>
                        <span><?php echo $fetch_course['date']; ?></span>
                    </div>
                </div>
                    <img src="profile\thumb.png"  class="thumb" alt="">
                    <h3 class="title"><?php echo $fetch_course['title']; ?></h3>
                    <!-- Modify the a tag to pass the playlist_id as a parameter in url -->
                    <a href="playlist.php?playlist_id=<?php echo $course_id; ?>" class="inline-btn">View Playlist</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No courses added yet!</p>';
        }
        ?>
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