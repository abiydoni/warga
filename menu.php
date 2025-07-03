<?php
require_once __DIR__.'/dashboard/api/db.php';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = intval($_GET['limit'] ?? 10);
$offset = ($page-1)*$limit;
$where = $search ? "WHERE nama LIKE :s OR url_nama LIKE :s OR ikon LIKE :s" : "";
$sql = "SELECT * FROM tb_menu $where ORDER BY id ASC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
if ($search) $stmt->bindValue(':s', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$menus = $stmt->fetchAll();
// Hitung total
$sqlCount = "SELECT COUNT(*) FROM tb_menu $where";
$stmtCount = $pdo->prepare($sqlCount);
if ($search) $stmtCount->bindValue(':s', "%$search%", PDO::PARAM_STR);
$stmtCount->execute();
$total = $stmtCount->fetchColumn();
$totalPages = max(1, ceil($total/$limit));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Menu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/table-style.css">
</head>
<body>
<div class="table-responsive">
  <div class="table-controls">
    <form method="get" style="display:inline-block;">
      <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari menu...">
      <button type="submit">Cari</button>
    </form>
    <form method="get" style="display:inline-block;">
      <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
      <select name="limit" onchange="this.form.submit()">
        <?php foreach ([10,25,50,100] as $opt): ?>
        <option value="<?= $opt ?>" <?= $limit==$opt?'selected':'' ?>><?= $opt ?> baris</option>
        <?php endforeach; ?>
      </select>
    </form>
  </div>
  <table class="custom-table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>URL</th>
        <th>Ikon</th>
        <th>Super Admin</th>
        <th>Admin</th>
        <th>User</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($menus as $m): ?>
      <tr>
        <td><?= htmlspecialchars($m['nama']) ?></td>
        <td><?= htmlspecialchars($m['url_nama']) ?></td>
        <td><i class="bx <?= htmlspecialchars($m['ikon']) ?> text-lg"></i> <span class="text-xs text-gray-500"><?= htmlspecialchars($m['ikon']) ?></span></td>
        <td style="text-align:center;"><?= $m['s_admin'] ? '✔️' : '' ?></td>
        <td style="text-align:center;"><?= $m['admin'] ? '✔️' : '' ?></td>
        <td style="text-align:center;"><?= $m['user'] ? '✔️' : '' ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if(!$menus): ?><tr><td colspan="6" style="text-align:center;">Tidak ada data.</td></tr><?php endif; ?>
    </tbody>
  </table>
  <div class="pagination">
    <?php if($page>1): ?><a href="?search=<?=urlencode($search)?>&limit=<?=$limit?>&page=<?=($page-1)?>"><button>&laquo;</button></a><?php endif; ?>
    <?php for($i=1;$i<=$totalPages;$i++): ?>
      <?php if($i==$page): ?><span class="active"><?=$i?></span>
      <?php else: ?><a href="?search=<?=urlencode($search)?>&limit=<?=$limit?>&page=<?=$i?>"><button><?=$i?></button></a><?php endif; ?>
    <?php endfor; ?>
    <?php if($page<$totalPages): ?><a href="?search=<?=urlencode($search)?>&limit=<?=$limit?>&page=<?=($page+1)?>"><button>&raquo;</button></a><?php endif; ?>
  </div>
</div>
</body>
</html> 