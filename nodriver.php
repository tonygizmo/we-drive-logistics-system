<?php
require_once "connect.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Handle job selection
if(isset($_POST['submit'])){
    $_SESSION['job'] = htmlentities($_POST['pID']);
    header('location: jobdetails.php');
    exit;
}

// Fetch unallocated jobs
$jobs = $mysqli->query("SELECT * FROM jobs WHERE driver LIKE 'none'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We-Drive | Unallocated Jobs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
      body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
      #sidebar { min-height: 100vh; }
      .table thead { background-color: #343a40; color: white; }
      .btn-action { min-width: 100px; }
      .dashboard-card { border-radius: 12px; }
      .page-header { margin-bottom: 30px; }
      main { height: 90vh; overflow-y: hidden; }
      .card-body { height: 100%; overflow-y: auto; }
      thead { position: sticky; top: 0; z-index: 10; }
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
        <li class="nav-item mt-2"><a class="nav-link btn btn-warning text-dark mt-4" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-4">
      <div class="page-header">
        <h2>Unallocated Jobs</h2>
        <div class="mb-3">
          <a href="welcome.php" class="btn btn-danger">Home</a>
          <a href="viewjobs.php" class="btn btn-danger">Back</a>
        </div>
      </div>

      <div class="card shadow-sm mb-4" style="height: calc(90vh - 100px);">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-dark">
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
  <?php while ($job = $jobs->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($job['jobNumber']) ?></td>
      <td><?= htmlspecialchars($job['colTown']) ?></td>
      <td><?= htmlspecialchars($job['delTown']) ?></td>
      <td><?= htmlspecialchars($job['colDate']) ?></td>
      <td>No Driver</td>
      <td>Booked</td>
      <td>
        <select class="form-select form-select-lg mb-3"
                onchange="setDriver(this.value, '<?= $job['jobNumber'] ?>')">
          <option selected disabled>Allocate Driver</option>
          <?php
          $sql = "SELECT username FROM users";
          $result = mysqli_query($mysqli, $sql);
          while ($user = mysqli_fetch_assoc($result)) {
            echo "<option value='{$user['username']}'>{$user['username']}</option>";
          }
          ?>
        </select>
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

<script>
function setDriver(username, jobNumber) {
  if (!username || !jobNumber) return;

  fetch('update_driver.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'username=' + encodeURIComponent(username) + '&jobNumber=' + encodeURIComponent(jobNumber)
  })
  .then(response => response.text())
  .then(data => {
    alert(data); // optional: show success or error
  })
  .catch(err => console.error(err));
}
</script>

