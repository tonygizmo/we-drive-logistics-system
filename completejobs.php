<?php
require_once "connect.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Handle job selection
if (isset($_POST['submit'])) {
    $_SESSION['job'] = htmlentities($_POST['pID']);
    header('location: jobdetails.php');
    exit;
}

// Handle driver filter
$selectedDriver = isset($_GET['driver']) ? $_GET['driver'] : '';

// Fetch completed jobs, filtered by driver if selected
if ($selectedDriver && $selectedDriver !== 'all') {
    $stmt = $mysqli->prepare("SELECT * FROM jobs WHERE complete = 1 AND driver = ?");
    $stmt->bind_param("s", $selectedDriver);
    $stmt->execute();
    $jobs = $stmt->get_result();
} else {
    $jobs = $mysqli->query("SELECT * FROM jobs WHERE complete = 1");
}

// Fetch drivers and count of completed jobs
$drivers = $mysqli->query("
  SELECT 
    u.username,
    COUNT(j.jobNumber) AS job_count
  FROM users u
  LEFT JOIN jobs j ON j.driver = u.username AND j.complete = 1
  GROUP BY u.username
  ORDER BY u.username ASC
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We-Drive | Completed Jobs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
      body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
      #sidebar { min-height: 100vh; }
      .table thead { background-color: #343a40; color: white; }
      .btn-action { min-width: 100px; }
      .dashboard-card { border-radius: 12px; }
      .page-header { margin-bottom: 30px; }
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
        <li class="nav-item"><a class="nav-link text-white" href="viewjobs.php">Active Jobs</a></li>
        <li class="nav-item"><a class="nav-link btn btn-warning text-dark mt-4" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

<!-- Main Content -->
<main class="col-md-10 ms-sm-auto px-4" style="height: 90vh; overflow-y: hidden;">
  <div class="page-header">
    <h2>Completed Jobs</h2>
    <div class="mb-3">
      <a href="welcome.php" class="btn btn-danger">Home</a>
      <a href="viewjob.php" class="btn btn-danger">Active Jobs</a>
      <a href="nodriver.php" class="btn btn-danger">Allocate Drivers</a>
  
  <form method="GET" class="d-flex align-items-center">
    <label for="driver" class="me-2 fw-bold">Filter by Driver:</label>
    <select name="driver" id="driver" class="form-select form-select-sm me-2" onchange="this.form.submit()">
      <option value="all" <?= $selectedDriver === 'all' || $selectedDriver === '' ? 'selected' : '' ?>>
        All Drivers (<?= $mysqli->query("SELECT COUNT(*) AS total FROM jobs WHERE complete = 1")->fetch_assoc()['total'] ?>)
      </option>
      <?php while ($user = $drivers->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($user['username']) ?>" <?= $selectedDriver === $user['username'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($user['username']) ?> (<?= $user['job_count'] ?>)
        </option>
      <?php endwhile; ?>
    </select>
    <noscript><button type="submit" class="btn btn-primary btn-sm">Filter</button></noscript>
  </form>


      
    </div>
  </div>

  <div class="card shadow-sm mb-4" style="height: calc(90vh - 100px);">
    <div class="card-body" style="overflow-y: auto; height: 100%;">
      <div class="table-responsive" style="max-height: 100%; overflow-y: auto;">
        <table class="table table-hover">
          <thead class="table-dark" style="position: sticky; top: 0; z-index: 10;">
            <tr>
              <th>Job Number</th>
              <th>Col Town</th>
              <th>Del Town</th>
              <th>Date</th>
              <th>Driver</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $jobs->fetch_assoc()): ?>
              <tr>
                <td><?= $row['jobNumber'] ?></td>
                <td><?= $row['colTown'] ?></td>
                <td><?= $row['delTown'] ?></td>
                <td><?= $row['colDate'] ?></td>
                <td><?= $row['driver'] === 'none' ? 'No Driver' : $row['driver'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                  <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>">
                    <input type="hidden" name="pID" value="<?= $row['jobNumber'] ?>">
                    <input type="submit" name="submit" class="btn btn-sm btn-primary btn-action" value="<?= $row['status'] === 'Delivered' ? 'Complete Job' : 'Info' ?>">
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>


  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>






    
   