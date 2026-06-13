<?php
session_start();
require 'connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$success = "";
$error = "";

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vehicle_reg = trim($_POST['vehicle_reg']);
    $odometer = !empty($_POST['odometer']) ? $_POST['odometer'] : null;

    $checks = [
        'tyres_ok',
        'lights_ok',
        'indicators_ok',
        'brakes_ok',
        'steering_ok',
        'mirrors_ok',
        'horn_ok',
        'windscreen_ok',
        'wipers_ok',
        'washers_ok',
        'oil_ok',
        'coolant_ok',
        'fuel_ok',
        'bodywork_ok',
        'load_secure_ok'
    ];

    $data = [];

    foreach ($checks as $check) {
        $data[$check] = isset($_POST[$check]) ? 1 : 0;
    }

    $defects_found = isset($_POST['defects_found']) ? 1 : 0;
    $defect_notes = trim($_POST['defect_notes']);
    $vehicle_safe_to_use = isset($_POST['vehicle_safe_to_use']) ? 1 : 0;

    if (empty($vehicle_reg)) {
        $error = "Vehicle registration is required.";
    } else {
        $stmt = $conn->prepare("
            INSERT INTO vehicle_checks (
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
                ?, ?, ?, CURDATE(), CURTIME(),
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->bind_param(
            "isiiiiiiiiiiiiiiiiiiisi",
            $user_id,
            $vehicle_reg,
            $odometer,
            $data['tyres_ok'],
            $data['lights_ok'],
            $data['indicators_ok'],
            $data['brakes_ok'],
            $data['steering_ok'],
            $data['mirrors_ok'],
            $data['horn_ok'],
            $data['windscreen_ok'],
            $data['wipers_ok'],
            $data['washers_ok'],
            $data['oil_ok'],
            $data['coolant_ok'],
            $data['fuel_ok'],
            $data['bodywork_ok'],
            $data['load_secure_ok'],
            $defects_found,
            $defect_notes,
            $vehicle_safe_to_use
        );

        if ($stmt->execute()) {
            $success = "Vehicle check submitted successfully.";
        } else {
            $error = "Error submitting vehicle check.";
        }
    }
}

// Pull user's previous checks
$stmt = $conn->prepare("
    SELECT *
    FROM vehicle_checks
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$previous_checks = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Check</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4">

    <h2>Daily Vehicle Check</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 mb-4">

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

        <h5>Vehicle Checks</h5>

        <div class="row">

            <?php
            $check_labels = [
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

            foreach ($check_labels as $name => $label):
            ?>

                <div class="col-md-4 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="<?= $name; ?>" id="<?= $name; ?>">
                        <label class="form-check-label" for="<?= $name; ?>">
                            <?= $label; ?> OK
                        </label>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

        <hr>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="defects_found" id="defects_found">
            <label class="form-check-label" for="defects_found">
                Defects found
            </label>
        </div>

        <div class="mb-3">
            <label class="form-label">Defect Notes</label>
            <textarea name="defect_notes" class="form-control" rows="4"></textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="vehicle_safe_to_use" id="vehicle_safe_to_use" checked>
            <label class="form-check-label" for="vehicle_safe_to_use">
                Vehicle safe to use
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            Submit Vehicle Check
        </button>

    </form>

    <h4>Previous Vehicle Checks</h4>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Vehicle</th>
                <th>Mileage</th>
                <th>Defects</th>
                <th>Safe to Use</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($check = $previous_checks->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($check['check_date']); ?></td>
                    <td><?= htmlspecialchars($check['vehicle_reg']); ?></td>
                    <td><?= htmlspecialchars($check['odometer']); ?></td>
                    <td>
                        <?= $check['defects_found'] ? 'Yes' : 'No'; ?>
                    </td>
                    <td>
                        <?= $check['vehicle_safe_to_use'] ? 'Yes' : 'No'; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</div>

</body>
</html>