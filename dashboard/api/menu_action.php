<?php
header('Content-Type: application/json');
require_once 'db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

session_start();
$current_role = $_SESSION['user']['role'] ?? '';

function response($data) {
    echo json_encode($data);
    exit;
}

if ($action === 'read') {
    $stmt = $pdo->query('SELECT * FROM tb_menu ORDER BY id ASC');
    $menus = $stmt->fetchAll();
    response(['success' => true, 'data' => $menus]);
}

if ($action === 'create') {
    $nama = $_POST['nama'] ?? '';
    $url_nama = $_POST['url_nama'] ?? '';
    $ikon = $_POST['ikon'] ?? '';
    $s_admin = isset($_POST['s_admin']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;
    $user = isset($_POST['user']) ? 1 : 0;
    if (!$nama || !$url_nama) response(['success' => false, 'message' => 'Lengkapi data!']);
    $stmt = $pdo->prepare('INSERT INTO tb_menu (nama, url_nama, ikon, s_admin, admin, user) VALUES (?, ?, ?, ?, ?, ?)');
    $ok = $stmt->execute([$nama, $url_nama, $ikon, $s_admin, $admin, $user]);
    response(['success' => $ok]);
}

if ($action === 'update') {
    $id = $_POST['id'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $url_nama = $_POST['url_nama'] ?? '';
    $ikon = $_POST['ikon'] ?? '';
    $s_admin = isset($_POST['s_admin']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;
    $user = isset($_POST['user']) ? 1 : 0;
    // Log debug POST data
    file_put_contents(__DIR__ . '/../debug_menu.txt', date('Y-m-d H:i:s') . ' ' . json_encode($_POST) . PHP_EOL, FILE_APPEND);
    if (!$id || !$nama || !$url_nama) response(['success' => false, 'message' => 'Lengkapi data!']);
    $stmt = $pdo->prepare('UPDATE tb_menu SET nama=?, url_nama=?, ikon=?, s_admin=?, admin=?, user=? WHERE id=?');
    $ok = $stmt->execute([$nama, $url_nama, $ikon, $s_admin, $admin, $user, $id]);
    response(['success' => $ok]);
}

if ($action === 'delete') {
    $id = $_POST['id'] ?? '';
    if (!$id) response(['success' => false, 'message' => 'ID kosong!']);
    $stmt = $pdo->prepare('DELETE FROM tb_menu WHERE id=?');
    $ok = $stmt->execute([$id]);
    response(['success' => $ok]);
}

response(['success' => false, 'message' => 'Aksi tidak dikenali!']); 