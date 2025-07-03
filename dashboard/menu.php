<?php
// dashboard/menu.php
include 'api/db.php';

// Ambil semua menu
$menus = mysqli_query($conn, "SELECT * FROM tb_menu");

// Fungsi untuk menambah menu
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $url_nama = $_POST['url_nama'];
    $ikon = $_POST['ikon'];
    $s_admin = isset($_POST['s_admin']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;
    $user = isset($_POST['user']) ? 1 : 0;
    mysqli_query($conn, "INSERT INTO tb_menu (nama, url_nama, ikon, s_admin, admin, user) VALUES ('$nama', '$url_nama', '$ikon', $s_admin, $admin, $user)");
    header('Location: menu.php');
    exit;
}

// Fungsi untuk menghapus menu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tb_menu WHERE id=$id");
    header('Location: menu.php');
    exit;
}

// Fungsi untuk edit menu
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $url_nama = $_POST['url_nama'];
    $ikon = $_POST['ikon'];
    $s_admin = isset($_POST['s_admin']) ? 1 : 0;
    $admin = isset($_POST['admin']) ? 1 : 0;
    $user = isset($_POST['user']) ? 1 : 0;
    mysqli_query($conn, "UPDATE tb_menu SET nama='$nama', url_nama='$url_nama', ikon='$ikon', s_admin=$s_admin, admin=$admin, user=$user WHERE id=$id");
    header('Location: menu.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Menu</title>
    <link rel="stylesheet" href="../css/modal-fix.css">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background: #eee; }
        form { margin: 0; }
    </style>
</head>
<body>
    <h2>Manajemen Menu</h2>
    <form method="post">
        <input type="text" name="nama" placeholder="Nama Menu" required>
        <input type="text" name="url_nama" placeholder="URL" required>
        <input type="text" name="ikon" placeholder="Ikon">
        <label><input type="checkbox" name="s_admin"> Super Admin</label>
        <label><input type="checkbox" name="admin"> Admin</label>
        <label><input type="checkbox" name="user"> User</label>
        <button type="submit" name="tambah">Tambah</button>
    </form>
    <br>
    <table>
        <tr>
            <th>Nama</th>
            <th>URL</th>
            <th>Ikon</th>
            <th>Super Admin</th>
            <th>Admin</th>
            <th>User</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($menus)): ?>
        <tr>
            <form method="post">
                <td><input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required></td>
                <td><input type="text" name="url_nama" value="<?= htmlspecialchars($row['url_nama']) ?>" required></td>
                <td><input type="text" name="ikon" value="<?= htmlspecialchars($row['ikon']) ?>"></td>
                <td><input type="checkbox" name="s_admin" <?= $row['s_admin'] ? 'checked' : '' ?>></td>
                <td><input type="checkbox" name="admin" <?= $row['admin'] ? 'checked' : '' ?>></td>
                <td><input type="checkbox" name="user" <?= $row['user'] ? 'checked' : '' ?>></td>
                <td>
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="edit">Simpan</button>
                    <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus menu ini?')">Hapus</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html> 