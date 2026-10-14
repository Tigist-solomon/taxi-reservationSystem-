<?php
if(session_status() == PHP_SESSION_NONE){ session_start(); }
if(!isset($_SESSION['user_id'])){ header("Location: /taxi_reservation/app/views/login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Taxi — Taxi Reservation</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
  <link rel="stylesheet" href="/taxi_reservation/public/assets/css/style.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="page-body wide">

  <div class="page-heading">
    <h1>Book a Taxi</h1>
    <p>All rides depart from Mexico Square, Addis Ababa</p>
  </div>

  <?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <div class="booking-layout">

    <!-- Form -->
    <div class="card">
      <div class="card-body">
        <form action="/taxi_reservation/app/controllers/bookingController.php" method="POST">

          <div class="form-group">
            <label>Pickup Location</label>
            <input id="pickup" name="pickup" type="text" class="form-control" value="Mexico" readonly>
            <div class="field-hint">📍 Fixed departure — Mexico Square</div>
          </div>

          <div class="form-group">
            <label>Destination</label>
            <select id="destination" name="dropoff" class="form-control" required>
              <option value="">Select destination</option>
              <option value="Piassa">Piassa</option>
              <option value="Bole">Bole</option>
              <option value="Megenagna">Megenagna</option>
              <option value="Kazanchis">Kazanchis</option>
              <option value="Sarbet">Sarbet</option>
              <option value="CMC">CMC</option>
            </select>
          </div>

          <!-- Price display -->
          <div id="price-box-wrap" style="display:none;">
            <div class="divider">Estimated Fare</div>
            <div class="price-box">
              <div class="amount" id="price-display">—</div>
              <div class="currency">Ethiopian Birr (ETB)</div>
            </div>
          </div>

          <input type="hidden" id="price" name="price">
          <div id="result"></div>

          <button type="submit" name="book" id="book-btn"
            class="btn btn-success w-100" style="display:none; margin-top:24px;">
            🚖 Confirm Booking
          </button>

        </form>
      </div>
    </div>

    <!-- Map -->
    <div>
      <div id="map"></div>
      <!-- Route info: distance + time shown here -->
      <div id="routeInfo"></div>
    </div>

  </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="/taxi_reservation/public/assets/js/script.js"></script>
<script>
  document.getElementById('destination').addEventListener('change', function(){
    const has = this.value !== '';
    document.getElementById('price-box-wrap').style.display = has ? 'block' : 'none';
    document.getElementById('book-btn').style.display       = has ? 'block' : 'none';
    if(!has) document.getElementById('routeInfo').style.display = 'none';
  });

  // Mirror hidden #price → styled display
  const priceInput   = document.getElementById('price');
  const priceDisplay = document.getElementById('price-display');
  let _pv = '';
  Object.defineProperty(priceInput, 'value', {
    get(){ return _pv; },
    set(v){ _pv = v; const r = v.replace(' Birr','').trim(); if(r) priceDisplay.textContent = r + ' ETB'; }
  });
</script>
</body>
</html>
