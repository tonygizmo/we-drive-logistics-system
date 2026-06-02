<?php
require_once "connect.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Optional: Dynamic stats
$driver = $_SESSION['username'];
$driverjobs = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE driver = '$driver'")->fetch_assoc()['count'];
$active = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE complete = 0")->fetch_assoc()['count'];
$completed = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE complete = 1")->fetch_assoc()['count'];
$noDriver = $mysqli->query("SELECT COUNT(*) as count FROM jobs WHERE driver='none'")->fetch_assoc()['count'];
$totalUsers = ($_SESSION["admin"] == 1) ? $mysqli->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? "We-Drive Dashboard" ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
      body { font-family: 'Poppins', sans-serif; }
      #sidebar { min-height: 100vh; }
      .card { border-radius: 12px; }
      .dashboard-card h5 { font-weight: bold; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">

    <!-- Mobile toggle button -->
    <button class="btn btn-dark d-md-none m-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        ☰ Menu
    </button>

    <!-- Sidebar Offcanvas -->
    <div class="offcanvas offcanvas-start d-md-block bg-dark text-white" tabindex="-1" id="mobileSidebar">
      <div class="offcanvas-header d-md-none">
        <h5 class="offcanvas-title">We-Drive</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body p-3">
        <ul class="nav flex-column mt-4">
          <li class="nav-item"><a class="nav-link text-white" href="welcome.php">Dashboard</a></li>
          <?php if ($_SESSION["admin"] == 1): ?>
            <li class="nav-item"><a class="nav-link text-white" href="manageuser.php">Manage Users</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="addjob.php">Add Job</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="viewjob.php">View Jobs</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link text-white" href="userjobs.php">My Jobs</a></li>
          <?php endif; ?>
          <li class="nav-item mt-4"><a class="nav-link btn btn-warning text-dark" href="reset-password.php">Reset Password</a></li>
          <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>

    <!-- Main Content -->
    <main class="col-md-10 ms-md-auto px-2 px-md-4 py-3">
      <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
        <h1 class="h2"><?= $pageTitle ?? "Dashboard" ?></h1>
        <span class="badge <?= ($_SESSION["admin"] == 1) ? 'bg-warning text-dark' : 'bg-success text-dark' ?>">
            <?= ($_SESSION["admin"] == 1) ? 'Administrator' : 'Driver' ?>
        </span>
      </div>

      <?= $content ?? "" ?>
    </main>

  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
