<?php

session_start();
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




// Retrieve the playlist_id from the URL parameter
if (isset($_GET['playlist_id'])) {
    $playlist_id = $_GET['playlist_id'];

    // Fetch playlist data
    $stmt = $conn->prepare("SELECT * FROM `playlist` WHERE playlist_id = ? LIMIT 1");
    $stmt->bind_param("i", $playlist_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $playlist = $result->fetch_assoc();
    } else {
    }

    // Fetch videos from specific playlist
    $select_videos = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = ? ORDER BY date ASC");
    $select_videos->bind_param("i", $playlist_id);
    $select_videos->execute();
    $result_videos = $select_videos->get_result();    
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>LearnWiz.</title>
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
                            <span>student</span>
                            <h3><?php echo $name; ?></h3>
                            <a href="<?php echo $image; ?>" class="btn">view profile</a>
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


<?php
    $_SESSION['student_id'] = $student_id;

    if (isset($_POST['add_comment'])) {
        if (isset($_POST['comment_box']) && isset($_POST['content_id']) && isset($_SESSION['student_id'])) {
            $comment = $_POST['comment_box'];
            $video_id = $_POST['content_id'];
            $student_id = $_SESSION['student_id']; // Retrieve student ID from the session

            // Insert the comment into the comments table
            $insert_comment = $conn->prepare("INSERT INTO `comments` (comment, date, student_id, content_id) VALUES (?, NOW(), ?, ?)");
            $insert_comment->bind_param("sii", $comment, $student_id, $video_id);
            $insert_comment->execute();
            $insert_comment->close();
        }
    }
?>



<section class="playlist">
    <h1 class="heading"><?php echo $playlist['title']; ?></h1>
    <p><?php echo $playlist['description']; ?></p>

    <?php
    if ($result_videos !== false && $result_videos->num_rows > 0) {
        while ($video = $result_videos->fetch_assoc()) {
            ?>
            <div class="video-box">
                <h3 class="video-title"><?php echo $video['title']; ?></h3>
                <video controls>
                    <source src="image/<?php echo $video['video']; ?>" type="video/mp4">
                </video>
                <div class="box">
                <form action="" method="post" class="add-comment">
                    <textarea name="comment_box" required placeholder="write your comment..." maxlength="1000" cols="30" rows="3"></textarea>
                    <input type="hidden" name="content_id" value="<?php echo $video['content_id']; ?>">
                    <input type="submit" value="add comment" name="add_comment" class="inline-btn">
                </form>
                
                <!-- Display Old Comments -->
            <?php
                $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
                $select_comments->bind_param("i", $video['content_id']);
                $select_comments->execute();
                $result_comments = $select_comments->get_result();


              if (isset($_POST['delete_comment'])) {
               $comment_id = $_POST['comment_id'];
           
               // Delete the comment from the comments table
               $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE comment_id = ?");
               $delete_comment->bind_param("i", $comment_id);
               $delete_comment->execute();
               $delete_comment->close();
           }

                if ($result_comments->num_rows > 0) {
                    while ($fetch_comment = $result_comments->fetch_assoc()) {
                        $select_commentor = $conn->prepare("SELECT * FROM `student` WHERE student_id = ?");
                        $select_commentor->bind_param("i", $fetch_comment['student_id']);
                        $select_commentor->execute();
                        $result_commentor = $select_commentor->get_result();
                        $fetch_commentor = $result_commentor->fetch_assoc();
            ?>


                        <div class="box">
                            <div class="user">
                                <img src="image/<?= $fetch_commentor['image']; ?>" alt="">
                                <div>
                                    <h3><?= $fetch_commentor['name']; ?></h3>
                                    <span><?= $fetch_comment['date']; ?></span>
                                </div>
                            </div>
                            <p class="text"><?= $fetch_comment['comment']; ?></p>
                        </div>

    <form action="" method="post" class="flex-btn">
        <input type="hidden" name="comment_id" value="<?= $fetch_comment['comment_id']; ?>">
        <button type="submit" name="edit_comment" class="inline-option-btn">Edit Comment</button>
        <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('Delete this comment?');">Delete Comment</button>
    </form>
                    
                    <?php
                    }
                } else {
                    echo '<p class="empty">No comments added yet!</p>';
                }
                ?>
            </div>
        <?php
        }
    } else {
        echo '<p class="empty">No videos found for this playlist!</p>';
    }
    ?>

<?php

    if (isset($_POST['edit_comment'])) {
        $edit_id = $_POST['comment_id'];
        $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment_id = ? LIMIT 1");
        $verify_comment->bind_param("i", $edit_id);
        $verify_comment->execute();
        $result_verify_comment = $verify_comment->get_result();
        if ($result_verify_comment->num_rows > 0) {
            $fetch_edit_comment = $result_verify_comment->fetch_assoc();
 ?>


<section class="edit-comment">
            <h1 class="heading">Edit Comment</h1>
            <form action="" method="post">
                <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['comment_id']; ?>">
                <textarea name="update_box" class="box" maxlength="1000" required placeholder="Please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
                <div class="flex">
                    <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">Cancel Edit</a>
                    <input type="submit" value="Update Now" name="update_now" class="inline-btn">
                </div>
            </form>
        </section>
<?php
        } else {
            $message[] = 'Comment was not found!';
        }
    }


?>

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
