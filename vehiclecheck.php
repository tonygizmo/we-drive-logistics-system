<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php';
session_start();
include 'connect.php';
// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$userID = $_SESSION['user_id'];

if (isset($_POST['submitCheck'])) {

    $vehicleReg = mysqli_real_escape_string($mysqli, $_POST['vehicle_reg']);
    $odometer = mysqli_real_escape_string($mysqli, $_POST['odometer']);
    $defectNotes = mysqli_real_escape_string($mysqli, $_POST['defect_notes']);

    $tyres = isset($_POST['tyres_ok']) ? 1 : 0;
    $lights = isset($_POST['lights_ok']) ? 1 : 0;
    $indicators = isset($_POST['indicators_ok']) ? 1 : 0;
    $brakes = isset($_POST['brakes_ok']) ? 1 : 0;
    $steering = isset($_POST['steering_ok']) ? 1 : 0;
    $mirrors = isset($_POST['mirrors_ok']) ? 1 : 0;
    $horn = isset($_POST['horn_ok']) ? 1 : 0;
    $windscreen = isset($_POST['windscreen_ok']) ? 1 : 0;
    $wipers = isset($_POST['wipers_ok']) ? 1 : 0;
    $washers = isset($_POST['washers_ok']) ? 1 : 0;
    $oil = isset($_POST['oil_ok']) ? 1 : 0;
    $coolant = isset($_POST['coolant_ok']) ? 1 : 0;
    $fuel = isset($_POST['fuel_ok']) ? 1 : 0;
    $bodywork = isset($_POST['bodywork_ok']) ? 1 : 0;
    $loadSecure = isset($_POST['load_secure_ok']) ? 1 : 0;

    $defectsFound = isset($_POST['defects_found']) ? 1 : 0;
    $vehicleSafe = isset($_POST['vehicle_safe_to_use']) ? 1 : 0;

    $sql = "INSERT INTO vehicle_checks (
                user_id,
                vehicle_reg,
                odometer,
                check_date,
                check_time,
                tyres_ok,
                lights_ok,
                indicators_ok,
                brakes_ok,
                steering_ok,
                mirrors_ok,
                horn_ok,
                windscreen_ok,
                wipers_ok,
                washers_ok,
                oil_ok,
                coolant_ok,
                fuel_ok,
                bodywork_ok,
                load_secure_ok,
                defects_found,
                defect_notes,
                vehicle_safe_to_use
            ) VALUES (
                '$userID',
                '$vehicleReg',
                '$odometer',
                CURDATE(),
                CURTIME(),
                '$tyres',
                '$lights',
                '$indicators',
                '$brakes',
                '$steering',
                '$mirrors',
                '$horn',
                '$windscreen',
                '$wipers',
                '$washers',
                '$oil',
                '$coolant',
                '$fuel',
                '$bodywork',
                '$loadSecure',
                '$defectsFound',
                '$defectNotes',
                '$vehicleSafe'
            )";
if (mysqli_query($mysqli, $sql)) {
    header("Location: vehiclecheck.php?success=1");
    exit();
} else {
    die("Database insert failed: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Check | We-Drive</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <?php else: ?>
        <li class="nav-item"><a class="nav-link text-white" href="userjobs.php">My Jobs</a></li>
        <?php endif; ?>
        <li class="nav-item mt-4"><a class="nav-link btn btn-warning text-dark" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

<div class="container mt-4 mb-5">

    <h2 class="mb-3">Daily Vehicle Check</h2>

    <?php if (isset($_GET['success'])) { ?>
        <div class="alert alert-success">
            Vehicle check submitted successfully.
        </div>
    <?php } ?>

    <div class="card mb-4">
        <div class="card-header">
            Submit New Vehicle Check
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Vehicle Registration</label>
                        <input type="text" name="vehicle_reg" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Odometer / Mileage</label>
                        <input type="number" name="odometer" class="form-control">
                    </div>
                </div>

                <h5 class="mt-4">Daily Checks</h5>

                <div class="row">

                    <?php
                    $checks = [
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
                        'oil_ok' => 'Oil Level',
                        'coolant_ok' => 'Coolant Level',
                        'fuel_ok' => 'Fuel',
                        'bodywork_ok' => 'Bodywork',
                        'load_secure_ok' => 'Load Secure'
                    ];

                    foreach ($checks as $name => $label) {
                        echo "
                        <div class='col-md-4 mb-2'>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='$name' id='$name'>
                                <label class='form-check-label' for='$name'>
                                    $label OK
                                </label>
                            </div>
                        </div>";
                    }
                    ?>

                </div>

                <hr>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="defects_found" id="defects_found">
                    <label class="form-check-label" for="defects_found">
                        Defects Found
                    </label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Defect Notes</label>
                    <textarea name="defect_notes" class="form-control" rows="4"></textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="vehicle_safe_to_use" id="vehicle_safe_to_use" checked>
                    <label class="form-check-label" for="vehicle_safe_to_use">
                        Vehicle Safe To Use
                    </label>
                </div>

                <button type="submit" name="submitCheck" class="btn btn-primary">
                    Submit Vehicle Check
                </button>

            </form>

        </div>
    </div>

    <h4 class="mb-3">Previous Vehicle Checks</h4>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vehicle</th>
                <th>Mileage</th>
                <th>Defects</th>
                <th>Safe</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $sql = "SELECT * FROM vehicle_checks WHERE user_id = '$userID' ORDER BY created_at DESC";
        $result = mysqli_query($mysqli, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['check_date'] . "</td>";
            echo "<td>" . $row['vehicle_reg'] . "</td>";
            echo "<td>" . $row['odometer'] . "</td>";
            echo "<td>" . ($row['defects_found'] ? 'Yes' : 'No') . "</td>";
            echo "<td>" . ($row['vehicle_safe_to_use'] ? 'Yes' : 'No') . "</td>";
            echo "</tr>";
        }
        ?>

        </tbody>
    </table>

</div>
</div>
</div>
</body>
</html>