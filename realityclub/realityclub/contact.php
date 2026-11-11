<?php require_once __DIR__ . '/includes/helpers.php'; $pdo = db(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact — Reality Club</title>
<meta name="description" content="Hubungi tim Reality Club untuk booking, media, atau kerja sama.">
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
      <li><a href="band.php">The Band</a></li>
      <li><a href="music.php">Music</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="contact.php" class="active">Contact</a></li>
    </ul>
    <a href="tour.php" class="nav-cta">Get Tickets</a>
  </div>
</nav>

<header class="page-hero">
  <div class="container">
    <div class="eyebrow reveal">Get in Touch</div>
    <h1 class="reveal mt-2">Mari<br>Bicara.</h1>
    <p class="reveal">Untuk booking, kerja sama media, atau sekadar menyapa — tim kami siap membaca pesanmu.</p>
  </div>
</header>

<section id="contact-main">
  <div class="container contact-wrap">

    <div class="reveal-left">
      <?php
$icons = [
  'Email' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M2 4L9 10L16 4" stroke="currentColor" stroke-width="1.3"/><rect x="2" y="3" width="14" height="12" rx="1.5" stroke="currentColor" stroke-width="1.3"/></svg>',
  'Booking' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M3 2h3l1.5 4L6 7.5a10 10 0 0 0 4.5 4.5L12 10.5l4 1.5v3a1.5 1.5 0 0 1-1.6 1.5A14 14 0 0 1 2 3.6 1.5 1.5 0 0 1 3 2Z" stroke="currentColor" stroke-width="1.2" stroke-linejoin="round"/></svg>',
  'Manajemen' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><circle cx="9" cy="7" r="3" stroke="currentColor" stroke-width="1.3"/><path d="M3 16c0-3 2.7-5 6-5s6 2 6 5" stroke="currentColor" stroke-width="1.3"/></svg>',
  'Studio' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M9 16s6-4.4 6-9A6 6 0 0 0 3 7c0 4.6 6 9 6 9Z" stroke="currentColor" stroke-width="1.3"/><circle cx="9" cy="7" r="2" stroke="currentColor" stroke-width="1.3"/></svg>'
];
$info = $pdo->query("SELECT label, value FROM contact_info ORDER BY id")->fetchAll();
foreach ($info as $n => $c):
?>
      <div class="contact-info-item"<?= $n === count($info) - 1 ? ' style="border-bottom:none;"' : '' ?>>
        <span class="ic"><?= $icons[$c['label']] ?? '' ?></span>
        <div><h4><?= e($c['label']) ?></h4><p><?= e($c['value']) ?></p></div>
      </div>
<?php endforeach; ?>

      <div class="map-frame">
        <iframe src="https://maps.google.com/maps?q=Jakarta%2C%20Indonesia&t=&z=11&ie=UTF8&iwloc=&output=embed" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
      </div>
    </div>

    <div class="reveal-right">
      <div class="glass-form">
        <div class="eyebrow">Kirim Pesan</div>
        <h2 class="mt-2" style="font-size:1.8rem;">Contact Form</h2>
        <form data-validate="#msg-success-modal" data-endpoint="api/kirim_pesan.php" class="mt-4">
          <div class="field">
            <label>Nama</label>
            <input type="text" name="name" required placeholder="Nama kamu">
            <span class="field-error">Nama wajib diisi.</span>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" name="email" required placeholder="nama@email.com">
            <span class="field-error">Masukkan email yang valid.</span>
          </div>
          <div class="field">
            <label>Topik</label>
            <select name="topic" required>
              <option value="">Pilih topik</option>
              <option>Booking &amp; Kolaborasi</option>
              <option>Media &amp; Pers</option>
              <option>Fan Mail</option>
              <option>Lainnya</option>
            </select>
            <span class="field-error">Pilih salah satu topik.</span>
          </div>
          <div class="field">
            <label>Pesan</label>
            <textarea name="message" rows="4" required placeholder="Tulis pesanmu di sini..."></textarea>
            <span class="field-error">Pesan tidak boleh kosong.</span>
          </div>
          <button type="submit" class="btn btn-primary mt-2" style="width:100%;justify-content:center;">Kirim Pesan</button>
        </form>
      </div>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


<button class="back-to-top" aria-label="Kembali ke atas">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 13V3M8 3L3 8M8 3L13 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<div class="modal-overlay" id="msg-success-modal">
  <div class="modal text-center">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow" style="justify-content:center;">Terkirim</div>
    <h3 class="mt-2">Message Sent</h3>
    <p class="mt-2" style="color:var(--silver);">Terima kasih sudah menghubungi kami. Tim Reality Club akan membalas secepatnya.</p>
  </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
