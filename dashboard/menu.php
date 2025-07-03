<?php
// dashboard/menu.php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
$user = $_SESSION['user'];
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
            <tr><td colspan="6" class="text-center text-gray-500">Loading...</td></tr>
        </tbody>
      </table>
    </div>
    <!-- Modal Form Menu -->
    <div id="menuModal" class="modal-overlay hidden">
      <div class="modal-container bg-white text-black rounded-lg shadow-xl p-4 w-full max-w-xs max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b pb-2 mb-4 text-black">
          <h2 id="menuModalTitle" class="text-lg font-bold">Tambah Menu</h2>
        </div>
        <form id="menuForm" class="text-sm text-black">
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
            <label class="flex items-center gap-1">
              <input type="checkbox" id="menu_s_admin" class="accent-blue-500">
              <input type="hidden" name="s_admin" id="hidden_s_admin" value="0">
              Super Admin
            </label>
            <label class="flex items-center gap-1">
              <input type="checkbox" id="menu_admin" class="accent-blue-500">
              <input type="hidden" name="admin" id="hidden_admin" value="0">
              Admin
            </label>
            <label class="flex items-center gap-1">
              <input type="checkbox" id="menu_user" class="accent-blue-500">
              <input type="hidden" name="user" id="hidden_user" value="0">
              User
            </label>
          </div>
          <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" id="cancelMenuBtn" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" id="simpanMenuBtn">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <footer class="fixed bottom-2 right-4 text-xs text-gray-300 bg-black/30 px-3 py-1 rounded-lg backdrop-blur-md">
    &copy; 2025 Sistem Data Warga by Abiy Doni
  </footer>
  <script>
  $(document).ready(function() {
    // Load data menu
    function loadMenus() {
      $('#menuDataBody').html('<tr><td colspan="8" class="text-center text-gray-500">Loading...</td></tr>');
      $.post('api/menu_action.php', { action: 'read' }, function(res) {
        if (res.success && res.data.length) {
          let html = '';
          res.data.forEach((m, i) => {
            html += `<tr>
              <td class="py-1 px-2 border text-center">${i+1}</td>
              <td class="py-1 px-2 border text-left">${m.nama}</td>
              <td class="py-1 px-2 border text-left">${m.url_nama}</td>
              <td class="py-1 px-2 border text-center">${m.ikon ? `<i class='bx ${m.ikon} text-xl'></i><br><span class='text-xs text-gray-500'>${m.ikon}</span>` : ''}</td>
              <td class="py-1 px-2 border text-center">${m.s_admin == 1 ? '<i class=\'bx bx-check text-green-600\'></i>' : ''}</td>
              <td class="py-1 px-2 border text-center">${m.admin == 1 ? '<i class=\'bx bx-check text-green-600\'></i>' : ''}</td>
              <td class="py-1 px-2 border text-center border-r-0">${m.user == 1 ? '<i class=\'bx bx-check text-green-600\'></i>' : ''}</td>
              <td class="py-1 px-2 border text-center">
                <button class=\"editMenuBtn text-blue-600 hover:text-blue-800 font-bold py-1 px-1\"
                  data-id=\"${m.id}\"
                  data-nama=\"${$('<div>').text(m.nama).html()}\"
                  data-url_nama=\"${$('<div>').text(m.url_nama).html()}\"
                  data-ikon=\"${$('<div>').text(m.ikon).html()}\"
                  data-s_admin=\"${m.s_admin}\"
                  data-admin=\"${m.admin}\"
                  data-user=\"${m.user}\">
                  <i class='bx bx-edit'></i>
                </button>
                <button type=\"button\" class=\"deleteMenuBtn text-red-600 hover:text-red-800 font-bold py-1 px-1 ml-1\" data-id=\"${m.id}\">
                  <i class='bx bx-trash'></i>
                </button>
              </td>
            </tr>`;
          });
          $('#menuDataBody').html(html);
        } else {
          $('#menuDataBody').html('<tr><td colspan="8" class="text-center text-gray-500">Tidak ada data menu.</td></tr>');
        }
      }, 'json');
    }
    loadMenus();

    // Sinkronisasi checkbox dan input hidden (pakai event delegation)
    $(document).on('change', '#menu_s_admin', function() {
      $('#hidden_s_admin').val(this.checked ? 1 : 0);
    });
    $(document).on('change', '#menu_admin', function() {
      $('#hidden_admin').val(this.checked ? 1 : 0);
    });
    $(document).on('change', '#menu_user', function() {
      $('#hidden_user').val(this.checked ? 1 : 0);
    });

    // Saat buka modal edit/tambah, set hidden sesuai checkbox
    function syncHiddenCheckbox() {
      $('#hidden_s_admin').val($('#menu_s_admin').is(':checked') ? 1 : 0);
      $('#hidden_admin').val($('#menu_admin').is(':checked') ? 1 : 0);
      $('#hidden_user').val($('#menu_user').is(':checked') ? 1 : 0);
    }
    // Tambah menu
    $('#tambahMenuBtn').click(function() {
      $('#menuModalTitle').text('Tambah Menu');
      $('#menuFormAction').val('create');
      $('#menuForm')[0].reset();
      $('#menuModal').removeClass('hidden').addClass('modal-show');
      $('#menu_id').val('');
      syncHiddenCheckbox();
    });
    // Tutup modal
    $('#cancelMenuBtn').click(function() {
      $('#menuModal').removeClass('modal-show').addClass('hidden');
    });
    // Tampilkan modal edit menu (delegasi karena tombol dinamis)
    $(document).on('click', '.editMenuBtn', function() {
      $('#menuForm')[0].reset();
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
      syncHiddenCheckbox();
    });
    // Submit form tambah/edit menu (pakai event delegation agar selalu terpasang)
    $(document).on('submit', '#menuForm', function(e) {
      e.preventDefault();
      syncHiddenCheckbox(); // Pastikan nilai hidden sinkron
      console.log(
        's_admin checkbox:', $('#menu_s_admin').is(':checked'),
        's_admin hidden:', $('#hidden_s_admin').val(),
        'admin checkbox:', $('#menu_admin').is(':checked'),
        'admin hidden:', $('#hidden_admin').val(),
        'user checkbox:', $('#menu_user').is(':checked'),
        'user hidden:', $('#hidden_user').val()
      );
      const action = $('#menuFormAction').val() === 'edit' ? 'update' : 'create';
      const id = $('#menu_id').val();
      const nama = $('#menu_nama').val();
      const url_nama = $('#menu_url_nama').val();
      const ikon = $('#menu_ikon').val();
      const s_admin = $('#hidden_s_admin').val();
      const admin = $('#hidden_admin').val();
      const user = $('#hidden_user').val();
      const data = { action, id, nama, url_nama, ikon, s_admin, admin, user };
      console.log('Data yang dikirim ke backend:', data);
      $.post('api/menu_action.php', data, function(res) {
        if (res.success) {
          $('#menuModal').removeClass('modal-show').addClass('hidden');
          loadMenus();
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Menu berhasil disimpan.',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
          });
        } else {
          Swal.fire('Gagal', res.message || 'Gagal menyimpan menu', 'error');
        }
      }, 'json');
    });
    // SweetAlert konfirmasi hapus (delegasi)
    $(document).on('click', '.deleteMenuBtn', function() {
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
          $.post('api/menu_action.php', { action: 'delete', id }, function(res) {
            if (res.success) {
              loadMenus();
              Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Menu berhasil dihapus.',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
              });
            } else {
              Swal.fire('Gagal', res.message || 'Gagal menghapus menu', 'error');
            }
          }, 'json');
        }
      });
    });
  });
  </script>
</body>
</html> 