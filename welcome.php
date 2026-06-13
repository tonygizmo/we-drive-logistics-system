<?php
require_once "connect.php"; // keep your existing connection
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Fetch dynamic data
// Total jobs
$totalJobs = $mysqli->query("SELECT COUNT(*) as count FROM jobs")->fetch_assoc()['count'];

$noDriver= $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE driver='none'")->fetch_assoc()['count'];

$completed = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE complete = 1")->fetch_assoc()['count']; 

$outstanding = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE complete = 0")->fetch_assoc()['count']; 

$active = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE driverStatus != 'not_started'")->fetch_assoc()['count'];

$flagged = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE flagged = 1")->fetch_assoc()['count'];


$driver = $_SESSION['username']; // or whatever field stores the driver’s login name
$driverjobs = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE driver = '$driver'")->fetch_assoc()['count'];


// Total users (admin view only)
$totalUsers = 0;
if ($_SESSION["admin"] == 1) {
    $totalUsers = $mysqli->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
}

// Additional stats can be added similarly
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We-Drive Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
      body {
          font-family: 'Poppins', sans-serif;
      }
      #sidebar {
          min-height: 100vh;
      }
      .card {
          border-radius: 12px;
      }
      .dashboard-card h5 {
          font-weight: bold;
      }
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
        <?php if ($_SESSION["admin"] == 1): ?>
        <li class="nav-item"><a class="nav-link text-white" href="manageuser.php">Manage Users</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="addjob.php">Add Job</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="viewjob.php">View Jobs</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="vehiclecheckdash.php">Checks Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="vehiclecheck.php">Vehicle Checks</a></li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link text-white" href="userjobs.php">My Jobs</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="vehiclecheckdash.php">Checks Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="vehiclecheck.php">Vehicle Checks</a></li>
        <?php endif; ?>
        <li class="nav-item mt-4"><a class="nav-link btn btn-warning text-dark" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-4">
      <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Welcome, <?= htmlspecialchars($_SESSION["username"]); ?></h1>
        <?php if ($_SESSION["admin"] == 1): ?>
          <span class="badge bg-warning text-dark">Administrator</span>
         
          
        <?php else: ?>
        <span class="badge bg-success text-dark">Driver</span>
      <?php  endif; ?>
      </div>

      <!-- Dashboard Cards -->
   
        <?php if ($_SESSION["admin"] == 1): ?>
         <div class="row">
        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Active Jobs</h5>
              <p class="card-text display-6"><?= $active ?></p>
              <a href="activejobs.php" class="btn btn-success">View Jobs</a>
            </div>
          </div>
          </div>
    
         <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Flagged Jobs</h5>
              <p class="card-text display-6"><?= $flagged ?></p>
              <a href="flaggedjobs.php" class="btn btn-danger">View Jobs</a>
            </div>
          </div>
          </div>
        
         
        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Outstanding Jobs</h5>
              <p class="card-text display-6"><?= $outstanding ?></p>
              <a href="viewjob.php" class="btn btn-warning">View Jobs</a>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Completed Jobs</h5>
              <p class="card-text display-6"><?= $completed ?></p>
              <a href="completejobs.php" class="btn btn-success">Completed Jobs</a>
            </div>
          </div>
        </div>
        
            <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Unallocated Jobs</h5>
              <p class="card-text display-6"><?= $noDriver ?></p>
              <a href="nodriver.php" class="btn btn-info">Allocate Jobs</a>
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Total Users</h5>
              <p class="card-text display-6"><?= $totalUsers ?></p>
              <a href="manageuser.php" class="btn btn-warning">Manage Users</a>
            </div>
          </div>
        </div>
        
        

        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Add New Job</h5>
              <p class="card-text">Quickly assign jobs to drivers</p>
              <a href="addjob.php" class="btn btn-success">Add Job</a>
            </div>
          </div>
        </div>
        <?php else: ?>
          <div class="row">
        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">Active Jobs</h5>
              <p class="card-text display-6"><?= $driverjobs ?></p>
              <a href="myjobs.php" class="btn btn-danger">View Jobs</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card dashboard-card text-center mb-4 shadow-sm">
            <div class="card-body">
              <h5 class="card-title">New Vehicle Check</h5>
              <p class="card-text">Begin Vehicle Checksheet</p>
              <a href="vehiclecheck.php" class="btn btn-success">Add Job</a>
            </div>
          </div>
        </div>
        
        
       <?php endif; ?>
      </div>

    </main>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>