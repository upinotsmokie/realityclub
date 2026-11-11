<?php
// Menyimpan email ke waitlist show yang sold out
define('API_MODE', true);
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'message' => 'Metode tidak diizinkan.'], 405);
}

$tourId = (int)($_POST['tour_id'] ?? 0);
$email  = trim($_POST['email'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    json_out(['ok' => false, 'message' => 'Email tidak valid.'], 422);
}

$pdo = db();
$st = $pdo->prepare('SELECT id FROM tours WHERE id = ?');
$st->execute([$tourId]);
if (!$st->fetch()) {
    json_out(['ok' => false, 'message' => 'Konser tidak ditemukan.'], 404);
}

try {
    $pdo->prepare('INSERT INTO waitlist (tour_id, email) VALUES (?, ?)')->execute([$tourId, $email]);
} catch (PDOException $ex) {
    if ($ex->getCode() === '23000') {
        json_out(['ok' => true, 'message' => 'Email ini sudah terdaftar di waitlist show tersebut.', 'details' => []]);
    }
    error_log('waitlist gagal: ' . $ex->getMessage());
    json_out(['ok' => false, 'message' => 'Gagal mendaftar waitlist. Silakan coba lagi.'], 500);
}

json_out(['ok' => true, 'message' => 'Kamu sudah masuk waitlist. Kami kabari lewat email jika ada tiket tambahan.', 'details' => []]);
