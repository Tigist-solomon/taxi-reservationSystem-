<?php
session_start();
if(isset($_SESSION['user_id']))  { header("Location: /taxi_reservation/app/views/dashboard.php"); exit(); }
if(isset($_SESSION['admin_id'])) { header("Location: /taxi_reservation/app/views/admindashboard.php"); exit(); }
$msg = '';
if(isset($_SESSION['message'])){ $msg = $_SESSION['message']; unset($_SESSION['message']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In — Taxi Reservation</title>
  <link rel="stylesheet" href="/taxi_reservation/public/assets/css/style.css">
</head>
<body class="auth-page">
<div class="auth-wrap">

  <!-- Hero -->
  <div class="auth-hero">
    <div class="hero-inner">
      <div class="hero-brand">
        <div class="hero-logo-mark">
          <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>
        <span class="hero-brand-name">Taxi<span>Reservation</span></span>
      </div>
      <h1 class="hero-tagline">Your city,<em>on demand.</em></h1>
      <p class="hero-sub">Fast, reliable taxi reservations across Addis Ababa. Fixed prices, no surprises.</p>
      <div class="hero-dots">
        <div class="hero-dot active"></div><div class="hero-dot"></div>
        <div class="hero-dot"></div><div class="hero-dot"></div>
      </div>
    </div>
  </div>

  <!-- Form -->
  <div class="auth-form-side">
    <span class="auth-form-brand">Taxi<span>Reservation</span></span>
    <h2 class="auth-title">Welcome back</h2>
    <p class="auth-sub">Sign in to your account</p>

    <?php if($msg): ?>
      <div class="alert alert-<?= strpos($msg,'✅')!==false?'success':'danger' ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form action="/taxi_reservation/app/controllers/loginController.php" method="POST">
      <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control" required>
          <option value="">Select role</option>
          <option value="user">Passenger</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div class="form-group">
        <label>Email address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <button type="submit" name="login" class="btn btn-success w-100">Sign in &rarr;</button>
    </form>

    <p class="auth-footer">Don't have an account? <a href="/taxi_reservation/app/views/register.php">Create one</a></p>
  </div>

</div>
</body>
</html>
