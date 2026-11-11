<?php require_once __DIR__ . '/includes/helpers.php'; $pdo = db(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Music — Reality Club</title>
<meta name="description" content="Diskografi lengkap Reality Club: album, lagu, dan tautan streaming.">
<link rel="stylesheet" href="css/style.css?v=<?= filemtime(__DIR__ . '/css/style.css') ?>">
<link rel="stylesheet" href="css/pages.css?v=<?= filemtime(__DIR__ . '/css/pages.css') ?>">
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
      <li><a href="music.php" class="active">Music</a></li>
      <li><a href="tour.php">Tour</a></li>
      <li><a href="shop.php">Shop</a></li>
      <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="tour.php" class="nav-cta">Get Tickets</a>
  </div>
</nav>

<header class="page-hero">
  <div class="container">
    <div class="eyebrow reveal">Discography</div>
    <h1 class="reveal mt-2">Empat album.<br>Satu cerita yang terus tumbuh.</h1>
    <p class="reveal">Dari rekaman kamar tidur hingga produksi penuh — jejak musik Reality Club sejak 2017.</p>
  </div>
</header>

<section id="discography">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Full Discography</div>
      <h2>Album</h2>
    </div>
    <?php
$albums = $pdo->query("SELECT * FROM albums ORDER BY release_year DESC, id DESC")->fetchAll();
$streams = $pdo->query("SELECT platform, url FROM streaming_links ORDER BY id")->fetchAll();

// Ambil semua lagu sekaligus, lalu kelompokkan per album
$tracksByAlbum = [];
foreach ($pdo->query("SELECT * FROM tracks ORDER BY album_id, track_number")->fetchAll() as $t) {
    $tracksByAlbum[$t['album_id']][] = $t;
}

// Data cadangan: dipakai bila tabel tracks di database belum terisi lengkap
$fallbackTracks = [
    'Never Get Better' => [
        ['Elastic Hearts', '4:35'],
        ['Things I Don\'t Know', '3:21'],
        ['Shouldn\'t End This Way', '4:36'],
        ['Cursive Curses', '2:51'],
        ['Fatal Attraction', '3:30'],
        ['Hesitation', '5:12'],
        ['Okay', '5:24'],
        ['Mentors', '4:45'],
        ['A Graceful Retreat', '4:28'],
        ['For Lack of a Better Word', '4:50'],
        ['Is It The Answer?', '3:50'],
        ['Never Get Better', '5:20'],
        ['Epilogue', '3:10'],
    ],
    'What Do You Really Know?' => [
        ['Prologue', '1:11'],
        ['SSR', '4:02'],
        ['All Along All Things Were Wrong', '2:07'],
        ['Caught in a Trap', '5:48'],
        ['The Rush', '3:31'],
        ['Vita o morte', '3:53'],
        ['Telenovia', '5:21'],
        ['On My Own, Again', '3:43'],
        ['Alexandra', '3:59'],
        ['A Sorrowful Reunion', '4:20'],
        ['2112', '5:57'],
    ],
    'Reality Club Presents...' => [
        ['I Wish I Was Your Joke', '3:38'],
        ['You Let Her Go Again', '3:30'],
        ['Dancing In The Breeze Alone', '4:27'],
        ['Tell Me I\'m Wrong', '4:26'],
        ['Desire', '4:06'],
        ['Arrowhead Man', '3:01'],
        ['Am I Bothering You?', '3:45'],
        ['Anything You Want', '3:56'],
        ['Four Summers', '2:32'],
        ['Love Epiphany', '5:39'],
    ],
    'Who Knows Where Life Will Take You?' => [
        ['I\'ll Do It Myself', '3:01'],
        ['Lost Myself in Reveries', '2:49'],
        ['Enough for You', '3:59'],
        ['Does It Happen?', '3:54'],
        ['Shut Up, Behave', '3:41'],
        ['Finding a Catholic Man to Love the Love of My Life', '3:08'],
        ['Muted Sirens', '4:10'],
        ['Close to You / Jauh', '3:19'],
        ['Quick! Love!', '3:24'],
        ['Now I\'m a Diplomat', '3:15'],
        ['Thank You for Hijacking My Existential Crisis', '3:51'],
        ['You\'ll Find Lovers Like You and Me', '3:29'],
        ['Mama\'s Coming Home', '3:53'],
    ],
];
foreach ($albums as $a) {
    $fb = $fallbackTracks[$a['title']] ?? [];
    if (count($tracksByAlbum[$a['id']] ?? []) < count($fb)) {
        $tracksByAlbum[$a['id']] = [];
        foreach ($fb as $i => [$ft, $fd]) {
            $tracksByAlbum[$a['id']][] = ['track_number' => $i + 1, 'title' => $ft, 'duration' => $fd];
        }
    }
}

// Total durasi album, mis. "55:52"
function total_duration(array $tracks): string
{
    $sec = 0;
    foreach ($tracks as $t) {
        if (!empty($t['duration']) && strpos($t['duration'], ':') !== false) {
            [$m, $s] = array_map('intval', explode(':', $t['duration']));
            $sec += $m * 60 + $s;
        }
    }
    return $sec ? intdiv($sec, 60) . ':' . str_pad($sec % 60, 2, '0', STR_PAD_LEFT) : '';
}
?>
<div class="shelf reveal">
<?php foreach ($albums as $a): ?>
      <div class="album album-clickable" role="button" tabindex="0" aria-expanded="false"
           aria-controls="album-panel-<?= (int)$a['id'] ?>" data-album-panel="#album-panel-<?= (int)$a['id'] ?>"
           aria-label="Lihat lagu album <?= e($a['title']) ?>">
        <div class="album-art">
          <img src="<?= e($a['cover_image']) ?>" alt="<?= e($a['title']) ?>">
          <span class="album-art-hint">
            <svg width="14" height="14" viewBox="0 0 12 12" fill="none"><path d="M2 1.5L10 6L2 10.5V1.5Z" fill="currentColor"/></svg>
            Lihat lagu
          </span>
        </div>
        <div class="album-meta"><span class="yr"><?= e(album_label($a)) ?></span><h3><?= e($a['title']) ?></h3></div>
      </div>
<?php endforeach; ?>
    </div>

    <!-- Daftar lagu: terbuka di bawah rak album saat cover diklik -->
<?php foreach ($albums as $a):
  $tracks = $tracksByAlbum[$a['id']] ?? [];
  $total  = total_duration($tracks);
?>
    <div class="album-panel" id="album-panel-<?= (int)$a['id'] ?>" aria-hidden="true">
      <div class="album-panel-inner">
        <div class="album-panel-body">
          <button class="album-panel-close" data-panel-close aria-label="Tutup daftar lagu">×</button>
          <div class="album-panel-info">
            <div class="album-art"><img src="<?= e($a['cover_image']) ?>" alt="<?= e($a['title']) ?>"></div>
            <div class="eyebrow"><?= e(album_label($a)) ?></div>
            <h3><?= e($a['title']) ?></h3>
<?php if ($tracks): ?>
            <p class="album-modal-count"><?= count($tracks) ?> lagu<?= $total ? ' · ' . e($total) : '' ?></p>
<?php endif; ?>
<?php if (!empty($a['description'])): ?>
            <p class="album-modal-desc"><?= e($a['description']) ?></p>
<?php endif; ?>
            <div class="stream-row">
<?php foreach ($streams as $sl): ?>
              <a href="<?= e($sl['url']) ?>" target="_blank" rel="noopener noreferrer" class="stream-btn"><?= e($sl['platform']) ?></a>
<?php endforeach; ?>
            </div>
          </div>
          <div class="album-panel-tracks">
<?php foreach ($tracks as $t): ?>
            <div class="track-row compact">
              <span class="num"><?= str_pad($t['track_number'], 2, '0', STR_PAD_LEFT) ?></span>
              <span class="title"><?= e($t['title']) ?></span>
<?php if (!empty($t['duration'])): ?>
              <span class="len"><?= e($t['duration']) ?></span>
<?php endif; ?>
            </div>
<?php endforeach; ?>
<?php if (!$tracks): ?>
            <p style="color:var(--silver-dim);font-size:.9rem;">Daftar lagu belum tersedia.</p>
<?php endif; ?>
          </div>
        </div>
      </div>
    </div>
<?php endforeach; ?>
  </div>
</section>

<div class="section-divider">
  <div class="waveform-wrap">
    <svg viewBox="0 0 1200 60" preserveAspectRatio="none"><path class="wave-path dim" d="M0,30 Q50,5 100,30 T200,30 T300,30 T400,30 T500,30 T600,30 T700,30 T800,30 T900,30 T1000,30 T1100,30 T1200,30"/></svg>
  </div>
</div>

<?php
$feat = $pdo->query("SELECT * FROM albums WHERE is_featured = 1 ORDER BY release_year DESC LIMIT 1")->fetch();
$hasAlbumProducts = (int)$pdo->query("SELECT COUNT(*) FROM products WHERE is_active = 1 AND product_type IN ('album_physical','album_digital')")->fetchColumn() > 0;
if ($feat):
  $st = $pdo->prepare("SELECT * FROM tracks WHERE album_id = ? ORDER BY track_number");
  $st->execute([$feat['id']]);
  $tracks = $st->fetchAll();
?>
<section id="featured-album">
  <div class="container grid-2">
    <div class="reveal-left">
      <div class="eyebrow">Featured Release</div>
      <h2 class="mt-2"><?= e($feat['title']) ?></h2>
      <p class="mt-3" style="color:var(--silver);max-width:460px;"><?= e($feat['description']) ?></p>

      <div class="mt-4">
<?php foreach ($tracks as $t): ?>
        <div class="track-row">
          <span class="num"><?= str_pad($t['track_number'], 2, '0', STR_PAD_LEFT) ?></span>
          <span class="title"><?= e($t['title']) ?></span>
          <div class="track-play">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 1.5L10 6L2 10.5V1.5Z" fill="currentColor"/></svg>
          </div>
        </div>
<?php endforeach; ?>
      </div>
      <p class="mt-2" style="color:var(--silver-dim);font-size:0.85rem;">Klik cover album di atas untuk melihat daftar lagu album lainnya.</p>

<?php if (!empty($feat['spotify_embed_url'])): ?>
      <iframe class="mt-3" src="<?= e($feat['spotify_embed_url']) ?>" width="100%" height="152" style="border:0;border-radius:12px;" allow="encrypted-media" loading="lazy"></iframe>
<?php endif; ?>
<?php if (!empty($feat['youtube_embed_url'])): ?>
      <iframe class="mt-3" src="<?= e($feat['youtube_embed_url']) ?>" width="100%" height="220" style="border:0;border-radius:12px;" allowfullscreen loading="lazy"></iframe>
<?php endif; ?>

      <div class="stream-row">
<?php foreach ($streams as $sl): ?>
        <a href="<?= e($sl['url']) ?>" target="_blank" rel="noopener noreferrer" class="stream-btn"><?= e($sl['platform']) ?></a>
<?php endforeach; ?>
<?php if ($hasAlbumProducts): ?>
        <a href="shop.php" class="stream-btn">Beli Album</a>
<?php endif; ?>
      </div>
    </div>
    <div class="reveal-right">
      <div class="album-art"><img src="<?= e($feat['cover_image']) ?>" alt="<?= e($feat['title']) ?>"></div>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
$bts = $pdo->query("SELECT b.*, a.title AS album_title FROM album_behind_the_scenes b JOIN albums a ON a.id = b.album_id ORDER BY b.sort_order, b.id")->fetchAll();
if ($bts):
?>
<section id="behind-the-scenes">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Behind The Scenes</div>
      <h2>Di Balik Album</h2>
    </div>
    <div class="grid-3">
<?php foreach ($bts as $i => $b): ?>
      <div class="card reveal<?= $i ? ' delay-' . min($i, 2) : '' ?>">
<?php if (!empty($b['media_url'])): ?>
        <div class="album-art" style="aspect-ratio:16/9;"><img src="<?= e($b['media_url']) ?>" alt="<?= e($b['title']) ?>"></div>
<?php endif; ?>
        <div class="album-meta"><span class="yr"><?= e($b['album_title']) ?></span><h3><?= e($b['title']) ?></h3>
          <p style="color:var(--silver);font-size:0.9rem;margin-top:0.5rem;"><?= e($b['description']) ?></p></div>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section id="videos">
  <div class="container">
    <div class="section-head reveal">
      <div class="eyebrow">Watch</div>
      <h2>Music Videos</h2>
    </div>
    <?php $videos = $pdo->query("SELECT * FROM music_videos ORDER BY id")->fetchAll(); ?>
<div class="grid-3">
<?php foreach ($videos as $i => $v): ?>
      <div class="card reveal<?= $i ? ' delay-' . min($i, 2) : '' ?>">
<?php if (!empty($v['video_url'])): ?><a href="<?= e($v['video_url']) ?>" target="_blank" rel="noopener noreferrer"><?php endif; ?>
        <div class="album-art" style="aspect-ratio:16/9;"><img src="<?= e($v['thumbnail']) ?>" alt="<?= e($v['title']) ?>"></div>
<?php if (!empty($v['video_url'])): ?></a><?php endif; ?>
        <div class="album-meta"><span class="yr"><?= e($v['video_type']) ?></span><h3><?= e($v['title']) ?></h3></div>
      </div>
<?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<button class="back-to-top" aria-label="Kembali ke atas">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 13V3M8 3L3 8M8 3L13 8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
</button>

<script src="js/main.js?v=<?= filemtime(__DIR__ . '/js/main.js') ?>"></script>
</body>
</html>
