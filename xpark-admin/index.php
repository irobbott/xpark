<?php

session_start();

require_once '../path.php';
require_once ROOT_PATH . '/app/db.php';
require_once ROOT_PATH . '/app/controller.php';

// Check if the session has expired
if (isset($_SESSION['expire_time'])) {
    if (time() > $_SESSION['expire_time']) {
        // Session has expired
        session_unset();
        session_destroy();
        header('Location: ' . ROOT_PATH . '/xpark-admin/sign-in.php'); // Adjust the redirect URL as needed
        exit();
    }
}   elseif (!isset($_SESSION['admin_id'])) {
    header('Location: ' . ROOT_PATH . '/xpark-admin/sign-in.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN &vert; Dashboard &mdash; Xparkling Touch Unisex Salon</title>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- VANILLA CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- MONTSERRAT FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="body-wrap">
        <?php include ROOT_PATH . '/xpark-admin/includes/sidebar.php'; ?>
        <div class="main-page">
            <div class="container">
                <!-- NAV -->
                <div class="top-strip">
                    <div class="l-side">
                        <button class="btn btn-primary toggle-menu">
                            <i class="bi bi-list"></i>
                        </button>
                    </div>
                    <div class="right-side">
                        <a href="?logout=1"><i class="fa-solid fa-power-off bg-primary"></i> Logout</a>
                    </div>
                </div>
                <div class="sct">
                    <div class="sct-wrap">
                        <h3>
                            Dashboard
                        </h3>
                    </div>
                </div>
                <div class="sct">
                    <!-- MESSAGE -->
					<?php if (isset($_SESSION['message'])): ?>
						<div class="alert <?php echo $_SESSION['type'] ?>">
							<?php
							echo $_SESSION['message'];
							unset($_SESSION['message']);
							unset($_SESSION['type']);
							?>
						</div>
					<?php endif; ?>

                    <div class="d-board-info mb-4">
                        <h3>You can navigate the admin panel by clicking an option on the menu</h3>
                        <div class="d-board-info-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <?php
                                            // Write a SQL query to count the rows
                                            $cussql = "SELECT COUNT(*) AS total FROM customers";
                                            $cusresult = $conn->query($cussql);
                                            $cusrow = $cusresult->fetch_assoc();
                                            $custotalRows = $cusrow['total'];
                                            ?>
                                            <div class="d-board-card card">
                                                <div class="menu-icon">
                                                    <i class="fa-solid fa-rectangle-list"></i>
                                                </div>
                                                <p class="fw-bold text-center">Total orders</p>
                                                <p class="d-board-total"><?php echo $custotalRows; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <?php
                                            // Write a SQL query to count the rows
                                            $prsql = "SELECT COUNT(*) AS total FROM prices";
                                            $prresult = $conn->query($prsql);
                                            $prrow = $prresult->fetch_assoc();
                                            $prtotalRows = $prrow['total'];
                                            ?>
                                            <div class="d-board-card card">
                                                <div class="menu-icon">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                <p class="fw-bold text-center">Total services</p>
                                                <p class="d-board-total"><?php echo $prtotalRows; ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h4 class="my-4">Recent orders</h4>
                    <?php
                        // Fetch categories
                        $sql = "SELECT * FROM customers LIMIT 3";
                        $result = $conn->query($sql);
                    ?>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Full Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Order Date</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                            <?php while($customers = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customers['name']); ?></td>
                                <td><?php echo htmlspecialchars($customers['email']); ?></td>
                                <td><?php echo htmlspecialchars($customers['number']); ?></td>
                                <td><?php echo htmlspecialchars(date('l jS F, Y', strtotime($customers['date']))); ?></td>
                                <td><?php echo htmlspecialchars($customers['status']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="2">No orders yet.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="view-orders mt-4">
                        <a href="orders" class="btn btn-success">View all orders <i class="fa-solid fa-circle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- JS & VENDORS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
    <script>
        var baseUrl = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/xpark-admin/assets/js/script.js"></script>
</body>
</html>