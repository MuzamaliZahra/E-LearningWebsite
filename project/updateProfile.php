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
        $image = "image/" .$row['image']; // Assuming the column name for profile image is 'profile_image'
        $name = $row['name']; // Assuming the column name for user's name is 'name'
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
?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>LearnWiz.</title>
</head>

<body>   
<header class="header">
        <section class="flex" >
                    <a href="" class="logo">LearnWiz..</a>
                        <form action="search_course.php" method="post" class="search-form">
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
                            <span>student</span>
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
                    <a href="HOME.php"><i class="fas fa-home"></i><span>Home</span></a>
                    <a href="about.php"><i class="fas fa-question"></i><span>About us</span></a>
                    <a href="cources.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
                    <a href="tutor.php"><i class="fas fa-chalkboard-user"></i><span>Tutors</span></a>
                    <a href="studentDocument.php"><i class="fa fa-edit"></i><span>Documents</span></a>
            </nav>
</header>

   
<!-- side bar section starts  -->


<section class="form-container">
    <form class="register" action="" method="" >
    <h3>Update Profile </h3>

    <div class="flex">
        <div class="col">
            <P>Your Name</P>
            <input type="text" name="name" placeholder="enter Your name" maxlength="20" required class="box">
            
            <P>Your Email</P>
            <input type="text" name="email" placeholder="enter Your email" maxlength="20" required class="box">
        
            <p>select pic <span>*</span></p>
            <input type="file" name="image" accept="image/*" required class="box">

        </div>

        <div class="col">
            <p>Old password <span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
           
            <p>New password <span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">

            <p>confirm password <span>*</span></p>
            <input type="password" name="cpass" placeholder="confirm your password" maxlength="20" required class="box">
        </div>
    
    
    </div>
    
    <input type="submit" name="submit" value="Update Profile" class="btn">
 </form>
</section>


 <script src="script.js"></script>
    </body>

</html>