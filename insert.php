<?php
session_start();
require_once "connect.php";



if(isset($_POST['submit'])) {    

    // --- Delivery ---
    $delname   = $_POST['delAddName'] ?? '';
    $delstreet = $_POST['delAddStreet'] ?? '';
    $deltown   = $_POST['delAddTown'] ?? '';
    $delcode   = $_POST['delAddCode'] ?? '';
    $delfname  = $_POST['delAddfName'] ?? '';
    $dellname  = $_POST['delAddlName'] ?? '';

    // --- Collection ---
    $colname   = $_POST['colAddName'] ?? '';
    $colstreet = $_POST['colAddStreet'] ?? '';
    $coltown   = $_POST['colAddTown'] ?? '';
    $colcode   = $_POST['colAddCode'] ?? '';
    $colfname  = $_POST['colAddfName'] ?? '';
    $collname  = $_POST['colAddlName'] ?? '';

    // --- Vehicle ---
    $make  = $_POST['vehicleMake'] ?? '';
    $model = $_POST['vehicleModel'] ?? '';
    $vin   = $_POST['vin'] ?? '';
    $driver = "none"; // default

    // --- Date & Time Validation ---
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        die("Invalid date format. Please use YYYY-MM-DD.");
    }
    if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        die("Invalid time format. Please use HH:MM (24-hour).");
    }

    // --- Prepare and Execute SQL (Safe) ---
    $stmt = $mysqli->prepare("
        INSERT INTO jobs (
            delAddressName, delAddressStreet, delTown, delAddressCode, delAddressFirstName, delAddressLastName,
            colAddressName, colAddressStreet, colTown, colAddressCode, colAddressFirstName, colAddressLastName,
            vehicleMake, vehicleModel, vinNum, driver, colDate, colTime
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssssssssssssss",
        $delname, $delstreet, $deltown, $delcode, $delfname, $dellname,
        $colname, $colstreet, $coltown, $colcode, $colfname, $collname,
        $make, $model, $vin, $driver, $date, $time
    );

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>New Job has been added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($stmt->error) . "</div>";
    }

    $stmt->close();
    $mysqli->close();
}
?>
