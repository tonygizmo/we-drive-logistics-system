<?php
session_start();
unset($_SESSION['job']);

header("location: viewjob.php");
?>