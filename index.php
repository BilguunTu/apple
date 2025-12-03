<?php
require_once 'db_connect.php';

function getFoodsByCategory(mysqli $conn, string $category): array {
    $items = [];

    $sql = "SELECT ner, tailbar, une 
            FROM food 
            WHERE angilal = ?
            ORDER BY id ASC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        $stmt->close();
    }

    return $items;
}

$page = $_GET['page'] ?? 'menu'; // үндсэн нь "menu"
?>
<!DOCTYPE html>
<html lang="mn">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>
    <?php echo ($page === 'contact') ? 'Холбоо барих – Ресторан' : 'Ресторан – Меню'; ?>
  </title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <style>
  
</style>
</head>
<body class="bg-light">


<div class="container d-flex align-items-center justify-content-between">
    <a class="navbar-brand fw-bold m-0" href="index.php?page=menu">Ресторан</a>

  
    <ul class="nav d-none d-md-flex gap-1">
    
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#hot-appetizer">Халуун зууш</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#salad">Салат</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#soup">Шөл</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#mongolian-meals">Монгол хоол</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#grills">Грилл</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#main-dish">Үндсэн хоол</a></li>
      <li class="nav-item"><a class="nav-link" href="index.php?page=menu#drinks">Ундаа</a></li>

    
      <li class="nav-item">
        <a class="nav-link <?php echo ($page === 'menu') ? 'active' : ''; ?>" href="index.php?page=menu">🏠 Нүүр</a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?php echo ($page === 'contact') ? 'active' : ''; ?>" href="index.php?page=contact">📍 Холбоо барих</a>
      </li>
    </ul>

  
    <a href="admin_login.php" target="_blank" class="btn btn-primary d-none d-md-inline-flex shadow-md border text-black">
      Админ
    </a>
  
    <button class="navbar-toggler d-md-none" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#mobileNav"
            aria-controls="mobileNav" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  </div>
</header>

<div class="offcanvas offcanvas-start offcanvas-pink" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="mobileNavLabel">Цэс</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <nav class="nav flex-column">
      <a class="nav-link" href="index.php?page=menu#hot-appetizer">Халуун зууш</a>
      <a class="nav-link" href="index.php?page=menu#salad">Салат</a>
      <a class="nav-link" href="index.php?page=menu#soup">Шөл</a>
      <a class="nav-link" href="index.php?page=menu#mongolian-meals">Монгол хоол</a>
      <a class="nav-link" href="index.php?page=menu#grills">Грилл</a>
      <a class="nav-link" href="index.php?page=menu#main-dish">Үндсэн хоол</a>
      <a class="nav-link" href="index.php?page=menu#drinks">Ундаа</a>
      <a class="nav-link <?php echo ($page === 'menu') ? 'active' : ''; ?>" href="index.php?page=menu">🏠 Нүүр</a>
      <a class="nav-link <?php echo ($page === 'contact') ? 'active' : ''; ?>" href="index.php?page=contact">📍 Холбоо барих</a>
    </nav>
  </div>
</div>


<main class="container py-4">

<?php if ($page === 'menu'): ?>

  <div class="mb-5 text-center">
    <h1 class="h3 mb-0">Меню</h1>
    <small class="text-muted">Үнэ төгрөгөөр (₮)</small>
  </div>
  
  <?php
    $menu_sections = [
      'hot-appetizer' => 'ХАЛУУН ЗУУШ / HOT APPETIZER',
      'salad'         => 'САЛАТ / SALAD',
      'soup'          => 'ШӨЛ / SOUP',
      'mongolian-meals' => 'МОНГОЛ ХООЛ / MONGOLIAN MEALS',
      'grills'        => 'ГРИЛЛ / GRILL’S',
      'main-dish'     => 'ҮНДСЭН ХООЛ / MAIN DISH',
      'drinks'        => 'ХАЛУУН ЦАЙ, КОФЕ, УНДАА / HOT TEA, COFFEE AND DRINK',
    ];

    foreach ($menu_sections as $category_slug => $title):
     
      $items = getFoodsByCategory($conn, $category_slug);
    
      $image_base_url = 'images/'; 
  ?>
      
      <section id="<?php echo $category_slug; ?>" class="mb-5">
        <h2 class="section-title"><?php echo $title; ?></h2>
        
        <div class="row row-cols-2 row-cols-md-4 g-3">
          <?php if (empty($items)): ?>
            <div class="col-12 text-muted text-center py-3 border">
              Одоогоор энэ ангилалд хоол бүртгэгдээгүй байна.
            </div>
          <?php else: ?>
            <?php foreach ($items as $food): ?>
              <div class="col">
                <div class="food-grid-item shadow-sm">
                  <div class="food-img-container" 
                       style="background-image: url('<?php echo $image_base_url . $category_slug . '/' . $food['ner'] . '.jpg'; ?>');">
                    </div>
                  
                  <div class="p-3">
                    <div class="fw-bold text-truncate" style="color:var(--brand-dark);">
                      <img src/>
                      <?php echo htmlspecialchars($food['ner']); ?>
                    </div>
                    <?php if (!empty($food['tailbar'])): ?>
                      <small class="text-muted d-block mb-1 text-truncate" title="<?php echo htmlspecialchars($food['tailbar']); ?>">
                        <?php echo htmlspecialchars($food['tailbar']); ?>
                      </small>
                    <?php endif; ?>
                    <div class="food-price">
                      ₮ <?php echo number_format($food['une']); ?>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

  <?php endforeach; ?>

<?php elseif ($page === 'contact'): ?>

  <section class="py-5">
    <h1 class="h4 mb-3">📞 Холбоо барих / Хаяг</h1>
    <ul class="list-group shadow-sm mb-4">
      <li class="list-group-item"><strong>📍 Хаяг:</strong> Улаанбаатар хот, Город, 3-19</li>
      <li class="list-group-item">
        <strong>📞 Утас:</strong> <a class="text-decoration-none" href="tel:99355569">9935-5569</a>
      </li>
      <li class="list-group-item">
        <strong>🌐 Facebook:</strong>
        <a class="link-primary" target="_blank" href="https://www.facebook.com/profile.php?id=61575183713497">Манай Facebook хуудас</a>
      </li>
    </ul>

    <section class="text-center">
      <h2 class="h5 mb-3">🗺️ Байршил</h2>
      <p class="mb-4">Манай ресторан Улаанбаатар хотын төвд байрладаг. Дарахад Google Maps нээгдэнэ.</p>
      <a class="btn btn-primary btn-lg" target="_blank"
         href="https://www.google.com/maps/place/Mine+e-sports/@49.0247864,104.043745,765m/data=!3m2!1e3!4b1!4m6!3m5!1s0x5d9f51003891619b:0x308a84cf6bbeebd2!8m2!3d49.0247865!4d104.0486159!16s%2Fg%2F11yfbhjf0b?entry=ttu&g_ep=EgoyMDI1MTEzMC4wIKXMDSoASAFQAw%3D%3D">📍 Google Maps дээр харах</a>
    </section>
  </section>

<?php endif; ?>

</main>


<footer class="footer py-4">
  <div class="container text-center small">
    © 2025 Ресторан • 📞 9935-5569 • 📍 Улаанбаатар хот
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const ocEl = document.getElementById('mobileNav');
  if (!ocEl) return;
  document.querySelectorAll('#mobileNav a.nav-link').forEach(a => {
    a.addEventListener('click', function (e) {
      const href = a.getAttribute('href') || '';
      const oc = bootstrap.Offcanvas.getOrCreateInstance(ocEl);
      oc.hide();
      setTimeout(()=>{ window.location.href = href; }, 200);
    });
  });
});
</script>
</body>
</html>