<?php
require_once __DIR__.'/dashboard/api/db.php';
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$limit = intval($_GET['limit'] ?? 10);
$offset = ($page-1)*$limit;
$where = $search ? "WHERE username LIKE :s OR name LIKE :s OR role LIKE :s" : "";
$sql = "SELECT * FROM tb_user $where ORDER BY username ASC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
if ($search) $stmt->bindValue(':s', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();
// Hitung total
$sqlCount = "SELECT COUNT(*) FROM tb_user $where";
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
  <title>Daftar User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/table-style.css">
</head>
<body>
<div class="table-responsive">
  <div class="table-controls">
    <form method="get" style="display:inline-block;">
      <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari user...">
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
        <th>Username</th>
        <th>Nama</th>
        <th>Role</th>
        <th>RT</th>
        <th>RW</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($users as $u): ?>
      <tr>
        <td><?= htmlspecialchars($u['username']) ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['role']) ?></td>
        <td><?= htmlspecialchars($u['rt']) ?></td>
        <td><?= htmlspecialchars($u['rw']) ?></td>
        <td><?= $u['status'] ? 'Aktif' : 'Nonaktif' ?></td>
      </tr>
      <?php endforeach; ?>
      <?php if(!$users): ?><tr><td colspan="6" style="text-align:center;">Tidak ada data.</td></tr><?php endif; ?>
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