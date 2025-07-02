<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php'); // Redirect to login page
    exit;
}

// Check if user is admin
if (!in_array($_SESSION['user']['role'], ['pengurus', 'admin', 's_admin'])) {
    header('Location: ../login.php');
    exit;
}

// Include the database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $name = $_POST['name'];
    $shift = $_POST['shift'];
    $role = $_POST['role'];
    $id_code = $_POST['id_code']; // Menambahkan id_code untuk query

    // Validasi input
    if (empty($user_name) || empty($name) || empty($shift) || empty($role)) {
        session_start();
        $_SESSION['swal'] = ['msg' => 'Input tidak boleh kosong!', 'icon' => 'error'];
        header('Location: ../jadwal.php');
        exit();
    }

    // Update user data in the database
    $sql = "UPDATE users SET user_name = ?, name = ?, shift = ?, role = ? WHERE id_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_name, $name, $shift, $role, $id_code]);
    session_start();
    $_SESSION['swal'] = ['msg' => 'Data berhasil diperbarui!', 'icon' => 'success'];
    header('Location: ../jadwal.php');
    exit();
}
?>