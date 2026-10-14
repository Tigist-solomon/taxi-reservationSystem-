<?php
if(session_status() == PHP_SESSION_NONE){ session_start(); }
if(!isset($_SESSION['admin_id'])){ header("Location: /taxi_reservation/app/views/login.php"); exit(); }
include '../models/Admin.php';

// Stats computed from all bookings
$all = getAllBookings();
$total = $active = $waiting = $completed = 0;
while($row = mysqli_fetch_assoc($all)){
    $total++;
    if($row['status'] === 'active')         $active++;
    elseif($row['status'] === 'waiting')    $waiting++;
    elseif($row['status'] === 'completed')  $completed++;
}

// Table only shows bookings that aren't completed yet
$result = getActiveBookings();
$rows = [];
while($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — Taxi Reservation</title>
  <link rel="stylesheet" href="/taxi_reservation/public/assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-body wide">

  <div class="page-heading">
    <h1>Admin Dashboard</h1>
    <p>Overview of all taxi reservations</p>
  </div>

  <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <!-- Stats -->
  <div class="admin-stat-row">
    <div class="stat-box">
      <div class="number"><?= $total ?></div>
      <div class="label">Total Bookings</div>
    </div>
    <div class="stat-box">
      <div class="number" style="color:var(--gold)"><?= $active ?></div>
      <div class="label">Active</div>
    </div>
    <div class="stat-box">
      <div class="number" style="color:var(--warn)"><?= $waiting ?></div>
      <div class="label">Waiting</div>
    </div>
    <div class="stat-box">
      <div class="number" style="color:var(--ink-soft)"><?= $completed ?></div>
      <div class="label">Completed</div>
    </div>
  </div>

  <!-- Table -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">Active Reservations</span>
    </div>
    <div style="overflow-x:auto;">
      <table class="table">
        <thead>
          <tr>
            <th>ID</th><th>User</th><th>Route</th><th>Status</th><th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($rows)): ?>
            <tr><td colspan="5" style="text-align:center;color:var(--ink-soft);padding:40px;">No active bookings.</td></tr>
          <?php endif; ?>
          <?php foreach($rows as $row): ?>
          <tr>
            <td style="color:var(--ink-soft);font-size:12px;">#<?= (int)$row['id'] ?></td>
            <td>
              <div class="user-chip">
                <div class="chip-avatar">U<?= (int)$row['user_id'] ?></div>
                User <?= (int)$row['user_id'] ?>
              </div>
            </td>
            <td>
              <strong><?= htmlspecialchars($row['pickup_location']) ?></strong>
              <span class="route-arrow">&rarr;</span>
              <?= htmlspecialchars($row['dropoff_location']) ?>
            </td>
            <td>
              <?php $s=$row['status']; $bc=($s==='active')?'bg-primary':'bg-warning'; ?>
              <span class="badge <?= $bc ?>"><?= ucfirst(htmlspecialchars($s)) ?></span>
            </td>
            <td>
              <a href="/taxi_reservation/app/controllers/bookingController.php?complete_id=<?= (int)$row['id'] ?>"
                 class="btn btn-complete btn-sm">Complete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
</body>
</html>
