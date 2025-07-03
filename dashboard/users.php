<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index');
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola User - <?= htmlspecialchars($user['username']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
  <link rel="stylesheet" href="../css/modal-fix.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="../css/style.css">
  
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
      <h1 class="text-2xl md:text-3xl font-bold">
        <i class='bx bx-user'></i> Kelola User
      </h1>
      <a href="index" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md text-white shadow flex items-center gap-2" title="Kembali ke Dashboard">
        <i class='bx bx-arrow-back text-xl'></i>
      </a>
    </div>
    <div class="mb-2 flex flex-wrap gap-2 items-center">
      <button id="tambahUserBtn" class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700" title="Tambah User">
        <i class='bx bx-plus text-xl'></i>
      </button>
    </div>
    <div id="table-container" class="overflow-x-auto">
      <table id="example" class="min-w-full w-full border-collapse border border-gray-200 shadow-lg rounded-lg overflow-hidden text-xs bg-white text-black" style="width:100%">
        <thead class="bg-gray-200 text-black">
          <tr>
            <th class="py-2 px-3 w-10 border">No</th>
            <th class="py-2 px-3 w-40 text-left border">Username</th>
            <th class="py-2 px-3 w-56 text-left border">Nama</th>
            <th class="py-2 px-3 w-32 text-center border">Role</th>
            <th class="py-2 px-3 w-24 text-center border">RT</th>
            <th class="py-2 px-3 w-24 text-center border">RW</th>
            <th class="py-2 px-3 w-24 text-center border">Status</th>
            <th class="py-2 px-3 w-32 text-center border">Aksi</th>
          </tr>
        </thead>
        <tbody id="userDataBody">
          <tr><td colspan="8" class="text-center text-gray-500">Loading...</td></tr>
        </tbody>
      </table>
    </div>
    <!-- Modal Form User -->
    <div id="userModal" class="modal-overlay hidden">
      <div class="modal-container bg-white text-black rounded-lg shadow-xl p-4 w-full max-w-xs max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b pb-2 mb-4 text-black">
          <h2 id="userModalTitle" class="text-lg font-bold">Tambah User</h2>
        </div>
        <form id="userForm" class="text-sm text-black">
          <input type="hidden" name="id_user" id="id_user">
          <input type="hidden" name="action" id="userFormAction" value="create">
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Username *</label>
            <input type="text" name="username" id="username" class="w-full border px-2 py-0.5 rounded text-sm form-input" required>
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Nama</label>
            <input type="text" name="name" id="name" class="w-full border px-2 py-0.5 rounded text-sm form-input">
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Role *</label>
            <select name="role" id="role" class="w-full border px-2 py-0.5 rounded text-sm form-input" required>
              <option value="">Pilih Role</option>
              <option value="s_admin">Super Admin</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
          <div class="mb-2" id="passwordField">
            <label class="block text-xs font-medium mb-0.5">Password *</label>
            <input type="password" name="password" id="password" class="w-full border px-2 py-0.5 rounded text-sm form-input" required autocomplete="new-password">
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Status</label>
            <select name="status" id="status" class="w-full border px-2 py-0.5 rounded text-sm form-input">
              <option value="1">Aktif</option>
              <option value="0">Nonaktif</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">RT</label>
            <input type="number" name="rt" id="rt" class="w-full border px-2 py-0.5 rounded text-sm form-input">
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">RW</label>
            <input type="number" name="rw" id="rw" class="w-full border px-2 py-0.5 rounded text-sm form-input">
          </div>
          <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" id="cancelUserBtn" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
          </div>
        </form>
      </div>
    </div>
    <!-- Modal Ubah Password -->
    <div id="passwordModal" class="modal-overlay hidden">
      <div class="modal-container bg-white text-black rounded-lg shadow-xl p-4 w-full max-w-xs max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b pb-2 mb-4 text-black">
          <h2 class="text-lg font-bold">Ubah Password</h2>
        </div>
        <form id="passwordForm" class="text-sm text-black">
          <input type="hidden" name="id_user_pw" id="id_user_pw">
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Username</label>
            <input type="text" id="username_pw" class="w-full border px-2 py-0.5 rounded text-sm form-input bg-gray-100" readonly>
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Nama</label>
            <input type="text" id="name_pw" class="w-full border px-2 py-0.5 rounded text-sm form-input bg-gray-100" readonly>
          </div>
          <div class="mb-2">
            <label class="block text-xs font-medium mb-0.5">Password Baru *</label>
            <input type="password" name="password_baru" id="password_baru" class="w-full border px-2 py-0.5 rounded text-sm form-input" required autocomplete="new-password">
          </div>
          <div class="flex justify-end gap-2 mt-4 pt-4 border-t">
            <button type="button" id="cancelPasswordBtn" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Batal</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <script src="../js/halaman_tabel.js"></script>
    <script>
    $(document).ready(function() {
      // Load data user
      function loadUsers() {
        $('#userDataBody').html('<tr><td colspan="8" class="text-center text-gray-500">Loading...</td></tr>');
        $.post('dashboard/api/users_action', { action: 'read' }, function(res) {
          if (res.success && res.data.length) {
            let html = '';
            res.data.forEach((u, i) => {
              html += `<tr>
                <td class="py-1 px-2 border text-center">${i+1}</td>
                <td class="py-1 px-2 border text-left">${u.username}</td>
                <td class="py-1 px-2 border text-left">${u.name || '-'}</td>
                <td class="py-1 px-2 border text-center">${u.role}</td>
                <td class="py-1 px-2 border text-center">${u.rt || '-'}</td>
                <td class="py-1 px-2 border text-center">${u.rw || '-'}</td>
                <td class="py-1 px-2 border text-center">${u.status == 1 ? 'Aktif' : 'Nonaktif'}</td>
                <td class="py-1 px-2 border text-center">
                  <button class="editUserBtn text-blue-600 hover:text-blue-800 font-bold py-1 px-1" data-username="${u.username}" data-role="${u.role}" data-rt="${u.rt}" data-rw="${u.rw}" data-name="${u.name}" data-status="${u.status}"><i class='bx bx-edit'></i></button>
                  <button class="deleteUserBtn text-red-600 hover:text-red-800 font-bold py-1 px-1 ml-1" data-username="${u.username}"><i class='bx bx-trash'></i></button>
                  <button class="pwUserBtn text-yellow-600 hover:text-yellow-800 font-bold py-1 px-1 ml-1" data-username="${u.username}" data-name="${u.name || ''}"><i class='bx bx-key'></i></button>
                </td>
              </tr>`;
            });
            $('#userDataBody').html(html);
            if (typeof initDataTable === 'function') {
              initDataTable();
            }
          } else {
            $('#userDataBody').html('<tr><td colspan="8" class="text-center text-gray-500">Tidak ada data user.</td></tr>');
          }
        }, 'json');
      }
      loadUsers();

      // Tampilkan modal tambah user
      $('#tambahUserBtn').click(function() {
        $('#userModalTitle').text('Tambah User');
        $('#userFormAction').val('create');
        $('#userForm')[0].reset();
        $('#userModal').removeClass('hidden').addClass('modal-show');
        $('#username').prop('readonly', false);
        $('#passwordField').show();
        $('#password').prop('required', true);
      });
      // Tutup modal user
      $('#cancelUserBtn').click(function() {
        $('#userModal').removeClass('modal-show').addClass('hidden');
        $('#passwordField').show();
        $('#password').prop('required', true);
      });
      // Submit form tambah/edit user
      $('#userForm').submit(function(e) {
        e.preventDefault();
        const action = $('#userFormAction').val();
        const username = $('#username').val().trim();
        const password = $('#password').val();
        const role = $('#role').val();
        const rt = $('#rt').val();
        const rw = $('#rw').val();
        const name = $('#name').val();
        const status = $('#status').val();
        if (!username || !role || (action==='create' && !password)) {
          Swal.fire({
            title: 'Lengkapi data!',
            icon: 'warning',
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
          });
          return;
        }
        let data = { action, username, role, rt, rw, name, status };
        if (action === 'create') data.password = password;
        if (action === 'update') data.old_username = $('#userForm').data('old_username');
        if (action === 'create' || action === 'update') {
          if (action === 'update' && password) data.password = password;
          $.post('dashboard/api/users_action', data, function(res) {
            if (res.success) {
              Swal.fire({
                title: 'Berhasil',
                text: 'Data user disimpan',
                icon: 'success',
                timer: 2000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
              });
              $('#userModal').removeClass('modal-show').addClass('hidden');
              $('#passwordField').show();
              $('#password').prop('required', true);
              loadUsers();
            } else {
              Swal.fire({
                title: 'Gagal',
                text: res.message || 'Gagal simpan user',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          }, 'json');
        }
      });
      // Edit user
      $(document).on('click', '.editUserBtn', function() {
        const username = $(this).data('username');
        const role = $(this).data('role');
        const rt = $(this).data('rt') || '';
        const rw = $(this).data('rw') || '';
        const name = $(this).data('name') || '';
        const status = $(this).data('status') || '1';
        $('#userModalTitle').text('Edit User');
        $('#userFormAction').val('update');
        $('#username').val(username).prop('readonly', true);
        $('#role').val(role);
        $('#passwordField').hide();
        $('#password').val('').prop('required', false);
        $('#userForm').data('old_username', username);
        $('#rt').val(rt);
        $('#rw').val(rw);
        $('#name').val(name);
        $('#status').val(status);
        $('#userModal').removeClass('hidden').addClass('modal-show');
      });
      // Hapus user
      $(document).on('click', '.deleteUserBtn', function() {
        const username = $(this).data('username');
        Swal.fire({
          title: 'Hapus User?',
          text: `Yakin hapus user ${username}?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            $.post('dashboard/api/users_action', { action: 'delete', username }, function(res) {
              if (res.success) {
                Swal.fire({
                  title: 'Berhasil',
                  text: 'User dihapus',
                  icon: 'success',
                  timer: 2000,
                  timerProgressBar: true,
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false
                });
                loadUsers();
              } else {
                Swal.fire({
                  title: 'Gagal',
                  text: res.message || 'Gagal hapus user',
                  icon: 'error',
                  confirmButtonText: 'OK'
                });
              }
            }, 'json');
          }
        });
      });
      // Modal ubah password
      $(document).on('click', '.pwUserBtn', function() {
        const username = $(this).data('username');
        const name = $(this).data('name') || '';
        $('#id_user_pw').val(username);
        $('#username_pw').val(username);
        $('#name_pw').val(name);
        $('#password_baru').val('');
        $('#passwordModal').removeClass('hidden').addClass('modal-show');
      });
      // Tutup modal password
      $('#cancelPasswordBtn').click(function() {
        $('#passwordModal').removeClass('modal-show').addClass('hidden');
      });
      // Submit ubah password
      $('#passwordForm').submit(function(e) {
        e.preventDefault();
        const username = $('#id_user_pw').val();
        const password = $('#password_baru').val();
        if (!password) {
          Swal.fire({
            title: 'Isi password baru!',
            icon: 'warning',
            timer: 2000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false
          });
          return;
        }
        $.post('dashboard/api/users_action', { action: 'change_password', username, password }, function(res) {
          if (res.success) {
            Swal.fire({
              title: 'Berhasil',
              text: 'Password diubah',
              icon: 'success',
              timer: 2000,
              timerProgressBar: true,
              toast: true,
              position: 'top-end',
              showConfirmButton: false
            });
            $('#passwordModal').removeClass('modal-show').addClass('hidden');
          } else {
            Swal.fire({
              title: 'Gagal',
              text: res.message || 'Gagal ubah password',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          }
        }, 'json');
      });
    });
    </script>
  </div>
  <footer class="fixed bottom-2 right-4 text-xs text-gray-300 bg-black/30 px-3 py-1 rounded-lg backdrop-blur-md">
    &copy; 2025 Sistem Data Warga by Abiy Doni
  </footer>
</body>
</html> 