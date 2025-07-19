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

// Pagination variables
$limit = 70; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search filter
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Base query
$price_sql = "SELECT * FROM prices";
$price_where = [];

// Add search filter if present
if ($search) {
    $price_where[] = "(service LIKE '%$search%' OR description LIKE '%$search%')";
}

// Combine filters with base query
if (count($price_where) > 0) {
    $price_sql .= " WHERE " . implode(" AND ", $price_where);
}

// Get total number of records
$total_result = $conn->query($price_sql);
$total = $total_result->num_rows;

// Add limit and offset for pagination
$price_sql .= " LIMIT $limit OFFSET $offset";

$price_result = $conn->query($price_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN &vert; Prices &mdash; Xparkling Touch Unisex Salon</title>
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
                        <h1>Price Lists</h1>
                    </div>
                </div>

                <div class="sct mb-4">
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
                    <h3>Service Category</h3>
                    <div class="view-orders my-4">
                        <a href="add-service-category.php" class="btn btn-success">Add new category</a>
                    </div>
                    <?php
                        // Fetch categories
                        $sql = "SELECT * FROM price_category";
                        $result = $conn->query($sql);
                    ?>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                <?php while($category = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['category_name']); ?></td>
                                    <td>
                                        <a href="edit-category.php?category-id=<?php echo $category['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="?delete-category=<?php echo $category['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this category? All services associated with it will become uncategorized');">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="2">No categories found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>


                    <!-- PRICES -->
                    <div id="prices" style="height: 40px;"></div>
                    <h3 style="margin-top: 40px">Price List</h3>
                    <!-- MESSAGE 2 -->
					<?php if (isset($_SESSION['message2'])): ?>
						<div class="alert <?php echo $_SESSION['type2'] ?>">
							<?php
							echo $_SESSION['message2'];
							unset($_SESSION['message2']);
							unset($_SESSION['type2']);
							?>
						</div>
					<?php endif; ?>
                    <div class="view-orders my-4">
                        <a href="add-service.php" class="btn btn-success">Add new service</a>
                    </div>
                    <div class="return">
                        <a class="btn btn-outline-secondary mb-4" href="index.php"><i class="fa-solid fa-circle"></i> Show all</a>
                    </div>

                    <div class="search my-4">
                        <form action="#prices" method="get">
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
                                    <th scope="col">Service Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($price_result->num_rows > 0): ?>
                                <?php while($price_service = $price_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($price_service['service']); ?></td>
                                    <td><?php echo htmlspecialchars($price_service['description']); ?></td>
                                    <td>&#8358;<?php echo htmlspecialchars(number_format($price_service['amount'])); ?></td>
                                    <td>
                                        <a href="edit-service.php?service-id=<?php echo $price_service['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="?delete-service=<?php echo $price_service['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="3">No services found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        
                        <?php
                        // Calculate total pages
                        $total_pages = ceil($total / $limit);
                        ?>
                        
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>#prices">Previous</a></li>
                                <?php endif; ?>
                                
                                <?php
                                $start = max(1, $page - 1);
                                $end = min($total_pages, $page + 1);
                                
                                if ($start > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="?page=1&search=' . urlencode($search) . '#prices">1</a></li>';
                                    if ($start > 2) {
                                        echo '<li class="page-item"><a class="page-link">...</a></li>';
                                    }
                                }
                                
                                for ($i = $start; $i <= $end; $i++) {
                                    echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">
                                            <a class="page-link" href="?page=' . $i . '&search=' . urlencode($search) . '#prices">' . $i . '</a>
                                        </li>';
                                }
                                
                                if ($end < $total_pages) {
                                    if ($end < $total_pages - 1) {
                                        echo '<li class="page-item"><a class="page-link">...</a></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="?page=' . $total_pages . '&search=' . urlencode($search) . '#prices">' . $total_pages . '</a></li>';
                                }
                                ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>#prices">Next</a></li>
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