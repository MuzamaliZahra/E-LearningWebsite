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


if (isset($_GET['video_id'])) {
    $video_id = $_GET['video_id'];

    // Fetch the video details from the database
    $video_query = "SELECT * FROM `content` WHERE content_id = ? LIMIT 1";
    $video_stmt = mysqli_prepare($conn, $video_query);
    mysqli_stmt_bind_param($video_stmt, "s", $video_id);
    mysqli_stmt_execute($video_stmt);
    $video_result = mysqli_stmt_get_result($video_stmt);

    if ($video_result->num_rows > 0) {
        $video_row = $video_result->fetch_assoc();
        $video_title = $video_row['title'];
        $video_description = $video_row['description'];
        $video_url = $video_row['video'];
        $thumbnail_url = $video_row['thumb'];
    } else {   
    }
} else {
}
?>

<!DOCTYPE html>
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

<section class="view-content">   
    <main>
        <video width="800" height="450" controls>
            <source src="image/<?php echo $video_url; ?>" type="video/mp4">
        </video>
        <h1><?php echo $video_title; ?></h1>
        <p><?php echo $video_description; ?></p>
    </main>
</section>


<section class="comments">
    <h1 class="heading">User Comments</h1>
    <div class="show-comments">

<?php
        $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE tutor_id = ?");
        $select_comments->bind_param("s", $tutor_id);
        $select_comments->execute();
        $select_comments_result = $select_comments->get_result();

        if($select_comments_result->num_rows > 0){
        while($fetch_comment = $select_comments_result->fetch_assoc()){
            $select_content = $conn->prepare("SELECT * FROM `content` WHERE content_id = ?");
            $select_content->bind_param("s", $fetch_comment['content_id']);
            $select_content->execute();
            $select_content_result = $select_content->get_result();
            $fetch_content = $select_content_result->fetch_assoc();
?>

                                    
<div class="box" style="<?php if($fetch_comment['tutor_id'] == $tutor_id){echo 'order:-1;';} ?>">
   <div class="content">
    <span><?= $fetch_comment['date']; ?></span>
    <?php
    if (isset($fetch_content) && isset($fetch_content['title'])) {
    echo '<p>' . $fetch_content['title'] . '</p>';
} else {
    echo '<p>Title </p>';
}?>
    <a href="admContent.php.php?get_id=<?= $fetch_content['content_id']; ?>">view content</a></div>
   
    <p class="text"><?= $fetch_comment['comment']; ?></p>
   <form action="" method="post">
      <input type="hidden" name="comment_id" value="<?= $fetch_comment['comment_id']; ?>">
      <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('Delete this comment?');">Delete comment</button>
   </form>
</div>
<?php
   }
}else{
   echo '<p class="empty">No comments added yet!</p>';
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
