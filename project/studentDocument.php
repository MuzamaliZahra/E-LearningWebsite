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

// Function to download file
function downloadFile($doc_id) {
    global $conn;
    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT filename, filedata FROM document WHERE doc_id = ?");
    $stmt->bind_param("i", $doc_id);
    $stmt->execute();
    $stmt->store_result();

    // Check if a document found
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($filename, $filedata);
        $stmt->fetch();

        // Set the  headers for the file download
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename='$filename'");

        // Output the file data
        echo $filedata;
        exit(); 
    } else {
        echo "Document not found.";
    }
}

function prepareData($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}
// Check if a document ID is provided in download
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
    <title>LearnWiz.</title>
</head>

<body>   
<header class="header">
        <section class="flex" >
                    <a href="" class="logo">LearnWiz..</a>
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
                            <a href="studentProfile.php" class="btn">view profile</a>
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
</header


<section class="courses">
    <section class="courses">  
<div class="box-container">
   

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
</section>'

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