<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pengelolaan Data Warga</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Boxicons -->
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-image: url('assets/img/bg.jpg'); /* Ganti sesuai gambar */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    .bg-overlay {
      background: rgba(0, 0, 0, 0.6);
    }
  </style>
</head>
<body class="h-screen w-full text-white font-sans antialiased">

  <!-- Overlay -->
  <div class="bg-overlay h-screen flex items-center justify-center">
    <div class="text-center px-6">
      <h1 class="text-4xl md:text-6xl font-bold mb-4 animate-fade-in-up">Sistem Pengelolaan Data Warga</h1>
      <p class="text-lg md:text-xl mb-6 text-gray-200 animate-fade-in-up delay-200">
        Kelola data warga dan laporan secara digital dan mudah.
      </p>
      <button onclick="showLogin()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transition transform hover:scale-105 hover:shadow-xl text-lg font-medium">
        <i class='bx bx-log-in-circle mr-2 text-xl'></i> Login Admin
      </button>
    </div>
  </div>

  <!-- Footer -->
  <footer class="absolute bottom-4 w-full text-center text-sm text-gray-300">
    &copy; <?= date("Y") ?> Sistem Data Warga by Abiy Doni
  </footer>

  <!-- Tailwind animation utilities -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          animation: {
            'fade-in-up': 'fadeInUp 1s ease-out forwards',
          },
          keyframes: {
            fadeInUp: {
              '0%': { opacity: 0, transform: 'translateY(20px)' },
              '100%': { opacity: 1, transform: 'translateY(0)' },
            }
          }
        }
      }
    }
  </script>

  <!-- SweetAlert Login -->
  <script>
    function showLogin() {
      Swal.fire({
        title: '<span class="text-white font-bold text-2xl">🔐 Login Admin</span>',
        html: `
          <div class="flex flex-col gap-4 text-left text-white">
            <div class="relative">
              <i class='bx bx-user absolute left-3 top-3 text-xl text-blue-300'></i>
              <input id="username" type="text" placeholder="Username" class="pl-10 py-2 w-full rounded-md bg-white/10 border border-blue-300 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder:text-gray-300" />
            </div>
            <div class="relative">
              <i class='bx bx-lock-alt absolute left-3 top-3 text-xl text-blue-300'></i>
              <input id="password" type="password" placeholder="Password" class="pl-10 py-2 w-full rounded-md bg-white/10 border border-blue-300 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none placeholder:text-gray-300" />
            </div>
          </div>
        `,
        background: 'rgba(30, 41, 59, 0.6)',
        showCancelButton: true,
        confirmButtonText: 'Masuk',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        customClass: {
          popup: 'backdrop-blur-xl rounded-xl border border-blue-500',
          confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition',
          cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded transition'
        },
        preConfirm: () => {
          const username = Swal.getPopup().querySelector('#username').value
          const password = Swal.getPopup().querySelector('#password').value
          if (!username || !password) {
            Swal.showValidationMessage('Harap isi Username dan Password')
          }
          return { username: username, password: password }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const username = result.value.username;
          const password = result.value.password;

          fetch('cek_login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
              username: username,
              password: password,
              redirect_option: 'dashboard'
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              Swal.fire({
                icon: 'success',
                title: 'Login Berhasil!',
                showConfirmButton: false,
                timer: 1000
              }).then(() => {
                window.location.href = 'dashboard';
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: data.error || 'Gagal login!',
                background: 'rgba(15,23,42,0.9)',
                color: '#fff'
              });
            }
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: 'Terjadi Kesalahan!',
              text: 'Tidak dapat menghubungi server.',
              background: '#1e293b',
              color: '#fff'
            });
          });
        }
      });
    }
  </script>
</body>
</html>
