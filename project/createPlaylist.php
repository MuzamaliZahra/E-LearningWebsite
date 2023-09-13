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
   
   
    

 

    $image = $_FILES['image']['name'];
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = uniqid().'.'. $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploads/'.$rename;

 
    $stmt = $conn->prepare("INSERT INTO `playlist` (playlist_id, tutor_id, title, description, thumb, status) 
    VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $playlist_id, $tutor_id, $title, $description, $rename, $status);

   



    if ($stmt->execute()) {
        
        move_uploaded_file($image_tmp_name, $image);
        $message[] = 'New playlist created!';
    } else {
        $message[] = 'Error creating playlist: ' . $conn->error;
    }
 
    $stmt->close();
    $conn->close();
 }
 
?>

<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    
       
        <link rel="stylesheet" href="style.css">
    
    <title>
      createPlaylist -EDUCATE
    </title>
    </head>
    
    
    <body>
    <header class="header">
    
        <section class="flex">
    
            <a href="" class="logo">Admin.</a>
    
            <form action="" method="POST" class="search-form">
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
   <a href="tutorDocument.php"><i class="fas fa-graduation-cap"></i><span>Documents</span></a>
   <a href="userComments.php"><i class="fas fa-comment"></i><span>comments</span></a>
   <a href="tutor.php"><i class="fa fa-clone"></i><span>Quizes</span></a>
   <a href="tutorlogout.php" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
</nav>
    
        
    
    
    
    </header>
    
    
    
    
    
    <section class="playlist-form">
        <h1 class="heading">Create Playlist</h1>
    
        <form action="" method="post" enctype="multipart/form-data">
            
            <p>Playlist Status</p>
            <select name="status" class="box" required >
                <option value="" selected disabled>select status</option>
                <option value="active">active</option>
                <option value="deactive">deactive</option>
            </select>
    
            <p>Playlist Title</p>
            <input type="text" name="title" maxlength="100" required placeholder="Enter playlist title"
             class="box">
    
            <p>playlist description </p>
          <textarea name="description" class="box" required placeholder="write description" maxlength="1000" 
          cols="30" rows="10"></textarea>
    
          <p>playlist thumbnail </p>
          <input type="file" name="image" accept="image/*" required class="box">
            
       
    
          <input type="submit" value="Create playlist" name="submit" class="btn">
          <div class="flex-btn">
             
             <a href="playlistDetails.php" class="option-btn">view playlist</a>
          </div>
        
        </form>
            
    
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