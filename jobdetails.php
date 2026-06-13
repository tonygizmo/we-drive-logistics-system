<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "connect.php";

$jobNumber = $_SESSION['job'] ?? null;

if (!$jobNumber) {
    header("location: viewjob.php");
    exit;
}

$sql = "SELECT * FROM jobs WHERE jobNumber = '$jobNumber'";
$result = mysqli_query($mysqli, $sql);
$job = mysqli_fetch_assoc($result);

if (!$job) {
    echo "Job not found.";
    exit;
}

$status = $job['status'] ?? '';
$driver = !empty($job['driver']) ? $job['driver'] : "Driver Unallocated";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Details | We-Drive</title>
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
        .detail-label {
            font-weight: 600;
            color: #555;
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
                <li class="nav-item">
                    <a class="nav-link text-white" href="welcome.php">Dashboard</a>
                </li>

                <?php if ($_SESSION["admin"] == 1): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="manageuser.php">Manage Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="addjob.php">Add Job</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="viewjob.php">View Jobs</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="userjobs.php">My Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="vehiclecheck.php">Vehicle Check</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item mt-4">
                    <a class="nav-link btn btn-warning text-dark" href="reset-password.php">Reset Password</a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link btn btn-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="col-md-10 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 mb-1">Job Details</h1>
                    <p class="text-muted mb-0">Job Number: <?= htmlspecialchars($job['jobNumber']); ?></p>
                </div>

                <div>
                    <span class="badge bg-primary fs-6">
                        <?= htmlspecialchars($status); ?>
                    </span>
                </div>
            </div>

            <div class="row g-4">

                <!-- Collection Details -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-dark text-white">
                            Collection Details
                        </div>

                        <div class="card-body">
                            <p><span class="detail-label">Driver:</span> <?= htmlspecialchars($driver); ?></p>
                            <p><span class="detail-label">Name:</span> <?= htmlspecialchars($job['colAddressFirstName'] . ' ' . $job['colAddressLastName']); ?></p>
                            <p><span class="detail-label">Address:</span><br>
                                <?= htmlspecialchars($job['colAddressName']); ?><br>
                                <?= htmlspecialchars($job['colAddressStreet']); ?><br>
                                <?= htmlspecialchars($job['colTown']); ?><br>
                                <?= htmlspecialchars($job['colAddressCode']); ?>
                            </p>
                            <p><span class="detail-label">Date:</span> <?= htmlspecialchars($job['colDate']); ?></p>
                            <p><span class="detail-label">Time:</span> <?= htmlspecialchars(number_format($job['colTime'], 2)); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Delivery Details -->
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-dark text-white">
                            Delivery Details
                        </div>

                        <div class="card-body">
                            <p><span class="detail-label">Name:</span> <?= htmlspecialchars($job['delAddressFirstName'] . ' ' . $job['delAddressLastName']); ?></p>
                            <p><span class="detail-label">Address:</span><br>
                                <?= htmlspecialchars($job['delAddressName']); ?><br>
                                <?= htmlspecialchars($job['delAddressStreet']); ?><br>
                                <?= htmlspecialchars($job['delTown']); ?><br>
                                <?= htmlspecialchars($job['delAddressCode']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Vehicle Details -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            Vehicle Details
                        </div>

                        <div class="card-body">
                            <p><span class="detail-label">Vehicle:</span> <?= htmlspecialchars($job['vehicleMake'] . ' ' . $job['vehicleModel']); ?></p>
                            <p><span class="detail-label">VIN:</span> <?= htmlspecialchars($job['vinNum']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Job Actions -->
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-dark text-white">
                            Job Actions
                        </div>

                        <div class="card-body">
                            <a href="end.php" class="btn btn-secondary mb-2">Back</a>

                            <?php if ($job['status'] === "Delivered"): ?>
                                <a href="completejob.php" class="btn btn-danger mb-2">Complete Job</a>
                            <?php elseif ($job['status'] === "Complete"): ?>
                                <a href="completejob.php" class="btn btn-outline-primary mb-2">View Checklist</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

</body>
</html>