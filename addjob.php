<?php
require_once "connect.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We-Drive | Add Job</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>

  <style>
      body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
      #sidebar { min-height: 100vh; }
      .page-header { margin-bottom: 30px; }
      main { height: 90vh; overflow-y: hidden; }
      .card-body { height: 100%; overflow-y: auto; }
      .form-section { border-radius: 12px; }
      .form-control { margin-bottom: 10px; }
      .btn-section { margin-top: 20px; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <nav id="sidebar" class="col-md-2 bg-dark text-white p-3">
      <h4 class="text-center">We-Drive</h4>
      <ul class="nav flex-column mt-4">
        <li class="nav-item"><a class="nav-link text-white" href="welcome.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="viewjob.php">Active Jobs</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="completejob.php">Completed Jobs</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-warning text-dark mt-4" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-4">
      <div class="page-header">
        <h2>Add New Job</h2>
        <div class="mb-3">
          <a href="welcome.php" class="btn btn-danger">Home</a>
          <a href="viewjob.php" class="btn btn-danger">Active Jobs</a>
       
        </div>
      </div>

      <div class="card shadow-sm mb-4 form-section" style="height: calc(90vh - 100px);">
        <div class="card-body">
          <form action="insert.php" method="post">
            <div class="row g-3">

              <!-- Delivery Details -->
              <div class="col-lg-6">
                <h5>Delivery Address Details</h5>
                <input class="form-control form-control-sm" name="delAddName" type="text" placeholder="Delivery Address Name">
                <input class="form-control form-control-sm" name="delAddStreet" type="text" placeholder="Delivery Address Street">
                <input class="form-control form-control-sm" name="delAddTown" type="text" placeholder="Delivery Address Town">
                <input class="form-control form-control-sm" name="delAddCode" type="text" placeholder="Delivery Address Postcode">
                <input class="form-control form-control-sm" name="delAddfName" type="text" placeholder="Contact First Name">
                <input class="form-control form-control-sm" name="delAddlName" type="text" placeholder="Contact Last Name">
                <input class="form-control form-control-sm" name="date" id="datePicker" type="text" placeholder="Select Delivery Date"required>
                <input class="form-control form-control-sm" name="time" type="text" placeholder="Delivery Time">
              </div>

              <!-- Collection & Vehicle Details -->
              <div class="col-lg-6" style="background-color: rgb(99, 95, 95); padding: 20px; border-radius: 12px; color: white;">
                <h5>Collection Address Details</h5>
                <input class="form-control form-control-sm" name="colAddName" type="text" placeholder="Collection Address Name">
                <input class="form-control form-control-sm" name="colAddStreet" type="text" placeholder="Collection Address Street">
                <input class="form-control form-control-sm" name="colAddTown" type="text" placeholder="Collection Address Town">
                <input class="form-control form-control-sm" name="colAddCode" type="text" placeholder="Collection Address Postcode">
                <input class="form-control form-control-sm" name="colAddfName" type="text" placeholder="Contact First Name">
                <input class="form-control form-control-sm" name="colAddlName" type="text" placeholder="Contact Last Name">

                <h5 class="mt-3">Vehicle Details</h5>
                <input class="form-control form-control-sm" name="vehicleMake" type="text" placeholder="Vehicle Make">
                <input class="form-control form-control-sm" name="vehicleModel" type="text" placeholder="Vehicle Model">
                <input class="form-control form-control-sm" name="vin" type="text" placeholder="VIN">

                <div class="btn-section">
                     
            <button type="button" id="randomJobBtn" class="btn btn-warning">Generate Random Job</button>


                  <input type="submit" class="btn btn-primary" name="submit" value="Submit">
                  <a href="welcome.php" class="btn btn-danger">Back</a>
                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
    </main>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  $(document).ready(function(){
    $('#datePicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true,
      todayHighlight: true
    });
  });
</script>
<script>
$(document).ready(function(){
    function getRandomElement(arr) {
        return arr[Math.floor(Math.random() * arr.length)];
    }

    $('#randomJobBtn').click(function() {
        // Random Names
        const firstNames = ['John', 'Jane', 'Alice', 'Bob', 'Charlie', 'Diana'];
        const lastNames = ['Smith', 'Brown', 'Johnson', 'Williams', 'Taylor', 'Lee'];

        // Towns / Streets
        const towns = ['London', 'Manchester', 'Liverpool', 'Bristol', 'Leeds'];
        const streets = ['High Street', 'Main Street', 'Station Road', 'Church Lane', 'Park Avenue'];

        // Vehicles
        const makes = ['Ford', 'BMW', 'Audi', 'Mercedes', 'Toyota'];
        const models = ['Focus', '3 Series', 'A4', 'C-Class', 'Corolla'];

        // Populate Delivery
        $('[name="delAddName"]').val(getRandomElement(towns) + ' Delivery');
        $('[name="delAddStreet"]').val(getRandomElement(streets));
        $('[name="delAddTown"]').val(getRandomElement(towns));
        $('[name="delAddCode"]').val(Math.floor(10000 + Math.random() * 90000));
        $('[name="delAddfName"]').val(getRandomElement(firstNames));
        $('[name="delAddlName"]').val(getRandomElement(lastNames));

        // Populate Collection
        $('[name="colAddName"]').val(getRandomElement(towns) + ' Collection');
        $('[name="colAddStreet"]').val(getRandomElement(streets));
        $('[name="colAddTown"]').val(getRandomElement(towns));
        $('[name="colAddCode"]').val(Math.floor(10000 + Math.random() * 90000));
        $('[name="colAddfName"]').val(getRandomElement(firstNames));
        $('[name="colAddlName"]').val(getRandomElement(lastNames));

        // Vehicle
        const make = getRandomElement(makes);
        $('[name="vehicleMake"]').val(make);
        $('[name="vehicleModel"]').val(getRandomElement(models));
        $('[name="vin"]').val(Math.random().toString(36).substring(2, 10).toUpperCase());

        // Date & Time (future dates)
        const today = new Date();
        const randomDate = new Date(today.getTime() + Math.floor(Math.random() * 14) * 24 * 60 * 60 * 1000);
        $('[name="date"]').val(randomDate.toISOString().split('T')[0]); // yyyy-mm-dd
        const hours = Math.floor(Math.random() * 8) + 8; // 8-15h
        const minutes = [0, 15, 30, 45][Math.floor(Math.random() * 4)];
        $('[name="time"]').val(("0"+hours).slice(-2) + ":" + ("0"+minutes).slice(-2));

       // Optional: auto-submit
         //$('form').submit();
    });
});
</script>


</body>
</html>
