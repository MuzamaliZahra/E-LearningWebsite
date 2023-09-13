<?php
// playlistDetails.php

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


// Retrieve playlist_id and tutor_id from the URL
if (isset($_GET['playlist_id']) && isset($_GET['tutor_id'])) {
    $playlist_id = $_GET['playlist_id'];
    $tutor_id = $_GET['tutor_id'];

    // Fetch playlist data
    $stmt = $conn->prepare("SELECT * FROM `playlist` WHERE playlist_id = ? AND tutor_id = ? LIMIT 1");
    $stmt->bind_param("ss", $playlist_id, $tutor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Playlist data found
        $playlist_data = $result->fetch_assoc();
        // Now, you can use $playlist_data to display playlist details or fetch videos related to this playlist.
        $playlist_title = $playlist_data['title'];
        $playlist_description = $playlist_data['description'];

        // Fetch videos for this playlist
        $select_videos = "SELECT * FROM `content` WHERE playlist_id = ?";
        $stmt_videos = $conn->prepare($select_videos);
        $stmt_videos->bind_param("i", $playlist_id);
        $stmt_videos->execute();
        $result_videos = $stmt_videos->get_result();
    } else {
        // Playlist data not found, handle accordingly
        // Redirect back to the playlist page or show an error message
        exit("Playlist not found.");
    }
} else {
    // Invalid URL parameters, handle accordingly
    // Redirect back to the playlist page or show an error message
    exit("Invalid URL parameters.");
}
?>

<html>

<head>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>LearnWiz</title>
</head>

<body>

    <header class="header">
        <section class="flex" >
                    <a href="#" class="logo">Admin</a>
         
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
         </nav>
    </header>
    
     
  
<section class="playlists">



<h1 class="heading">Added Playlist</h1>

   
<div class="box-container">
<div class="box" style="text-align: center;">
  <h3 class="title" style="margin-bottom: .5rem;">Create New Playlist</h3>
  
<a href="createPlaylist.php" class="btn">Add Playlist</a>
</div>
</br>
     
     <div class="box-container">
  
     
        <div class="box">
           <div class="flex">
<!-- Your HTML code here -->

<h2><?= $playlist_title; ?></h2>
<p><?= $playlist_description; ?></p>
           </div>
<!-- Loop through videos and display them -->
<?php if ($result_videos && $result_videos->num_rows > 0) { ?>
    <?php while ($video = $result_videos->fetch_assoc()) { ?>
        <div class="video-box">
            <h3><?= $video['title']; ?></h3>
            <p><?= $video['description']; ?></p>
            <!-- Embed video using HTML5 video tag -->
            <video controls>
                <source src="image/<?php echo$video['video']; ?>" type="video/mp4">
            </video>
            
        </div>
    <?php } ?>
<?php } else { ?>
    <p>No videos found for this playlist.</p>
<?php } ?>

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
