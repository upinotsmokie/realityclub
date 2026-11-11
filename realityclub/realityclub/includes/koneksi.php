<?php
// =====================================================================
// Pengaturan koneksi database (XAMPP default: user root, password kosong)
// Ubah nilai di bawah ini jika pengaturan MySQL kamu berbeda.
// =====================================================================
const DB_HOST = 'localhost';
const DB_NAME = 'realityclub';
const DB_USER = 'root';
const DB_PASS = '';

function json_out(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $ex) {
        error_log('Koneksi database gagal: ' . $ex->getMessage());
        if (defined('API_MODE')) {
            json_out(['ok' => false, 'message' => 'Server database tidak dapat dihubungi.'], 500);
        }
        http_response_code(500);
        exit('Database belum terhubung. Pastikan MySQL di XAMPP sudah Start, database realityclub sudah di-import, dan pengaturan di includes/koneksi.php sudah benar.');
    }
    return $pdo;
}
