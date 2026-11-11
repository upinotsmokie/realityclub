<?php require_once __DIR__ . '/includes/helpers.php'; $pdo = db(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tour — Reality Club</title>
<meta name="description" content="Jadwal konser Reality Club dan cara membeli tiket.">
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
      <li><a href="tour.php" class="active">Tour</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="#tour-schedule" class="nav-cta">Get Tickets</a>
  </div>
</nav>

<header class="page-hero">
  <div class="container">
    <div class="eyebrow reveal">Live 2026</div>
    <h1 class="reveal mt-2">Kami akan<br>ke kotamu.</h1>
    <p class="reveal">Semua jadwal konser Reality Club — dari panggung kecil di Bandung sampai tur regional Asia Tenggara.</p>
  </div>
</header>

<section id="tour-schedule">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Jadwal Konser</div>
      <h2>Upcoming Shows</h2>
    </div>

    <div class="tour-list">
<?php render_tour_cards($pdo->query("SELECT * FROM tours ORDER BY show_date")->fetchAll(), false); ?>
    </div>
  </div>
</section>

<div class="section-divider">
  <div class="waveform-wrap">
    <svg viewBox="0 0 1200 60" preserveAspectRatio="none"><path class="wave-path dim" d="M0,30 Q50,5 100,30 T200,30 T300,30 T400,30 T500,30 T600,30 T700,30 T800,30 T900,30 T1000,30 T1100,30 T1200,30"/></svg>
  </div>
</div>

<section id="faq">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Perlu Tahu</div>
      <h2>Frequently Asked</h2>
    </div>

    <div class="reveal" style="max-width:720px;">
<?php foreach ($pdo->query("SELECT * FROM faqs ORDER BY sort_order, id")->fetchAll() as $f): ?>
      <div class="faq-item">
        <div class="faq-q"><span><?= e($f['question']) ?></span><span class="plus">+</span></div>
        <div class="faq-a"><p><?= e($f['answer']) ?></p></div>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>


<button class="back-to-top" aria-label="Kembali ke atas">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 13V3M8 3L3 8M8 3L13 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<!-- Ticket modal -->
<div class="modal-overlay" id="ticket-modal">
  <div class="modal">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow">Beli Tiket</div>
    <h3 class="mt-2" data-fill="tour-name">—</h3>
    <form data-validate="#success-modal" data-endpoint="api/pesan_tiket.php" class="mt-4">
      <input type="hidden" name="tour_id">
      <div class="field">
        <label>Nama Lengkap</label>
        <input type="text" name="name" required placeholder="Nama sesuai KTP">
        <span class="field-error">Nama wajib diisi.</span>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required placeholder="nama@email.com">
        <span class="field-error">Masukkan email yang valid.</span>
      </div>
      <div class="field">
        <label>Nomor HP</label>
        <input type="tel" name="phone" required placeholder="08xxxxxxxxxx">
        <span class="field-error">Nomor HP wajib diisi.</span>
      </div>
      <div class="field">
        <label>Jumlah Tiket</label>
        <select name="qty" required>
          <option value="">Pilih jumlah</option>
          <option>1</option><option>2</option><option>3</option><option>4</option>
        </select>
        <span class="field-error">Pilih jumlah tiket.</span>
      </div>
      <button type="submit" class="btn btn-primary mt-2" style="width:100%;justify-content:center;">Konfirmasi Pesanan</button>
    </form>
  </div>
</div>

<!-- Waitlist modal -->
<div class="modal-overlay" id="waitlist-modal">
  <div class="modal">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow">Waitlist</div>
    <h3 class="mt-2" data-fill="tour-name">—</h3>
    <p class="mt-2" style="color:var(--silver);">Show ini sudah sold out. Daftar di waitlist dan kami kabari jika ada tiket tambahan dirilis.</p>
    <form data-validate="#success-modal" data-endpoint="api/waitlist.php" class="mt-4">
      <input type="hidden" name="tour_id">
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required placeholder="nama@email.com">
        <span class="field-error">Masukkan email yang valid.</span>
      </div>
      <button type="submit" class="btn btn-primary mt-2" style="width:100%;justify-content:center;">Gabung Waitlist</button>
    </form>
  </div>
</div>

<div class="modal-overlay" id="success-modal">
  <div class="modal text-center">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow" style="justify-content:center;">Berhasil</div>
    <h3 class="mt-2">Terima Kasih</h3>
    <p class="mt-2" data-fill="result-message" style="color:var(--silver);">Cek email kamu untuk konfirmasi lebih lanjut. Sampai jumpa!</p>
    <div class="mt-2 ticket-codes" data-fill="result-extra"></div>
  </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
