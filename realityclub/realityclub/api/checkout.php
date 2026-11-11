<?php
// Menyimpan pesanan merch / album (orders + order_items). Harga selalu diambil dari database.
define('API_MODE', true);
require_once __DIR__ . '/../includes/helpers.php';

class StokException extends RuntimeException {}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'message' => 'Metode tidak diizinkan.'], 405);
}

$name    = trim($_POST['customer_name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = preg_replace('/[^0-9+]/', '', $_POST['phone'] ?? '');
$address = trim($_POST['shipping_address'] ?? '');
$cart    = json_decode($_POST['cart'] ?? '[]', true);

if (mb_strlen($name) < 2 || mb_strlen($name) > 100) {
    json_out(['ok' => false, 'message' => 'Nama wajib diisi (maksimal 100 karakter).'], 422);
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
    json_out(['ok' => false, 'message' => 'Email tidak valid.'], 422);
}
if (strlen($phone) < 8 || strlen($phone) > 15) {
    json_out(['ok' => false, 'message' => 'Nomor HP tidak valid.'], 422);
}
if (!is_array($cart) || !$cart) {
    json_out(['ok' => false, 'message' => 'Keranjang masih kosong.'], 422);
}

// rapikan isi keranjang: id produk => jumlah (maks. 10 per produk)
$want = [];
foreach ($cart as $row) {
    $id = (int)($row['id'] ?? 0);
    $q  = (int)($row['qty'] ?? 0);
    if ($id > 0 && $q >= 1) {
        $want[$id] = min(10, ($want[$id] ?? 0) + $q);
    }
}
if (!$want) {
    json_out(['ok' => false, 'message' => 'Keranjang masih kosong.'], 422);
}

$pdo = db();
$in  = implode(',', array_fill(0, count($want), '?'));
$st  = $pdo->prepare("SELECT * FROM products WHERE is_active = 1 AND id IN ($in)");
$st->execute(array_keys($want));
$products = [];
foreach ($st->fetchAll() as $p) {
    $products[(int)$p['id']] = $p;
}
if (count($products) !== count($want)) {
    json_out(['ok' => false, 'message' => 'Ada produk yang sudah tidak tersedia.'], 409);
}

$total       = 0;
$needAddress = false;
foreach ($want as $id => $q) {
    $total += (int)$products[$id]['price'] * $q;
    if ($products[$id]['product_type'] !== 'album_digital') {
        $needAddress = true;
    }
}
if ($needAddress && mb_strlen($address) < 10) {
    json_out(['ok' => false, 'message' => 'Alamat pengiriman wajib diisi untuk produk fisik.'], 422);
}

try {
    $pdo->beginTransaction();
    $pdo->prepare('INSERT INTO orders (customer_name, email, phone, shipping_address, total_price) VALUES (?, ?, ?, ?, ?)')
        ->execute([$name, $email, $phone, $needAddress ? $address : null, $total]);
    $orderId = (int)$pdo->lastInsertId();

    $item  = $pdo->prepare('INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?, ?, ?, ?)');
    $stock = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
    foreach ($want as $id => $q) {
        $p = $products[$id];
        if ($p['stock'] !== null) { // stok NULL = tidak dibatasi
            $stock->execute([$q, $id, $q]);
            if ($stock->rowCount() === 0) {
                throw new StokException($p['name']);
            }
        }
        $item->execute([$orderId, $id, $q, (int)$p['price']]);
    }
    $pdo->commit();
} catch (StokException $ex) {
    $pdo->rollBack();
    json_out(['ok' => false, 'message' => 'Stok tidak cukup untuk: ' . $ex->getMessage()], 409);
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('checkout gagal: ' . $ex->getMessage());
    json_out(['ok' => false, 'message' => 'Pesanan gagal diproses. Silakan coba lagi.'], 500);
}

json_out([
    'ok'      => true,
    'message' => 'Pesanan #' . $orderId . ' tercatat dengan total ' . rupiah($total) . '. Kami akan menghubungi lewat email / HP untuk pembayaran.',
    'details' => [],
]);
