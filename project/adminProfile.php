<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   
    <link rel="stylesheet" href="style.css">

<title>
    AdminProfile- EDUCATE
</title>
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
            <span>proffession</span>
            <a href="tutor.php" class="btn">view profile</a>


            <div class="flex-btn">
                <a href="login.php" class="option-btn">login</a>
                <a href="registration.php" class="option-btn">register</a>
            </div>   


            <a href="" onclick="return confirm('logout from this website?');" class="delete-btn">logout</a>

            <h3>please login or register</h3>
          <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
        </div>

    </section>

    <h1 class="heading"></h1>

<nav class="navbar">
   <a href="Dashboard.php"><i class="fas fa-home"></i><span>home</span></a>
   <a href="adminPlaylis.php"><i class="fa-solid fa-bars-staggered"></i><span>playlists</span></a>
   <a href="adminContentUpload.php"><i class="fas fa-graduation-cap"></i><span>contents</span></a>
   <a href="userComments.php"><i class="fas fa-comment"></i><span>comments</span></a>
   <a href="" onclick="return confirm('logout from this website?');"><i class="fas fa-right-from-bracket"></i><span>logout</span></a>
</nav>

</header>






    <section class="tutor-profile">

    <h1 class="Profile Details"></h1>

    <div class="details">
        <div class="tutor">
            <img src="" alt="">
            <h3> Tutor Name</h3>
            <p>Tutor</p>
            <a href="updateProfile.php" class="inline-btn">Update Profile</a>
        </div>
    
    

        <div class="flex">
            <div class="box">
                    <p>3</p>
                    <P>Total Playlist</P>
                    <a href="adminPlaylis.php" class="btn"> View Playlist</a>
                
            </div>


            <div class="box">
                    <p>2</P>    
                    <P>Total Videos</P>
                    <a href="admContent.php" class="btn">View Contents</a>
            </div>

            <div class="box">
                    <p>5</P>
                    <P>Total Likes</P>
                    <a href="admContent.php" class="btn">View Contents</a>
            </div>


            <div class="box">
                    <p>2</p>
                    <P>Total Comments</P>
                    <a href="admContent.php" class="btn">View Contents</a>
            </div>

        </div>
    
    </div>

</section>




<script src="script.js"></script>
</body>

</html>