<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

$user = $_SESSION['user'];
$menus = $_SESSION['menus'] ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - <?= htmlspecialchars($user['username']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Boxicons -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .menu-card:hover {
      transform: scale(1.05);
      transition: transform 0.3s ease-in-out;
    }
    .bg-hero {
      background-image: url('../assets/img/bg.jpg');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
  </style>
</head>
<body class="bg-hero min-h-screen text-white font-sans antialiased">

  <div class="bg-black bg-opacity-60 min-h-screen p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-2xl md:text-3xl font-bold">
        👋 Selamat datang, <?= htmlspecialchars($user['name']) ?>!
      </h1>
      <button onclick="logout()" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white shadow" title="Logout">
        <i class='bx bx-log-out text-xl'></i>
      </button>
    </div>

    <!-- Menu Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($menus as $menu): ?>
        <a href="<?= htmlspecialchars($menu['url_nama']) ?>" class="menu-card bg-white/10 border border-white/20 rounded-lg p-6 text-center shadow-lg hover:shadow-xl backdrop-blur-md">
          <div class="text-4xl mb-3 text-blue-400">
            <i class='bx <?= htmlspecialchars($menu['ikon']) ?>'></i>
          </div>
          <h2 class="text-lg font-semibold"><?= htmlspecialchars($menu['nama']) ?></h2>
        </a>
      <?php endforeach; ?>
    </div>

  </div>
    <!-- Footer -->
    <footer class="fixed bottom-2 right-4 text-xs text-gray-300 bg-black/30 px-3 py-1 rounded-lg backdrop-blur-md">
    &copy; 2025 Sistem Data Warga by Abiy Doni
    </footer>

  <!-- Logout -->
  <script>
    function logout() {
      Swal.fire({
        title: 'Keluar?',
        text: 'Apakah kamu yakin ingin logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        background: '#1e293b',
        color: '#fff',
        customClass: {
          confirmButton: 'bg-red-600 text-white px-4 py-2 rounded',
          cancelButton: 'bg-gray-300 text-black px-4 py-2 rounded'
        }
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '../logout.php';
        }
      });
    }
  </script>

</body>
</html>
