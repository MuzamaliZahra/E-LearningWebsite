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

if(isset($_POST['delete'])){
    $delete_id = $_POST['playlist_id'];
     
    $verify_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE playlist_id = ? AND tutor_id = ? LIMIT 1");
    $verify_playlist->bind_param("ss", $delete_id, $tutor_id);
    $verify_playlist->execute();
    $verify_playlist_result = $verify_playlist->get_result();
 
    if($verify_playlist_result->num_rows > 0){
 
       $delete_playlist_thumb = $conn->prepare("SELECT * FROM `playlist` WHERE playlist_id = ? LIMIT 1");
       $delete_playlist_thumb->bind_param("s", $delete_id);
       $delete_playlist_thumb->execute();
       $delete_playlist_thumb_result = $delete_playlist_thumb->get_result();
       $fetch_thumb = $delete_playlist_thumb_result->fetch_assoc();
       unlink('image/'.$fetch_thumb);
     
       $delete_playlist = $conn->prepare("DELETE FROM `playlist` WHERE playlist_id = ?");
       $delete_playlist->bind_param("s", $delete_id);
       $delete_playlist->execute();
 
       $message[] = 'Playlist deleted!';
    } else {
       $message[] = 'Playlist already deleted!';
    }
 }

?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <title>
            LearnWiz.
    </title>
</head>
<body>
<header class="header">
    <section class="flex">
        <a href="#" class="logo">Admin.</a>
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

<section class="playlists">
    <h1 class="heading">Added Playlist</h1>
    <div class="box-container">
    <div class="box" style="text-align: center;">
        <h3 class="title" style="margin-bottom: .5rem;">Create New Playlist</h3>
        <a href="createPlaylist.php" class="btn">Add Playlist</a>
    </div>

   <?php
      $select_playlists = "SELECT * FROM `playlist` WHERE tutor_id = ? ORDER BY date DESC";
      $stmt = $conn->prepare($select_playlists);
      $stmt->bind_param("i", $tutor_id);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows > 0){
         while($fetch_playlists = $result->fetch_assoc()){ 
            $playlist_id = $fetch_playlists['playlist_id'];
            $thumbnail = $fetch_playlists['thumb'];            
   ?>

      <div class="box">
         <div class="flex">
            <div><i class="fas fa-dot-circle" style="<?php if($fetch_playlists['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"></i><span style="<?php if($fetch_playlists['status'] == 'active'){echo 'color:limegreen'; }else{echo 'color:red';} ?>"><?= $fetch_playlists['status']; ?></span></div>
            <div><i class="fas fa-calendar"></i><span><?= $fetch_playlists['date']; ?></span></div>
         </div>
              
         <div class="thumb">
                <img src="profile\thumb.png" alt="its not fetchig">
        </div>
       
         <h3 class="title"><?= $fetch_playlists['title']; ?></h3>
         <div class="description"><?= $fetch_playlists['description']; ?></div>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="playlist_id" value="<?= $playlist_id; ?>">
            <a href="updatePlaylist.php?get_id=<?= $playlist_id; ?>" class="option-btn">Update Playlist</a>
            <input type="submit" value="Delete Playlist" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete">
         </form>
         <a href="playlistDetails.php?playlist_id=<?= $playlist_id; ?>&tutor_id=<?= $tutor_id; ?>" class="btn">View Playlist</a>

        
      </div>
   <?php
         }
      }else{
         echo '<p class="empty">No playlists added yet!</p>';
      }
   ?>
</div>

</section>
<script src="script.js"></script>
</body>


<footer class="footer">

    <P >
    Emil  - EDUCATE@gmail.com        &nbsp &nbsp
    PHONE   -  077456270        &nbsp &nbsp
    ADDRESS  -2nd, colombo
    
    </P>
</footer>
</html>