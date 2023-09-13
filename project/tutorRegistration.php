<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "educate";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $profession = $_POST['profession'];
   $email = $_POST['email'];
   $password = $_POST['pass'];
   $confirmPassword = $_POST['cpass'];
   $image = $_FILES['image']['name'];

   // Perform basic input validation
   if (empty($name) || empty($profession) ||  empty($email) || empty($password) || empty($confirmPassword) || empty($image)) {
      $message = "Please fill in all the fields.";
   } elseif ($password !== $confirmPassword) {
      $message = "Passwords do not match.";
   } else {
      $select_student = $conn->prepare("SELECT * FROM `tutor` WHERE email = ?");
      $select_student->bind_param("s", $email);
      $select_student->execute();
      $select_student_result = $select_student->get_result();

      if ($select_student_result->num_rows > 0) {
         $message = "Email already taken!";
      } else {
         $image_ext = pathinfo($image, PATHINFO_EXTENSION);
         $rename = uniqid().'.'.$image_ext;
         $image_folder = 'image/'.$rename;

         $insert_student = $conn->prepare("INSERT INTO `tutor`(name, profession, email, pass, image) VALUES(?,?,?,?,?)");
         $insert_student->bind_param("sssss", $name, $profession, $email, $password, $rename);
         $insert_student->execute();

         move_uploaded_file($_FILES['image']['tmp_name'], $image_folder);

         $verify_student = $conn->prepare("SELECT * FROM `tutor` WHERE email = ? AND pass = ? LIMIT 1");
         $verify_student->bind_param("ss", $email, $password);
         $verify_student->execute();
         $verify_student_result = $verify_student->get_result();

         if ($verify_student_result->num_rows > 0) {
            $row = $verify_student_result->fetch_assoc();
            setcookie('tutor_id', $row['id'], time() + 60*60*24*30, '/');
            header('location: tutorLogin.php');
            exit();
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

        

           
           
   
   



<section class="form-container">
    <form class="register" action="" method="POST" enctype="multipart/form-data" >
    <h3>Create Admin Account </h3>
    
    
  



    <div class="flex">
        <div class="col">
            <P>Your Name</P>
            <input type="text" name="name" placeholder="enter Your name" maxlength="20" required class="box">
            
            <P>Your Email</P>
            <input type="text" name="email" placeholder="enter Your email" maxlength="20" required class="box">
        </div>


        


        <div class="col">
            <p>your password <span>*</span></p>
            <input type="password" name="pass" placeholder="enter your password" maxlength="20" required class="box">
           
            <p>confirm password <span>*</span></p>
            <input type="password" name="cpass" placeholder="confirm your password" maxlength="20" required class="box">
        </div>
    
    </div>

    <p>your profession</p>
            <select name="profession" class="box" required>
               <option value="" disabled selected>-- select your profession</option>
               <option value="developer">Developer</option>
               <option value="desginer">Desginer</option>
               <option value="musician">Data scientist</option>
               <option value="teacher">Teacher</option>
               <option value="engineer">Software engineer</option>
               <option value="lawyer">Computer network</option>
               <option value="journalist">Programmer</option>
               <option value="photographer">Database Administrator</option>
            </select>




    <div class="col">

    </div>

    <p>select pic <span>*</span></p>
    <input type="file" name="image" accept="image/*" required class="box">

    <p class="link">already have an account? <a href="tutorLogin.php">login now</a></p>
    <input type="submit" name="submit" value="register now" class="btn">
 </form>
</section>


 <script src="script.js"></script>
    </body>

</html>