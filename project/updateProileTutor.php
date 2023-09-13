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
        $image = "image/" . $row['image']; // Assuming the column name for the profile image is 'image'
        $name = $row['name']; // Assuming the column name for the user's name is 'name'
        $profession = $row['profession'];

    } else {
        // User data not found, handle accordingly
        $image = "image.jpg"; // Set a default profile image
        $name = "Guest"; // Set a default name for guest users
    }
} else {
    $tutor_id = '';
    $image = "default-profile-image.jpg"; // Set a default profile image
    $name = "Guest"; // Set a default name for guest users
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['name'])) {
        $name = $_POST['name'];
        $update_name = $conn->prepare("UPDATE `tutor` SET name = ? WHERE tutor_id = ?");
        $update_name->bind_param("si", $name, $tutor_id);
        $update_name->execute();
        $message[] = "Name updated successfully!";
    }

    
 

    if (!empty($_POST['profession'])) {
        $profession = $_POST['profession'];
        $update_profession = $conn->prepare("UPDATE `tutor` SET profession = ? WHERE tutor_id = ?");
        $update_profession->bind_param("si", $profession, $tutor_id);
        $update_profession->execute();
        $message[] = "Profession updated successfully!";
    }

    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        $select_email = $conn->prepare("SELECT email FROM `tutor` WHERE tutor_id = ? AND email = ?");
        $select_email->bind_param("is", $tutor_id, $email);
        $select_email->execute();
        $select_email->store_result();
        if ($select_email->num_rows > 0) {
            $message[] = 'Email already taken!';
        } else {
            $update_email = $conn->prepare("UPDATE `tutor` SET email = ? WHERE tutor_id = ?");
            $update_email->bind_param("si", $email, $tutor_id);
            $update_email->execute();
            $message[] = 'Email updated successfully!';
        }
    }

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = ''; // Replace with appropriate code to generate a unique filename
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_files/' . $rename;

        if ($image_size > 2000000) {
            $message[] = 'Image size too large!';
        } else {
            $update_image = $conn->prepare("UPDATE `tutor` SET `image` = ? WHERE tutor_id = ?");
            $update_image->bind_param("si", $rename, $tutor_id);
            $update_image->execute();
            move_uploaded_file($image_tmp_name, $image_folder);
            if ($prev_image != '' && $prev_image != $rename) {
                unlink('../uploaded_files/' . $prev_image);
            }
            $message[] = 'Image updated successfully!';
        }
    }


    $empty_password='';
    $prev_pass='';


    $old_pass =$_POST['old_pass'];
 
   $pass = $_POST['pass'];
   
   $cpass =$_POST['cpass'];
   

   if($old_pass != $empty_password){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_password){
            $update_pass = $conn->prepare("UPDATE `tutors` SET password = ? WHERE tutor_id = ?");
            $update_pass->execute([$cpass, $tutor_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
         }
      }
   }

}
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
            <a href="#" class="logo">Admin.</a>

            <form action="" method="post" class="search-form">
                <input type="text" name="search" placeholder="Search here" required maxlength="100">
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
                <a href="tutors_Profile.php" class="btn">View Profile</a>

                <div class="flex-btn">
                    <a href="log_reg.php" class="option-btn">Login</a>
                    <a href="log_reg.php" class="option-btn">Register</a>
                </div>

                <a href="tutorlogout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
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


    <section class="form-container">
        <form class="register" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <h3>Update Profile</h3>

            <div class="flex">
                <div class="col">
                    <p>Your Name</p>
                    <input type="text" name="name" placeholder=<?php echo $name; ?> maxlength="20" required
                        class="box">

                    <p>Your Email</p>
                    <input type="text" name="email" placeholder= "" maxlength="20" required
                        class="box">

                    <p>Select Profile Picture</p>
                    <input type="file" name="image" accept="image/*" required class="box">
                </div>

                <div class="col">
                    <p>Old Password</p>
                    <input type="password" name="old_pass" placeholder="Enter Your Old Password" maxlength="20"
                        required class="box">

                    <p>New Password</p>
                    <input type="password" name="pass" placeholder="Enter Your New Password" maxlength="20"
                        required class="box">

                    <p>Confirm Password</p>
                    <input type="password" name="cpass" placeholder="Confirm Your Password" maxlength="20"
                        required class="box">
                </div>
            </div>

            <div>
                <p>Your Profession</p>
                <select name="profession" class="box" required>
                    <option value="" disabled selected><?php echo $profession; ?></option>
                    <option value="developer">Developer</option>
                    <option value="designer">Designer</option>
                    <option value="musician">Musician</option>
                    <option value="biologist">Biologist</option>
                    <option value="teacher">Teacher</option>
                    <option value="engineer">Engineer</option>
                    <option value="lawyer">Lawyer</option>
                    <option value="accountant">Accountant</option>
                    <option value="doctor">Doctor</option>
                    <option value="journalist">Journalist</option>
                    <option value="photographer">Photographer</option>
                </select>
            </div>

            <input type="submit" name="submit" value="Update Profile" class="btn">
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

