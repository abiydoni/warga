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
  <style>
    .table-header-flex { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 10px; gap: 8px; }
    .table-header-flex .info { font-size: 13px; color: #555; min-width: 160px; }
    .table-header-flex .controls { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
    .table-header-flex select, .table-header-flex input[type='search'] { max-width: 120px; min-width: 60px; }
    .table-header-flex input[type='search'] { width: 140px; }
    @media (max-width: 600px) {
      .table-header-flex { flex-direction: column; align-items: stretch; gap: 6px; }
      .table-header-flex .controls { flex-direction: column; gap: 6px; align-items: stretch; }
      .table-header-flex input[type='search'] { width: 100%; max-width: 100%; }
    }
  </style>
</head>
<body>
<div class="table-responsive" style="overflow-x:auto;">
  <div class="table-header-flex">
    <div class="info">
      Menampilkan <?=count($users)?> dari <?=$total?> data user<?php if($total>0): ?> (Halaman <?=$page?>/<?=$totalPages?>)<?php endif; ?>
    </div>
    <form method="get" class="controls">
      <label style="font-size:13px; color:#555;">Tampilkan
        <select name="limit" onchange="this.form.submit()" style="margin:0 4px;">
          <?php foreach ([10,25,50,100] as $opt): ?>
          <option value="<?= $opt ?>" <?= $limit==$opt?'selected':'' ?>><?= $opt ?></option>
          <?php endforeach; ?>
        </select>
        baris
      </label>
      <input type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari user...">
      <button type="submit">Cari</button>
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