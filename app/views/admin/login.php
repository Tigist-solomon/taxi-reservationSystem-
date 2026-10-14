<?php
session_start();
if(isset($_SESSION['admin_id'])){ header("Location: /taxi_reservation/app/views/admindashboard.php"); exit(); }
$error = '';
if(isset($_SESSION['admin_error'])){ $error = $_SESSION['admin_error']; unset($_SESSION['admin_error']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — RideAddis</title>
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
                <span class="hero-brand-name">Ride<span>Addis</span></span>
            </div>
            <h1 class="hero-tagline">Admin<em>Control Panel</em></h1>
            <p class="hero-sub">Restricted access. Authorised personnel only. Not accessible to regular passengers.</p>
            <div class="hero-dots">
                <div class="hero-dot active"></div><div class="hero-dot"></div><div class="hero-dot"></div>
            </div>
        </div>
    </div>

    <div class="auth-form-side">
        <span class="auth-form-brand">Ride<span>Addis</span></span>
        <h2 class="auth-title">Admin Sign In</h2>
        <p class="auth-sub">Enter your admin credentials to continue</p>

        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="/taxi_reservation/app/controllers/adminController.php" method="POST">
            <div class="form-group">
                <label>Admin Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@rideaddis.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="admin_login" class="btn btn-success w-100">Sign in as Admin &rarr;</button>
        </form>

        <p class="auth-footer" style="margin-top:28px;">
            <a href="/taxi_reservation/app/views/login.php" style="color:var(--ink-soft); font-weight:400; text-decoration:none;">
                &larr; Back to user login
            </a>
        </p>
    </div>

</div>
</body>
</html>
