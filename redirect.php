<?
session_start(); 
$_SESSION['role']=$_GET['role'];
?>

<script>window.location.replace("viewjob.php");</script>