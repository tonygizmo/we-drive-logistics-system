<?php
session_start();

$job = $_SESSION['job'];
$driver = $_SESSION['driver'];
$status = "Allocated";

  $db_host = 'localhost';
  $db_user = 'u103640329_WeDriveApp';
  $db_password = 'Coast49tower!';
  $db_db = 'u103640329_WeDriveApp';

  $mysqli = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );

  if ($mysqli->connect_error) {
    echo 'Errno: '.$mysqli->connect_errno;
    echo '<br>';
    echo 'Error: '.$mysqli->connect_error;
    exit();
  }

if(isset($_POST['update']))
{    
     $job = $_POST['jobNum'];
     $driver = $_POST['driver'];
  
    
 
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>We-Drive  </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/open-iconic-bootstrap.min.css">
    <link rel="stylesheet" href="css/animate.css">
    
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/magnific-popup.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/ionicons.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.timepicker.css">

    
    <link rel="stylesheet" href="css/flaticon.css">
    <link rel="stylesheet" href="css/icomoon.css">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    
	  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
	    <div class="container">
	      <a class="navbar-brand" href="index.php">We-Drive  </a>
	      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
	        <span class="oi oi-menu"></span> Menu
	      </button>

	      <div class="collapse navbar-collapse" id="ftco-nav">
	        <ul class="navbar-nav ml-auto">
	          <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
              <li class="nav-item active"><a href="login.php" class="nav-link">Login</a></li>
	          <li class="nav-item "><a href="about.php" class="nav-link">About</a></li>
	          <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
	          <li class="nav-item"><a href="pricing.php" class="nav-link">Pricing</a></li>
	          <li class="nav-item"><a href="car.php" class="nav-link">Cars</a></li>
	          <li class="nav-item"><a href="blog.php" class="nav-link">Blog</a></li>
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->
    
   

      <section class="mt-20 h-100 gradient-form" style="background-color: rgb(5, 5, 5);">
        <div class="container py-5 h-100 " style="margin: 0px 50px 0px 50px; padding-top: 100px !important;">
          <div class="row d-flex justify-content-center align-items-center h-100 "style="overflow-x:scroll; overflow-y:scroll;"> <!-- sort white box width here - maybe add an inner scroll here -->
            <div class="col-xl-12">
              <div class="card rounded-3 text-black">
                <div class="row g-0">
                  <div class="col-lg-12">
                    <div class="card-body p-md-5 mx-md-4">

                    <div class="text-center">
                        <img src="images/logo.png" 
                          style="width: 185px; margin-bottom: 10px; " alt="logo">
                      
                          <h2> Driver Updated</h2>
                         
                          <p>
                            
                            <a href="welcome.php" class="btn btn-danger mt-3">Home</a>
                        </p>
                      


                      <?php
                      //update driver & status of job 

                          $sql = "UPDATE `jobs` SET `driver`='$driver' WHERE jobNumber = '$job'";
                         if (mysqli_query($mysqli, $sql)) {
                           echo "Job Number  ", $job,"   Assigned to Driver:  ",$driver;
                         } else {
                            echo "Error: " . $sql . " - " . mysqli_error($conn);
                          }

                          $update = "UPDATE jobs SET status = '$status' WHERE jobNumber LIKE '$job' ";
                          if (mysqli_query($mysqli, $update)) {
                             $message2 = "$update";
                            // echo $message2;
                           } else {
                              $message2 = "Error: " . $update . " - " . mysqli_error($conn);
                              echo $message2;
                           }


                      ?>
                      </div>

                        <a href="end.php" class="btn btn-danger mt-3">back</a>
                      
      
                    </div>
                  </div>
               </div>
            </div>
        </div>
     </div>
  </div>

              
    </section>









 <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2"><a href="#" class="logo">We-Drive  </a></h2>
              <p>The one stop solution for all your distrubution needs</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-5">
              <h2 class="ftco-heading-2">Information</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">About</a></li>
                <li><a href="#" class="py-2 d-block">Term and Conditions</a></li>
                <li><a href="#" class="py-2 d-block">Privacy &amp; Cookies Policy</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
             <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Customer Support</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">FAQ</a></li>
                <li><a href="#" class="py-2 d-block">Contact Us</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Have a Questions?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text">63 Rhuddlan Road </span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">0898465674</span></a></li>
	                <li><a href=""><span class="icon icon-envelope"></span><span class="text"> tonygibbons@agdigital.co.uk</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
            <p>We-Drive  Powered by<a href="https://agdigital.co.uk"target="_blank"><img src="images/agd.png" style="width: 30px"></a></p>
            <p>
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved |<a href="#" target="_blank">  We-Drive  </a></p>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="js/jquery.min.js"></script>
  <script src="js/jquery-migrate-3.0.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/jquery.waypoints.min.js"></script>
  <script src="js/jquery.stellar.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.animateNumber.min.js"></script>
  <script src="js/bootstrap-datepicker.js"></script>
  <script src="js/jquery.timepicker.min.js"></script>
  <script src="js/scrollax.min.js"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="js/google-map.js"></script>
  <script src="js/main.js"></script>
    
  </body>
</html>