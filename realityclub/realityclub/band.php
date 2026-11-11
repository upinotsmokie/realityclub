<?php require_once __DIR__ . '/includes/helpers.php'; $pdo = db(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>The Band — Reality Club</title>
<meta name="description" content="Kenali personel Reality Club dan perjalanan band sejak 2016.">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/pages.css">
</head>
<body>

<div class="loader"><div class="loader-mark">REALITY&nbsp;CLUB</div><div class="loader-bar"></div></div>

<nav class="navbar">
  <div class="nav-inner">
    <a href="index.php" class="brand"><span class="brand-mark"></span>Reality Club</a>
    <button class="nav-toggle" aria-label="Buka menu" aria-expanded="false">
      <svg width="22" height="16" viewBox="0 0 22 16" fill="none"><path d="M0 1H22M0 8H22M0 15H22" stroke="currentColor" stroke-width="1.4"/></svg>
    </button>
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="band.php" class="active">The Band</a></li>
      <li><a href="music.php">Music</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="tour.php" class="nav-cta">Get Tickets</a>
  </div>
</nav>

<?php $band = $pdo->query("SELECT * FROM band LIMIT 1")->fetch(); ?>
<header class="page-hero">
  <div class="container">
    <div class="eyebrow reveal">Sejak <?= e($band['founded_year']) ?> — <?= e($band['origin_city']) ?></div>
    <h1 class="reveal mt-2">Empat orang.<br>Satu bahasa musik.</h1>
    <p class="reveal">Reality Club adalah band indie rock asal Jakarta yang dikenal lewat pendekatan "home-made" — menulis dan merekam lagu di kamar sendiri sebelum akhirnya membawa suara itu ke panggung internasional.</p>
  </div>
</header>

<section id="members">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Formasi Saat Ini</div>
      <h2>The Members</h2>
    </div>
    <?php
$members = $pdo->query("SELECT * FROM members WHERE is_current = 1 ORDER BY sort_order, id")->fetchAll();
$msocial = [];
foreach ($pdo->query("SELECT * FROM member_social_links ORDER BY id") as $r) { $msocial[$r['member_id']][] = $r; }
?>
<div class="grid-4">
<?php foreach ($members as $i => $m): ?>
      <div class="card member-card reveal<?= $i ? ' delay-' . min($i, 3) : '' ?>">
        <img src="<?= e($m['photo']) ?>" alt="<?= e($m['full_name']) ?>" class="member-photo">
        <span class="role"><?= e($m['role']) ?></span>
        <h3><?= e($m['full_name']) ?></h3>
        <p class="bio"><?= e($m['bio']) ?></p>
<?php if (!empty($msocial[$m['id']])): ?>
        <div class="member-social">
<?php foreach ($msocial[$m['id']] as $sl): ?>
          <a href="<?= e($sl['url']) ?>" target="_blank" rel="noopener noreferrer"><?= e($sl['platform']) ?></a>
<?php endforeach; ?>
        </div>
<?php endif; ?>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<div class="section-divider">
  <div class="waveform-wrap">
    <svg viewBox="0 0 1200 60" preserveAspectRatio="none"><path class="wave-path dim" d="M0,30 Q50,5 100,30 T200,30 T300,30 T400,30 T500,30 T600,30 T700,30 T800,30 T900,30 T1000,30 T1100,30 T1200,30"/></svg>
  </div>
</div>

<section id="history">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Perjalanan</div>
      <h2>The Timeline</h2>
    </div>

    <?php $events = $pdo->query("SELECT * FROM timeline_events ORDER BY year, id")->fetchAll(); ?>
<div class="timeline">
<?php foreach ($events as $ev): ?>
      <div class="timeline-item">
        <div class="timeline-dot"></div>
        <span class="timeline-year"><?= e($ev['year']) ?></span>
        <h3><?= e($ev['title']) ?></h3>
        <p><?= e($ev['description']) ?></p>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


<button class="back-to-top" aria-label="Kembali ke atas">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 13V3M8 3L3 8M8 3L13 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<script src="js/main.js"></script>
</body>
</html>
