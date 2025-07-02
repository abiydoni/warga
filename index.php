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
      background-image: url('https://images.unsplash.com/photo-1570135996210-44a68f0344ec?auto=format&fit=crop&w=1920&q=80');
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
        Kelola data warga, iuran, dan laporan secara digital dan mudah.
      </p>
      <button onclick="showLogin()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full shadow-lg transition transform hover:scale-105 hover:shadow-xl text-lg font-medium">
        <i class='bx bx-log-in-circle mr-2 text-xl'></i> Login Admin
      </button>
    </div>
  </div>

  <!-- Footer -->
  <footer class="absolute bottom-4 w-full text-center text-sm text-gray-300">
    &copy; <?= date("Y") ?> Sistem Data Warga by Abiy
  </footer>

  <!-- SweetAlert Login (Demo) -->
  <script>
    function showLogin() {
      Swal.fire({
        title: 'Login Admin',
        html: `
          <input type="text" id="username" class="swal2-input" placeholder="Username">
          <input type="password" id="password" class="swal2-input" placeholder="Password">
        `,
        confirmButtonText: 'Masuk',
        showCancelButton: true,
        focusConfirm: false,
        preConfirm: () => {
          const username = Swal.getPopup().querySelector('#username').value
          const password = Swal.getPopup().querySelector('#password').value
          if (!username || !password) {
            Swal.showValidationMessage(`Harap isi username dan password`)
          }
          return { username: username, password: password }
        }
      }).then((result) => {
        if (result.isConfirmed) {
          // Simulasi login, arahkan ke halaman login.php
          window.location.href = 'login.php';
        }
      })
    }
  </script>

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

</body>
</html>
