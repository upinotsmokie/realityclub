<?php
// Menyimpan pesan dari form Contact
define('API_MODE', true);
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'message' => 'Metode tidak diizinkan.'], 405);
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$topic   = trim($_POST['topic'] ?? '');
$message = trim($_POST['message'] ?? '');
$topics  = ['Booking & Kolaborasi', 'Media & Pers', 'Fan Mail', 'Lainnya'];

if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    json_out(['ok' => false, 'message' => 'Nama wajib diisi (maksimal 100 karakter).'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    json_out(['ok' => false, 'message' => 'Email tidak valid.'], 422);
}
if (!in_array($topic, $topics, true)) {
    json_out(['ok' => false, 'message' => 'Pilih salah satu topik.'], 422);
}
if (mb_strlen($message) < 3 || mb_strlen($message) > 5000) {
    json_out(['ok' => false, 'message' => 'Pesan wajib diisi (maksimal 5000 karakter).'], 422);
}

try {
    db()->prepare('INSERT INTO contact_messages (name, email, topic, message) VALUES (?, ?, ?, ?)')
        ->execute([$name, $email, $topic, $message]);
} catch (PDOException $ex) {
    error_log('kirim_pesan gagal: ' . $ex->getMessage());
    json_out(['ok' => false, 'message' => 'Pesan gagal dikirim. Silakan coba lagi.'], 500);
}

json_out(['ok' => true, 'message' => 'Terima kasih sudah menghubungi kami. Tim Reality Club akan membalas secepatnya.', 'details' => []]);
