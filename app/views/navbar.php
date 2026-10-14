<?php
$isAdmin = isset($_SESSION['admin_id']);
$isUser  = isset($_SESSION['user_id']);
$cur     = basename($_SERVER['PHP_SELF']);
$name    = $isAdmin ? ($_SESSION['admin_name'] ?? 'Admin') : ($_SESSION['fullname'] ?? '');
$parts   = explode(' ', trim($name));
$initials = strtoupper(substr($parts[0],0,1) . (isset($parts[1]) ? substr($parts[1],0,1) : ''));
?>
<nav class="navbar">

  <!-- Brand -->
  <a class="navbar-brand" href="<?= $isAdmin
      ? '/taxi_reservation/app/views/admindashboard.php'
      : '/taxi_reservation/app/views/dashboard.php' ?>">
    <div class="navbar-logo-mark">
      <svg viewBox="0 0 24 24"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
    </div>
    <span class="navbar-brand-name">Taxi<span>Reservation</span></span>
  </a>

  <!-- Links -->
  <div class="navbar-links">
    <?php if($isAdmin): ?>
      <a href="/taxi_reservation/app/views/admindashboard.php"
         class="navbar-link <?= $cur==='admindashboard.php'?'active':'' ?>">Dashboard</a>
    <?php elseif($isUser): ?>
      <a href="/taxi_reservation/app/views/dashboard.php"
         class="navbar-link <?= $cur==='dashboard.php'?'active':'' ?>">Home</a>
      <a href="/taxi_reservation/app/views/booking.php"
         class="navbar-link <?= $cur==='booking.php'?'active':'' ?>">Book a Taxi</a>
      <a href="/taxi_reservation/app/views/history.php"
         class="navbar-link <?= $cur==='history.php'?'active':'' ?>">My Bookings</a>
    <?php endif; ?>
  </div>

  <!-- Right -->
  <div class="navbar-right">
    <?php if($isAdmin || $isUser): ?>
      <div style="display:flex;align-items:center;gap:8px;">
        <div class="navbar-avatar"><?= $initials ?></div>
        <span class="navbar-username"><?= htmlspecialchars($name) ?></span>
      </div>
      <?php if($isAdmin): ?><span class="admin-pill">Admin</span><?php endif; ?>
      <div class="navbar-divider"></div>
      <a href="/taxi_reservation/app/controllers/logout.php" class="btn-nav btn-nav-danger">Logout</a>
    <?php else: ?>
      <a href="/taxi_reservation/app/views/login.php"    class="btn-nav btn-nav-ghost">Sign In</a>
      <a href="/taxi_reservation/app/views/register.php" class="btn-nav btn-nav-primary">Register</a>
    <?php endif; ?>
  </div>

</nav>
