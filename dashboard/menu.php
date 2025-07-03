<?php
// dashboard/menu.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
$user = $_SESSION['user'];
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
    $userx = isset($_POST['user']) ? 1 : 0;
    mysqli_query($conn, "INSERT INTO tb_menu (nama, url_nama, ikon, s_admin, admin, user) VALUES ('$nama', '$url_nama', '$ikon', $s_admin, $admin, $userx)");
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
    $userx = isset($_POST['user']) ? 1 : 0;
    mysqli_query($conn, "UPDATE tb_menu SET nama='$nama', url_nama='$url_nama', ikon='$ikon', s_admin=$s_admin, admin=$admin, user=$userx WHERE id=$id");
    header('Location: menu.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Menu - <?= htmlspecialchars($user['username']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/modal-fix.css">
    <style>
      .main-content { position: relative; z-index: 1; }
      .blur-sm { filter: blur(4px); transition: filter 0.2s; }
      .bg-hero { background-image: url('../assets/img/bg.jpg'); background-size: cover; background-position: center; background-attachment: fixed; position: relative; }
      .bg-hero::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); z-index: 0; }
      table.min-w-full { font-family: 'Arial', 'Helvetica', 'Tahoma', 'Geneva', 'Verdana', sans-serif; font-size: 12px; }
      .swal2-container { z-index: 1000001 !important; }
    </style>
</head>
<body class="bg-hero min-h-screen text-black font-sans antialiased">
  <div class="main-content min-h-screen p-6">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold flex items-center gap-2">
        <i class='bx bx-menu'></i> Kelola Menu
      </h1>
      <a href="index.php" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white shadow flex items-center gap-2">
        <i class='bx bx-arrow-back'></i> Kembali ke Dashboard
      </a>
    </div>
    <div class="mb-2 flex flex-wrap gap-2 items-center">
      <button id="tambahMenuBtn" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700" title="Tambah Menu">
        <i class='bx bx-plus'></i> Tambah Menu
      </button>
    </div>
    <div id="table-container">
      <table class="min-w-full border-collapse border border-gray-200 shadow-lg rounded-lg overflow-hidden text-xs bg-white text-black" style="width:100%">
        <thead class="bg-gray-200 text-black">
          <tr>
            <th class="py-2 px-3 w-10 border">No</th>
            <th class="py-2 px-3 w-40 text-left border">Nama</th>
            <th class="py-2 px-3 w-56 text-left border">URL</th>
            <th class="py-2 px-3 w-32 text-center border">Ikon</th>
            <th class="py-2 px-3 w-24 text-center border">Super Admin</th>
            <th class="py-2 px-3 w-24 text-center border">Admin</th>
            <th class="py-2 px-3 w-24 text-center border">User</th>
            <th class="py-2 px-3 w-32 text-center border">Aksi</th>
          </tr>
        </thead>
        <tbody id="menuDataBody">
          <?php $no=1; while($row = mysqli_fetch_assoc($menus)): ?>
          <tr>
            <td class="py-1 px-2 border text-center"><?= $no++ ?></td>
            <td class="py-1 px-2 border text-left"><?= htmlspecialchars($row['nama']) ?></td>
            <td class="py-1 px-2 border text-left"><?= htmlspecialchars($row['url_nama']) ?></td>
            <td class="py-1 px-2 border text-center">
              <?php if($row['ikon']): ?>
                <i class='bx <?= htmlspecialchars($row['ikon']) ?> text-xl'></i><br>
                <span class="text-xs text-gray-500"><?= htmlspecialchars($row['ikon']) ?></span>
              <?php endif; ?>
            </td>
            <td class="py-1 px-2 border text-center"><?= $row['s_admin'] ? '<i class="bx bx-check text-green-600"></i>' : '' ?></td>
            <td class="py-1 px-2 border text-center"><?= $row['admin'] ? '<i class="bx bx-check text-green-600"></i>' : '' ?></td>
            <td class="py-1 px-2 border text-center"><?= $row['user'] ? '<i class="bx bx-check text-green-600"></i>' : '' ?></td>
            <td class="py-1 px-2 border text-center flex gap-1 justify-center">
              <button class="editMenuBtn text-blue-600 hover:text-blue-800 font-bold py-1 px-1" 
                data-id="<?= $row['id'] ?>" 
                data-nama="<?= htmlspecialchars($row['nama']) ?>" 
                data-url_nama="<?= htmlspecialchars($row['url_nama']) ?>" 
                data-ikon="<?= htmlspecialchars($row['ikon']) ?>" 
                data-s_admin="<?= $row['s_admin'] ?>" 
                data-admin="<?= $row['admin'] ?>" 
                data-user="<?= $row['user'] ?>">
                <i class='bx bx-edit'></i>
              </button>
              <button type="button" class="deleteMenuBtn text-red-600 hover:text-red-800 font-bold py-1 px-1 ml-1" data-id="<?= $row['id'] ?>">
                <i class='bx bx-trash'></i>
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <!-- Modal Form Menu -->
    <div id="menuModal" class="modal-overlay hidden">
      <div class="modal-container bg-white text-black rounded-lg shadow-xl p-4 w-full max-w-xs max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b pb-2 mb-4 text-black">
          <h2 id="menuModalTitle" class="text-lg font-bold">Tambah Menu</h2>
        </div>
        <form id="menuForm" class="text-sm text-black" method="post">
          <input type="hidden" name="id" id="menu_id">
          <input type="hidden" name="action" id="menuFormAction" value="create">
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Nama Menu *</label>
            <input type="text" name="nama" id="menu_nama" class="w-full border px-2 py-0.5 rounded text-sm form-input" required>
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">URL *</label>
            <input type="text" name="url_nama" id="menu_url_nama" class="w-full border px-2 py-0.5 rounded text-sm form-input" required>
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Ikon (nama boxicon)</label>
            <input type="text" name="ikon" id="menu_ikon" class="w-full border px-2 py-0.5 rounded text-sm form-input">
          </div>
          <div class="mb-2 flex gap-2">
            <label class="flex items-center gap-1"><input type="checkbox" name="s_admin" id="menu_s_admin" class="accent-blue-500"> Super Admin</label>
            <label class="flex items-center gap-1"><input type="checkbox" name="admin" id="menu_admin" class="accent-blue-500"> Admin</label>
            <label class="flex items-center gap-1"><input type="checkbox" name="user" id="menu_user" class="accent-blue-500"> User</label>
          </div>
          <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" id="cancelMenuBtn" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" id="simpanMenuBtn">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
  // Modal logic
  $(document).ready(function() {
    // Tampilkan modal tambah menu
    $('#tambahMenuBtn').click(function() {
      $('#menuModalTitle').text('Tambah Menu');
      $('#menuFormAction').val('create');
      $('#menuForm')[0].reset();
      $('#menuModal').removeClass('hidden').addClass('modal-show');
      $('#menu_id').val('');
    });
    // Tutup modal
    $('#cancelMenuBtn').click(function() {
      $('#menuModal').removeClass('modal-show').addClass('hidden');
    });
    // Tampilkan modal edit menu
    $('.editMenuBtn').click(function() {
      $('#menuModalTitle').text('Edit Menu');
      $('#menuFormAction').val('edit');
      $('#menu_id').val($(this).data('id'));
      $('#menu_nama').val($(this).data('nama'));
      $('#menu_url_nama').val($(this).data('url_nama'));
      $('#menu_ikon').val($(this).data('ikon'));
      $('#menu_s_admin').prop('checked', $(this).data('s_admin') == 1);
      $('#menu_admin').prop('checked', $(this).data('admin') == 1);
      $('#menu_user').prop('checked', $(this).data('user') == 1);
      $('#menuModal').removeClass('hidden').addClass('modal-show');
    });
    // SweetAlert konfirmasi hapus
    $('.deleteMenuBtn').click(function() {
      const id = $(this).data('id');
      Swal.fire({
        title: 'Yakin hapus menu ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location = '?hapus=' + id;
        }
      });
    });
    // Submit form menu
    $('#menuForm').submit(function(e) {
      if ($('#menuFormAction').val() === 'edit') {
        $('<input>').attr({type: 'hidden', name: 'edit', value: '1'}).appendTo('#menuForm');
      } else {
        $('<input>').attr({type: 'hidden', name: 'tambah', value: '1'}).appendTo('#menuForm');
      }
    });
  });
  </script>
</body>
</html> 