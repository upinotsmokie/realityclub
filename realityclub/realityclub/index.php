<?php require_once __DIR__ . '/includes/helpers.php'; $pdo = db(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Reality Club — Official Site</title>
<meta name="description" content="Situs resmi Reality Club — band indie rock asal Jakarta. Album, jadwal tur, merchandise, dan cerita band." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/pages.css" />
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
      <li><a href="index.php" class="active">Home</a></li>
      <li><a href="band.php">The Band</a></li>
      <li><a href="music.php">Music</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="tour.php" class="nav-cta">Get Tickets</a>
  </div>
</nav>

<header class="hero">
  <canvas class="hero-canvas"></canvas>
  <div class="hero-inner">
    <div class="eyebrow hero-eyebrow">Jakarta, Indonesia — Since 2016</div>
    <h1>Reality<br /><em>Club</em></h1>
    <p class="hero-sub">Empat orang, satu kamar, dan lagu-lagu yang lahir dari situ. Dari kamar Faiz di Jakarta sampai panggung di tiga benua — ini rumah digital kami.</p>
    <div class="hero-actions">
      <a href="music.php" class="btn btn-primary">
        Dengarkan Album Terbaru
        <svg class="btn-icon" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L8.5 3.5M13 8L8.5 12.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <a href="tour.php" class="btn btn-outline">Jadwal Tur</a>
    </div>
  </div>
  <div class="hero-scroll-cue"><span>SCROLL</span><span class="scroll-line"></span></div>
</header>

<?php $footStreamHome = $pdo->query("SELECT platform, url FROM streaming_links ORDER BY id")->fetchAll(); ?>
<section id="latest-music">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Rilisan Terbaru</div>
      <div class="section-head-row">
        <h2>Who Knows Where<br />Life Will Take You?</h2>
        <a href="music.php" class="btn btn-outline">Lihat Semua Diskografi</a>
      </div>
    </div>

    <?php $albums = $pdo->query("SELECT * FROM albums ORDER BY release_year DESC, id DESC LIMIT 3")->fetchAll(); ?>
<div class="grid-3">
<?php foreach ($albums as $i => $a): ?>
      <div class="card reveal<?= $i ? ' delay-' . $i : '' ?>">
        <div class="album-art"><img src="<?= e($a['cover_image']) ?>" alt="<?= e($a['title']) ?>"></div>
        <div class="album-meta">
          <span class="yr"><?= e(album_label($a)) ?></span>
          <h3><?= e($a['title']) ?></h3>
        </div>
      </div>
<?php endforeach; ?>
    </div>

    <div class="stream-row reveal">
<?php foreach ($footStreamHome as $st): ?>
      <a href="<?= e($st['url']) ?>" class="stream-btn" target="_blank" rel="noopener noreferrer"><?= e($st['platform']) ?></a>
<?php endforeach; ?>
    </div>
  </div>
</section>

<div class="section-divider">
  <div class="waveform-wrap">
    <svg viewBox="0 0 1200 60" preserveAspectRatio="none"><path class="wave-path dim" d="M0,30 Q50,5 100,30 T200,30 T300,30 T400,30 T500,30 T600,30 T700,30 T800,30 T900,30 T1000,30 T1100,30 T1200,30"/></svg>
  </div>
</div>

<section id="upcoming-tour">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Upcoming Shows</div>
      <div class="section-head-row">
        <h2>Temui Kami<br />di Kotamu</h2>
        <a href="tour.php" class="btn btn-outline">Semua Jadwal Tur</a>
      </div>
    </div>

    <div class="tour-list">
<?php render_tour_cards($pdo->query("SELECT * FROM tours WHERE show_on_home = 1 ORDER BY show_date LIMIT 3")->fetchAll(), true); ?>
    </div>
  </div>
</section>

<section id="about-preview">
  <div class="container grid-2">
    <div class="reveal-left">
      <div class="eyebrow">Sejak 2016</div>
      <h2 class="mt-2">Dimulai dari<br />Kamar Tidur</h2>
      <p class="mt-3" style="color:var(--silver);max-width:480px;">Reality Club terbentuk di Jakarta pada 2016, dan rekaman-rekaman awal mereka benar-benar dibuat "home-made" di kamar sang gitaris. Formasi sekarang: Fathia Izzati, Faiz Novascotia Saripudin, Nugi Wicaksono, dan Era Patigo — empat orang yang membangun salah satu nama paling dihormati di skena indie rock Indonesia.</p>
      <a href="band.php" class="btn btn-outline mt-4">Kenali Kami Lebih Dekat</a>
    </div>
    <div class="reveal-right">
      <img
    src="assets/images/home/realityclubmember.jpeg"
    alt="Reality Club"
    class="about-photo">
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
    <h3 class="mt-2" data-fill="tour-name">Jakarta — Istora Senayan</h3>
    <form data-validate="#success-modal" class="mt-4">
      <input type="hidden" name="show" />
      <div class="field">
        <label>Nama Lengkap</label>
        <input type="text" name="name" required placeholder="Nama sesuai KTP" />
        <span class="field-error">Nama wajib diisi.</span>
      </div>
      <div class="field">
        <label>Email</label>
        <input type="email" name="email" required placeholder="nama@email.com" />
        <span class="field-error">Masukkan email yang valid.</span>
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

<div class="modal-overlay" id="success-modal">
  <div class="modal text-center">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow" style="justify-content:center;">Berhasil</div>
    <h3 class="mt-2">Pesanan Terkirim</h3>
    <p class="mt-2" style="color:var(--silver);">Cek email kamu untuk detail e-ticket. Sampai jumpa di venue!</p>
  </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
