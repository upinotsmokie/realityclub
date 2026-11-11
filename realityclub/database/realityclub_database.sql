-- =====================================================================
-- DATABASE: realityclub  (versi 2 - sesuai brief dosen)
-- Prinsip: apa yang tampil di front-end harus tersedia di back-end.
-- Orientasi: Informational (profil, sejarah, album, BTS) dan
--            Task (tiket konser, merchandise, album fisik/digital, kontak).
-- Kompatibel dengan MySQL 8 / MariaDB 10.4+ (XAMPP / phpMyAdmin)
-- =====================================================================

DROP DATABASE IF EXISTS realityclub;
CREATE DATABASE realityclub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE realityclub;

-- Profil umum band (hero, footer, about) (halaman: Home, Band, Contact)
CREATE TABLE band (
  id TINYINT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID band',
  name VARCHAR(100) NOT NULL COMMENT 'Nama band',
  genre VARCHAR(100) COMMENT 'Genre musik',
  origin_city VARCHAR(100) COMMENT 'Kota asal',
  founded_year YEAR COMMENT 'Tahun berdiri',
  tagline VARCHAR(255) COMMENT 'Kalimat singkat di hero',
  description TEXT COMMENT 'Deskripsi / cerita singkat band',
  management VARCHAR(100) COMMENT 'Nama manajemen'
) ENGINE=InnoDB COMMENT='Profil umum band (hero, footer, about)';

-- Profil personel (About) (halaman: Band, Home)
CREATE TABLE members (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID personel',
  full_name VARCHAR(100) NOT NULL COMMENT 'Nama personel',
  role VARCHAR(100) NOT NULL COMMENT 'Peran / instrumen',
  bio TEXT COMMENT 'Bio singkat',
  photo VARCHAR(255) COMMENT 'Path / URL foto',
  is_current BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Anggota aktif atau bukan',
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Urutan tampil'
) ENGINE=InnoDB COMMENT='Profil personel (About)';

-- Link medsos tiap personel (halaman: Band)
CREATE TABLE member_social_links (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID link',
  member_id INT UNSIGNED NOT NULL COMMENT 'Personel pemilik akun',
  platform VARCHAR(50) NOT NULL COMMENT 'Instagram / TikTok / X / dll',
  url VARCHAR(500) NOT NULL COMMENT 'Alamat akun',
  CONSTRAINT fk_member_social_links_member_id FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Link medsos tiap personel';

-- Sejarah / cerita perjalanan band (halaman: Band)
CREATE TABLE timeline_events (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID event',
  year YEAR NOT NULL COMMENT 'Tahun kejadian',
  title VARCHAR(150) NOT NULL COMMENT 'Judul event',
  description TEXT NOT NULL COMMENT 'Isi cerita'
) ENGINE=InnoDB COMMENT='Sejarah / cerita perjalanan band';

-- Data album (diskografi) (halaman: Home, Music)
CREATE TABLE albums (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID album',
  title VARCHAR(150) NOT NULL COMMENT 'Judul album',
  release_year YEAR NOT NULL COMMENT 'Tahun rilis',
  release_date DATE COMMENT 'Tanggal rilis lengkap (diisi jika diketahui)',
  album_type VARCHAR(20) NOT NULL DEFAULT 'LP' COMMENT 'Jenis rilisan (LP/EP/Single)',
  award VARCHAR(150) COMMENT 'Penghargaan yang diraih',
  is_debut BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Penanda album debut',
  is_featured BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Tampil sebagai Featured Release',
  description TEXT COMMENT 'Deskripsi album',
  cover_image VARCHAR(255) COMMENT 'Path / URL foto cover',
  spotify_embed_url VARCHAR(500) COMMENT 'Embedded link Spotify',
  youtube_embed_url VARCHAR(500) COMMENT 'Embedded link YouTube'
) ENGINE=InnoDB COMMENT='Data album (diskografi)';

-- Daftar lagu (track list) per album (halaman: Music)
CREATE TABLE tracks (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID lagu',
  album_id INT UNSIGNED NOT NULL COMMENT 'Album induk',
  track_number TINYINT UNSIGNED NOT NULL COMMENT 'Nomor urut lagu',
  title VARCHAR(150) NOT NULL COMMENT 'Judul lagu',
  duration VARCHAR(8) COMMENT 'Durasi lagu (m:dd)',
  CONSTRAINT fk_tracks_album_id FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE CASCADE,
  UNIQUE KEY uq_album_track (album_id, track_number)
) ENGINE=InnoDB COMMENT='Daftar lagu (track list) per album';

-- Konten behind the scenes pembuatan album (halaman: Music)
CREATE TABLE album_behind_the_scenes (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID konten',
  album_id INT UNSIGNED NOT NULL COMMENT 'Album terkait',
  title VARCHAR(150) NOT NULL COMMENT 'Judul konten',
  description TEXT COMMENT 'Cerita di balik pembuatan album',
  media_url VARCHAR(255) COMMENT 'Foto / video behind the scenes',
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Urutan tampil',
  CONSTRAINT fk_album_behind_the_scenes_album_id FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Konten behind the scenes pembuatan album';

-- Video musik / live session (halaman: Music)
CREATE TABLE music_videos (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID video',
  album_id INT UNSIGNED COMMENT 'Album terkait (boleh kosong)',
  title VARCHAR(150) NOT NULL COMMENT 'Judul video',
  video_type VARCHAR(50) NOT NULL COMMENT 'Official MV / Live Session',
  thumbnail VARCHAR(255) COMMENT 'Gambar thumbnail',
  video_url VARCHAR(500) COMMENT 'Link video',
  CONSTRAINT fk_music_videos_album_id FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Video musik / live session';

-- Link platform streaming band (halaman: Home, Music, Footer)
CREATE TABLE streaming_links (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID link',
  platform VARCHAR(50) NOT NULL COMMENT 'Spotify / Apple Music / YouTube Music',
  url VARCHAR(500) NOT NULL COMMENT 'Alamat link'
) ENGINE=InnoDB COMMENT='Link platform streaming band';

-- Link medsos resmi band (halaman: Footer)
CREATE TABLE social_links (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID link',
  platform VARCHAR(50) NOT NULL COMMENT 'Instagram / YouTube / TikTok / X',
  label VARCHAR(10) NOT NULL COMMENT 'Singkatan tampilan (IG, YT, ...)',
  url VARCHAR(500) NOT NULL COMMENT 'Alamat akun'
) ENGINE=InnoDB COMMENT='Link medsos resmi band';

-- Jadwal konser (halaman: Home, Tour)
CREATE TABLE tours (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID konser',
  city VARCHAR(100) NOT NULL COMMENT 'Kota',
  venue VARCHAR(150) NOT NULL COMMENT 'Nama venue',
  show_date DATE NOT NULL COMMENT 'Tanggal konser',
  tour_name VARCHAR(150) NOT NULL COMMENT 'Nama tur / acara',
  status ENUM('available','limited','soldout') NOT NULL DEFAULT 'available' COMMENT 'Status tiket (Tersedia / Terbatas / Sold Out)',
  ticket_price INT UNSIGNED COMMENT 'Harga tiket per lembar (Rp). Belum ada di web, isi oleh tim',
  show_on_home BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Tampil di halaman Home'
) ENGINE=InnoDB COMMENT='Jadwal konser';

-- Pesanan tiket dari form Beli Tiket (halaman: Tour)
CREATE TABLE ticket_orders (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID pesanan',
  tour_id INT UNSIGNED NOT NULL COMMENT 'Konser yang dipesan',
  buyer_name VARCHAR(100) NOT NULL COMMENT 'Nama pembeli',
  email VARCHAR(150) NOT NULL COMMENT 'Email pembeli',
  phone VARCHAR(20) NOT NULL COMMENT 'Nomor HP pembeli',
  quantity TINYINT UNSIGNED NOT NULL COMMENT 'Jumlah tiket (1-4)',
  total_price INT UNSIGNED COMMENT 'Total bayar (Rp)',
  status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Status pembayaran',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pemesanan',
  CONSTRAINT fk_ticket_orders_tour_id FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE RESTRICT,
  CONSTRAINT chk_qty CHECK (quantity BETWEEN 1 AND 4)
) ENGINE=InnoDB COMMENT='Pesanan tiket dari form Beli Tiket';

-- Tiket individual dengan kode unik / barcode (halaman: Tour)
CREATE TABLE tickets (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID tiket',
  order_id INT UNSIGNED NOT NULL COMMENT 'Pesanan asal',
  ticket_code CHAR(12) NOT NULL UNIQUE COMMENT 'Kode unik tiket (sumber barcode / QR)',
  is_used BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Sudah dipakai check-in',
  issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu tiket diterbitkan',
  CONSTRAINT fk_tickets_order_id FOREIGN KEY (order_id) REFERENCES ticket_orders(id) ON DELETE CASCADE
) ENGINE=InnoDB COMMENT='Tiket individual dengan kode unik / barcode';

-- Daftar tunggu untuk show sold out (halaman: Tour)
CREATE TABLE waitlist (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID waitlist',
  tour_id INT UNSIGNED NOT NULL COMMENT 'Konser sold out',
  email VARCHAR(150) NOT NULL COMMENT 'Email pendaftar',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu mendaftar',
  CONSTRAINT fk_waitlist_tour_id FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE,
  UNIQUE KEY uq_waitlist (tour_id, email)
) ENGINE=InnoDB COMMENT='Daftar tunggu untuk show sold out';

-- Pertanyaan yang sering diajukan (halaman: Tour)
CREATE TABLE faqs (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID FAQ',
  question VARCHAR(255) NOT NULL COMMENT 'Pertanyaan',
  answer TEXT NOT NULL COMMENT 'Jawaban',
  sort_order TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Urutan tampil'
) ENGINE=InnoDB COMMENT='Pertanyaan yang sering diajukan';

-- Produk dijual: merchandise, album fisik, album digital (halaman: Shop, Music)
CREATE TABLE products (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID produk',
  name VARCHAR(200) NOT NULL COMMENT 'Nama produk',
  variant VARCHAR(150) COMMENT 'Keterangan varian',
  product_type ENUM('merch','album_physical','album_digital') NOT NULL COMMENT 'Jenis produk',
  category VARCHAR(50) NOT NULL COMMENT 'T-Shirt / Long Sleeve / Cap / CD / Kaset / Digital',
  album_id INT UNSIGNED COMMENT 'Album terkait (khusus produk album)',
  collection VARCHAR(100) COMMENT 'Nama koleksi',
  price INT UNSIGNED NOT NULL COMMENT 'Harga (Rp)',
  stock INT UNSIGNED COMMENT 'Stok (NULL = tidak dibatasi / digital)',
  image VARCHAR(255) COMMENT 'Path / URL foto produk',
  is_active BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Produk tampil di toko',
  CONSTRAINT fk_products_album_id FOREIGN KEY (album_id) REFERENCES albums(id) ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Produk dijual: merchandise, album fisik, album digital';

-- Pesanan pembelian merch / album (halaman: Shop)
CREATE TABLE orders (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID pesanan',
  customer_name VARCHAR(100) NOT NULL COMMENT 'Nama pembeli',
  email VARCHAR(150) NOT NULL COMMENT 'Email pembeli',
  phone VARCHAR(20) NOT NULL COMMENT 'Nomor HP pembeli',
  shipping_address TEXT COMMENT 'Alamat kirim (kosong jika semua item digital)',
  total_price INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total pesanan (Rp)',
  status ENUM('pending','paid','shipped','cancelled') NOT NULL DEFAULT 'pending' COMMENT 'Status pesanan',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pemesanan'
) ENGINE=InnoDB COMMENT='Pesanan pembelian merch / album';

-- Rincian item tiap pesanan (isi keranjang) (halaman: Shop)
CREATE TABLE order_items (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID item',
  order_id INT UNSIGNED NOT NULL COMMENT 'Pesanan induk',
  product_id INT UNSIGNED NOT NULL COMMENT 'Produk yang dibeli',
  quantity TINYINT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Jumlah',
  unit_price INT UNSIGNED NOT NULL COMMENT 'Harga satuan saat dibeli (Rp)',
  CONSTRAINT fk_order_items_order_id FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Rincian item tiap pesanan (isi keranjang)';

-- Informasi kontak band (halaman: Contact, Footer)
CREATE TABLE contact_info (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID',
  label VARCHAR(50) NOT NULL COMMENT 'Email / Booking / Manajemen / Studio',
  value VARCHAR(150) NOT NULL COMMENT 'Isi kontak'
) ENGINE=InnoDB COMMENT='Informasi kontak band';

-- Pesan dari form Contact (halaman: Contact)
CREATE TABLE contact_messages (
  id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT COMMENT 'ID pesan',
  name VARCHAR(100) NOT NULL COMMENT 'Nama pengirim',
  email VARCHAR(150) NOT NULL COMMENT 'Email pengirim',
  topic ENUM('Booking & Kolaborasi','Media & Pers','Fan Mail','Lainnya') NOT NULL COMMENT 'Topik pesan',
  message TEXT NOT NULL COMMENT 'Isi pesan',
  is_read BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Sudah dibaca admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu kirim'
) ENGINE=InnoDB COMMENT='Pesan dari form Contact';


-- =====================================================================
-- DATA AWAL (diambil dari konten website)
-- =====================================================================
INSERT INTO band (name, genre, origin_city, founded_year, tagline, description, management) VALUES
('Reality Club','Indie Rock','Jakarta',2016,'Empat orang, satu kamar, dan lagu-lagu yang lahir dari situ.',
 'Reality Club adalah band indie rock asal Jakarta yang dikenal lewat pendekatan "home-made" — menulis dan merekam lagu di kamar sendiri sebelum akhirnya membawa suara itu ke panggung internasional.',
 'Sun Eater Management');

INSERT INTO members (full_name, role, bio, photo, is_current, sort_order) VALUES
('Fathia Izzati','Vokal · Kibor · Gitar','Awalnya dikenal sebagai kreator YouTube, kini menjadi vokalis sentral yang membawa warna khas Reality Club.','assets/images/the band/fathia.jpg',TRUE,1),
('Faiz Novascotia Saripudin','Vokal · Gitar','Kakak Fathia. Sebagian besar lagu Reality Club lahir dari kamarnya — pusat kreatif band sejak awal.','assets/images/the band/Faiz.jpg',TRUE,2),
('Nugi Wicaksono','Bass','Bergabung tak lama setelah band terbentuk. Pecinta sastra dengan selera humor yang jadi ciri khasnya di atas panggung.','assets/images/the band/nugi.jpg',TRUE,3),
('Era Patigo','Drum','Salah satu pendiri band, menjaga ritme Reality Club sejak hari pertama di tahun 2016.','assets/images/the band/era.jpg',TRUE,4),
('Mayo Falmonti','Pendiri (mantan anggota)','Salah satu pendiri Reality Club bersama Fathia dan Era pada 2016.',NULL,FALSE,5);

INSERT INTO timeline_events (year, title, description) VALUES
(2016,'Terbentuk di Jakarta','Fathia Izzati, Era Patigo, dan Mayo Falmonti membentuk band sebagai proyek cover di waktu senggang. Tak lama, Faiz Novascotia (kakak Fathia) ikut bergabung, dan nama Reality Club mulai terbentuk.'),
(2017,'Never Get Better','Nugi Wicaksono bergabung mengisi posisi bass. Album debut penuh, Never Get Better, dirilis dan memperkenalkan Reality Club ke skena indie Indonesia.'),
(2019,'What Do You Really Know?','Album kedua hadir dengan eksplorasi musik yang lebih matang, memperkuat posisi Reality Club sebagai salah satu nama besar indie rock lokal.'),
(2022,'Formasi Baru','Reality Club melanjutkan perjalanan sebagai kuartet: Fathia, Faiz, Nugi, dan Era — formasi yang bertahan hingga sekarang.'),
(2023,'Reality Club Presents...','Album ketiga meraih penghargaan AMI Award untuk Best Alternative Album dan Best Alternative Group tahun 2023 — pengakuan tertinggi di industri musik Indonesia.'),
(2025,'Panggung Dunia','Membuka konser NIKI pada leg Asia dari Buzz World Tour, lalu merilis album keempat, Who Knows Where Life Will Take You? — babak baru perjalanan Reality Club.');

INSERT INTO albums (title, release_year, album_type, award, is_debut, is_featured, description, cover_image) VALUES
('Never Get Better',2017,'LP',NULL,TRUE,FALSE,'Album debut penuh Reality Club.','assets/images/music/never get better.jpg'),
('What Do You Really Know?',2019,'LP',NULL,FALSE,FALSE,'Album kedua dengan eksplorasi musik yang lebih matang.','assets/images/music/whatdoyou.jpg'),
('Reality Club Presents...',2023,'LP','AMI Award — Best Alternative Album & Best Alternative Group 2023',FALSE,TRUE,'Album ketiga Reality Club, pemenang AMI Award. Memuat "Anything You Want", salah satu lagu paling dicintai penggemar.','assets/images/music/rc presents.jpg'),
('Who Knows Where Life Will Take You?',2025,'LP',NULL,FALSE,FALSE,'Album keempat, babak baru perjalanan Reality Club.','assets/images/music/whoknow.jpg');

INSERT INTO tracks (album_id, track_number, title, duration) VALUES
((SELECT id FROM albums WHERE title='Never Get Better'),1,'Elastic Hearts','4:35'),
((SELECT id FROM albums WHERE title='Never Get Better'),2,'Things I Don''t Know','3:21'),
((SELECT id FROM albums WHERE title='Never Get Better'),3,'Shouldn''t End This Way','4:36'),
((SELECT id FROM albums WHERE title='Never Get Better'),4,'Cursive Curses','2:51'),
((SELECT id FROM albums WHERE title='Never Get Better'),5,'Fatal Attraction','3:30'),
((SELECT id FROM albums WHERE title='Never Get Better'),6,'Hesitation','5:12'),
((SELECT id FROM albums WHERE title='Never Get Better'),7,'Okay','5:24'),
((SELECT id FROM albums WHERE title='Never Get Better'),8,'Mentors','4:45'),
((SELECT id FROM albums WHERE title='Never Get Better'),9,'A Graceful Retreat','4:28'),
((SELECT id FROM albums WHERE title='Never Get Better'),10,'For Lack of a Better Word','4:50'),
((SELECT id FROM albums WHERE title='Never Get Better'),11,'Is It The Answer?','3:50'),
((SELECT id FROM albums WHERE title='Never Get Better'),12,'Never Get Better','5:20'),
((SELECT id FROM albums WHERE title='Never Get Better'),13,'Epilogue','3:10'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),1,'Prologue','1:11'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),2,'SSR','4:02'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),3,'All Along All Things Were Wrong','2:07'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),4,'Caught in a Trap','5:48'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),5,'The Rush','3:31'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),6,'Vita o morte','3:53'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),7,'Telenovia','5:21'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),8,'On My Own, Again','3:43'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),9,'Alexandra','3:59'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),10,'A Sorrowful Reunion','4:20'),
((SELECT id FROM albums WHERE title='What Do You Really Know?'),11,'2112','5:57'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),1,'I Wish I Was Your Joke','3:38'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),2,'You Let Her Go Again','3:30'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),3,'Dancing In The Breeze Alone','4:27'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),4,'Tell Me I''m Wrong','4:26'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),5,'Desire','4:06'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),6,'Arrowhead Man','3:01'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),7,'Am I Bothering You?','3:45'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),8,'Anything You Want','3:56'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),9,'Four Summers','2:32'),
((SELECT id FROM albums WHERE title='Reality Club Presents...'),10,'Love Epiphany','5:39'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),1,'I''ll Do It Myself','3:01'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),2,'Lost Myself in Reveries','2:49'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),3,'Enough for You','3:59'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),4,'Does It Happen?','3:54'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),5,'Shut Up, Behave','3:41'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),6,'Finding a Catholic Man to Love the Love of My Life','3:08'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),7,'Muted Sirens','4:10'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),8,'Close to You / Jauh','3:19'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),9,'Quick! Love!','3:24'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),10,'Now I''m a Diplomat','3:15'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),11,'Thank You for Hijacking My Existential Crisis','3:51'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),12,'You''ll Find Lovers Like You and Me','3:29'),
((SELECT id FROM albums WHERE title='Who Knows Where Life Will Take You?'),13,'Mama''s Coming Home','3:53');

INSERT INTO music_videos (album_id, title, video_type, thumbnail) VALUES
(3,'Anything You Want','Official MV','assets/images/music/anything u want.jpg'),
(3,'Reality Club Presents... — Visualizer','Official MV','assets/images/music/rc presents.jpg'),
(4,'Reality Club — Acoustic Set','Live Session','assets/images/music/whoknow.jpg');

INSERT INTO streaming_links (platform, url) VALUES
('Spotify','https://open.spotify.com/artist/1DjZI46mVZZZYmmmygRnTw?si=4kOoPzGmQDWc0l3ONV6rPQ&utm_source=copy-link'),
('Apple Music','https://music.apple.com/id/artist/reality-club/1468177977?l=id'),
('YouTube Music','https://music.youtube.com/@realityclub?si=_EXQ30zdU9vR6tC6');

INSERT INTO social_links (platform, label, url) VALUES
('Instagram','IG','https://www.instagram.com/realityclub?igsh=cGNobDA1N2gxazF4'),
('YouTube','YT','https://youtube.com/@realityclub?si=weDtIt1MQTSetSfz'),
('TikTok','TT','https://www.tiktok.com/@realityclubofficial?_r=1&_t=ZS-98BQJG15XKG'),
('X','X','https://x.com/reality_club');

INSERT INTO tours (city, venue, show_date, tour_name, status, show_on_home) VALUES
('Jakarta','Istora Senayan','2026-09-14','Reality Club: Homecoming Show','limited',TRUE),
('Bandung','Sasana Budaya Ganesha','2026-09-21','Reality Club: Homecoming Show','available',TRUE),
('Surabaya','Grand City Convex','2026-09-28','Homecoming Show','soldout',FALSE),
('Singapore','Esplanade Annexe','2026-10-05','Southeast Asia Run','available',TRUE),
('Kuala Lumpur','KL Live','2026-10-12','Southeast Asia Run','available',FALSE);

INSERT INTO faqs (question, answer, sort_order) VALUES
('Bagaimana cara mengubah tiket yang sudah dibeli?','Hubungi tim kami melalui halaman Contact selambat-lambatnya 3 hari sebelum acara. Perubahan tergantung ketersediaan kursi di venue tujuan.',1),
('Apakah ada batasan usia untuk menonton?','Sebagian besar venue terbuka untuk semua umur, namun beberapa venue klub memberlakukan batas usia minimum. Cek detail venue di halaman tiket.',2),
('Bagaimana jika saya kehilangan e-ticket?','E-ticket dikirim ke email pembelian dan bisa diunduh ulang kapan saja. Bawa identitas yang sesuai dengan nama pemesan saat check-in.',3),
('Apakah tiket bisa dikembalikan (refund)?','Tiket bersifat final dan tidak dapat dikembalikan, kecuali acara dibatalkan oleh penyelenggara.',4);

INSERT INTO products (name, variant, product_type, category, collection, price, image) VALUES
('You''ll Find Lovers Like You and Me T-Shirt','Official Reality Club T-Shirt','merch','T-Shirt','Homecoming Collection',250000,'assets/images/shop/Baju merch.webp'),
('Long Sleeve','Official Reality Club Long Sleeve','merch','Long Sleeve','Homecoming Collection',300000,'assets/images/shop/long sleve merch.webp'),
('Who Knows Where Life Will Take You? Cap','Official Reality Club Cap','merch','Cap','Homecoming Collection',150000,'assets/images/shop/topi merch 2.webp');

INSERT INTO contact_info (label, value) VALUES
('Email','management@realityclub.id'),('Booking','booking@realityclub.id'),
('Manajemen','Sun Eater Management'),('Studio','Jakarta, Indonesia');

-- ---------------- Contoh data transaksi (untuk testing, boleh dihapus) ----------------
INSERT INTO ticket_orders (tour_id, buyer_name, email, phone, quantity, status) VALUES
(1,'Budi Santoso','budi@email.com','081234567890',2,'paid'),
(2,'Siti Rahma','siti@email.com','089876543210',1,'pending');

-- Satu baris tiket per lembar, kode unik dibuat otomatis (di aplikasi nanti bisa diganti generator sendiri)
INSERT INTO tickets (order_id, ticket_code) VALUES
(1, UPPER(SUBSTRING(REPLACE(UUID(),'-',''),1,12))),
(1, UPPER(SUBSTRING(REPLACE(UUID(),'-',''),1,12))),
(2, UPPER(SUBSTRING(REPLACE(UUID(),'-',''),1,12)));

INSERT INTO waitlist (tour_id, email) VALUES (3,'fans@email.com');

INSERT INTO orders (customer_name, email, phone, shipping_address, total_price, status) VALUES
('Budi Santoso','budi@email.com','081234567890','Jl. Contoh No. 1, Pontianak',400000,'paid');
INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (1,1,1,250000),(1,3,1,150000);

INSERT INTO contact_messages (name, email, topic, message) VALUES
('Rina','rina@email.com','Fan Mail','Terima kasih sudah bikin lagu-lagu yang menemani saya!');

-- ---------------- VIEW untuk kebutuhan tampilan ----------------
CREATE VIEW v_upcoming_tours AS
SELECT id, CONCAT(city,' — ',venue) AS title, tour_name, show_date, status
FROM tours WHERE show_date >= CURDATE() ORDER BY show_date;

CREATE VIEW v_album_list AS
SELECT title, CONCAT(release_year,' · ',album_type,IF(award IS NOT NULL,' · AMI Award',''),IF(is_debut,' · Debut','')) AS label, cover_image
FROM albums ORDER BY release_year DESC;

-- Tiket lengkap beserta pembelinya (untuk cetak / cek barcode)
CREATE VIEW v_tickets_detail AS
SELECT t.ticket_code, o.buyer_name, o.email, o.phone, CONCAT(tr.city,' — ',tr.venue) AS konser, tr.show_date, t.is_used
FROM tickets t JOIN ticket_orders o ON o.id=t.order_id JOIN tours tr ON tr.id=o.tour_id;
