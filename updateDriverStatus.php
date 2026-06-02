<?php
require_once "connect.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobNumber = (int)$_POST['jobNumber'];
    $nextStatus = $_POST['nextStatus'];

    $stmt = $mysqli->prepare("UPDATE jobs SET driverStatus=? WHERE jobNumber=?");
    $stmt->bind_param("si", $nextStatus, $jobNumber);
    $stmt->execute();

    // Redirect back to job details
    header("Location: jobdetailsuser.php");
    exit;
}
?>
