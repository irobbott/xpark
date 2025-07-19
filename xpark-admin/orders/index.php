<?php

session_start();

require_once '../../path.php';
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

// Pagination settings
$limit = 10; // Number of entries to show per page
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;

// Search functionality
$search = '';
if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR number LIKE '%$search%'";
} else {
    $search_query = '';
}

// Fetch data from the 'customers' table ordered by date (most recent first)
$sql = "SELECT * FROM customers $search_query ORDER BY date DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Get total number of records for pagination
$total_sql = "SELECT COUNT(*) FROM customers $search_query";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_array($total_result);
$total_records = $total_row[0];
$total_pages = ceil($total_records / $limit);

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
                        <h1>Orders</h1>
                    </div>
                </div>
                <div class="sct mb-4">
                    <div class="search my-4">
                        <form action="" method="get">
                            <div class="searchformgroup col-md-4">
                                <input type="text" name="search" class="search-input mb-2" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>" required>
                                <button type="submit" class="search-btn bg-primary">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone number</th>
                                    <th scope="col">Order Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (mysqli_num_rows($result) > 0) {
                                    $counter = ($page - 1) * $limit + 1; // Counter for ordered numbers
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>
                                                <th scope="row">' . $counter . '</th>
                                                <td>' . htmlspecialchars($row['name']) . '</td>
                                                <td>' . htmlspecialchars($row['email']) . '</td>
                                                <td>' . htmlspecialchars($row['number']) . '</td>
                                                <td>' . htmlspecialchars(date('l jS F, Y', strtotime($row['date']))) . '</td>
                                                <td>' . htmlspecialchars($row['status']) . '</td>
                                                <td>
                                                    <a href="order-details.php?order-id=' . $row['id'] . '" class="btn btn-primary btn-sm">Details</a>
                                                </td>
                                            </tr>';
                                        $counter++;
                                    }
                                } else {
                                    echo '<tr><td colspan="6">No orders found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a></li>
                                <?php endif; ?>
                                
                                <?php
                                $start = max(1, $page - 1);
                                $end = min($total_pages, $page + 1);
                                
                                if ($start > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1&search=' . urlencode($search) . '">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item"><a class="page-link">...</a></li>';
                                    }
                                }
                                
                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                                            <a class="page-link" href="?page=' . $i . '&search=' . urlencode($search) . '">' . $i . '</a>
                                        </li>';
                                }
                                
                                if ($end < $total_pages) {
                                    if ($end < $total_pages - 1) {
                                        echo '<li class="page-item"><a class="page-link">...</a></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&search=' . urlencode($search) . '">' . $total_pages . '</a></li>';
                                }
                                ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
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