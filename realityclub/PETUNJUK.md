# Reality Club — Website terhubung ke Database (PHP + MySQL)

## Cara menjalankan (XAMPP)
1. Buka XAMPP Control Panel, **Start** Apache dan MySQL.
2. Buka http://localhost/phpmyadmin → tab **Import** → pilih `database/realityclub_database.sql` → **Go**.
3. Salin folder **realityclub** (yang berisi index.php, band.php, dst.) ke `C:\xampp\htdocs\`.
4. Buka http://localhost/realityclub/index.php

Jika password MySQL kamu bukan kosong, ubah `includes/koneksi.php`.

## File yang ditambah / diubah
- `includes/koneksi.php`  : koneksi database (PDO), tempat mengubah user/password.
- `includes/helpers.php`  : fungsi bantu (format rupiah, kartu konser, dll).
- `includes/footer.php`   : footer bersama, data dari tabel streaming_links, social_links, contact_info.
- `api/pesan_tiket.php`   : form Beli Tiket -> ticket_orders + tickets (kode unik per lembar).
- `api/waitlist.php`      : form Waitlist -> waitlist.
- `api/kirim_pesan.php`   : form Contact -> contact_messages.
- `api/checkout.php`      : keranjang Shop -> orders + order_items (harga & stok dicek dari database).
- Semua `*.html` diubah menjadi `*.php`; `js/main.js` dan `css/pages.css` diperbarui.

## Halaman -> tabel
- index.php   : albums, tours, streaming_links
- band.php    : band, members, member_social_links, timeline_events
- music.php   : albums, tracks, album_behind_the_scenes, music_videos, streaming_links, products
- tour.php    : tours, faqs, ticket_orders, tickets, waitlist
- shop.php    : products, orders, order_items
- contact.php : contact_info, contact_messages
