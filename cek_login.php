<?php
session_start();
require_once 'api/db.php';
header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$redirect = $_POST['redirect_option'] ?? '/dashboard';

try {
    $stmt = $pdo->prepare("SELECT * FROM tb_user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // ✅ Cek password MD5
    if ($user && md5($password) === $user['password']) {
        $_SESSION['user'] = $user;

        // Ambil menu berdasarkan role string
        $role = $user['role'];
        switch ($role) {
            case "s_admin": // s_admin
                $stmtMenu = $pdo->query("SELECT * FROM tb_menu WHERE s_admin = 1 ORDER BY nama ASC");
                break;
            case "admin": // admin
                $stmtMenu = $pdo->query("SELECT * FROM tb_menu WHERE admin = 1 ORDER BY nama ASC");
                break;
            case "user": // user
                $stmtMenu = $pdo->query("SELECT * FROM tb_menu WHERE user = 1 ORDER BY nama ASC");
                break;
            default:
                $stmtMenu = $pdo->query("SELECT * FROM tb_menu WHERE 1 = 0");
        }

        // Debug: tampilkan hasil query menu sebelum masuk ke session
        $menus_debug = $stmtMenu->fetchAll();
        file_put_contents('debug_menu.txt', print_r($menus_debug, true)); // simpan ke file untuk dicek manual
        $_SESSION['menus'] = $menus_debug;

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Username atau password salah']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
