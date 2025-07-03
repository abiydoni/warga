<?php
header('Content-Type: application/json');
require_once 'db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// Ambil role user login dari session jika ada
session_start();
$current_role = $_SESSION['user']['role'] ?? '';

function response($data) {
    echo json_encode($data);
    exit;
}

if ($action === 'read') {
    if ($current_role === 'admin') {
        $stmt = $pdo->query("SELECT username, role, rt, rw, name, status FROM tb_user WHERE role IN ('admin','user') ORDER BY username ASC");
    } else {
        $stmt = $pdo->query('SELECT username, role, rt, rw, name, status FROM tb_user ORDER BY username ASC');
    }
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    response(['success' => true, 'data' => $users]);
}

if ($action === 'create') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $rt = $_POST['rt'] ?? '';
    $rw = $_POST['rw'] ?? '';
    $name = $_POST['name'] ?? '';
    $status = isset($_POST['status']) ? $_POST['status'] : 1;
    if (!$username || !$password || !$role) response(['success' => false, 'message' => 'Lengkapi data!']);
    // Batasi admin hanya bisa membuat admin/user
    if ($current_role === 'admin' && !in_array($role, ['admin','user'])) {
        response(['success' => false, 'message' => 'Admin hanya bisa membuat user admin/user!']);
    }
    // Cek username unik
    $cek = $pdo->prepare('SELECT COUNT(*) FROM tb_user WHERE username = ?');
    $cek->execute([$username]);
    if ($cek->fetchColumn() > 0) response(['success' => false, 'message' => 'Username sudah terdaftar!']);
    $hash = md5($password);
    $stmt = $pdo->prepare('INSERT INTO tb_user (username, password, role, rt, rw, name, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $ok = $stmt->execute([$username, $hash, $role, $rt, $rw, $name, $status]);
    response(['success' => $ok]);
}

if ($action === 'update') {
    $username = $_POST['username'] ?? '';
    $role = $_POST['role'] ?? '';
    $old_username = $_POST['old_username'] ?? '';
    $rt = $_POST['rt'] ?? '';
    $rw = $_POST['rw'] ?? '';
    $name = $_POST['name'] ?? '';
    $status = isset($_POST['status']) ? $_POST['status'] : 1;
    if (!$username || !$role || !$old_username) response(['success' => false, 'message' => 'Lengkapi data!']);
    // Batasi admin hanya bisa update ke admin/user
    if ($current_role === 'admin' && !in_array($role, ['admin','user'])) {
        response(['success' => false, 'message' => 'Admin hanya bisa mengubah ke role admin/user!']);
    }
    // Cek username unik jika diubah
    if ($username !== $old_username) {
        $cek = $pdo->prepare('SELECT COUNT(*) FROM tb_user WHERE username = ?');
        $cek->execute([$username]);
        if ($cek->fetchColumn() > 0) response(['success' => false, 'message' => 'Username sudah terdaftar!']);
    }
    $stmt = $pdo->prepare('UPDATE tb_user SET username=?, role=?, rt=?, rw=?, name=?, status=? WHERE username=?');
    $ok = $stmt->execute([$username, $role, $rt, $rw, $name, $status, $old_username]);
    response(['success' => $ok]);
}

if ($action === 'delete') {
    $username = $_POST['username'] ?? '';
    if (!$username) response(['success' => false, 'message' => 'Username kosong!']);
    $stmt = $pdo->prepare('DELETE FROM tb_user WHERE username=?');
    $ok = $stmt->execute([$username]);
    response(['success' => $ok]);
}

if ($action === 'change_password') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!$username || !$password) response(['success' => false, 'message' => 'Lengkapi data!']);
    $hash = md5($password);
    $stmt = $pdo->prepare('UPDATE tb_user SET password=? WHERE username=?');
    $ok = $stmt->execute([$hash, $username]);
    response(['success' => $ok]);
}

response(['success' => false, 'message' => 'Aksi tidak dikenali!']); 