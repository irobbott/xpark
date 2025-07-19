<?php

session_start();

require_once '../path.php';
require_once ROOT_PATH . '/app/db.php';
require_once ROOT_PATH . '/app/controller.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN &vert; Sign In &mdash; Xparkling Touch Unisex Salon</title>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- VANILLA CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- MONTSERRAT FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- ANIMATE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
    <div class="login-wrap">
        <img src="../assets/imgs/logo-2.png" alt="" class="img-fluid">
        <h3>Admin &vert; Login</h3>
        <div class="form">
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
            <?php if (isset($error)): ?>
                <div class="alert alert-danger animate__animated animate__fadeIn animate__duration-2s"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <input type="email" class="form-control f1" name="email" placeholder="Email" value="charlze07@gmail.com">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control f2" name="password" placeholder="Password" value="1234">
                </div>
                <div class="form-group">
                    <button type="submit" name="sign_in" class="btn btn-primary col-12">Sign In</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        new WOW().init();
    </script>
    <!-- JS & VENDORS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>