<?php require_once __DIR__ . '/includes/helpers.php'; $pdo = db(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shop — Reality Club</title>
<meta name="description" content="Merchandise resmi Reality Club.">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/pages.css">
<style>
  .shop-grid-3{display:grid;grid-template-columns:repeat(3, minmax(220px, 300px));justify-content:center;gap:2rem;max-width:1040px;margin:0 auto;}
  @media (max-width:900px){ .shop-grid-3{grid-template-columns:repeat(2, minmax(220px, 300px));} }
  @media (max-width:600px){ .shop-grid-3{grid-template-columns:1fr;max-width:320px;} }
</style>
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
      <li><a href="shop.php" class="active">Shop</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="tour.php" class="nav-cta">Get Tickets</a>
  </div>
</nav>

<header class="page-hero">
  <div class="container">
    <div class="eyebrow reveal">Official Merch</div>
    <h1 class="reveal mt-2">Bawa pulang<br>sepotong Reality Club.</h1>
    <p class="reveal">Koleksi resmi — didesain oleh tim yang sama yang bikin lagunya.</p>
  </div>
</header>

<?php
$rows = $pdo->query("SELECT * FROM products WHERE is_active = 1 ORDER BY FIELD(product_type,'merch','album_physical','album_digital'), id")->fetchAll();
$groups = [];
foreach ($rows as $p) {
  $key = $p['collection'] ?: ($p['product_type'] === 'album_digital' ? 'Album Digital' : 'Album Fisik');
  $groups[$key][] = $p;
}
$gi = 0;
foreach ($groups as $title => $items):
?>
<section id="collection-<?= $gi ?>">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow"><?= $gi === 0 ? 'Featured' : 'Koleksi' ?></div>
      <h2><?= e($title) ?></h2>
    </div>

    <div class="grid-4 shop-grid-3">
<?php foreach ($items as $i => $p): $habis = $p['stock'] !== null && (int)$p['stock'] <= 0; ?>
      <div class="card product-card reveal<?= $i ? ' delay-' . min($i, 4) : '' ?>">
<?php if (!empty($p['image'])): ?>
        <div class="product-media">
          <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>" class="product-img">
        </div>
<?php endif; ?>
        <div class="product-info">
          <div>
            <h3><?= e($p['name']) ?></h3>
            <div class="variant"><?= e($p['variant']) ?></div>
          </div>
          <span class="price"><?= e(rupiah_short((int)$p['price'])) ?></span>
        </div>
<?php if ($habis): ?>
        <button class="add-btn" disabled><span class="label" style="display:inline;">Stok Habis</span></button>
<?php else: ?>
        <button class="add-btn" data-id="<?= (int)$p['id'] ?>" data-name="<?= e($p['name']) ?>" data-price="<?= (int)$p['price'] ?>" data-digital="<?= $p['product_type'] === 'album_digital' ? '1' : '0' ?>"><span class="label">Add to Cart</span><span class="check">Added ✓</span></button>
<?php endif; ?>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php $gi++; endforeach; ?>

<div class="cart-fab">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M1 1H2.5L4.3 9.4A1.5 1.5 0 0 0 5.76 10.6H12.4A1.5 1.5 0 0 0 13.86 9.4L15 4H3.2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="6" cy="14" r="1" fill="currentColor"/><circle cx="12.5" cy="14" r="1" fill="currentColor"/></svg>
  <span class="count">0</span>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<button class="back-to-top" aria-label="Kembali ke atas">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 13V3M8 3L3 8M8 3L13 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<div class="modal-overlay" id="checkout-modal">
  <div class="modal">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow">Checkout</div>
    <h3 class="mt-2">Keranjang Belanja</h3>
    <div id="cart-list" class="cart-list"></div>
    <form id="checkout-form" data-validate="#success-modal" data-endpoint="api/checkout.php" class="mt-4">
      <input type="hidden" name="cart" id="cart-json">
      <div class="field">
        <label>Nama Lengkap</label>
        <input type="text" name="customer_name" required placeholder="Nama penerima">
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
      <div class="field" id="address-field">
        <label>Alamat Pengiriman</label>
        <textarea name="shipping_address" rows="3" id="shipping-address" placeholder="Alamat lengkap (tidak perlu untuk album digital)"></textarea>
        <span class="field-error">Alamat wajib diisi untuk produk fisik.</span>
      </div>
      <button type="submit" class="btn btn-primary mt-2" style="width:100%;justify-content:center;">Buat Pesanan</button>
    </form>
  </div>
</div>

<div class="modal-overlay" id="success-modal">
  <div class="modal text-center">
    <button class="modal-close" data-modal-close aria-label="Tutup">×</button>
    <div class="eyebrow" style="justify-content:center;">Berhasil</div>
    <h3 class="mt-2">Terima Kasih</h3>
    <p class="mt-2" data-fill="result-message" style="color:var(--silver);">Pesananmu sudah kami terima.</p>
  </div>
</div>

<script src="js/main.js"></script>
</body>
</html>
