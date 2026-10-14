<?php
session_start();
if(isset($_SESSION['user_id'])) { header("Location: /taxi_reservation/app/views/dashboard.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register — Taxi Reservation</title>
  <link rel="stylesheet" href="/taxi_reservation/public/assets/css/style.css">
</head>
<body class="auth-page">
<div class="auth-wrap">

  <div class="auth-hero">
    <div class="hero-inner">
      <div class="hero-brand">
        <div class="hero-logo-mark">
          <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
        </div>
        <span class="hero-brand-name">Taxi<span>Reservation</span></span>
      </div>
      <h1 class="hero-tagline">Join thousands<em>of daily riders.</em></h1>
      <p class="hero-sub">Create your account and start booking rides in minutes. Fixed fares, no hidden costs.</p>
      <div class="hero-dots">
        <div class="hero-dot active"></div><div class="hero-dot"></div>
        <div class="hero-dot"></div><div class="hero-dot"></div>
      </div>
    </div>
  </div>

  <div class="auth-form-side">
    <a href="/taxi_reservation/app/views/login.php" class="auth-form-brand">Taxi<span>Reservation</span></a>
    <h2 class="auth-title">Create account</h2>
    <p class="auth-sub">It only takes a moment</p>

    <?php if(isset($_SESSION['message'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['message']) ?></div>
      <?php unset($_SESSION['message']); ?>
    <?php endif; ?>

    <form action="/taxi_reservation/app/controllers/registerController.php" method="POST">
      <div class="form-group">
        <label>Full name</label>
        <input type="text" name="fullname" class="form-control" placeholder="Abebe Girma" required>
      </div>
      <div class="form-group">
        <label>Email address</label>
        <input type="email" name="email" class="form-control" placeholder="you@gmail.com" required>
        <?php if(isset($_SESSION['errors']['email'])): ?>
          <div class="field-error"><?= htmlspecialchars($_SESSION['errors']['email']) ?></div>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min 8 chars, upper, number, symbol" required>
        <?php if(isset($_SESSION['errors']['password'])): ?>
          <div class="field-error"><?= htmlspecialchars($_SESSION['errors']['password']) ?></div>
        <?php endif; ?>
      </div>
      <button type="submit" class="btn btn-success w-100">Create account &rarr;</button>
    </form>
    <p class="auth-footer">Already have an account? <a href="/taxi_reservation/app/views/login.php">Sign in</a></p>
  </div>

</div>
<?php unset($_SESSION['errors']); ?>
</body>
</html>
