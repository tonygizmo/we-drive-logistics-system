<?php
require_once "connect.php";
session_start();

// Redirect if not logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Fetch users
$users = $mysqli->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>We-Drive | Manage Users</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <style>
      body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
      #sidebar { min-height: 100vh; }
      main { height: 90vh; overflow-y: hidden; }
      .card-body { height: 100%; overflow-y: auto; }
      .table thead { background-color: #343a40; color: white; position: sticky; top: 0; z-index: 10; }
      .page-header { margin-bottom: 30px; }
      .btn-action { min-width: 100px; }
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
        <li class="nav-item"><a class="nav-link text-white" href="viewjobs.php">Active Jobs</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-warning text-dark mt-4" href="reset-password.php">Reset Password</a></li>
        <li class="nav-item mt-2"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <main class="col-md-10 ms-sm-auto px-4">
      <div class="page-header">
        <h2>Manage Users</h2>
        <div class="mb-3">
          <a href="welcome.php" class="btn btn-danger">Home</a>
          <a href="register.php" class="btn btn-danger">Add User</a>
        </div>
      </div>

      <div class="card shadow-sm mb-4" style="height: calc(90vh - 100px);">
        <div class="card-body">
          <div class="table-responsive" style="max-height: 100%; overflow-y: auto;">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Access Level</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = $users->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['admin'] == 1 ? 'Admin' : 'Driver' ?></td>
                  </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
