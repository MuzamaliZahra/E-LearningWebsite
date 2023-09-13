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

?>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
<style>


    .gallery{
      margin:0 0.65rem;
    }

    .gallery-item{
      height: auto;
      margin: 0.5rem;
    }

    .gallery-item img{
      width: 100%;
      height: 100%;
      object-fit: cover;

    }

    @media(min-width:640px){
      .gallery{
        display: grid;
        grid-template-columns: repeat(2 1fr);
        grid-auto-flow: row dense;
      }

      .gallery-item{
        margin: 0.7rem;
      }

      .gallery-item:first-child{
        grid-row: span 1; 
      }
      .gallery-item:nth-child(2){
        grid-column: 2/ 3;
        grid-row: span 2;
      }

      .gallery-item:nth-child(6),
      .gallery-item:nth-child(8){
        grid-row: span 2;
      }
        }

    @media (min-width: 960px){
      .gallery{
        grid-template-columns: repeat(5, 1fr);
        grid-template-rows: repeat(3, auto-flow);

      }

      .gallery-item:first-child,
      .gallery-item:nth-child(7),
      .gallery-item:nth-child(8){
          grid-column: span 2;
          grid-row : span 1;
      }

      .gallery-item:nth-child(2){
          grid-column: span 2;
          grid-row: span 2; 

      }

    }

</style>
    <title>LearnWiz</title>
</head>
<body>
    <header class="header">
        <section class="flex">
           <a href="" class="logo">LearnWiz.</a>
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
              <img src="image\649a7320ad908.jpg"  alt="Guest">
              
              <h3>Guest</h3>
              
              <a href="log_reg.php" class="btn">view profile</a>
              <div class="flex-btn">
                 <a href="log_reg.php" class="option-btn">login</a>
                 <a href="log_reg.php" class="option-btn">register</a>
              </div>
              <a href="studentLogout.php" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>
           </div>       
</section>

        <h1 class="heading"></h1>
            <nav class="navbar">
         
         <div class="flex-btn" style="padding-top: .5rem;"></div>

         <a href="guestHome.php"><i class="fas fa-home"></i><span>Home</span></a>
         <a href="log_reg.php"><i class="fas fa-question"></i><span>About us</span></a>
         <a href="log_reg.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
         <a href="log_reg.php"><i class="fas fa-chalkboard-user"></i><span>Tutors</span></a>
         <a href="log_reg.php"><i class="fa fa-edit"></i><span>Documents</span></a>
         </nav>
</header>
<!-- quick select section starts  -->

<section class="quick-select">

   <h1 class="heading">quick options</h1>

   <div class="box-container">
  
    <div class="box" style="text-align: center;">
        <h3 class="title">please login or register</h3>
        <div class="flex-btn" style="padding-top: .5rem;">
            <a href="log_reg.php" class="option-btn">login as tutor</a>
            <a href="log_reg.php" class="option-btn">register as tutor</a> 
        </div>
            <div class="box" style="text-align: center;">
        <h3 class="title">please login or register</h3>
    
            <a href="log_reg.php" class="option-btn">login as student</a>
            <a href="log_reg.php" class="option-btn">register as student</a>
        </div>
    </div>
 
      <div class="box">
        
         <div class="flex">
         <img src="image\home.jpg" alt="check" width="240" height="260">
         </div>
      </div>

      <div class="box">
         <h3 class="title">popular topics</h3>
         <div class="flex">
            <a href="#"><i class="fab fa-html5"></i><span>HTML</span></a>
            <a href="#"><i class="fab fa-css3"></i><span>CSS</span></a>
            <a href="#"><i class="fab fa-js"></i><span>javascript</span></a>
            <a href="#"><i class="fab fa-react"></i><span>react</span></a>
            <a href="#"><i class="fab fa-php"></i><span>PHP</span></a>
            <a href="#"><i class="fab fa-bootstrap"></i><span>bootstrap</span></a>
         </div>
      </div>

      <div class="box">
         <h3 class="title">BUY A BOOK  <i class="fa fa-book"></i></h3>
         <div class="flex">
         <p>Just clicke here and donate</br>  us to buy books for poor kids</p>
         <a href="#" class="inline-btn">DONATE</a>
            
         </div>
      </div>
   </div>
   </div> 
</section>

<!-- courses section starts  -->
<section class="courses">
   <section class="courses">
    <h1 class="heading">latest courses</h1>
    <div class="box-container">
    <?php
    $select_courses = $conn->prepare("SELECT * FROM `playlist` WHERE status = ? ORDER BY date DESC LIMIT 6");
    $select_courses->bind_param("s", $status);
    $status = "active";
    $select_courses->execute();
    $result = $select_courses->get_result();
    if ($result->num_rows > 0) {
        while ($fetch_course = $result->fetch_assoc()) {
            $course_id = $fetch_course['playlist_id'];

            $select_tutor = $conn->prepare("SELECT * FROM `tutor` WHERE tutor_id = ?");
            $select_tutor->bind_param("i", $tutor_id);
            $tutor_id = $fetch_course['tutor_id'];
            $select_tutor->execute();
            $result_tutor = $select_tutor->get_result();
            $fetch_tutor = $result_tutor->fetch_assoc();
            ?>
            <div class="box">
                <div class="tutor">
                    <img src="image/<?php echo $fetch_tutor['image']; ?>" alt="">
                    <div>
                        <h3><?php echo $fetch_tutor['name']; ?></h3>
                        <span><?php echo $fetch_course['date']; ?></span>
                    </div>
                </div>
                <img src="profile\thumb.png"<?php echo $fetch_course['thumb']; ?> class="thumb" alt="thumbnail">
                <h3 class="title"><?php echo $fetch_course['title']; ?></h3>
                <a href="log_reg.php?get_id=<?php echo $course_id; ?>" class="inline-btn">view playlist</a>
            </div>
            <?php
        }
    } else {
        echo '<p class="empty">no courses added yet!</p>';
    }
    ?>
    </div>
</section>
         
<h1 class="heading"> ABOUT US</h1>
        <section class="about">
            <div class="row">
                <div class="image"><img src="image\about.jpg" alt="about image"></div>
                <div class="content">
                   <h3>WHY US?</h3>
                   <P>We are here to help you all <br>
                   with our talented tutors and <br>
                   there understandable video lessons<br> 
                   </P>
                   <a href="log_reg.php" class="inline-btn">OUR COURSES</a>
                </div>
           </div>   
           <div class="box-container">
                <div class="box">
                    <i class="fas fa-graduation-cap"></i>
                    <div>
                    <h3>+10k</h3>
                    <span>Online Courses</span>
                    </div>
                </div>   
                <div class="box">
                    <i class="fas fa-chalkboard-user"></i>
                    <div>
                    <h3>+40k</h3>
                    <span>Brilliant Students</span>
                    </div>
                </div>  
                <div class="box">
                    <i class="fas fa-user-graduate"></i>
                <div>
                    <h3>+2k</h3>
                    <span>Expert Tutors</span>
                </div>    
                </div>    
        
                <div class="box">
                    <i class="fas fa-briefcase"></i>
                   <div>
                    <h3>100%</h3>
                    <span>Job Placement</span>
                    </div>
                </div>   
       
             </div>
        </section>

        <section class="reviews">

            <h1 class="heading"> STUDENT REVIEWS</h1>
            <div class="box-container">

                <div class="box">
                    <p>Best patform to learn IT related cources</br>lecturesrs helped me 
                    alot to improve my skills thank you so much</p>
                    <div class="user">
                        <img src="image\profile.jpeg" alt="">
                        <div>
                            <h3>student name</h3>
                            <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>   
                    </div>    
                </div>


                
                <div class="box">
                <p>Best patform to learn IT related cources</br>lecturesrs helped me 
                    alot to improve my skills thank you so much</p>
                    <div class="user">
                        <img src="image\profile.jpeg" alt="">
                        <div>
                            <h3>student name</h3>
                            <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>   
                    </div>    
                </div>



                
                <div class="box">
                <p>Best patform to learn IT related cources</br>lecturesrs helped me 
                    alot to improve my skills thank you so much</p> <div class="user">
                        <img src="image\profile.jpeg" alt="">
                        <div>
                            <h3>student name</h3>
                            <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            
                        </div>
                    </div>   
                    </div>    
                </div>


                
                <div class="box">
                <p>Best patform to learn IT related cources</br>lecturesrs helped me 
                    alot to improve my skills thank you so much</p><div class="user">
                        <img src="image\profile.jpeg" alt="">
                        <div>
                            <h3>student name</h3>
                            <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>   
                    </div>    
                </div>
                

            </div>



        </section>

        <h1 class="heading"> CONTACT US </h1>
        <section class="about">


        <div class="box-container">
                <div class="box">
                    <i class="fas fa-phone"></i>
                    <div>
                    <h3>Phone Number</h3>
                    <span>077456270 </span>
                    </div>
                </div>    

                <div class="box">
                    <i class="fas fa-envelope"></i>
                    <div>
                    <h3>EMAIL</h3>
                    <span>EDUCATE@gmail.com </span>
                    </div>
                </div>    

                <div class="box">
                    <i class="fas fa-map-marker"></i>
                    <div>
                    <h3>ADDRESS</h3>
                    <span>COLOMBO 9 </span>
                    </div>
                </div>  
        
        </div>
        </section>
        
        <section class="about">
                <h1 class="heading"> OUR GALLERY</h1>

                     

  <div class="gallery">
   <div class="gallery-item">
     <img src="gallery\1.jpeg" alt="1">

   </div>


    <div class="gallery-item">
     <img src="gallery\16.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\3.jpeg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\4.jpeg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\5.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\6.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\7.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\8.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\9.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\10.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\11.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\12.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\13.jpg" alt="1">

   </div>
    <div class="gallery-item">
     <img src="gallery\14.jpg" alt="1">

   </div>
   <div class="gallery-item">
     <img src="gallery\15.jpg" alt="1">

   </div>

  </div>


        </section>













  
</body>
</html>


<footer class="footer">

    <P >
    Emil  - EDUCATE@gmail.com        &nbsp &nbsp &nbsp &nbsp &nbsp
    PHONE   -  077456270        &nbsp &nbsp &nbsp &nbsp &nbsp
    ADDRESS  -2nd, colombo &nbsp &nbsp &nbsp &nbsp &nbsp
    </P>


</footer>


<script src="script.js"></script>

</body>
</html>