<?php
require_once "connect.php";
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SESSION["admin"] == 1) {

    $sql = "SELECT vc.*, u.username
            FROM vehicle_checks vc
            LEFT JOIN users u ON vc.user_id = u.id
            WHERE vc.defects_found = 1
               OR vc.vehicle_safe_to_use = 0
               OR vc.tyres_ok = 0
               OR vc.lights_ok = 0
               OR vc.indicators_ok = 0
               OR vc.brakes_ok = 0
               OR vc.steering_ok = 0
               OR vc.mirrors_ok = 0
               OR vc.horn_ok = 0
               OR vc.windscreen_ok = 0
               OR vc.wipers_ok = 0
               OR vc.washers_ok = 0
               OR vc.oil_ok = 0
               OR vc.coolant_ok = 0
               OR vc.fuel_ok = 0
               OR vc.bodywork_ok = 0
               OR vc.load_secure_ok = 0
            ORDER BY vc.created_at DESC";

} else {

    $userID = $_SESSION['id'];

    $sql = "SELECT vc.*, u.username
            FROM vehicle_checks vc
            LEFT JOIN users u ON vc.user_id = u.id
            WHERE vc.user_id = '$userID'
            AND (
                vc.defects_found = 1
                OR vc.vehicle_safe_to_use = 0
                OR vc.tyres_ok = 0
                OR vc.lights_ok = 0
                OR vc.indicators_ok = 0
                OR vc.brakes_ok = 0
                OR vc.steering_ok = 0
                OR vc.mirrors_ok = 0
                OR vc.horn_ok = 0
                OR vc.windscreen_ok = 0
                OR vc.wipers_ok = 0
                OR vc.washers_ok = 0
                OR vc.oil_ok = 0
                OR vc.coolant_ok = 0
                OR vc.fuel_ok = 0
                OR vc.bodywork_ok = 0
                OR vc.load_secure_ok = 0
            )
            ORDER BY vc.created_at DESC";
}

$result = mysqli_query($mysqli, $sql);
$failedCount = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Failed Vehicle Checks | We-Drive</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
      .table th {
          white-space: nowrap;
      }
      .check-badge {
          font-size: 0.8rem;
      }
  </style>
</head>

<body>
<div class="container-fluid">
  <div class="row">

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

    <main class="col-md-10 ms-sm-auto px-4">

      <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
          <h1 class="h2 text-danger">Failed Vehicle Checks</h1>
          <p class="text-muted mb-0">Total Failed Checks: <?= $failedCount ?></p>
        </div>

        <a href="vehiclecheckdash.php" class="btn btn-secondary">Back to Vehicle Dashboard</a>
      </div>

      <div class="row mb-3">
        <div class="col-md-4">
          <div class="card border-danger shadow-sm">
            <div class="card-body text-center">
              <h5 class="card-title text-danger">Failed Checks</h5>
              <p class="display-5 mb-0"><?= $failedCount ?></p>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-header bg-danger text-white">
          Failed / Defect Vehicle Checks
        </div>

        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Driver</th>
                <th>Vehicle</th>
                <th>Mileage</th>
                <th>Defects</th>
                <th>Safe</th>
                <th>Failed Items</th>
                <th>Notes</th>
              </tr>
            </thead>

            <tbody>
              <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <?php
                $failedItems = [];

                $checkLabels = [
                    'tyres_ok' => 'Tyres',
                    'lights_ok' => 'Lights',
                    'indicators_ok' => 'Indicators',
                    'brakes_ok' => 'Brakes',
                    'steering_ok' => 'Steering',
                    'mirrors_ok' => 'Mirrors',
                    'horn_ok' => 'Horn',
                    'windscreen_ok' => 'Windscreen',
                    'wipers_ok' => 'Wipers',
                    'washers_ok' => 'Washers',
                    'oil_ok' => 'Oil',
                    'coolant_ok' => 'Coolant',
                    'fuel_ok' => 'Fuel',
                    'bodywork_ok' => 'Bodywork',
                    'load_secure_ok' => 'Load Secure'
                ];

                foreach ($checkLabels as $column => $label) {
                    if ($row[$column] == 0) {
                        $failedItems[] = $label;
                    }
                }
                ?>

                <tr class="table-danger">
                  <td><?= htmlspecialchars($row['check_date']); ?></td>
                  <td><?= htmlspecialchars($row['check_time']); ?></td>
                  <td><?= htmlspecialchars($row['username'] ?? 'Unknown'); ?></td>
                  <td><?= htmlspecialchars($row['vehicle_reg']); ?></td>
                  <td><?= htmlspecialchars($row['odometer']); ?></td>

                  <td>
                    <?php if ($row['defects_found'] == 1): ?>
                      <span class="badge bg-danger">Defect Found</span>
                    <?php else: ?>
                      <span class="badge bg-secondary">Item Failed</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?php if ($row['vehicle_safe_to_use'] == 1): ?>
                      <span class="badge bg-warning text-dark">Check Required</span>
                    <?php else: ?>
                      <span class="badge bg-danger">Unsafe</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?php if (count($failedItems) > 0): ?>
                      <?php foreach ($failedItems as $item): ?>
                        <span class="badge bg-danger check-badge mb-1"><?= htmlspecialchars($item); ?></span>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <span class="badge bg-warning text-dark">Defect Notes Only</span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <?= !empty($row['defect_notes']) 
                        ? nl2br(htmlspecialchars($row['defect_notes'])) 
                        : '<span class="text-muted">No notes</span>'; ?>
                  </td>
                </tr>

              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>

    </main>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>