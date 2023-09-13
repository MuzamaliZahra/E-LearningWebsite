<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educate";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to prevent SQL injection
function prepareData($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Check if a tutor is logged in
if (isset($_COOKIE['tutor_id'])) {
    $tutor_id = prepareData($_COOKIE['tutor_id']);

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
    $image = "default-profile-image.jpg"; 
    $name = "Guest"; 
    $profession = "";
}

// Function to download PDF file
function downloadFile($doc_id) {
    global $conn;
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT filename, filedata FROM document WHERE doc_id = ?");
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $stmt->store_result();

    // Check if a document was found
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($filename, $filedata);
        $stmt->fetch();

        // Set the appropriate headers for the file download
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename='$filename'");

        // Output the file data
        echo $filedata;
        exit(); 
    } else {
        echo "Document not found.";
    }
}
// Check if a document ID is provided in the URL and initiate file download
if (isset($_GET['doc_id'])) {
    $doc_id = prepareData($_GET['doc_id']);
    downloadFile($doc_id);
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
                <h3><?php echo $profession; ?></h3>
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
            <a href="" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
        </nav>
</header>

<section class="contents">
    <h1 class="heading">Your Documents</h1>
    <div class="box-container">

<div class="box" style="text-align: center;">
    <h3 class="title" style="margin-bottom: .5rem;">create new documents</h3>
    <a href="documentUpload.php" class="btn">add documents</a>
</div>
      
      <?php
        // Fetch the PDF documents from the database
        $sql = "SELECT doc_id, filename FROM document";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $doc_id = $row['doc_id'];
                $filename = $row['filename'];

                // Display the PDF document link inside a box.
                echo "<div class='box' style='text-align: center;'>";
                echo "<img src='image/pdf.png'>";
                echo "<a href='?doc_id=$doc_id' target='_blank'>$filename</a><br>";
                echo "</div>";
            }
        } else {
            echo "No PDF documents found.";
        }

        $conn->close();
        ?>
    </div>
</section>

<footer class="footer">
 
    <P>
    Emil  - EDUCATE@gmail.com        &nbsp &nbsp &nbsp &nbsp &nbsp
    PHONE   -  077456270        &nbsp &nbsp &nbsp &nbsp &nbsp
    ADDRESS  -2nd, colombo
    
    </P>
 
 
 </footer>

<script src="script.js"></script>

</body>
</html>
