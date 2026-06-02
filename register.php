<?php
require_once "config.php";
session_start();

// Redirect if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Define variables and initialize
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Process form data
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = trim($_POST["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Insert into DB if no errors
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO users (username, password, admin) VALUES (?, ?, 0)";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            if(mysqli_stmt_execute($stmt)){
                header("location: manageuser.php");
                exit;
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We-Drive | Add User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
      body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
      #sidebar { min-height: 100vh; }
      main { height: 90vh; overflow-y: hidden; }
      .card-body { height: 100%; overflow-y: auto; }
      .form-control.is-invalid { border-color: #dc3545; }
      .invalid-feedback { display: block; }
      .page-header { margin-bottom: 30px; }
  </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <nav id="sidebar" class="col-md-2 bg-dark text-white p-3">
      <h4 class="text-center">We-Drive</h4>
      <ul class="nav flex-column mt-4">
        <li class="nav-item"><a class="nav-link text-white" href="welcome.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="manageuser.php">Manage Users</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-warning text-dark mt-4" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-4">
      <div class="page-header">
        <h2>Add New User</h2>
        <div class="mb-3">
          <a href="manageusers.php" class="btn btn-danger">Back</a>
          <a href="welcome.php" class="btn btn-danger">Home</a>
        </div>
      </div>

      <div class="card shadow-sm mb-4" style="height: calc(90vh - 100px);">
        <div class="card-body">
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
              <label>Username</label>
              <input type="text" name="username" class="form-control <?= !empty($username_err) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($username) ?>">
              <div class="invalid-feedback"><?= $username_err ?></div>
            </div>

            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control <?= !empty($password_err) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($password) ?>">
              <div class="invalid-feedback"><?= $password_err ?></div>
            </div>

            <div class="mb-3">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control <?= !empty($confirm_password_err) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($confirm_password) ?>">
              <div class="invalid-feedback"><?= $confirm_password_err ?></div>
            </div>

            <div class="mb-3">
              <input type="submit" class="btn btn-primary" value="Submit">
              <input type="reset" class="btn btn-secondary" value="Reset">
            </div>
          </form>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
