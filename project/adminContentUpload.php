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
        $profession = $row['profession'];
    } else {
        
        $image = "image.jpg"; 
        $name = "Guest"; 
    }
} else {
    $tutor_id = '';
    $image = "default-profile-image.jpg"; 
    $name = "Guest"; 
}

if (isset($_POST['delete_video'])) {
    $delete_id = $_POST['video_id'];
    $delete_id = mysqli_real_escape_string($conn, $delete_id);
    $verify_video = "SELECT * FROM `content` WHERE content_id = '$delete_id' LIMIT 1";
    $verify_video_result = mysqli_query($conn, $verify_video);
    if (mysqli_num_rows($verify_video_result) > 0) {
        $delete_video_thumb = "SELECT * FROM `content` WHERE content_id = '$delete_id' LIMIT 1";
        $delete_video_thumb_result = mysqli_query($conn, $delete_video_thumb);
        $fetch_thumb = mysqli_fetch_assoc($delete_video_thumb_result);
        unlink('image/' . $fetch_thumb['thumb']);
        $delete_video = "SELECT * FROM `content` WHERE content_id = '$delete_id' LIMIT 1";
        $delete_video_result = mysqli_query($conn, $delete_video);
        $fetch_video = mysqli_fetch_assoc($delete_video_result);
        unlink('image/' . $fetch_video['video']);
        $delete_likes = "DELETE FROM `likes` WHERE content_id = '$delete_id'";
        mysqli_query($conn, $delete_likes);
        $delete_comments = "DELETE FROM `comments` WHERE content_id = '$delete_id'";
        mysqli_query($conn, $delete_comments);
        $delete_content = "DELETE FROM `content` WHERE content_id = '$delete_id'";
        mysqli_query($conn, $delete_content);
        $message[] = 'video deleted!';
    } else {
        $message[] = 'video already deleted!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        
        .video-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            width: 300px;
        }

        .thumb img {
            max-width: 100%;
            height: auto;
        }

        .title {
            margin: 10px 0;
        }

        .description {
            font-size: 14px;
        }

           </style>

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

    <body>
    <section class="contents">
            <h1 class="heading">your contents</h1>

            <div class="box-container">

                <div class="box" style="text-align: center;">
                    <h3 class="title" style="margin-bottom: .5rem;">create new content</h3>
                    <a href="uploadContent.php" class="btn">add content</a>
                </div>
            <?php
            // PHP code for fetching and displaying videos
            function prepareData($data) {
                global $conn;
                return mysqli_real_escape_string($conn, trim($data));
            }

            function displayVideos() {
                global $conn;
                if (isset($_COOKIE['tutor_id'])) {
                    $tutor_id = prepareData($_COOKIE['tutor_id']);
                    $sql = "SELECT * FROM `content` WHERE tutor_id = '$tutor_id' AND status = 'active' ORDER BY date DESC";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $video_id = $row['content_id'];
                            $video_title = $row['title'];
                            $video_description = $row['description'];
                            $video_url = $row['video'];
                            $thumbnail_url = $row['thumb'];







                            

                            // Display the video information with a link to play the video
                            echo '<div class="box">';
                            echo '<div class="flex">';
                            echo '<div>';
                            if ($row['status'] == 'active') {
                                echo '<i class="fas fa-dot-circle" style="color:limegreen"></i>';
                            } else {
                                echo '<i class="fas fa-dot-circle" style="color:red"></i>';
                            }
                            echo '<span style="margin-left: .5rem;">' . $row['status'] . '</span>';
                            echo '</div>';
                            echo '<div class="menu">';
                            echo '<i class="fas fa-ellipsis-v"></i>';
                            echo '<ul>';
                            echo '<form action="" method="POST">';
                            echo '<input type="hidden" name="video_id" value="' . $video_id . '">';
                            echo '<li><button type="submit" name="delete_video" onclick="return confirm(\'Do you really want to delete this video?\');" style="background: transparent;border:none;cursor:pointer;color:tomato;"><i class="fas fa-trash"></i>Delete</button></li>';
                            echo '</form>';
                            echo '</ul>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="thumb">';
                            echo '<img src="image/' . $thumbnail_url . '" alt="">';
                            echo '</div>';
                            echo '<h3 class="title">' . $video_title . '</h3>';
                            echo '<p class="description">' . $video_description . '</p>';
                            echo '<a href="admContent.php?video_id=' . $video_id . '" class="btn">View Content</a>';
                            echo '</div>';

                            
                        }
                    } else {
                        echo '<div class="box">No videos found.</div>';
                    }
                } else {
                    echo '<div class="box">Please log in as a tutor to view videos.</div>';
                }
            }

            // Call the function to display videos
            displayVideos();
            ?>
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
