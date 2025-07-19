<?php

    require_once '../../path.php';
    require_once ROOT_PATH . '/app/db.php';

    function generateUniqueTrxRef($conn) {
        do {
            // Generate a random 10-digit number
            $randomNumber = mt_rand(1000000000, 9999999999);
            // Prefix it with 'X' to make it 11 characters long
            $trxRef = 'X' . $randomNumber;
    
            // Check if this trxRef already exists in the 'customers' table
            $query = $conn->prepare("SELECT COUNT(*) FROM customers WHERE trx_ref = ?");
            $query->bind_param("s", $trxRef);
            $query->execute();
            $query->bind_result($count);
            $query->fetch();
            $query->close();
        } while ($count > 0); // If it exists, generate a new one
    
        return $trxRef;
    }
    $newTrxRef = generateUniqueTrxRef($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- OTHER META TAGS -->
    <meta name="description" content="Benin City's Favourite
    Salon">
    <meta name="keywords" content="xpark, xparkling, xparkling touch, xparkling touch uniisex salon, online salon, online barbers, online spa, xparkling touch spa">
    <meta name="author" content="">
    <link rel="canonical" href="http://localhost/xpark">
    <link rel="icon" href="../../favicon.png">
    <title>CART &vert; XPARKLING TOUCH UNISEX SALON</title>
    <link rel="shortcut icon" href="../../favicon.png" type="image/x-icon">
    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- VANILLA CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/booking.css">
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
                <img src="../../assets/imgs/logo.png" alt="" class="img-fluid">
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
                    <div class="col-md-6">
                        <h3 class="cat-head">
                            Cart
                        </h3>
                        <div class="cat-bar"></div>
                        <a href="../"><i class="fa-solid fa-left-long"></i> Back</a>
                        <div id="cart-items" style="margin-top: 30px;">
                            <!-- <div class="ci">
                                <div class="row cc">
                                    <div class="col-8">
                                        <p class="fw-bold">Barbing</p>
                                        <p>2000</p>
                                    </div>
                                    <div class="col-4">
                                        <button>Delete</button>
                                    </div>
                                </div>
                            </div> -->
                        </div>

                        <form action="">
                            <!-- <select name="" id="" class="form-select">
                                <option value="0" selected>-- Service Type --</option>
                                <option value="0">Regular service</option>
                                <option value="15000">Home service &mdash; &#8358;15000 on every service</option>
                            </select> -->
                            <!-- HIDE HOME SERVICE FOR NOW -->
                            <div class="btn-group d-none" id="hs1" role="group" aria-label="Basic checkbox toggle button group">
                                <input type="checkbox" class="btn-check" id="homeService" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="homeService">Home Service</label>
                            </div>
                        </form>
                        
                        <div class="cart-btns">
                            <button id="clear-cart" onclick="clearCart()"><i class="fa-sharp fa-solid fa-trash"></i> Clear Cart</button>
                            <div id="total-amount">Total: ₦0</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Checkout</h3>
                        <div class="cat-bar"></div>
                        <div id="cForm" class="checkout-form">
                            <form id="checkoutForm" onsubmit="submitOrder(event)">
                                <input type="text" id="trx_ref" value="<?php echo $newTrxRef; ?>" hidden readonly>
                                <div class="form-group mb-4">
                                    <label for="name" class="mb-2">Full Name</label>
                                    <input type="text" id="name" name="" class="form-control" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="email" class="mb-2">Email</label>
                                    <input type="text" id="email" name="" class="form-control" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="number" class="mb-2">Phone Number</label>
                                    <input type="text" id="number" name="" class="form-control" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="date" class="mb-2">Date of appointment</label>
                                    <input type="date" id="date" name="" class="form-control" required>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="date" class="mb-2">Time</label>
                                    <select name="" id="time" class="form-select" required>
                                        <option value="" disabled selected>-- Select Time --</option>
                                        <option value="8:00 AM">8:00 AM</option>
                                        <option value="9:00 AM">9:00 AM</option>
                                        <option value="10:00 AM">10:00 AM</option>
                                        <option value="11:00 AM">11:00 AM</option>
                                        <option value="12:00 PM">12:00 PM</option>
                                        <option value="01:00 PM">01:00 PM</option>
                                        <option value="02:00 PM">02:00 PM</option>
                                        <option value="03:00 PM">03:00 PM</option>
                                        <option value="04:00 PM">04:00 PM</option>
                                        <option value="05:00 PM">05:00 PM</option>
                                        <option value="06:00 PM">06:00 PM</option>
                                    </select>
                                </div>
                                <div class="form-group mb-4">
                                    <!-- <button type="submit" class="submit-btn">Submit</button> -->
                                    <input type="submit" class="submit-btn" value="Submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    


    <script src="../../assets/js/order.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the current date
            var today = new Date();
            var year = today.getFullYear();
            var month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            var day = String(today.getDate() + 1).padStart(2, '0'); // Add 1 to include the current day

            // Format the date as YYYY-MM-DD
            var minDate = year + '-' + month + '-' + day;

            // Set the min attribute of the date input field
            document.getElementById('date').setAttribute('min', minDate);
        });
    </script>
</body>
</html>