<?php

session_start();

require_once '../../path.php';
require_once ROOT_PATH . '/vendor/autoload.php';
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

// Get the order-id from the URL
$order_id = isset($_GET['order-id']) ? (int)$_GET['order-id'] : 0;

// Fetch customer information
$customer_sql = "SELECT * FROM customers WHERE id = ?";
$customer_stmt = $conn->prepare($customer_sql);
$customer_stmt->bind_param("i", $order_id);
$customer_stmt->execute();
$customer_result = $customer_stmt->get_result();
$customer = $customer_result->fetch_assoc();

// Fetch order details
$order_sql = "SELECT * FROM orders WHERE customer_id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$orders = $order_result->fetch_all(MYSQLI_ASSOC);

// Calculate the total amount
$totalAmount = 0;
foreach ($orders as $order) {
    $totalAmount += $order['item_price'];
}

// Close statements
// $customer_stmt->close();
// $order_stmt->close();
// $conn->close();

if (!$customer) {
    echo "Customer not found";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN &vert; Orders &mdash; Xparkling Touch Unisex Salon</title>
    <link rel="shortcut icon" href="../../favicon.png" type="image/x-icon">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- VANILLA CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                        <h1>Order by '<?php echo htmlspecialchars($customer['name']); ?>'</h1>
                    </div>
                </div>
                <div class="sct" style="margin-bottom: 60px;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="return">
                                <a class="btn btn-outline-secondary mb-4" href="index.php"><i class="fa-solid fa-angles-left"></i> Back</a>
                            </div>
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
                            <div class="mb-2">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">Full name: </span><?php echo htmlspecialchars($customer['name']); ?>
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">Email: </span><?php echo htmlspecialchars($customer['email']); ?>
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">Phone number: </span><?php echo htmlspecialchars($customer['number']); ?>
                            </div>
                            <div class="mt-4 mb-2">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">SERVICES</span>
                            </div>
                            <?php foreach ($orders as $order): ?>
                                <div class="mb-2">
                                    <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;"><?php echo htmlspecialchars($order['item_name']); ?>: </span><?php echo htmlspecialchars($order['item_price']); ?>
                                </div>
                            <?php endforeach; ?>
                            <div class="mt-4 mb-2" style="border-bottom: 1px solid #222; padding-bottom: 20px;">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">Total Amount: </span><?php echo htmlspecialchars($totalAmount); ?>
                            </div>
                            <div class="mt-4 mb-2">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">Home service: </span><?php echo htmlspecialchars($order['home_service']); ?>
                            </div>
                            <div class="mb-2">
                                <span class="fw-bold" style="margin-right: 20px; margin-bottom: 10px;">Appointment date: </span><?php echo htmlspecialchars($customer['date']); ?>
                            </div>

                            <?php
                                if ($customer['status'] === 'Pending') {
                                    echo '
                                        <div class="status-strip status-strip-yellow">
                                            <i class="fa-solid fa-spinner"></i> ' . htmlspecialchars($customer['status']) . 
                                        '</div>
                                    ';
                                }   elseif ($customer['status'] === 'Approved') {
                                    echo '
                                        <div class="status-strip status-strip-green">
                                            <i class="fa-solid fa-check"></i> ' . htmlspecialchars($customer['status']) . 
                                        '</div>
                                    ';
                                }   elseif ($customer['status'] === 'Canceled') {
                                    echo '
                                        <div class="status-strip status-strip-red">
                                            <i class="fa-solid fa-xmark"></i> ' . htmlspecialchars($customer['status']) . 
                                        '</div>
                                    ';
                                }
                            ?>
                            <div class="dec">
                                <?php
                                    if ($customer['status'] === 'Pending') {
                                        echo '
                                            <div>
                                                <a href="?approve-order=' . $customer['id'] . '" class="app-btn btn btn-primary" id="approve-order-link">Approve order</a>
                                            </div>
                                            <div>
                                                <a href="?cancel-order=' . $customer['id'] . '" class="app-btn btn btn-danger" id="approve-order-link">Cancel order</a>
                                            </div>
                                        ';
                                    }   elseif ($customer['status'] === 'Approved') {
                                        echo '
                                            <div>
                                                <a href="?cancel-order=' . $customer['id'] . '" class="app-btn btn btn-danger" id="approve-order-link">Cancel order</a>
                                            </div>
                                        ';
                                    }   elseif ($customer['status'] === 'Canceled') {
                                        echo '
                                            <div>
                                                <a href="?approve-order=' . $customer['id'] . '" class="app-btn btn btn-primary" id="approve-order-link">Approve order</a>
                                            </div>
                                        ';
                                    }
                                ?>
                            </div>
                        </div>
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