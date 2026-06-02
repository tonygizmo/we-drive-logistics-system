<?php
require_once "connect.php";
session_start();

// Redirect if not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Handle Info button submission
if (isset($_POST['infoSubmit'])) {
    $_SESSION['job'] = htmlentities($_POST['pID']);
    header('location: jobdetailsuser.php');
    exit;
}

// Get current driver username
$driver = $_SESSION['username'];

// Fetch this user's active (incomplete) jobs
$stmt = $mysqli->prepare("SELECT * FROM jobs WHERE complete = 0 AND driver = ?");
$stmt->bind_param("s", $driver);
$stmt->execute();
$jobs = $stmt->get_result();
?>

<?php
// Start output buffering for layout
ob_start();
$pageTitle = "My Jobs";
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="row">
<?php while ($job = $jobs->fetch_assoc()): 
    // Merge Collection Address and Contact
    $collectionAddress = implode('<br>', array_filter([
        $job['colAddressStreet'] ?? '',
        $job['colTown'] ?? '',
        $job['colAddressCode'] ?? ''
    ]));

    $collectionContact = implode(' ', array_filter([
        $job['colAddressFirstName'] ?? '',
        $job['colAddressLastName'] ?? ''
    ]));

    // Merge Delivery Address and Contact
    $deliveryAddress = implode('<br>', array_filter([
        $job['delAddressStreet'] ?? '',
        $job['delTown'] ?? '',
        $job['delAddressCode'] ?? ''
    ]));

    $deliveryContact = implode(' ', array_filter([
        $job['delAddressFirstName'] ?? '',
        $job['delAddressLastName'] ?? ''
    ]));
?>
<div class="col-12 mb-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-2">
                Job #<?= $job['jobNumber'] ?>
                <?php if ($job['flagged'] == 1): ?>
                    <i class="bi bi-exclamation-triangle-fill text-warning" title="This job has been flagged"></i>
                <?php endif; ?>
            </h5>

            <!-- Collection Section -->
            <div class="d-flex justify-content-between align-items-center">
                <p class="card-text mb-1"><strong>Collection Town:</strong> <?= htmlspecialchars($job['colTown']) ?></p>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#colDetails<?= $job['jobNumber'] ?>" aria-expanded="false" aria-controls="colDetails<?= $job['jobNumber'] ?>">
                    &#9656;
                </button>
            </div>
            <div class="collapse" id="colDetails<?= $job['jobNumber'] ?>">
                <p class="card-text mb-1"><strong>Address:</strong><br><?= $collectionAddress ?: 'N/A' ?></p>
                <p class="card-text mb-1"><strong>Contact:</strong> <?= $collectionContact ?: 'N/A' ?></p>
                <p class="card-text mb-1"><strong>Instructions:</strong> <?= nl2br(htmlspecialchars($job['colInstructions'] ?? '')) ?></p>
            </div>

            <!-- Delivery Section -->
            <div class="d-flex justify-content-between align-items-center mt-2">
                <p class="card-text mb-1"><strong>Delivery Town:</strong> <?= htmlspecialchars($job['delTown']) ?></p>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#delDetails<?= $job['jobNumber'] ?>" aria-expanded="false" aria-controls="delDetails<?= $job['jobNumber'] ?>">
                    &#9656;
                </button>
            </div>
            <div class="collapse" id="delDetails<?= $job['jobNumber'] ?>">
                <p class="card-text mb-1"><strong>Address:</strong><br><?= $deliveryAddress ?: 'N/A' ?></p>
                <p class="card-text mb-1"><strong>Contact:</strong> <?= $deliveryContact ?: 'N/A' ?></p>
                <p class="card-text mb-1"><strong>Instructions:</strong> <?= nl2br(htmlspecialchars($job['delInstructions'] ?? '')) ?></p>
            </div>

            <p class="card-text mt-2"><strong>Collection Date:</strong> <?= $job['colDate'] ?></p>

            <!-- Flag Job / Flagged Icon -->
            <?php if ($job['flagged'] != 1): ?>
                <form method="POST" action="flagjob.php" class="d-inline mt-2" onsubmit="return flagJob(this);">
                    <input type="hidden" name="jobNumber" value="<?= $job['jobNumber'] ?>">
                    <input type="hidden" name="issueDescription" class="issueDescription">
                    <button type="submit" class="btn btn-sm btn-warning w-100">Flag Job</button>
                </form>
            <?php else: ?>
                <div class="d-flex align-items-center mt-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                    <span class="text-warning fw-bold">Flagged</span>
                </div>
            <?php endif; ?>

            <!-- Info Button (posts to jobdetailsuser.php) -->
            <form method="POST" action="<?= $_SERVER['PHP_SELF']; ?>" class="mt-2">
                <input type="hidden" name="pID" value="<?= $job['jobNumber'] ?>">
                <input type="submit" name="infoSubmit" class="btn btn-sm btn-primary w-100" value="Info">
            </form>
        </div>
    </div>
</div>
<?php endwhile; ?>
</div>

<script>
function flagJob(form) {
    let issue = prompt("Please describe the issue for this job:");
    if (issue === null || issue.trim() === "") return false;
    form.querySelector('.issueDescription').value = issue.trim();
    return true;
}
</script>

<?php
$content = ob_get_clean();
require "layout.php";
?>
