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


if (isset($_POST['update'])) {

    $video_id = $_POST['video_id'];
    $status = $_POST['status'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $playlist = $_POST['playlist'];

    $update_content_query = "UPDATE `content` SET title = ?, description = ?, status = ? WHERE id = ?";
    $update_content_stmt = mysqli_prepare($conn, $update_content_query);
    mysqli_stmt_bind_param($update_content_stmt, "sssi", $title, $description, $status, $video_id);
    mysqli_stmt_execute($update_content_stmt);

    if (!empty($playlist)) {
        $update_playlist_query = "UPDATE `content` SET playlist_id = ? WHERE id = ?";
        $update_playlist_stmt = mysqli_prepare($conn, $update_playlist_query);
        mysqli_stmt_bind_param($update_playlist_stmt, "si", $playlist, $video_id);
        mysqli_stmt_execute($update_playlist_stmt);
    }

    $old_thumb = $_POST['old_thumb'];
    $thumb = $_FILES['thumb']['name'];
    $thumb_ext = pathinfo($thumb, PATHINFO_EXTENSION);
    $rename_thumb = unique_id() . '.' . $thumb_ext;
    $thumb_size = $_FILES['thumb']['size'];
    $thumb_tmp_name = $_FILES['thumb']['tmp_name'];
    $thumb_folder = '../uploaded_files/' . $rename_thumb;

    if (!empty($thumb)) {
        if ($thumb_size > 2000000) {
            $message[] = 'image size is too large!';
        } else {
            $update_thumb_query = "UPDATE `content` SET thumb = ? WHERE id = ?";
            $update_thumb_stmt = mysqli_prepare($conn, $update_thumb_query);
            mysqli_stmt_bind_param($update_thumb_stmt, "si", $rename_thumb, $video_id);
            mysqli_stmt_execute($update_thumb_stmt);

            move_uploaded_file($thumb_tmp_name, $thumb_folder);
            if ($old_thumb != '' && $old_thumb != $rename_thumb) {
                unlink('../uploaded_files/' . $old_thumb);
            }
        }
    }

    $old_video = $_POST['old_video'];
    $video = $_FILES['video']['name'];
    $video_ext = pathinfo($video, PATHINFO_EXTENSION);
    $rename_video = unique_id() . '.' . $video_ext;
    $video_tmp_name = $_FILES['video']['tmp_name'];
    $video_folder = '../uploaded_files/' . $rename_video;

    if (!empty($video)) {
        $update_video_query = "UPDATE `content` SET video = ? WHERE id = ?";
        $update_video_stmt = mysqli_prepare($conn, $update_video_query);
        mysqli_stmt_bind_param($update_video_stmt, "si", $rename_video, $video_id);
        mysqli_stmt_execute($update_video_stmt);

        move_uploaded_file($video_tmp_name, $video_folder);
        if ($old_video != '' && $old_video != $rename_video) {
            unlink('../uploaded_files/' . $old_video);
        }
    }

    $message[] = 'content updated!';
}

if (isset($_POST['delete_video'])) {

    $delete_id = $_POST['video_id'];

    $delete_video_thumb_query = "SELECT thumb FROM `content` WHERE id = ? LIMIT 1";
    $delete_video_thumb_stmt = mysqli_prepare($conn, $delete_video_thumb_query);
    mysqli_stmt_bind_param($delete_video_thumb_stmt, "s", $delete_id);
    mysqli_stmt_execute($delete_video_thumb_stmt);
    mysqli_stmt_store_result($delete_video_thumb_stmt);
    mysqli_stmt_bind_result($delete_video_thumb_stmt, $thumb);
    mysqli_stmt_fetch($delete_video_thumb_stmt);
    unlink('../uploaded_files/' . $thumb);

    $delete_video_query = "SELECT video FROM `content` WHERE id = ? LIMIT 1";
    $delete_video_stmt = mysqli_prepare($conn, $delete_video_query);
    mysqli_stmt_bind_param($delete_video_stmt, "s", $delete_id);
    mysqli_stmt_execute($delete_video_stmt);
    mysqli_stmt_store_result($delete_video_stmt);
    mysqli_stmt_bind_result($delete_video_stmt, $video);
    mysqli_stmt_fetch($delete_video_stmt);
    unlink('../uploaded_files/' . $video);

    $delete_likes_query = "DELETE FROM `likes` WHERE content_id = ?";
    $delete_likes_stmt = mysqli_prepare($conn, $delete_likes_query);
    mysqli_stmt_bind_param($delete_likes_stmt, "s", $delete_id);
    mysqli_stmt_execute($delete_likes_stmt);

    $delete_comments_query = "DELETE FROM `comments` WHERE content_id = ?";
    $delete_comments_stmt = mysqli_prepare($conn, $delete_comments_query);
    mysqli_stmt_bind_param($delete_comments_stmt, "s", $delete_id);
    mysqli_stmt_execute($delete_comments_stmt);

    $delete_content_query = "DELETE FROM `content` WHERE id = ?";
    $delete_content_stmt = mysqli_prepare($conn, $delete_content_query);
    mysqli_stmt_bind_param($delete_content_stmt, "s", $delete_id);
    mysqli_stmt_execute($delete_content_stmt);

    header('location:contents.php');
}
?>


<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   
    <link rel="stylesheet" href="style.css">

<title>
    UpdateContent
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
            <img src="" alt="">
            <h3>nsame</h3>
            <span>proffession</span>
            <a href="tutors_Profile.php" class="btn">view profile</a>


            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="registration.php" class="option-btn">register</a>
            </div>   


            <a href="" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>

            <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
        </div>

    </section>
    <h1 class="heading"></h1>

<nav class="navbar">
   <a href="Dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
   <a href="adminPlaylis.php"><i class="fa-solid fa-bars-staggered"></i><span>playlists</span></a>
   <a href="adminContentUpload.php"><i class="fas fa-graduation-cap"></i><span>contents</span></a>
   <a href="userComments.php"><i class="fas fa-comment"></i><span>comments</span></a>
   <a href="" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
</nav>


</header>


<section class="video-form">

    <h1 class="heading">update content</h1>




    
 
    
    <form action="" method="post" enctype="multipart/form-data">
       <input type="hidden" name="video_id" value="">
       <input type="hidden" name="old_thumb" value="">
       <input type="hidden" name="old_video" value="">
       <p>update status <span>*</span></p>
       <select name="status" class="box" required>
          <option value="" selected>status</option>
          <option value="active">active</option>
          <option value="deactive">deactive</option>
       </select>
       <p>update title <span>*</span></p>
       <input type="text" name="title" maxlength="100" required placeholder="enter video title" class="box" 
       value="">
       <p>update description <span>*</span></p>
       <textarea name="description" class="box" required placeholder="write description" maxlength="1000"
        cols="30" rows="10">description</textarea>
       <p>update playlist</p>
       <select name="playlist" class="box">
          <option value="" selected>--select playlist</option>
          
          <option value="">title</option>
         
       </select>
       <img src="" alt="">
       <p>update thumbnail</p>
       <input type="file" name="thumb" accept="image/*" class="box">
       <video src="" controls></video>
       <p>update video</p>
       <input type="file" name="video" accept="video/*" class="box">
       <input type="submit" value="update content" name="update" class="btn">
       <div class="flex-btn">
          <a href="" class="option-btn">view content</a>
          <input type="submit" value="delete content" name="delete_video" class="delete-btn">
       </div>
    </form>
   
 
 </section>
 


<script src="script.js"></script>
</body>

</html>