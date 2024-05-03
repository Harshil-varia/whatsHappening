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
        <h1>What's Happening</h1>
      </a>

      <nav id="navbar" class="navbar">
        <ul> <!-- Updates made to title of navbar according to Assignment Instruction-->
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

    <section class="single-post-content">
      <div class="container">
        <div class="row">
          <div class="col-md-9 post-content" data-aos="fade-up">

            <!-- ======= Single Post Content ======= -->
            <?php 
              include 'serverlogin.php';

                // Create database connection
              $conn = new mysqli($db_hostname, $db_username, $db_password, $db_database);
            
                // Check connection
              if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
              }
            
                // Retrieve EventID from query string
              if(isset($_GET['type'])) {
                  $eventID = $_GET['type'];
            
                    // Retrieve event information from the database
                  $sql = "SELECT Events.EventID, Events.EventTypeID, Events.GroupID, Events.EventDate, Events.EventTitle, Events.EventImage, Events.EventDesc, EventTypes.TypeName, Groups.GroupName, Groups.ContactName, Groups.ContactEmail
                          FROM Events
                          INNER JOIN EventTypes ON Events.EventTypeID = EventTypes.EventTypeID
                          INNER JOIN whats_happening.Groups ON Events.GroupID = whats_happening.Groups.GroupID
                          WHERE Events.EventID = $eventID";
                  $result = $conn->query($sql);
            
                    // Display event information
                  if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()){
                      $string1=substr($row["EventDesc"],0,1);
                      $string2=substr($row["EventDesc"],1,strlen($row["EventDesc"]));
                      $eventDateTime = date_create($row["EventDate"]);
                      $formattedDate = date_format($eventDateTime, "D M j, Y"); 
                      $formattedTime = date_format($eventDateTime, "g:i A");      
                      echo <<<DIVBLOCK
                          <div class="single-post">
                          <div class="post-meta"><span class="date">{$row["TypeName"]}</span> <span class="mx-1">&bullet;</span> <span>DATE: $formattedDate TIME: $formattedTime</span></div>
                          <h1 class="mb-5">{$row["EventTitle"]}</h1>
                          <p><h3>Organizers: {$row["GroupName"]}</h3>
                          <h3></h3>(Contact {$row["ContactName"]} at {$row["ContactEmail"]} for more info)</p><br><br>
                          <p><span class="firstcharacter">{$string1}</span>{$string2} <br><br>
                          <img src={$row["EventImage"]} height="510" width=""1992.450 alt="" class="me-4 thumbnail"></p>
                          </div><!-- End Single Post Content -->
                      DIVBLOCK;
                    }
                        // Format date and time

                  }
                }
          
                // Close database connection
                $conn->close();
            ?>

            <!-- ======= Comments ======= -->
            <div class="comments">
              <h5 class="comment-title py-4">2 Comments</h5>
              <div class="comment d-flex mb-4">
                <div class="flex-shrink-0">
                  <div class="avatar avatar-sm rounded-circle">
                    <img class="avatar-img" src="assets/img/person-5.jpg" alt="" class="img-fluid">
                  </div>
                </div>
                <div class="flex-grow-1 ms-2 ms-sm-3">
                  <div class="comment-meta d-flex align-items-baseline">
                    <h6 class="me-2">Jordan Singer</h6>
                    <span class="text-muted">2d</span>
                  </div>
                  <div class="comment-body">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Non minima ipsum at amet doloremque qui magni, placeat deserunt pariatur itaque laudantium impedit aliquam eligendi repellendus excepturi quibusdam nobis esse accusantium.
                  </div>

                  <div class="comment-replies bg-light p-3 mt-3 rounded">
                    <h6 class="comment-replies-title mb-4 text-muted text-uppercase">2 replies</h6>

                    <div class="reply d-flex mb-4">
                      <div class="flex-shrink-0">
                        <div class="avatar avatar-sm rounded-circle">
                          <img class="avatar-img" src="assets/img/person-4.jpg" alt="" class="img-fluid">
                        </div>
                      </div>
                      <div class="flex-grow-1 ms-2 ms-sm-3">
                        <div class="reply-meta d-flex align-items-baseline">
                          <h6 class="mb-0 me-2">Brandon Smith</h6>
                          <span class="text-muted">2d</span>
                        </div>
                        <div class="reply-body">
                          Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                        </div>
                      </div>
                    </div>
                    <div class="reply d-flex">
                      <div class="flex-shrink-0">
                        <div class="avatar avatar-sm rounded-circle">
                          <img class="avatar-img" src="assets/img/person-3.jpg" alt="" class="img-fluid">
                        </div>
                      </div>
                      <div class="flex-grow-1 ms-2 ms-sm-3">
                        <div class="reply-meta d-flex align-items-baseline">
                          <h6 class="mb-0 me-2">James Parsons</h6>
                          <span class="text-muted">1d</span>
                        </div>
                        <div class="reply-body">
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Distinctio dolore sed eos sapiente, praesentium.
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="comment d-flex">
                <div class="flex-shrink-0">
                  <div class="avatar avatar-sm rounded-circle">
                    <img class="avatar-img" src="assets/img/person-2.jpg" alt="" class="img-fluid">
                  </div>
                </div>
                <div class="flex-shrink-1 ms-2 ms-sm-3">
                  <div class="comment-meta d-flex">
                    <h6 class="me-2">Santiago Roberts</h6>
                    <span class="text-muted">4d</span>
                  </div>
                  <div class="comment-body">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto laborum in corrupti dolorum, quas delectus nobis porro accusantium molestias sequi.
                  </div>
                </div>
              </div>
            </div><!-- End Comments -->

            <!-- ======= Comments Form ======= -->
            <div class="row justify-content-center mt-5">

              <div class="col-lg-12">
                <h5 class="comment-title">Leave a Comment</h5>
                <div class="row">
                  <div class="col-lg-6 mb-3">
                    <label for="comment-name">Name</label>
                    <input type="text" class="form-control" id="comment-name" placeholder="Enter your name">
                  </div>
                  <div class="col-lg-6 mb-3">
                    <label for="comment-email">Email</label>
                    <input type="text" class="form-control" id="comment-email" placeholder="Enter your email">
                  </div>
                  <div class="col-12 mb-3">
                    <label for="comment-message">Message</label>

                    <textarea class="form-control" id="comment-message" placeholder="Enter your name" cols="30" rows="10"></textarea>
                  </div>
                  <div class="col-12">
                    <input type="submit" class="btn btn-primary" value="Post comment">
                  </div>
                </div>
              </div>
            </div><!-- End Comments Form -->

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
                <li><a href="events.php"><i class="bi bi-chevron-right"></i> All Events</a></li>
                <li><a href="events.php"><i class="bi bi-chevron-right"></i> Music</a></li>
                <li><a href="events.php"><i class="bi bi-chevron-right"></i> Art+Culture</a></li>
                <li><a href="events.php"><i class="bi bi-chevron-right"></i> Sports</a></li>
                <li><a href="events.php"><i class="bi bi-chevron-right"></i> Food</a></li>
                <li><a href="events.php"><i class="bi bi-chevron-right"></i> Fund Raiser</a></li>
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