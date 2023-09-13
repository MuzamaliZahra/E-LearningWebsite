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
<title>LearnWiz</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="style.css">
</head>

<body>
<header class="header">
<section class="flex" >
      <a href="#" class="logo">LearnWiz.</a>
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
         <img src="studentProfile.php" alt="student profile">
         <img src="<?php echo $image; ?>"  alt="student profile">
         <h3><?php echo $name; ?></h3>
         <h3>student</h3>
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
 <!-- courses section ends -->
<section class="teachers">
    <h1 class="heading">EXPERT TUTORS</h1>    
         <form action="#" method="post" class="search-tutor">
            <input type="text" name="search_box" placeholder="search tutor" required maxlength="100">
            <button type="submit" class="fas fa-search" name="search_tutor"></button>
         </form>

   <div class="box-container">         
      <div class="box offer">
            <h3>become a tutor</h3>
            <p>Join with us to bright up future leaders and </br> together improve your skills and knowledge...!
            <a href="log_reg.php" class="inline-btn">get started</a>
      </div>

<?php
   $select_tutors = $conn->prepare("SELECT * FROM `tutor`");
   $select_tutors->execute();
   $result_tutors = $select_tutors->get_result();
   if ($result_tutors->num_rows > 0) {
      while ($fetch_tutor = $result_tutors->fetch_assoc()) {

      $tutor_id = $fetch_tutor['tutor_id'];

      $count_playlists = $conn->prepare("SELECT * FROM `playlist` WHERE tutor_id = ?");
      $count_playlists->bind_param("i", $tutor_id);
      $count_playlists->execute();
      $result_playlists = $count_playlists->get_result();
      $total_playlists = $result_playlists->num_rows;

      $count_contents = $conn->prepare("SELECT * FROM `content` WHERE tutor_id = ?");
      $count_contents->bind_param("i", $tutor_id);
      $count_contents->execute();
      $result_contents = $count_contents->get_result();
      $total_contents = $result_contents->num_rows;

      $count_likes = $conn->prepare("SELECT * FROM `likes` WHERE tutor_id = ?");
      $count_likes->bind_param("i", $tutor_id);
      $count_likes->execute();
      $result_likes = $count_likes->get_result();
      $total_likes = $result_likes->num_rows;

      $count_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
      $count_comments->bind_param("i", $tutor_id);
      $count_comments->execute();
      $result_comments = $count_comments->get_result();
      $total_comments = $result_comments->num_rows;
?>
   
   <div class="box">
      <div class="tutor">
            <img src="image/<?php echo $fetch_tutor['image']; ?>" alt="">
      <div>
               <h3><?php echo $fetch_tutor['name']; ?></h3>
               <span><?php echo $fetch_tutor['profession']; ?></span>
      </div>
      </div>
                <!-- fetch the counts-->
            <p>playlists : <span><?php echo $total_playlists; ?></span></p>
            <p>total videos : <span><?php echo $total_contents; ?></span></p>
            <p>total likes : <span><?php echo $total_likes; ?></span></p>
            <p>total comments : <span><?php echo $total_comments; ?></span></p>
         <form action="useeTutors_Profile.php" method="post">
            <input type="hidden" name="tutor_email" value="<?php echo $fetch_tutor['email']; ?>">
            <input type="submit" value="view profile" name="tutor_fetch" class="inline-btn">
         </form>
      </div>
<?php
      }
   } else {
      echo '<p class="empty">no tutors found!</p>';
   }
?>
</div>
</div>   
</section>  
 <!-- courses section ends -->
 
<footer class="footer"> 
    <P>
    Emil  - EDUCATE@gmail.com   &nbsp &nbsp 
    PHONE   -  077456270        &nbsp &nbsp
    ADDRESS  -2nd, colombo
    </P> 
</footer>
<script src="script.js"></script>
</body>
</html>