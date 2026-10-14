<?php
if(session_status() == PHP_SESSION_NONE){ session_start(); }
if(!isset($_SESSION['user_id'])){ 
  header("Location: /taxi_reservation/app/views/login.php"); exit(); }
include '../controllers/bookingHistoryController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Bookings — Taxi Reservation</title>
  <link rel="stylesheet" href="/taxi_reservation/public/assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-body">

  <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:26px;">
    <div class="page-heading" style="margin-bottom:0;">
      <h1>My Bookings</h1>
      <p>Your complete ride history</p>
    </div>
    <a href="/taxi_reservation/app/views/booking.php" class="btn btn-success btn-sm">+ New Booking</a>
  </div>

  <div class="card">
    <?php if(mysqli_num_rows($bookings) === 0): ?>
      <div style="text-align:center; padding:60px 24px; color:var(--ink-soft);">
        <div style="font-size:40px; margin-bottom:12px;">🚖</div>
        <p style="font-weight:600; color:var(--ink); margin-bottom:6px;">No bookings yet</p>
        <p style="font-size:13px;">Book your first taxi to get started.</p>
        <a href="/taxi_reservation/app/views/booking.php" class="btn btn-success btn-sm" style="margin-top:16px;">Book a Taxi &rarr;</a>
      </div>
    <?php else: ?>
    <table class="table">
      <thead>
        <tr>
          <th>Pickup</th>
          <th>Destination</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($bookings)): ?>
        <tr>
          <td><?= htmlspecialchars($row['pickup_location']) ?></td>
          <td><?= htmlspecialchars($row['dropoff_location']) ?></td>
          <td style="color:var(--ink-soft);"><?= htmlspecialchars($row['booking_date']) ?></td>
          <td>
            <!-- Direct delete — no confirm popup, no URL-not-found -->
            <form method="GET" action="/taxi_reservation/app/controllers/deleteBookingController.php" style="display:inline;">
              <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

</div>
</body>
</html>
