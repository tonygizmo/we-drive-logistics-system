<?php
require_once "connect.php";
session_start();

// Check if logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Validate input
$jobNumber = $_POST['jobNumber'] ?? null;
$issue = $_POST['issueDescription'] ?? '';

if (!$jobNumber || trim($issue) === '') {
    die("Invalid input.");
}

// Update job: set flagged = 1 and append description
$stmt = $mysqli->prepare("
    UPDATE jobs 
    SET flagged = 1, description = CONCAT(IFNULL(description, ''), '\n[Flagged Issue] ', ?)
    WHERE jobNumber = ?
");
$stmt->bind_param("si", $issue, $jobNumber);
$stmt->execute();

header("Location: " . $_SERVER['HTTP_REFERER']); // go back to previous page
exit;
?>
