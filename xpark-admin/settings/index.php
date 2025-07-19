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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN &vert; Settings &mdash; Xparkling Touch Unisex Salon</title>
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
                        <h1>Settings</h1>
                    </div>
                </div>

                <div class="sct" style="margin-bottom: 60px;">
                    <h3>Set home service price</h3>
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
                    <!-- ERRORS -->
                    <!-- ERRORS -->
                    <?php
                        if (count($home_service_errors) > 0):
                    ?>
                    <div class="alert alert-danger">
                        <?php foreach ($home_service_errors as $error): ?>
                        <li>
                            <?php echo $error; ?>
                        </li>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php
                        // Fetch the category details from the database
                        $hresult = $conn->query("SELECT * FROM home_service WHERE id = '1'");
                        $hService = $hresult->fetch_assoc();
                    ?>
                    <form action="" method="post">
                        <div class="form-group mb-4">
                            <label for="">Home service price</label>
                            <input type="number" name="home-service" class="form-control" value="<?php echo $hService['cost']; ?>">
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" name="update-home-service" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>

                <div class="sct" style="margin-bottom: 60px;">
                <div id="password" style="height: 40px;"></div>
                    <h3>Update Password</h3>
                    <!-- MESSAGE -->
					<?php if (isset($_SESSION['pass-message'])): ?>
						<div class="alert <?php echo $_SESSION['pass-type'] ?>">
							<?php
							echo $_SESSION['pass-message'];
							unset($_SESSION['pass-message']);
							unset($_SESSION['pass-type']);
							?>
						</div>
					<?php endif; ?>
                    <!-- ERRORS -->
                    <!-- ERRORS -->
                    <?php
                        if (count($errors) > 0):
                    ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                        <li>
                            <?php echo $error; ?>
                        </li>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <div class="form-group mb-4">
                            <label for="">Current password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="form-group mb-4">
                            <label for="">New password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>
                        <div class="form-group mb-4">
                            <label for="">Confirm password</label>
                            <input type="password" name="conf_password" class="form-control">
                        </div>
                        <div class="form-group mb-4">
                            <button type="submit" name="updatePassword" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <!-- JS & VENDORS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    
    <script>
        var baseUrl = '<?php echo BASE_URL; ?>';
    </script>
    <script src="../assets/js/script.js"></script>
</body>
</html>