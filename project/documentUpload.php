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

// Check if a file was uploaded
if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pdfFile']['tmp_name'];
    $fileName = $_FILES['pdfFile']['name'];

    // Read the file content
    $fileData = file_get_contents($fileTmpPath);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO document (filename, filedata,tutor_id) VALUES (?, ?,?)");
    $stmt->bind_param("sss", $fileName, $fileData,$tutor_id);

    // Execute the statement
    if ($stmt->execute()) {
        
    } else {
          $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    ;
    if (isset($_FILES['pdfFile'])) {
        echo $_FILES['pdfFile']['error'];
    } else {
        ;
    }
}

// Close the connection
$conn->close();
?>

<html>
<head>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   
    <link rel="stylesheet" href="style.css">

    <title>LearnWiz</title>
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
        <img src="<?php echo $image; ?>" alt="tutor">
        <h3><?php echo $name; ?></h3>
        <h3><?php echo $profession; ?></h3>
        <a href="tutors_Profile.php"class="btn">view profile</a>


        <div class="flex-btn">
            <a href="log_reg.php" class="option-btn">login</a>
            <a href="log_reg.php" class="option-btn">register</a>
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
    <h1 class="heading">Upload Content</h1>
    <form action="" method="post" enctype="multipart/form-data">
    <P>File Name</P>
            <input type="text" name="name" placeholder="enter file name" maxlength="20" required class="box">
    <p>Select file</p>
        <input type="file" name="pdfFile" accept="pdfFile/*" class="box" />
        <input type="submit" value="Upload" name="submit" class="btn" />
    </form>
</section>    
<script src="script.js"></script>
</body>

<footer class="footer">
    <P >
    Emil  - EDUCATE@gmail.com   &nbsp &nbsp 
    PHONE   -  077456270        &nbsp &nbsp 
    ADDRESS  -2nd, colombo
    </P>
</footer>

</html>