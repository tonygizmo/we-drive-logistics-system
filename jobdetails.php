<?php
session_start();

// redirect if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
require_once "connect.php";

$jobNumber = $_SESSION['job'] ?? null;
if(!$jobNumber){
    header("location: viewjobs.php");
    exit;
}

// fetch job details
$sql = "SELECT * FROM jobs WHERE jobNumber = '$jobNumber'";
$result = mysqli_query($mysqli, $sql);
$job = mysqli_fetch_assoc($result);

if(!$job){
    echo "Job not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>We-Drive | Job Details</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">We-Drive</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav">
            <span class="navbar-toggler-icon"></span>
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

<section class="py-5" style="background-color: rgb(5, 5, 5);">
    <div class="container">
        <div class="card rounded-3 text-black mx-auto" style="max-width: 1100px;">
            <div class="row g-0">
                
                <!-- Collection Details -->
                <div class="col-lg-6 p-4">
                    <div class="text-center mb-4">
                        <img src="images/logo.png" style="width: 25%;" alt="logo">
                        <h2>Job Details</h2>
                    </div>

                    <h5>Collection Details</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><?= $job['driver'] ?: "Driver Unallocated" ?></li>
                        <li class="list-group-item"><?= $job['colAddressFirstName'] ?> <?= $job['colAddressLastName'] ?></li>
                        <li class="list-group-item"><?= $job['colAddressName'] ?>, <?= $job['colAddressStreet'] ?></li>
                        <li class="list-group-item"><?= $job['colTown'] ?></li>
                        <li class="list-group-item"><?= $job['colAddressCode'] ?></li>
                        <li class="list-group-item">Date: <?= $job['colDate'] ?></li>
                        <li class="list-group-item">Time: <?= number_format($job['colTime'], 2) ?></li>
                    </ul>
                </div>

                <!-- Delivery & Vehicle Details -->
                <div class="col-lg-6 p-4" style="background-color: rgb(99, 95, 95);">
                    <h5>Delivery Details</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><?= $job['delAddressFirstName'] ?> <?= $job['delAddressLastName'] ?></li>
                        <li class="list-group-item"><?= $job['delAddressName'] ?>, <?= $job['delAddressStreet'] ?></li>
                        <li class="list-group-item"><?= $job['delTown'] ?></li>
                        <li class="list-group-item"><?= $job['delAddressCode'] ?></li>
                    </ul>

                    <h5>Vehicle Details</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item"><?= $job['vehicleMake'] ?> <?= $job['vehicleModel'] ?></li>
                        <li class="list-group-item">VIN: <?= $job['vinNum'] ?></li>
                    </ul>

                    <div class="mt-3">
                        <a href="end.php" class="btn btn-danger">Back</a>
                        <?php if ($job['status'] === "Delivered"): ?>
                            <a href="completejob.php" class="btn btn-danger">Complete Job</a>
                        <?php elseif ($job['status'] === "Complete"): ?>
                            <a href="completejob.php" class="btn btn-danger">View Checklist</a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<footer class="text-center py-3 text-white" style="background-color: #222;">
    <p>&copy; <?= date('Y') ?> We-Drive. All rights reserved.</p>
</footer>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
