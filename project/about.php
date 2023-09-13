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

// is "student_id" cookie is set
  if (isset($_COOKIE['student_id'])) {
    $student_id = $_COOKIE['student_id'];

// Fetch user data
    $stmt = $conn->prepare("SELECT * FROM `student` WHERE student_id = ? LIMIT 1");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image = "image/" .$row['image']; //"image/- stored folder"
        $name = $row['name']; // 
    } else {
// handle who enters without login
        $image = "image.jpg";
        $name = "Guest";
    }
  } else {
      $student_id = '';
      $image = "default-profile-image.jpg"; 
      $name = "Guest"; 
  }
?>

<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
    body{
      margin:0; 
    }    

    img{
      max-width: 100%;
      height:auto;
    }

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

    @media(min-width:640px){/*define different styles for different screen sizes.*/
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
<section class="flex" >
            <a href="#" class="logo">LearnWiz.</a>
 
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
                    <img src="<?php echo $image; ?>" alt="student profile">
                    <span>student</span>
                    <h3><?php echo $name; ?></h3>
                    <a href="studentProfile.php" class="btn">view profile</a>
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
          <a href="HOME.php"><i class="fas fa-home"></i><span>Home</span></a>
          <a href="about.php"><i class="fas fa-question"></i><span>About us</span></a>
          <a href="cources.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
          <a href="tutor.php"><i class="fas fa-chalkboard-user"></i><span>Tutors</span></a>
          <a href="studentDocument.php"><i class="	fa fa-edit"></i><span>Documents</span></a>
          </nav>
  </header>

<section class="about">
        <div class="row">
            <div class="image"><img src="image\about.jpg" alt="about image"></div>
                <div class="content">
                   <h3>WHY US?</h3>
                   <P>We are here to help you all <br>
                   with our talented tutors and <br>
                   there understandable video lessons<br> 
                   </P>
                   <a href="cources.php" class="inline-btn">OUR COURSES</a>
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
                    alot to improve my skills thank you so much</p><div class="user">
                        <img src="profile\1.jpeg" alt="">
                    <div>
                          <h3>Yamuna</h3>
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
                    <p>It was wonderful experience to do course in LearnWiz.</br>
                    Had great mentor session. Well coordinated program coordinators.p>
                    <div class="user">
                        <img src="profile\2.jpeg" alt="">
                    <div>
                            <h3>Nadhiya</h3>
                            <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>   
                    </div>    
            </div>

          <div class="box">
                    <p>Joining Great Learning for upskilling has been a truly transformative journey. </br> As a entrepreneur 
                    I initially had zero knowledge of coding. 
                    </p>
                    <div class="user">
                        <img src="profile\3.jpeg" alt="">
                    <div>
                            <h3>kaveri</h3>
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
                    <p>Great experienceall tools and techniques covered in the course</p>
                    <div class="user">
                        <img src="profile\4.jpeg" alt="">
                        <div>
                            <h3>Nivin</h3>
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

        
        
<section class="about">
        <h1 class="heading"> CONTACT US </h1>

        <div class="box-container">
                <div class="box">
                    <i class="fas fa-phone"></i>
                    <div>
                    <h3>PHONE NUMBER</h3>
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

                <div class="box">
                    <i class="fa fa-facebook"></i>
                    <div>
                    <h3>FB PAGE</h3>
                    <span>LearnWiz. </span>
                    <span>https://www.facebook.com/</span>
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