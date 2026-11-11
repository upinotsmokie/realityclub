<?php
// Footer bersama untuk semua halaman (data dari tabel streaming_links, social_links, contact_info)
$footStream = $pdo->query("SELECT platform, url FROM streaming_links ORDER BY id")->fetchAll();
$footSocial = $pdo->query("SELECT platform, label, url FROM social_links ORDER BY id")->fetchAll();
$footEmail  = $pdo->query("SELECT value FROM contact_info WHERE label='Email' LIMIT 1")->fetchColumn() ?: 'management@realityclub.id';
?>
<footer>
  <div class="container">
    <div class="footer-grid">
      <div>
        <div class="brand"><span class="brand-mark"></span>Reality Club</div>
        <p style="color:var(--silver);margin-top:1rem;max-width:280px;font-size:0.9rem;">Dimulai dari sebuah kamar di Jakarta, 2016. Masih menulis lagu dengan cara yang sama.</p>
      </div>
      <div>
        <h4>Jelajahi</h4>
        <a href="band.php">The Band</a>
        <a href="music.php">Music</a>
        <a href="tour.php">Tour</a>
        <a href="shop.php">Shop</a>
      </div>
      <div>
        <h4>Dengarkan</h4>
<?php foreach ($footStream as $s): ?>
        <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener noreferrer"><?= e($s['platform']) ?></a>
<?php endforeach; ?>
      </div>
      <div>
        <h4>Kontak</h4>
        <a href="contact.php">Hubungi Kami</a>
        <a href="mailto:<?= e($footEmail) ?>"><?= e($footEmail) ?></a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Reality Club. All rights reserved.</span>
      <div class="social-row">
<?php foreach ($footSocial as $s): ?>
        <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= e($s['platform']) ?>"><?= e($s['label']) ?></a>
<?php endforeach; ?>
      </div>
    </div>
  </div>
</footer>
