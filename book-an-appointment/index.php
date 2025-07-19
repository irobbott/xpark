<?php

    require_once '../path.php';
    require_once ROOT_PATH . '/app/db.php';

    // Initialize variables
    $searchQuery = '';
    $categoryId = null;
    $items = [];

    // Check if search query is set
    if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
        $searchQuery = trim($_GET['search']);
    }

    // Check if category ID is set
    if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
        $categoryId = intval($_GET['category_id']);
    }

    // Fetch items based on search query or category filter
    $sql = "SELECT * FROM prices";
    if ($searchQuery) {
        $sql .= " WHERE service LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
    } elseif ($categoryId) {
        $sql .= " WHERE category_id = " . $categoryId;
    }
    $sql .= " ORDER BY RAND()"; // Random order

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- OPEN GRAPH META TAGS -->
  <meta property="og:title" content="Xparkling Touch Unisex Salon">
  <meta property="og:description" content="Benin City's Favourite Salon">
  <meta property="og:image" content="http://localhost/xpark/og-img.png">
  <meta property="og:url" content="http://localhost/xpark">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Xparkling Touch Unisex Salon">
  <!-- OTHER META TAGS -->
  <meta name="description" content="Benin City's Favourite
  Salon">
  <meta name="keywords" content="xpark, xparkling, xparkling touch, xparkling touch uniisex salon, online salon, online barbers, online spa, xparkling touch spa">
  <meta name="author" content="">
  <link rel="canonical" href="http://localhost/xpark">
  <link rel="icon" href="../favicon.png">
  <title>BOOK AN APPOINTMENT &vert; XPARKLING TOUCH UNISEX SALON</title>
  <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
  <!-- BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- VANILLA CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/booking.css">
  <!-- ICONS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- MONTSERRAT FONT -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <!-- ANIMATE CSS -->
  <link rel="stylesheet" href="../assets/css/animate.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/><!-- 2 -->
  <!-- FONT AWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark fixed-top">
        <div class="container">
            <a href="<?php echo BASE_URL ?>" class="navbar-brand">
                <img src="../assets/imgs/logo.png" alt="" class="img-fluid">
            </a>
          <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button> -->
          <div class="" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL . '/book-an-appointment' ?>">Home</a>
              </li>
            </ul>
          </div>
            <ul class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="cart"><i class="fa-solid fa-cart-shopping"></i> Cart<span id="cart-count">0</a></span>
            </ul>
        </div>
    </nav>

    <section id="shop-wrap">
        <div class="container">
            <div class="shop-inner">
                <div class="row">
                    <div class="col-md-3">
                        <h3 class="cat-head">
                            Categories
                        </h3>
                        <div class="cat-bar"></div>
                        <!-- SELECT CATEGORIES FROM DB -->
                        <?php
                            // Fetch categories from the price_category table
                            $sql = "SELECT * FROM price_category";
                            $result = $conn->query($sql);
                        ?>
                        <ul class="cat-options">
                            <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <a href="?category_id=<?php echo htmlspecialchars($row['id']); ?>">
                                <li class="cat-item">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </li>
                            </a>
                            <?php endwhile; ?>
                            <?php else: ?>
                                <li>No categories found</li>
                            <?php endif; ?>

                            <?php
                                if (isset($_GET['search']) || isset($_GET['category_id'])) {
                                    echo '
                                        <a href="../book-an-appointment" class="">
                                            <li class="cat-item">
                                                <i class="fa-solid fa-angles-left"></i> Show all
                                            </li>
                                        </a>
                                    ';
                                }
                            ?>
                            
                        </ul>
                    </div>
                    <div class="col-md-9">
                        <h3 class="shop-head">
                            <!-- SERVICES -->
                            <?php
                                if (isset($_GET['category_id'])) {
                                    // Fetch categories from the price_category table
                                    $sqlcat = "SELECT * FROM price_category WHERE id='$categoryId'";
                                    $result = $conn->query($sqlcat);

                                    if ($result && $result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "Results in the '" . htmlspecialchars($row['category_name']) . "' Category";
                                        }
                                    }
                                }   elseif (isset($_GET['search'])) {
                                    // Fetch categories from the price_category table
                                    $searchT = $_GET['search'];
                                    echo "Search results for '" . $searchT . "'";
                                }   else {
                                    echo 'Services';
                                }
                            ?>
                        </h3>
                        <div class="cat-bar"></div>
                        <div class="shop-items-bar">
                            <div class="search-form">
                                <form action="" method="get">
                                    <div class="form-g">
                                        <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search services...">
                                        <button class="search-form-btn" type="submit">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="shop-items">
                            <div class="row">
                                <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <div class="col-md-3 col-sm-6 mb-4">
                                        <div class="card bg-white" data-name="<?php echo htmlspecialchars($item['service']); ?>" data-price="<?php echo htmlspecialchars($item['amount']); ?>">
                                            <div class="card-img"></div>
                                            <div class="card-det p-2">
                                                <p class="" style="padding: 0; margin: 0; font-weight: bold;"><?php echo htmlspecialchars($item['service']); ?></p>
                                                <p class="" style="padding: 0; margin: 0; font-size: 0.8rem;"><?php echo htmlspecialchars($item['description']); ?></p>
                                                <p class="mts-small">&#8358; <?php echo number_format($item['amount']); ?></p>
                                                <div class="add-btn">
                                                    <button class="xbtn3" onclick="addToCart(this)"><i class="fa-solid fa-plus"></i> Add to cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No services found.</p>
                            <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    


    <script src="../assets/js/order.js"></script>
</body>
</html>