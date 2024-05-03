<?php 
  session_start();
  if (isset($_GET['type'])) {
    $type = $_GET['type'];
    
    if(strcmp($type,'logout')==0){ // if user wants to logout remove all saved and destroy session.
      session_destroy();
      header("Location: login.php");
      exit();
    }
  }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>ZenBlog Bootstrap Template - Category</title>
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
        <h1>What's Happening</h1> <!-- Changes made to nav bar (title,scroll down menu)-->
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
    <section>
      <div class="container">
        <div class="row">
          <div class="col-md-9" data-aos="fade-up"> 
          <?php
          // Below is the mysql version of populating events.php
        
              // Include the server login file
              include 'serverlogin.php';
      
              
              // Create database connection
              $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);

              $sql=NULL;
              // Check connection
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }
              $type=NULL;
              $result;
              // Retrieve event data from the database and sort by date according to queryString
              if (isset($_GET['type'])) {
                $type = $_GET['type'];
                if(strcmp($type,"Art")==0){
                  $type="Art+Culture"; // as the format is different from other types using this helps to avoid bugs..
                }// depending on the queryString we need to format our sql query to get desired Result..
                if(strcmp($type,"All")==0){
                  $sql="SELECT Events.EventID, Events.EventTypeID, Events.GroupID, Events.EventDate, Events.EventTitle, Events.EventImage, Events.EventDesc, EventTypes.TypeName, Groups.GroupName, Groups.GroupImage
                  FROM Events
                  INNER JOIN  EventTypes ON Events.EventTypeID = EventTypes.EventTypeID
                  INNER JOIN whats_happening.Groups on Events.GroupID = whats_happening.Groups.GroupID
                  ORDER BY abs(timediff(EventDate, current_timestamp())) ASC;;"; 
                } else{
                  $sql="SELECT Events.EventID, Events.EventTypeID, Events.GroupID, Events.EventDate, Events.EventTitle, Events.EventImage, Events.EventDesc, EventTypes.TypeName, Groups.GroupName, Groups.GroupImage
                  FROM Events
                  INNER JOIN  EventTypes ON Events.EventTypeID = EventTypes.EventTypeID
                  INNER JOIN whats_happening.Groups on Events.GroupID = whats_happening.Groups.GroupID
                  WHERE TypeName in ('$type')
                  ORDER BY abs(timediff(EventDate, current_timestamp())) ASC;";
                }
                $result= $conn->query($sql);
                
                
              }
            
              
              echo "<div class=\category-title\" data-aos=\"fade-up\">";
              echo "<h3 class=\"category-title\">EVENT CATEGORIES: $type </h3>";
              echo "</div>";
              // Display event information
              if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) { // Till the end of the records print the heredoc..
                      // Format date and time
                    $eventDateTime = date_create($row["EventDate"]);
                    $formattedDateTime = date_format($eventDateTime, "d-M-y g:i A"); //date formatted according to instructions.
                    echo <<<DIVBLOCK
                    <div class="d-md-flex post-entry-2 half">
                    <a href="single-post.php?type={$row["EventID"]}" class="me-4 thumbnail">
                    <img src="{$row["EventImage"]}" alt="" class="img-fluid">
                    </a>
                    <div>
                    <div class="post-meta">
                    <span class="date">{$row["TypeName"]}</span> <span class="mx-1">&bullet;</span> <span>{$formattedDateTime}</span>
                    </div>
                    <h3><a href="single-post.php?type={$row["EventID"]}">{$row["EventTitle"]}</a></h3>
                    <!-- <p>{$row["EventDesc"]}</p>-->
                    <div class="d-flex align-items-center author">
                    <div class="photo"><img src="{$row["GroupImage"]}" alt="" class="img-fluid"></div>
                    <div class="name">
                    <h3 class="m-0 p-0">{$row["GroupName"]}</h3>
                    </div>
                    </div>
                    </div>
                    </div>
                    DIVBLOCK;
                  }
              } 
      
              // Close database connection
              $conn->close();
            ?>

            <div class="text-start py-4">
              <div class="custom-pagination">
                <a href="#" class="prev">Previous</a>
                <a href="#" class="active">1</a>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#">4</a>
                <a href="#">5</a>
                <a href="#" class="next">Next</a>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <!-- ======= Sidebar ======= -->
            <div class="aside-block">

              <ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="pills-popular-tab" data-bs-toggle="pill" data-bs-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular" aria-selected="true">UPCOMING</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="pills-trending-tab" data-bs-toggle="pill" data-bs-target="#pills-trending" type="button" role="tab" aria-controls="pills-trending" aria-selected="false">LATEST ADDED</button>
                </li>
              </ul>

              <div class="tab-content" id="pills-tabContent">
                <?php 
                  include 'serverlogin.php';

                  // Create database connection
                $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
              
                  // Check connection
                if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
                }
              
                  // Retrieve EventID from query string
              
                      // Retrieve event information from the database
                    $sql = "SELECT  Events.EventID, Events.EventDate, Events.EventTitle, EventTypes.TypeName, Groups.GroupName
                            FROM Events
                            INNER JOIN EventTypes ON Events.EventTypeID = EventTypes.EventTypeID
                            INNER JOIN whats_happening.Groups ON Events.GroupID = whats_happening.Groups.GroupID
                            ORDER BY EventDate ASC;";
                    $result = $conn->query($sql);
              
                      // Display event information
                    
                    
                    if ($result->num_rows > 0) {
                      echo "<div class=\"tab-pane fade show active\" id=\"pills-popular\" role=\"tabpanel\" aria-labelledby=\"pills-popular-tab\">";
                      while($row = $result->fetch_assoc()){
                        $eventDateTime = date_create($row["EventDate"]);
                        $formattedDate = date_format($eventDateTime, "d-M-y"); 
                        echo <<<DIVBLOCK
                          <div class="post-entry-1 border-bottom">
                            <div class="post-meta"><span class="date">{$row["TypeName"]}</span> <span class="mx-1">&bullet;</span> <span>{$formattedDate}</span></div>
                            <h2 class="mb-2"><a href="single-post.php?type={$row["EventID"]}">{$row["EventTitle"]}</a></h2>
                            <span class="author mb-3 d-block">{$row["GroupName"]}</span>
                          </div>
                        DIVBLOCK;
                      }
                      echo "</div>";
                    }

                    

                        // Retrieve event information from the database
                    $sql = "SELECT  Events.EventID, Events.EventDate, Events.EventTitle, EventTypes.TypeName, Groups.GroupName
                    FROM Events
                    INNER JOIN EventTypes ON Events.EventTypeID = EventTypes.EventTypeID
                    INNER JOIN whats_happening.Groups ON Events.GroupID = whats_happening.Groups.GroupID
                    ORDER BY EventDate DESC;";
                    $result = $conn->query($sql);
              
                      // Display event information
                    
                    if ($result->num_rows > 0) {
                      echo "<div class=\"tab-pane fade \" id=\"pills-trending\" role=\"tabpanel\" aria-labelledby=\"pills-trending-tab\">";
                      while($row = $result->fetch_assoc()){
                        $eventDateTime = date_create($row["EventDate"]);
                        $formattedDate = date_format($eventDateTime, "d-M-y"); 
                        echo <<<DIVBLOCK
                          <div class="post-entry-1 border-bottom">
                            <div class="post-meta"><span class="date">{$row["TypeName"]}</span> <span class="mx-1">&bullet;</span> <span>{$formattedDate}</span></div>
                            <h2 class="mb-2"><a href="single-post.php?type={$row["EventID"]}">{$row["EventTitle"]}</a></h2>
                            <span class="author mb-3 d-block">{$row["GroupName"]}</span>
                          </div>
                        DIVBLOCK;
                      }
                      echo "</div>";
                          // Format date and time
                    }
                  
                  // Close database connection
                  $conn->close();
              ?>                
<!-- Removed video under slidebar section..-->
            <div class="aside-block">
              <h3 class="aside-title">Events</h3> <!-- Changed from Categories to Events -->
              <ul class="aside-links list-unstyled">
                <li><a href="events.php?type=All"><i class="bi bi-chevron-right"></i> All Events</a></li>
                <li><a href="events.php?type=Music"><i class="bi bi-chevron-right"></i> Music</a></li>
                <li><a href="events.php?type=Art"><i class="bi bi-chevron-right"></i> Art+Culture</a></li>
                <li><a href="events.php?type=Sports"><i class="bi bi-chevron-right"></i> Sports</a></li>
                <li><a href="events.php?type=Food"><i class="bi bi-chevron-right"></i> Food</a></li>
                <li><a href="events.php?type=Fund Raiser"><i class="bi bi-chevron-right"></i> Fund Raiser</a></li>
              </ul>
            </div><!-- End Categories -->

            <div class="aside-block">
              <h3 class="aside-title">Tags</h3>
              <ul class="aside-tags list-unstyled">
                <li><a id="all" href="events.php?type=All">All Events</a></li>
                <li><a id="music" href="events.php?type=Music">Music</a></li>
                <li><a id="artCulture" href="events.php?type=Art">Art+Culture</a></li>
                <li><a id="sports" href="events.php?type=Sports">Sports</a></li>
                <li><a id="food" href="events.php?type=Food">Food</a></li>
                <li><a id="funds" href="events.php?type=Fund Raiser">Fund Raiser</a></li>
              </ul>
            </div><!-- End Tags -->

          </div>

        </div>
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