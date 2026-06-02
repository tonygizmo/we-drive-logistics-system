
<?php



// Initialize the session
session_start(); 



$jobNumber = $_SESSION['job'];
if(isset($_GET['name'])){
    $driver= $_GET['name']; 
    $_SESSION['driver'] = $driver; 
    }
    echo $jobNumber;
    echo $driver;
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
  
require_once "config.php";
require_once "connect.php";

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
	          <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
	        </ul>
	      </div>
	    </div>
	  </nav>
    <!-- END nav -->
    
   

    <section class="mt-20 h-100 gradient-form" style="background-color: rgb(5, 5, 5);">
        <div class="container py-5 h-100 " style= "padding-top: 10% !important;">
          <div class="row d-flex justify-content-center align-items-center h-100 "style="overflow-x:scroll; overflow-y:scroll;"> <!-- sort white box width here - maybe add an inner scroll here -->
            <div class="col-xl-12">
              <div class="card rounded-3 text-black">
                <div class="row g-0">
                  <div class="col-lg-12">
                    <div class="card-body p-md-5 mx-md-4">

                    <div class="text-center">
                        <img src="images/logo.png" 
                          style="width: 25%; margin-bottom: 10px; " alt="logo">
                      
                          <h2> Allocate Driver</h2>
                        
                          <p>
                            
                            <a href="welcome.php" class="btn btn-danger mt-3">Home</a>
                            <a href="viewjob.php" class="btn btn-danger mt-3">Back</a>
                            
                        </p>
                      </div>

                      <table class="table">
                  <tbody>
                  <tr>
                  
                  <td>JOB NUMBER </td>
                  <td>COL TOWN</td>
                  <td> DEL TOWN </td>
                  <td>DATE</td>
                  <td>DRIVER</td>
                  <td>STATUS</td>
                 
                  
                  </tr>	
                                   
                 <div class="single-product-item">
                                      <?php   
                                // end of connection section
                                // get items from products database
                                $sql = "SELECT * FROM jobs WHERE jobNumber = '$jobNumber' ";
                                $result = mysqli_query($mysqli, $sql);
                                  while ($row = mysqli_fetch_assoc($result)) {
                                    // query for driver allocated 
                                   
                                
                ?>
                   
               
                 <tr>
                 <td><?php echo $row['jobNumber']; ?></td>
                 <td> <?php echo $row['colTown']; ?></td>
                 <td><?php echo $row['delTown']; ?></td>
                 <td><?php echo $row['colDate']; ?></td>
                 
                        

                <td> <?php echo $driver ?> </td>
                        
               

           
                

                
<?php } ?> 
                <td>
                   <div class="more-info">
               
                                    
						<form method="POST" action= "insertdriver.php" >
							<input type="hidden" name="jobNum" value="<?php  echo $jobNumber1; ?>"></input>
                            <input type="hidden" name="driver" value="<? echo $driver;?>"></input>
							<input type="submit" name="submit" value="Update"></input>
							<br>
						</form>
                  </td>

                                    

                                  

                 </tr>
                 
                 </div>
               
               
                 
                 </tbody>
                 </table>		
                  
                 </div>


                      
                     </div>
                  </div>

                     
      
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

