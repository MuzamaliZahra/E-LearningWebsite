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
        $image = "image/" . $row['image']; 
        $name = $row['name']; 
    } else {
  
        $image = "image.jpg"; 
        $name = "Guest"; 
    }
} else {
    $tutor_id = ''; 
    $image = "default-profile-image.jpg";
    $name = "Guest"; 
}

if (isset($_POST['submit'])) {
    $content_id = uniqid();
    $status = $_POST['status'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $playlist = $_POST['playlist'];

    $thumb = $_FILES['thumb']['name'];
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = uniqid() . '.' . $thumb_ext;
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = 'image/' . $rename_thumb;

    $video = $_FILES['video']['name'];
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $rename_video = uniqid() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = 'image/' . $rename_video;

    if ($thumb_size > 2000000) {
        $message[] = 'Image size is too large!';
    } else {
        $add_playlist = $conn->prepare("INSERT INTO `content`(tutor_id, playlist_id, title, description, video, thumb, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $add_playlist->bind_param("sssssss", $tutor_id, $playlist, $title, $description, $rename_video, $rename_thumb, $status);
        $add_playlist->execute();
        move_uploaded_file($thumb_tmp_name, $thumb_folder);
        move_uploaded_file($video_tmp_name, $video_folder);
        $message[] = 'New course uploaded!';
    }
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



<section class="playlist-form">
    <h1 class="heading">Upload Content</h1>
    <form action="" method="post"  enctype="multipart/form-data">

   <p>video status <span>*</span></p>
   <select name="status" class="box" required>
      <option value="" selected disabled>-- select status</option>
      <option value="active">active</option>
      <option value="deactive">deactive</option>
   </select>
   <p>video title <span>*</span></p>

   <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box">
   <p>video description <span>*</span></p>

   <textarea name="description" class="box" required placeholder="write description" maxlength="1000" cols="30" rows="10"></textarea>
   <p>video playlist <span>*</span></p>

   <select name="playlist" class="box" required>
      <option value="" disabled selected>--select playlist</option>
      <?php
      $select_playlist = mysqli_prepare($conn, "SELECT * FROM `playlist` WHERE tutor_id = ?");
      mysqli_stmt_bind_param($select_playlist, "s", $tutor_id);
      mysqli_stmt_execute($select_playlist);
      $result = mysqli_stmt_get_result($select_playlist);
      if (mysqli_num_rows($result) > 0) {
         while ($fetch_playlist = mysqli_fetch_assoc($result)) {
            ?>
            <option value="<?= $fetch_playlist['playlist_id']; ?>"><?= $fetch_playlist['title']; ?></option>
            <?php
         }
      } else {
         echo '<option value="disabled 
         
         no playlist created yet!"</option>';
      }
      ?>
   </select>

   <p>select thumbnail </p>
      <input type="file" name="thumb" id="thumb" accept="image/*" required class="box">
      <p>select video </p>
      <input type="file" name="video" accept="video/*" required class="box">
   <input type="submit" value="upload" name="submit" class="btn">
</form>
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