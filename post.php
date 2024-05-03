<?php
// start or resume logedin and use the data from login.
    session_start();
    if(!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
      header("Location: login.php");
      exit;  
    }


    if (isset($_GET['type'])) {
      $type = $_GET['type'];
      if (strcmp($type,'logout')==0){ // if user wants to logout remove all saved and destroy session.
        session_destroy();
        header("Location: login.php");
        exit();
      }
    }
// get all required info from user
  if ($_SERVER["REQUEST_METHOD"] == "POST") {

     $community_group = $_POST['community_group'];
     $event_title = $_POST['event_title'];
     $event_date = $_POST['event_date'];
     $event_time= $_POST['event_time'];
     $event_type = $_POST['event_type'];
     $image_name = $_POST['image_name'];
     $event_description = $_POST['event_description'];
     $var="files/images/events/";
     include 'serverlogin.php';

     // Check if the form is submitted
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
         
         $eventDateTime = date('Y-m-d H:i:s', strtotime($event_date . ' ' . $event_time));// Format event date and time
         
         // Create database connection
         $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
     
         // Check connection
         if ($conn->connect_error) {
             die("Connection failed: " . $conn->connect_error);
         }
    
        
        //get the correct EventTypeID from table.
         $sql="SELECT EventTypeID FROM EventTypes WHERE TypeName='$event_type' ;";
         $result = $conn->query($sql);
         $row = $result->fetch_assoc();
         $eventTypeID = $row["EventTypeID"]; 

         // get the correct GroupID from table.
         $sql2="SELECT GroupID FROM Groups where GroupName='$community_group';";
         $result2 = $conn->query($sql2) ;        
         $row2 = $result2->fetch_assoc();
         $groupID = $row2["GroupID"]; 

         $submitDate = date('Y-m-d H:i:s'); 
         $var=$var."".$image_name;

         // once we have all the neccesary details Bind the sql query.
         $query = $conn->prepare("INSERT INTO Events (EventTypeID, GroupID, EventDate, SubmitDate, EventTitle, EventImage, EventDesc) VALUES (?, ?, ?, ?, ?, ?, ?)");
         $query->bind_param("iisssss", $eventTypeID, $groupID, $eventDateTime, $submitDate, $event_title, $var , $event_description);
     

         // execute the query
         if ($query->execute()) {
             echo "New record created successfully";
         } 
     
         // Close statement and database connection
         $conn->close();
      }
     }

  ?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>What's Happening</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:wght@400;500&family=Inter:wght@400;500&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS Files -->
  <link href="assets/css/variables.css" rel="stylesheet">
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: ZenBlog
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/zenblog-bootstrap-blog-template/
  * Author: BootstrapMade.com
  * License: https:///bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.php" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1>What's Happening</h1><!-- Changes made to nav bar (title,scroll down menu)-->
      </a>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li class="dropdown"><a href="events.php?type=All"><span>Events</span> <i class="bi bi-chevron-down dropdown-indicator"></i></a>
          <ul>
              <li><a id="all" href="events.php?type=All">All Events</a></li>
              <li><a id="music" href="events.php?type=Music">Music</a></li>
              <li><a id="artCulture" href="events.php?type=Art">Art+Culture</a></li>
              <li><a id="sports" href="events.php?type=Sports">Sports</a></li>
              <li><a id="food" href="events.php?type=Food">Food</a></li>
              <li><a id="funds" href="events.php?type=Fund Raiser">Fund Raiser</a></li>
            </ul>
          </li>
          <li><a href="groups.php">Community Groups</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="post.php">Post Event</a></li>
          <?php 
                if((isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                  echo "<li class=\"dropdown\"><a href=\"#\"><span>Login</span> <i class=\"bi bi-chevron-down dropdown-indicator\"></i></a>";
                  echo "<ul>";
                  echo  "<li><a href=\"#\">Login</a></li>";
                  echo "<li><a href=\"login.php?type=logout\">Logout</a></li>" ;
                  echo "</ul>";
                  echo "</li>";
                }else{
                  echo "<li class=\"dropdown\"><a href=\"login.php\"><span>Login</span> <i class=\"bi bi-chevron-down dropdown-indicator\"></i></a>";
                  echo "<ul>";
                  echo  "<li><a href=\"login.php\">Login</a></li>";
                  echo "<li><a href=\"login.php?type=logout\">Logout</a></li>" ;
                  echo "</ul>";
                  echo "</li>";
                }
          ?> 
        </ul>
      </nav><!-- .navbar -->


      <div class="position-relative">
        <a href="#" class="mx-2"><span class="bi-facebook"></span></a>
        <a href="#" class="mx-2"><span class="bi-twitter"></span></a>
        <a href="#" class="mx-2"><span class="bi-instagram"></span></a>

        <a href="#" class="mx-2 js-search-open"><span class="bi-search"></span></a>
        <i class="bi bi-list mobile-nav-toggle"></i>

        <!-- ======= Search Form ======= -->
        <div class="search-form-wrap js-search-form-wrap">
          <form action="search-result.html" class="search-form">
            <span class="icon bi-search"></span>
            <input type="text" placeholder="Search" class="form-control">
            <button class="btn js-search-close"><span class="bi-x"></span></button>
          </form>
        </div><!-- End Search Form -->

      </div>

    </div>

  </header><!-- End Header -->

  <main id="main">
    <section id="addEvent" class="addEvent mb-5">
      <div class="container" data-aos="fade-up">
<!-- Removed Top Rows-->
        <div class="row">
          <div class="col-lg-12 text-center mb-5">
            <h1 class="page-title">Post New Event</h1> <!-- Changed from Contact Us to Login-->
            <?php // print the groupname 
            include 'serverlogin.php'; 
              $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
     
              // Check connection
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }
              
              if (isset($_SESSION['GroupID'])) {
                $groupId=$_SESSION['GroupID'];
                $sql="SELECT GroupName FROM Groups WHERE GroupId ='$groupId';"; //get the groupname based on the groupId saved
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $groupName = $row["GroupName"]; 
                echo "<br><br>";
                echo "<h5> $groupName </h5>";
              }
              
            ?>
          </div>
        </div>
        <div class="form mt-5">
          <form  action="post.php" method="post">
            <div class="form-group">
              <div class="row"  style="margin:10px">
                <input type="text" class="form-control" name="community_group" id="community_group" placeholder="Your Community Group" required>
              </div> 
              <div class="row"  style="margin:10px">
                <input type="text" class="form-control" name="event_title" id="event_title" placeholder="Your Event Title" required>   
              </div>   
              <div class="row"  style="margin:10px">  
                <input type="text" class="form-control" name="event_date" id="event_date" placeholder="Your Event Dates [Format: year-month-day]" required>
              </div>
              <div class="row"  style="margin:10px">
                <input type="text" class="form-control" name="event_time" id="event_time" placeholder="Your Event Time [Format: hh:mm AM/PM]" required>
              </div>
              <div class="row"  style="margin:10px">
                <input type="text" class="form-control" name="event_type" id="event_type" placeholder="Your Event Type" required>
              </div>  
              <div class="row"  style="margin:10px">
                <input type="text" class="form-control" name="image_name" id="image_name" placeholder="Image Name" required>
              </div> 
              <div class="row"  style="margin:10px">
                <textarea class="form-control" name="event_description" rows="2" placeholder="The Event Description" required></textarea>
              </div>
            <div class="text-center"><br>
            <button class="btn btn-primary" type="submit">Submit</button>
            </div><!-- Submit button changed.. -->   
          </form>
        </div><!-- End Contact Form -->

      </div>
    </section>

  </main><!-- End #main -->

   <!-- ======= Footer ======= -->
   <footer id="footer" class="footer">

<div class="footer-content">
  <div class="container">

    <div class="row g-5">
      <div class="col-lg-4">
        <h3 class="footer-heading">About What's Happening
        </h3>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam ab, perspiciatis beatae autem deleniti voluptate nulla a dolores, exercitationem eveniet libero laudantium recusandae officiis qui aliquid blanditiis omnis quae. Explicabo?</p>
        <p><a href="about.php" class="footer-link-more">Learn More</a></p>
      </div>
      <div class="col-6 col-lg-2">
        <h3 class="footer-heading">Navigation</h3>
        <ul class="footer-links list-unstyled">
          <!--Changes made to footer  -->
          <li><a href="index.php"><i class="bi bi-chevron-right"></i> Home</a></li>
          <li><a href="events.php?type=All"><i class="bi bi-chevron-right"></i> Events</a></li>
          <li><a href="groups.php"><i class="bi bi-chevron-right"></i> Community Groups</a></li>
          <li><a href="about.php"><i class="bi bi-chevron-right"></i> About</a></li>
          <li><a href="post.php"><i class="bi bi-chevron-right"></i> Post Event</a></li>
          <li><a href="login.php"><i class="bi bi-chevron-right"></i> Login</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h3 class="footer-heading">Categories</h3>
        <ul class="footer-links list-unstyled">
        <ul class="footer-links list-unstyled">
           <li><a href="events.php?type=All"><i class="bi bi-chevron-right"></i> All Events</a></li>
           <li><a href="events.php?type=Music"><i class="bi bi-chevron-right"></i> Music</a></li>
           <li><a href="events.php?type=Art"><i class="bi bi-chevron-right"></i> Art+Culture</a></li>
           <li><a href="events.php?type=Sports"><i class="bi bi-chevron-right"></i> Sports</a></li>
           <li><a href="events.php?type=Food"><i class="bi bi-chevron-right"></i> Food</a></li>
           <li><a href="events.php?type=Fund Raiser"><i class="bi bi-chevron-right"></i> Fund Raiser</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="footer-legal">
  <div class="container">

    <div class="row justify-content-between">
      <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
        <div class="copyright">
          © Copyright <strong><span>ZenBlog</span></strong>. All Rights Reserved
        </div>

        <div class="credits">
          <!-- All the links in the footer should remain intact. -->
          <!-- You can delete the links only if you purchased the pro version. -->
          <!-- Licensing information: https://bootstrapmade.com/license/ -->
          <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/herobiz-bootstrap-business-template/ -->
          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>

      </div>

      <div class="col-md-6">
        <div class="social-links mb-3 mb-lg-0 text-center text-md-end">
          <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
          <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
          <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
          <a href="#" class="google-plus"><i class="bi bi-skype"></i></a>
          <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
        </div>

      </div>

    </div>

  </div>
</div>

</footer>
  <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>