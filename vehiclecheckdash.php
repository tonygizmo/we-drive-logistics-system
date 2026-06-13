<?php
require_once "connect.php";
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Vehicle check stats
$totalChecks = $mysqli->query("SELECT COUNT(*) as count FROM vehicle_checks")->fetch_assoc()['count'];

$failedChecks = $mysqli->query("
    SELECT COUNT(*) as count 
    FROM vehicle_checks 
    WHERE defects_found = 1 OR vehicle_safe_to_use = 0
")->fetch_assoc()['count'];

$safeChecks = $mysqli->query("
    SELECT COUNT(*) as count 
    FROM vehicle_checks 
    WHERE defects_found = 0 AND vehicle_safe_to_use = 1
")->fetch_assoc()['count'];

$todaysChecks = $mysqli->query("
    SELECT COUNT(*) as count 
    FROM vehicle_checks 
    WHERE check_date = CURDATE()
")->fetch_assoc()['count'];

$driver = $_SESSION['username'];
$userID = $_SESSION['id'];

// Driver-only stats
$myChecks = $mysqli->query("
    SELECT COUNT(*) as count 
    FROM vehicle_checks 
    WHERE user_id = '$userID'
")->fetch_assoc()['count'];

$myFailedChecks = $mysqli->query("
    SELECT COUNT(*) as count 
    FROM vehicle_checks 
    WHERE user_id = '$userID'
    AND (defects_found = 1 OR vehicle_safe_to_use = 0)
")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle Check Dashboard | We-Drive</title>

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
          <li class="nav-item"><a class="nav-link text-white" href="vehiclecheckdashboard.php">Vehicle Checks</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="addjob.php">Add Job</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="viewjob.php">View Jobs</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link text-white" href="userjobs.php">My Jobs</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="vehiclecheck.php">Vehicle Check</a></li>
        <?php endif; ?>

        <li class="nav-item mt-4"><a class="nav-link btn btn-warning text-dark" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-4">

      <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Vehicle Check Dashboard</h1>

        <?php if ($_SESSION["admin"] == 1): ?>
          <span class="badge bg-warning text-dark">Administrator</span>
        <?php else: ?>
          <span class="badge bg-success text-dark">Driver</span>
        <?php endif; ?>
      </div>

      <?php if ($_SESSION["admin"] == 1): ?>

        <div class="row">

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">New Vehicle Check</h5>
                <p class="card-text">Complete a daily vehicle check</p>
                <a href="vehiclecheck.php" class="btn btn-success">Start Check</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Failed / Defect Checks</h5>
                <p class="card-text display-6"><?= $failedChecks ?></p>
                <a href="failedvehiclechecks.php" class="btn btn-danger">View Defects</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Today's Checks</h5>
                <p class="card-text display-6"><?= $todaysChecks ?></p>
                <a href="vehiclecheck.php" class="btn btn-info">View Checks</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Total Checks</h5>
                <p class="card-text display-6"><?= $totalChecks ?></p>
                <a href="totalchecks.php" class="btn btn-primary">View All</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Safe Checks</h5>
                <p class="card-text display-6"><?= $safeChecks ?></p>
                <a href="vehiclecheck.php" class="btn btn-success">View Safe</a>
              </div>
            </div>
          </div>

        </div>

      <?php else: ?>

        <div class="row">

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">New Vehicle Check</h5>
                <p class="card-text">Complete your daily vehicle check</p>
                <a href="vehiclecheck.php" class="btn btn-success">Start Check</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">My Checks</h5>
                <p class="card-text display-6"><?= $myChecks ?></p>
                <a href="vehiclecheck.php" class="btn btn-primary">View Mine</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card dashboard-card text-center mb-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">My Failed Checks</h5>
                <p class="card-text display-6"><?= $myFailedChecks ?></p>
                <a href="vehiclecheck.php" class="btn btn-danger">View Defects</a>
              </div>
            </div>
          </div>

        </div>

      <?php endif; ?>

    </main>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>