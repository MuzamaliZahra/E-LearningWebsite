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


if (isset($_GET['get_id'])) {
   $get_id = $_GET['get_id'];
} else {
   $get_id = '';
  
}

if (isset($_POST['like_content'])) {

   if ($student_id != '') {

      $content_id = $_POST['content_id'];

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE content_id = ? LIMIT 1");
      $select_content->bind_param("i", $content_id);
      $select_content->execute();
      $result_content = $select_content->get_result();
      $fetch_content = $result_content->fetch_assoc();

      $tutor_id = $fetch_content['tutor_id'];

      $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE student_id = ? AND content_id = ?");
      $select_likes->bind_param("ii", $student_id, $content_id);
      $select_likes->execute();
      $result_likes = $select_likes->get_result();

      if ($result_likes->num_rows > 0) {
         $remove_likes = $conn->prepare("DELETE FROM `likes` WHERE student_id = ? AND content_id = ?");
         $remove_likes->bind_param("ii", $student_id, $content_id);
         $remove_likes->execute();
         $message[] = 'removed from likes!';
      } else {
         $insert_likes = $conn->prepare("INSERT INTO `likes`(student_id, tutor_id, content_id) VALUES(?,?,?)");
         $insert_likes->bind_param("iii", $students_id, $tutor_id, $content_id);
         $insert_likes->execute();
         $message[] = 'added to likes!';
      }

   } else {
      $message[] = 'please login first!';
   }

}

if (isset($_POST['add_comment'])) {

   if ($user_id != '') {

      
      $comment_box = $_POST['comment_box'];
      $content_id = $_POST['content_id'];

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE content_id = ? LIMIT 1");
      $select_content->bind_param("i", $content_id);
      $select_content->execute();
      $result_content = $select_content->get_result();
      $fetch_content = $result_content->fetch_assoc();

      $tutor_id = $fetch_content['tutor_id'];

      if ($result_content->num_rows > 0) {

         $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND tutor_id = ? AND comment = ?");
         $select_comment->bind_param("iiis", $content_id, $user_id, $tutor_id, $comment_box);
         $select_comment->execute();
         $result_comment = $select_comment->get_result();

         if ($result_comment->num_rows > 0) {
            $message[] = 'comment already added!';
         } else {
            $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, tutor_id, comment) VALUES(?,?,?,?,?)");
            $insert_comment->bind_param("siiis", $id, $content_id, $user_id, $tutor_id, $comment_box);
            $insert_comment->execute();
            $message[] = 'new comment added!';
         }

      } else {
         $message[] = 'something went wrong!';
      }

   } else {
      $message[] = 'please login first!';
   }

}

if (isset($_POST['delete_comment'])) {

   $delete_id = $_POST['comment_id'];

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment_id = ?");
   $verify_comment->bind_param("s", $delete_id);
   $verify_comment->execute();
   $result_verify_comment = $verify_comment->get_result();

   if ($result_verify_comment->num_rows > 0) {
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE comment_id = ?");
      $delete_comment->bind_param("s", $delete_id);
      $delete_comment->execute();
      $message[] = 'comment deleted successfully!';
   } else {
      $message[] = 'comment already deleted!';
   }

}

if (isset($_POST['update_now'])) {

   $update_id = $_POST['update_id'];
   $update_box = $_POST['update_box'];

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment_id = ? AND comment = ?");
   $verify_comment->bind_param("ss", $update_id, $update_box);
   $verify_comment->execute();
   $result_verify_comment = $verify_comment->get_result();

   if ($result_verify_comment->num_rows > 0) {
      $message[] = 'comment already added!';
   } else {
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE comment_id = ?");
      $update_comment->bind_param("ss", $update_box, $update_id);
      $update_comment->execute();
      $message[] = 'comment edited successfully!';
   }

}

?>





<html>
    <head>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
        <link rel="stylesheet" href="style.css">
        

        <title>watch video</title>
    </head>
    <body>



        <header class="header">
            <section class="flex" >
                        <a href="" class="logo">Educate.</a>
             
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
                                <a href="" class="btn">view profile</a>
                        <div class="flex-btn">
                                <a href="login.php" class="option-btn">login</a>
                                <a href="registration.php" class="option-btn">register</a>
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

         </nav>
        </header>

        <?php

if (isset($_POST['edit_comment'])) {
   $edit_id = $_POST['comment_id'];
   $edit_id = filter_var($edit_id);
   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment_id = ? LIMIT 1");
   $verify_comment->bind_param("s", $edit_id);
   $verify_comment->execute();
   $result_comment = $verify_comment->get_result();
   
   if ($result_comment->num_rows > 0) {
      $fetch_edit_comment = $result_comment->fetch_assoc();
?>
<section class="edit-comment">
   <h1 class="heading">edit comment</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['comment_id']; ?>">
      <textarea name="update_box" class="box" maxlength="1000" required placeholder="please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="watch_video.php" class="inline-option-btn">cancel edit</a>
         <input type="submit" value="update now" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   } else {
      $message[] = 'comment was not found!';
   }
}
?>

<!-- watch video section starts  -->

<section class="watch-video">
   <?php
      $select_content = $conn->prepare("SELECT * FROM `content` WHERE status = ?");
      $status = 'active';
      $select_content->bind_param("s", $status);
      $select_content->execute();
      $result_content = $select_content->get_result();
      
      if ($result_content->num_rows > 0) {
         while ($fetch_content = $result_content->fetch_assoc()) {
            $content_id = $fetch_content['content_id'];

            $select_likes = $conn->prepare("SELECT * FROM `likes` WHERE content_id = ?");
            $select_likes->bind_param("i", $content_id);
            $select_likes->execute();
            $result_likes = $select_likes->get_result();
            $total_likes = $result_likes->num_rows;

            $verify_likes = $conn->prepare("SELECT * FROM `likes` WHERE student_id = ? AND content_id = ?");
            $verify_likes->bind_param("si", $user_id, $content_id);
            $verify_likes->execute();
            $result_verify_likes = $verify_likes->get_result();

            $select_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE tutor_id = ? LIMIT 1");
            $select_tutor->bind_param("i", $fetch_content['tutor_id']);
            $select_tutor->execute();
            $result_tutor = $select_tutor->get_result();
            $fetch_tutor = $result_tutor->fetch_assoc();
   ?>
   <div class="video-details">
      <video src="images/<?= $fetch_content['video']; ?>" class="video" poster="uploaded_files/<?= $fetch_content['thumb']; ?>" controls autoplay></video>
      
      video controls>
                <source src="image/<?php echo $video['video']; ?>" type="video/mp4">
            </video>
      
      <h3 class="title"><?= $fetch_content['title']; ?></h3>
      <div class="info">
         <p><i class="fas fa-calendar"></i><span><?= $fetch_content['date']; ?></span></p>
         <p><i class="fas fa-heart"></i><span><?= $total_likes; ?> likes</span></p>
      </div>
      <div class="tutor">
         <img src="image/<?= $fetch_tutor['image']; ?>" alt="">
         <div>
            <h3><?= $fetch_tutor['name']; ?></h3>
            <span><?= $fetch_tutor['profession']; ?></span>
         </div>
      </div>
      <form action="" method="post" class="flex">
         <input type="hidden" name="content_id" value="<?= $content_id; ?>">
         <a href="playlist.php?get_id=<?= $fetch_content['playlist_id']; ?>" class="inline-btn">view playlist</a>
         <?php
            if ($result_verify_likes->num_rows > 0) {
         ?>
         <button type="submit" name="like_content"><i class="fas fa-heart"></i><span>liked</span></button>
         <?php
            } else {
         ?>
         <button type="submit" name="like_content"><i class="far fa-heart"></i><span>like</span></button>
         <?php
            }
         ?>
      </form>
      <div class="description"><p><?= $fetch_content['description']; ?></p></div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">no videos added yet!</p>';
      }
   ?>
</section>

<!-- watch video section ends -->

<!-- comments section starts  -->

<section class="comments">

   <h1 class="heading">add a comment</h1>

   <form action="" method="post" class="add-comment">
      <textarea name="comment_box" required placeholder="write your comment..." maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" name="add_comment" class="inline-btn">
   </form>

   <h1 class="heading">user comments</h1>

   <div class="show-comments">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments`");
         $select_comments->execute();
         $result_comments = $select_comments->get_result();
         
         if ($result_comments->num_rows > 0) {
            while ($fetch_comment = $result_comments->fetch_assoc()) {
               $select_commentor = $conn->prepare("SELECT * FROM `student` WHERE student_id = ?");
               $select_commentor->bind_param("i", $fetch_comment['student_id']);
               $select_commentor->execute();
               $result_commentor = $select_commentor->get_result();
               $fetch_commentor = $result_commentor->fetch_assoc();



               if ($result_comments->num_rows > 0) {
                  while ($fetch_comment = $result_comments->fetch_assoc()) {
                      $select_commentor = $conn->prepare("SELECT * FROM `student` WHERE student_id = ?");
                      $select_commentor->bind_param("i", $fetch_comment['student_id']);
                      $select_commentor->execute();
                      $result_commentor = $select_commentor->get_result();
                      $fetch_commentor = $result_commentor->fetch_assoc();
              












      ?>
      <div class="box" style="<?php if ($fetch_comment['student_id'] == $user_id) { echo 'order:-1;'; } ?>">
         <div class="user">
            <img src="image/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <?php
            if ($fetch_comment['student_id'] == $student_id) {
         ?>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['comment_id']; ?>">
            <button type="submit" name="edit_comment" class="inline-option-btn">edit comment</button>
            
            <form action="" method="post">
                  
                    <input type="submit" value="Delete" name="delete_comment" class="delete-btn">
                </form>
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">delete comment</button>
         </form>
         <?php
            }
         ?>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">no comments added yet!</p>';
         }}}
      ?>
   </div>
   
</section>








        <footer class="footer">

            <P >CONTACT US <br>
            Emil  - EDUCATE@gmail.com        <br>
            PHONE   -  077456270 
            
            </P>
        
        
        </footer>
    
     <script src="script.js"></script>
    </body>
</html>