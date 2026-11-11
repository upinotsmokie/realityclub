<?php
// Menyimpan pesanan tiket konser + membuat kode unik untuk setiap lembar tiket
define('API_MODE', true);
require_once __DIR__ . '/../includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'message' => 'Metode tidak diizinkan.'], 405);
}

$tourId = (int)($_POST['tour_id'] ?? 0);
$name   = trim($_POST['name'] ?? '');
$email  = trim($_POST['email'] ?? '');
$phone  = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');
$qty    = (int)($_POST['qty'] ?? 0);

if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    json_out(['ok' => false, 'message' => 'Nama wajib diisi (maksimal 100 karakter).'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    json_out(['ok' => false, 'message' => 'Email tidak valid.'], 422);
}
if (strlen($phone) < 8 || strlen($phone) > 15) {
    json_out(['ok' => false, 'message' => 'Nomor HP tidak valid.'], 422);
}
if ($qty < 1 || $qty > 4) {
    json_out(['ok' => false, 'message' => 'Jumlah tiket harus 1 sampai 4.'], 422);
}

$pdo = db();
$st = $pdo->prepare('SELECT * FROM tours WHERE id = ?');
$st->execute([$tourId]);
$tour = $st->fetch();
if (!$tour) {
    json_out(['ok' => false, 'message' => 'Konser tidak ditemukan.'], 404);
}
if ($tour['status'] === 'soldout') {
    json_out(['ok' => false, 'message' => 'Maaf, tiket show ini sudah habis. Silakan daftar waitlist.'], 409);
}

$total = $tour['ticket_price'] !== null ? (int)$tour['ticket_price'] * $qty : null;

try {
    $pdo->beginTransaction();
    $pdo->prepare('INSERT INTO ticket_orders (tour_id, buyer_name, email, phone, quantity, total_price) VALUES (?, ?, ?, ?, ?, ?)')
        ->execute([$tourId, $name, $email, $phone, $qty, $total]);
    $orderId = (int)$pdo->lastInsertId();

    $ins   = $pdo->prepare('INSERT INTO tickets (order_id, ticket_code) VALUES (?, ?)');
    $codes = [];
    for ($k = 0; $k < $qty; $k++) {
        for ($try = 0; $try < 5; $try++) {
            $code = strtoupper(bin2hex(random_bytes(6))); // 12 karakter, kode unik
            try {
                $ins->execute([$orderId, $code]);
                $codes[] = implode('-', str_split($code, 4));
                break;
            } catch (PDOException $ex) {
                if ($ex->getCode() !== '23000' || $try === 4) {
                    throw $ex; // bukan bentrok kode, atau sudah 5x gagal
                }
            }
        }
    }
    $pdo->commit();
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('pesan_tiket gagal: ' . $ex->getMessage());
    json_out(['ok' => false, 'message' => 'Pesanan gagal diproses. Silakan coba lagi.'], 500);
}

json_out([
    'ok'      => true,
    'message' => 'Pesanan #' . $orderId . ' tercatat (menunggu pembayaran). Simpan kode tiketmu:',
    'details' => $codes,
]);
