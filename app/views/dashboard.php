<?php
include '../models/Booking.php';
include '../core/database.php';

if(session_status() == PHP_SESSION_NONE){ session_start(); }
if(!isset($_SESSION['user_id'])){ header("Location: /taxi_reservation/app/views/login.php"); exit(); }

$destinations = ["Piassa", "Bole", "Megenagna", "Kazanchis", "Sarbet", "CMC"];

$availableRows = getAvailableTaxis();

// Build a lookup: destination => count of available taxis
$counts = [];
foreach($availableRows as $row){
    $counts[$row['destination']] = (int)$row['total'];
}

$parts = explode(' ', trim($_SESSION['fullname']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Taxi Reservation</title>
  <link rel="stylesheet" href="/taxi_reservation/public/assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-body">

  <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <!-- Welcome banner -->
  <div class="welcome-banner">
    <div class="welcome-text">
      <h2>Welcome, <?= htmlspecialchars($parts[0]) ?> 👋</h2>
      <p>Manage your taxi reservations easily</p>
    </div>
  </div>

  <!-- Quick-action cards -->
  <div class="dash-grid">
    <div class="dash-card">
      <div class="dash-icon gold">🚖</div>
      <div class="dash-card-title">Book a Taxi</div>
      <div class="dash-card-desc">Request a new ride from Mexico Square</div>
      <a href="/taxi_reservation/app/views/booking.php" class="btn btn-success btn-sm">Book Now &rarr;</a>
    </div>
    <div class="dash-card">
      <div class="dash-icon blue">📋</div>
      <div class="dash-card-title">My Bookings</div>
      <div class="dash-card-desc">View and manage your ride history</div>
      <a href="/taxi_reservation/app/views/history.php" class="btn btn-info btn-sm">View History &rarr;</a>
    </div>
  </div>

  <!-- Available Taxis -->
  <div class="card" style="margin-top:24px;">
    <div class="card-header">
      <span class="card-title">🚕 Available Taxis</span>
    </div>
    <div class="card-body" style="padding:0 !important;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Destination</th>
            <th>Status</th>
            <th style="text-align:right;">Available Taxis</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($destinations as $dest):
                  $count = isset($counts[$dest]) ? $counts[$dest] : 0;
          ?>
          <tr>
            <td>
              <span class="avail-loc-name">📍 <?= htmlspecialchars($dest) ?></span>
            </td>
            <td>
              <?php if($count > 0): ?>
                <span class="avail-status-badge">Available</span>
              <?php else: ?>
                <span class="avail-status-badge avail-status-none">Unavailable</span>
              <?php endif; ?>
            </td>
            <td style="text-align:right;">
              <span class="avail-count-pill"><?= $count ?></span>
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
