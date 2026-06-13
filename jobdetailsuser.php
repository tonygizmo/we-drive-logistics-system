<?php
require_once "connect.php";
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if (isset($_POST['submit'])) {
    $_SESSION['job'] = htmlentities($_POST['pID']);
    header('location: myjobs.php');
    exit;
}

if (!isset($_SESSION['job'])) {
    header("location: driverjobs.php");
    exit;
}

$jobNumber = (int)$_SESSION['job'];

$stmt = $mysqli->prepare("SELECT * FROM jobs WHERE jobNumber = ?");
$stmt->bind_param("i", $jobNumber);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    die("Job not found.");
}

$pageTitle = "Job #{$job['jobNumber']} Details";

$collectionAddress = implode('<br>', array_filter([
    $job['colAddressStreet'] ?? '',
    $job['colTown'] ?? '',
    $job['colAddressCode'] ?? ''
]));

$collectionContact = implode(' ', array_filter([
    $job['colAddressFirstName'] ?? '',
    $job['colAddressLastName'] ?? ''
]));

$deliveryAddress = implode('<br>', array_filter([
    $job['delAddressStreet'] ?? '',
    $job['delTown'] ?? '',
    $job['delAddressCode'] ?? ''
]));

$deliveryContact = implode(' ', array_filter([
    $job['delAddressFirstName'] ?? '',
    $job['delAddressLastName'] ?? ''
]));

ob_start();
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="card-title mb-3">
                    Job #<?= htmlspecialchars($job['jobNumber']); ?>

                    <?php if ($job['flagged'] == 1): ?>
                        <i class="bi bi-exclamation-triangle-fill text-warning" title="This job has been flagged"></i>
                    <?php endif; ?>
                </h5>

                <div class="d-flex justify-content-between align-items-center">
                    <p class="card-text mb-1">
                        <strong>Collection Town:</strong> <?= htmlspecialchars($job['colTown']); ?>
                    </p>

                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colDetails">
                        &#9656;
                    </button>
                </div>

                <div class="collapse" id="colDetails">
                    <p class="card-text mb-1">
                        <strong>Address:</strong><br><?= $collectionAddress ?: 'N/A'; ?>
                    </p>
                    <p class="card-text mb-1">
                        <strong>Contact:</strong> <?= htmlspecialchars($collectionContact ?: 'N/A'); ?>
                    </p>
                    <p class="card-text mb-1">
                        <strong>Instructions:</strong> <?= nl2br(htmlspecialchars($job['colInstructions'] ?? '')); ?>
                    </p>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <p class="card-text mb-1">
                        <strong>Delivery Town:</strong> <?= htmlspecialchars($job['delTown']); ?>
                    </p>

                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#delDetails">
                        &#9656;
                    </button>
                </div>

                <div class="collapse" id="delDetails">
                    <p class="card-text mb-1">
                        <strong>Address:</strong><br><?= $deliveryAddress ?: 'N/A'; ?>
                    </p>
                    <p class="card-text mb-1">
                        <strong>Contact:</strong> <?= htmlspecialchars($deliveryContact ?: 'N/A'); ?>
                    </p>
                    <p class="card-text mb-1">
                        <strong>Instructions:</strong> <?= nl2br(htmlspecialchars($job['delInstructions'] ?? '')); ?>
                    </p>
                </div>

                <p class="card-text mt-3">
                    <strong>Collection Date:</strong> <?= htmlspecialchars($job['colDate']); ?>
                </p>

                <?php
                $buttonLabel = '';
                $nextStatus = '';
                $showButton = true;

                switch ($job['driverStatus']) {
                    case 'not_started':
                        $buttonLabel = 'En Route to Collection';
                        $nextStatus = 'en_route_collection';
                        break;
                    case 'en_route_collection':
                        $buttonLabel = 'Arrived at Collection';
                        $nextStatus = 'arrived_collection';
                        break;
                    case 'arrived_collection':
                        $buttonLabel = 'En Route to Delivery';
                        $nextStatus = 'en_route_delivery';
                        break;
                    case 'en_route_delivery':
                        $buttonLabel = 'Arrived at Delivery';
                        $nextStatus = 'arrived_delivery';
                        break;
                    case 'arrived_delivery':
                        $buttonLabel = 'Mark as Delivered';
                        $nextStatus = 'completed';
                        break;
                    case 'completed':
                    default:
                        $showButton = false;
                        break;
                }
                ?>

                <?php if ($showButton): ?>
                    <form method="POST" action="updateDriverStatus.php">
                        <input type="hidden" name="jobNumber" value="<?= htmlspecialchars($job['jobNumber']); ?>">
                        <input type="hidden" name="nextStatus" value="<?= htmlspecialchars($nextStatus); ?>">
                        <button type="submit" class="btn btn-success w-100 mt-3">
                            <?= htmlspecialchars($buttonLabel); ?>
                        </button>
                    </form>
                <?php endif; ?>

                <a href="myjobs.php" class="btn btn-secondary w-100 mt-2">Back to My Jobs</a>

                <?php if ($job['flagged'] != 1): ?>
                    <form method="POST" action="flagjob.php" class="d-inline" onsubmit="return flagJob(this);">
                        <input type="hidden" name="jobNumber" value="<?= htmlspecialchars($job['jobNumber']); ?>">
                        <input type="hidden" name="issueDescription" class="issueDescription">
                        <button type="submit" class="btn btn-sm btn-warning w-100 mt-2">Flag Job</button>
                    </form>
                <?php else: ?>
                    <div class="d-flex align-items-center mt-2">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                        <span class="text-warning fw-bold">Flagged</span>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<script>
function flagJob(form) {
    let issue = prompt("Please describe the issue for this job:");
    if (issue === null || issue.trim() === "") {
        return false;
    }
    form.querySelector('.issueDescription').value = issue.trim();
    return true;
}
</script>

<?php
$content = ob_get_clean();
require "layout.php";
?>