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

if (isset($_POST['submit'])) {
   
   
    $status = $_POST['status'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    
    $stmt = $conn->prepare("UPDATE `playlist` SET  status = ?, title =?, description=? WHERE playlist_id=?");
    $stmt->bind_param("sssi", $status ,$title ,$description, $get_id);
    $stmt->execute();
   
    if (isset($fetch_playlist['image'])) {
        $imageURL = $fetch_playlist['image'];
   
    $old_image1 = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = uniqid() . '.' . $ext;
    $image1_size = $_FILES['image']['size'];
    $image1_tmp_name = $_FILES['image']['tmp_name'];
    $image1_folder = '../uploaded_files/' . $rename;


    }
    if (!empty($image1)) {
        if ($image1_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            $update_image = $conn->prepare("UPDATE `playlist` SET thumb = ? WHERE playlist_id = ?");
            $update_image->bind_param("si", $rename, $get_id);
            $update_image->execute();
            move_uploaded_file($image1_tmp_name, $image1);
            if ($old_image1 != '' && $old_image1!= $rename) {
                unlink('../uploaded_files/' . $old_image1);
            }
        }
    }
    $message[] = 'Playlist updated!';

}
?>



<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   
    <link rel="stylesheet" href="style.css">

<title>
    UpdatePlaylist
</title>
</head>


<body>
<header class="header">

    <section class="flex">

        <a href="" class="logo">Admin.</a>

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
            <a href="studentProfile.php" class="btn">view profile</a>


            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="registration.php" class="option-btn">register</a>
            </div>   


            <a href="" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>

        
        </div>

    </section>

    <h1 class="heading"></h1>

<nav class="navbar">
   <a href="Dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
   <a href="adminPlaylis.php"><i class="fa-solid fa-bars-staggered"></i><span>playlists</span></a>
   <a href="adminContentUpload.php"><i class="fas fa-graduation-cap"></i><span>contents</span></a>
   <a href="tutorDocument.php"><i class="fa fa-edit"></i><span>Documents</span></a>
   <a href="userComments.php"><i class="fas fa-comment"></i><span>comments</span></a>
   <a href="" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
</nav>



</header>



<section class="playlist-form">
    <h1 class="heading">Update Playlist</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" >



<?php

$select_playlist = $conn->prepare("SELECT * FROM `playlist` WHERE playlist_id = ?");
$select_playlist->bind_param("i", $get_id);
$select_playlist->execute();
$select_playlist_result = $select_playlist->get_result();

if ($select_playlist_result->num_rows > 0) {
    while ($fetch_playlist = $select_playlist_result->fetch_assoc()) {
        $playlist_id = $fetch_playlist['playlist_id'];
        
        $count_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ?");
        $count_videos->bind_param("i", $playlist_id);
        $count_videos->execute();
        $count_videos_result = $count_videos->get_result();
        
        $total_videos = $count_videos_result->num_rows;
        
        // Rest of your code...
    }
}



?>





<input type="hidden" name="old_image" value="<?php echo $fetch_playlist['thumb']; ?>">
        <p>Playlist Status</p>
        <select name="status" class="box" required>
            <option value="active">active</option>
            <option value="deactive" >deactive</option>
        </select>

        <p>Playlist Title</p>
        <input type="text" name="title" maxlength="100" placeholder="Playlist Title" value="" class="box" required>

        <p>Playlist Description <span>*</span></p>
        <textarea name="description" class="box" placeholder="Playlist Description" maxlength="1000" cols="30" rows="10" required></textarea>

        <p>Playlist Thumbnail</p>
        <div class="thumb">
            <span><?php echo isset($total_videos) ? $total_videos : 0; ?></span>
            <img src="<?php echo $fetch_playlist['thumb']; ?>" alt="">
        </div>

        <input type="file" name="image" accept="image/*" class="box">
        <input type="submit" value="Update Playlist" name="submit" class="btn">
        <div class="flex-btn">
            <input type="submit" value="Delete" class="delete-btn" onclick="return confirm('Delete this playlist?');" name="delete">
            <a href="adminPlaylist.php" class="option-btn">View Playlist</a>
        </div>
    </form>
</section>



<script src="script.js"></script>
</body>

</html>