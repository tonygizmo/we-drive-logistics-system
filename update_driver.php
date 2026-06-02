<?php
require_once "config.php";
require_once "connect.php";
session_start(); // start session for flash message

if (isset($_POST['username'], $_POST['jobNumber'])) {
    $username = $_POST['username'];
    $jobNumber = $_POST['jobNumber'];

    $stmt = $mysqli->prepare("UPDATE jobs SET driver = ? WHERE jobNumber = ?");
    $stmt->bind_param('ss', $username, $jobNumber);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Driver updated successfully!";
        header("Location: unallocated_drivers.php"); // redirect back to table page
        exit;
    } else {
        $_SESSION['error_message'] = "Error updating driver.";
        header("Location: unallocated_drivers.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid input.";
    header("Location: unallocated_drivers.php");
    exit;
}
?>

