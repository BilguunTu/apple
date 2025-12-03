<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ner      = trim($_POST['ner'] ?? '');
    $tailbar  = trim($_POST['tailbar'] ?? '');
    $angilal  = trim($_POST['angilal'] ?? '');
    $une      = trim($_POST['une'] ?? '');

    if ($ner === '' || $une === '') {
        $error = 'Хоолны нэр болон үнийг заавал бөглөнө үү.';
    } elseif (!ctype_digit($une)) {
        $error = 'Үнэ нь зөвхөн бүхэл тоо байхаар оруулна уу (жишээ: 19500).';
    } else {
        $stmt = $conn->prepare("INSERT INTO food (ner, tailbar, angilal, une) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $ner, $tailbar, $angilal, $une);

        if ($stmt->execute()) {
            $success = 'Шинэ хоол амжилттай бүртгэлээ!';
        } else {
            $error = 'Алдаа гарлаа: ' . $conn->error;
        }

        $stmt->close();
    }
}

$result = $conn->query("SELECT id, ner, angilal, une, uusgesen_ognoo FROM food ORDER BY id DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="mn">
<head>
  <meta charset="UTF-8">
  <title>Админ – Шинэ хоол бүртгэх</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">🍽 Админ – Шинэ хоол бүртгэх</h1>
    <div>
      <span class="me-2">Сайн байна уу, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></span>
      <a href="logout.php" class="btn btn-outline-secondary btn-sm">Гарах</a>
    </div>
  </div>

  <?php if ($success): ?>
    <div class="alert alert-success py-2"><?php echo htmlspecialchars($success); ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger py-2"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <h2 class="h5 mb-3">Шинэ хоол нэмэх</h2>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Хоолны нэр</label>
          <input type="text" name="ner" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Тайлбар</label>
          <textarea name="tailbar" class="form-control" rows="2"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Ангилал (жишээ: salad, soup, mongolian-meals, grills, main-dish, drinks)</label>
          <input type="text" name="angilal" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Үнэ (₮)</label>
          <input type="text" name="une" class="form-control" placeholder="19500" required>
        </div>

        <button type="submit" class="btn btn-primary">Хоол бүртгэх</button>
      </form>
    </div>
  </div>

  <h2 class="h5 mb-2">Сүүлд бүртгэсэн хоолнууд</h2>
  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0 table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Нэр</th>
            <th>Ангилал</th>
            <th>Үнэ</th>
            <th>Огноо</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo htmlspecialchars($row['ner']); ?></td>
              <td><?php echo htmlspecialchars($row['angilal']); ?></td>
              <td>₮ <?php echo number_format($row['une']); ?></td>
              <td><?php echo $row['uusgesen_ognoo']; ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center py-3">Одоогоор бүртгэсэн хоол алга.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>