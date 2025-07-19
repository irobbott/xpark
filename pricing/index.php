<?php

require_once '../path.php';
require_once ROOT_PATH . '/app/db.php';
require_once ROOT_PATH . '/app/controller.php';

// Get the search query if it exists
$search_query = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch all categories
$category_sql = "SELECT * FROM price_category";
$category_result = $conn->query($category_sql);

// Fetch all services
if (!empty($search_query)) {
    $service_sql = "SELECT * FROM prices WHERE service LIKE '%$search_query%'";
} else {
    $service_sql = "SELECT * FROM prices";
}
$service_result = $conn->query($service_sql);

// Categorize services by their category_id
$services_by_category = [];
while ($service = $service_result->fetch_assoc()) {
    $services_by_category[$service['category_id']][] = $service;
}

// Separate 'Haircuts' category
$haircuts_category = null;
$other_categories = [];

while ($category = $category_result->fetch_assoc()) {
    if ($category['category_name'] === 'Haircuts') {
        $haircuts_category = $category;
    } else {
        $other_categories[] = $category;
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
  <title>XPARKLING TOUCH UNISEX SALON</title>
  <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
  <!-- BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- VANILLA CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
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
  <!-- MOBILE NAV -->
  <div class="mnav">
    <div class="mclose closeMenu">
      <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="mnav-wrp">
      <div class="mlogo">
        <a href=""> 
          <img src="../assets/imgs/logo.png" alt="" class="img-fluid">
        </a>
      </div>
      <div class="mnavlinks">
        <li class=""><a href="../#header">HOME</a></li>
        <li class=""><a href="../#about">ABOUT</a></li>
        <li class=""><a href="../#services">SERVICES</a></li>
        <li class=""><a href="../pricing">PRICING</a></li>
        <li class=""><a href="../#contact">CONTACT</a></li>
      </div>
    </div>
  </div>
  
  <header id="header" style="height: 50vh !important; background: url('../assets/imgs/header-image.jpg')no-repeat center center/cover;">
    <!-- NAVBAR -->
    <nav class="xnav">
      <div class="container">
        <div class="xmain-nav">
          <div class="xlogo">
            <a href=""> 
              <img src="../assets/imgs/logo.png" alt="" class="img-fluid">
            </a>
          </div>
          <div class="navlinks">
            <li class="nvl"><a href="../#header">HOME</a></li>
            <li class="nvl"><a href="../#about">ABOUT</a></li>
            <li class="nvl"><a href="../#services">SERVICES</a></li>
            <li class="nvl"><a href="../pricing">PRICING</a></li>
            <li class="nvl"><a href="../#contact">CONTACT</a></li>
            <button class="xbutton">
              <i class="fa-solid fa-bars toggle-menu"></i>
            </button>
          </div>
        </div>
      </div>
    </nav>
    <!-- FIXED HIDDEN NAVBAR -->
    <nav class="xnav xnav1 hidden xbg1 fixed-top wow fadeInDown">
      <div class="container">
        <div class="xmain-nav">
          <div class="xlogo">
            <a href=""> 
              <img src="../assets/imgs/logo.png" alt="" class="img-fluid">
            </a>
          </div>
          <div class="navlinks">
            <li class="nvl"><a href="../#header">HOME</a></li>
            <li class="nvl"><a href="../#about">ABOUT</a></li>
            <li class="nvl"><a href="../#services">SERVICES</a></li>
            <li class="nvl"><a href="../pricing">PRICING</a></li>
            <li class="nvl"><a href="../#contact">CONTACT</a></li>
            <button class="xbutton">
              <i class="fa-solid fa-bars toggle-menu-2"></i>
            </button>
          </div>
        </div>
      </div>
    </nav>
    
    <!-- HERO -->
    <div class="hero-msg">
      <h3 class="hero-tag kolak wow fadeInUp">
        PRICE LIST
      </h3>
      <div class="hero-bar wow fadeInUp" data-wow-delay="0.4s"></div>
    </div>
  </header>

  <section id="pricing">
    <div class="container">
      <div class="go-to-book">
        <a href="../book-an-appointment" class="go-to-book-btn footer-btn mts-small">
          BOOK AN APPOINTMENT <i class="fa-solid fa-right-long"></i>
        </a>
      </div>
      
      <div class="main-prices-wrap">
        <!-- MAIN PRICES -->
        <div class="row">
          <div class="col-6">
            <div class="search-form" style="margin-bottom: 40px;">
              <form action="" method="get">
                <div class="form-g mb-4">
                  <input type="text" name="search" id="search" class="form-control form-control-sm" placeholder="Search services...">
                  <button class="search-form-btn" type="submit">Search</button>
                </div>
              </form>
              <?php
                if (isset($_GET['search'])) {
                  echo '<a href="../pricing" class="go-back mb-4">
                          <i class="fa-solid fa-angles-left"></i> Show all
                        </a>';
                }
              ?>
            </div>
          </div>
        </div>
        <div class="row">
          <?php if ($haircuts_category && isset($services_by_category[$haircuts_category['id']])): ?>
          <div class="col-md-4" style="margin-bottom: 20px;">
            <h3 class="mts-small" style="border: 1px solid #222; border-radius: 5px; padding: 20px;"><?php echo htmlspecialchars($haircuts_category['category_name']); ?></h3>
            <?php foreach ($services_by_category[$haircuts_category['id']] as $service): ?>
            <div class="price-single">
              <h3 class="mts-small"><i class="fa-solid fa-circle-chevron-right"></i> <?php echo htmlspecialchars($service['service']); ?></h3>
              <div class="mid-dash"></div>
              <h3 class="mts-small">&#8358; <?php echo number_format($service['amount'], 0); ?></h3>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>

          <?php foreach ($other_categories as $category): ?>
          <?php if (isset($services_by_category[$category['id']])): ?>
          <div class="col-md-4">
            <h3 class="mts-small" style="border: 1px solid #222; border-radius: 5px; padding: 20px;"><?php echo htmlspecialchars($category['category_name']); ?></h3>
              <?php foreach ($services_by_category[$category['id']] as $service): ?>
              <div class="price-single">
                <h3 class="mts-small"><i class="fa-solid fa-circle-chevron-right"></i> <?php echo htmlspecialchars($service['service']); ?></h3>
                <div class="mid-dash"></div>
                <h3 class="mts-small">&#8358; <?php echo number_format($service['amount'], 0); ?></h3>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  

  <footer id="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4">
          <h3 class="footer-headers kolak">
            Contact
          </h3>
          <p class="footer-detail">
            Xparkling Touch Unisex Salon, <br>
            120 Irhirhi Road, Off Airport Road, <br>
            Benin City, Nigeria.
          </p>
          <p class="footer-no mts-small mb-4">
            +234 905 586 0488
          </p>
          <div class="footer-email">
            <p class="">
              info@xparklingtouch.com
            </p>
          </div>
          <div class="footer-icons">
            <a href="https://instagram.com/xparkling_touch">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="https://tiktok.com/@xparkling_touch">
              <i class="fa-brands fa-tiktok"></i>
            </a>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <h3 class="footer-headers kolak">
            Work Time
          </h3>
          <div class="work-time">
            <div class="wt">
              <p>Monday</p>
              <div class="wt-dash"></div>
              <p>08:00 - 20:00</p>
            </div>
            <div class="wt">
              <p>Tuesday</p>
              <div class="wt-dash"></div>
              <p>08:00 - 20:00</p>
            </div>
            <div class="wt">
              <p>Wednesday</p>
              <div class="wt-dash"></div>
              <p>08:00 - 20:00</p>
            </div>
            <div class="wt">
              <p>Thursday</p>
              <div class="wt-dash"></div>
              <p>08:00 - 20:00</p>
            </div>
            <div class="wt">
              <p>Friday</p>
              <div class="wt-dash"></div>
              <p>08:00 - 20:00</p>
            </div>
            <div class="wt">
              <p>Saturday</p>
              <div class="wt-dash"></div>
              <p>10:00 - 20:00</p>
            </div>
            <div class="wt">
              <p>Sunday</p>
              <div class="wt-dash"></div>
              <p>Closed</p>
            </div>
          </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-3 col-sm-4 mb-4">
          <h3 class="footer-headers kolak">
            Book an appointment
          </h3>
          <p class="footer-detail">
            Whether you're looking for a fresh cut or a bold new look, Xparkling Touch has you covered.
          </p>
          <div class="book">
            <a href="book-an-appointment/" class="footer-btn mts-small">
              BOOK AN APPOINTMENT
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- <hr class="footer-line">
    <div class="last-footer">
      <div class="container">
        <div class="last-footer-inner">
          &copy; 2024 &vert; Designed by <a class="iRobbott" href="https://iconicrobbott.com">iRobbott 🤖</a>
        </div>
      </div>
    </div> -->
  </footer>

  <!-- ANIMATE -->
  <script src="../assets/js/wow.min.js"></script>
  <script>
    new WOW().init();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../assets/js/script.js"></script>
</body>
</html>