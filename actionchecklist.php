<?php
// Initialize the session
// Initialize the session
session_start();

// Include config file
require_once "config.php";
require_once "connect.php";

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
  
  // Check connection
if (!$mysqli) {
      die("Connection failed:" . mysqli_connect_error());
}



// creates session variable & sets to the post id (product ID of selected item)

$jobNumber = $_SESSION['job'];
$checklist = $_GET["type"];
$vin = $_GET["vin"];
$driver = $_SESSION["username"];


if (($_GET["regDoc"])=='true'){
    $regdoc = true;
}
else{
    $regdoc = 0;
}
if (($_GET["lockingNut"])=='true'){
    $locknut = true;
}
else{
    $locknut = 0;
}
if (($_GET["serviceBook"])=='true'){
    $service = true;
}
else{
    $service = 0;
}
if (($_GET["roadTax"])=='true'){
    $tax = true;
}
else{
    $tax = 0;
}
if (($_GET["bookpack"])=='true'){
    $bookpack = true;
}
else{
    $bookpack = 0;
}
if (($_GET["inflation"])=='true'){
    $inflation = true;
}
else{
    $inflation = 0;
}
if (($_GET["floorMat"])=='true'){
    $floormat = true;
}
else{
    $floormat = 0;
}
if (($_GET["spareWheel"])=='true'){
    $wheel = true;
}
else{
    $wheel = 0;
}
if (($_GET["spareKey"])=='true'){
    $spareKey = true;
}
else{
    $spareKey = 0;
}
if (($_GET["toolKit"])=='true'){
    $toolKit = true;
}
else{
    $toolKit = 0;
}
if (($_GET["chargecable"])=='true'){
    $chargecable = true;
}
else{
    $chargecable = 0;
}
if (($_GET["weatherDry"])=='true'){
    $weatherDry = true;
}
else{
    $weatherDry = 0;
}
if (($_GET["damageEx"])=='true'){
    $damageEx = true;
}
else{
    $damageEx = 0;
}
if (($_GET["damageIn"])=='true'){
    $damageIn = true;
}
else{
    $damageIn = 0;
}
if (($_GET["light"])=='true'){
    $light = true;
}
else{
    $light = 0;
}
if (($_GET["lighter"])=='true'){
    $lighter = true;
}
else{
    $lighter = 0;
}
if (isset($_GET["signed"])){
    $signed = $_GET["signed"];
}
if (isset($_GET["mileage"])){
  $mileage = $_GET["mileage"];
}


$sql = "INSERT INTO checklist (vehicleVin,jobNumber,checktype,regdocument,lockingnutpresent,roadtax,bookpack,inflationdevice,floormats,cigarettelighter,toolkit,sparewheel,sparekeys,chargecable,weatherdry,light,damageinterior,damageexterior,driver,servicebookpresent,signed,mileage) 
VALUES ('$vin','$jobNumber','$checklist','$regdoc','$locknut','$tax','$bookpack','$inflation','$floormat','$lighter','$toolKit','$wheel','$spareKey','$chargecable','$weatherDry','$light','$damageIn','$damageEx','$driver','$service','$signed','$mileage')";
if (mysqli_query($conn, $sql)) {
   $message = "New Checklist has been added successfully !";
} else {
   $message = "Error: " . $sql . " - " . mysqli_error($conn);
}


if ($checklist =="collection"){
    $status = "Collected";
}
else if ($checklist =="delivery"){
    $status = "Delivered";
}

$update = "UPDATE jobs SET status = '$status' WHERE jobNumber LIKE '$jobNumber' ";
if (mysqli_query($conn, $update)) {
    $message2 = "$update";
 } else {
    $message2 = "Error: " . $update . " - " . mysqli_error($conn);
 }
mysqli_close($conn);






  



 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");

    
    exit;
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
                      
                          <h2> Vehicle Checklist </h2>
                          <h2> Job Number: <?php echo $_GET["job"]; ?></h2>
                          <h2> Vehicle Vin: <?php echo $vin; ?> </h2>
                          <h2> <?php echo $_GET["type"]; ?> </h2>
                        
                          <p>
                            
                            <a href="welcome.php" class="btn btn-danger mt-3">Home</a>
                            <a href="jobdetailsuser.php" class="btn btn-danger mt-3">Back</a>
                        </p>
                      </div>

                      <table class="table">
                  <tbody>
                  <tr>
               
                  <td>Item Name </td>
                  <td>Check Correct</td>
                      <?php 
                      echo $message;
                     // echo $message2;
                      ?>
                 
                  </tr>	
                                   
                 <div class="single-product-item">
                <?php 
                    
                ?>
                <tr>
                    <td> Vehcile Mileage </td> 
                    <td><?php echo $mileage ?></td> 
                    </tr>

                    <tr>
                    <td> Registration document </td> 
                    <td><?php echo $regdoc ?></td> 
                    </tr>

                    <td> Locking nut present </td>
                    <td> <?php echo $service ?></td> 
                    </tr>
                    <td>Service Book present</td>
                    <td> <?php echo $service ?></td> 
                    </tr>
                    <td>Road Tax </td>
                    <td><?php echo $tax ?></td> 
                    </tr>

                    <td>Book Pack </td>
                    <td><?php echo $bookpack; ?></td> 
                    </tr>

                    <td>Inflation Device </td>
                    <td><?php echo $inflation ?></td> 
                    </tr>
                    <td>Weather Dry</td>
                    <td><?php echo $weatherDry?></td> 
                    </tr>
                    <td>Weather Light</td>
                    <td><?php echo $light?></td> 
                    </tr>
                    <td>Charge Cable</td>
                    <td><?php echo $chargecable?></td> 
                    </tr>
                    <td>Spare Wheel</td>
                    <td><?php echo $spare?></td> 
                    </tr>
                    <td>Spare Keys</td>
                    <td><?php echo $spareKey?></td> 
                    </tr>

                    <td>Floor Mat </td>
                    <td><?php echo $floormat ?></td> 
                    </tr>

                    <td>Damage Exterior</td>
                    <td><?php echo $damageEx ?></td> 
                    </tr>

                    <td>Damage Interior</td>
                    <td><?php echo $damageIn?></td> 
                    </tr>

                    <td>Signed By Customer</td>
                    <td><?php echo $signed?></td> 
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
            <p>We-Drive  Powered by<a href="https://agdigital.co.uk" target="_blank"><img src="images/agd.png" style="width: 30px"></a> is part of the Project 63 group</p>
            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved |<a href="#" target="_blank">  We-Drive  </a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
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