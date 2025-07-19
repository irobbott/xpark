<?php

    require_once '../../path.php';
    require_once ROOT_PATH . '/app/db.php';
    require_once ROOT_PATH . '/lib/fpdf/fpdf.php'; // Adjust the path as needed
    require_once ROOT_PATH . '/vendor/autoload.php';

    // Get trx_ref from URL
    if (isset($_GET['trx_ref'])) {
        $trx_ref = $conn->real_escape_string($_GET['trx_ref']);
    } else {
        die("Transaction reference not provided.");
    }

    // Fetch customer data using trx_ref
    $customer_sql = "SELECT * FROM customers WHERE trx_ref = '$trx_ref'";
    $customer_result = $conn->query($customer_sql);

    if ($customer_result->num_rows > 0) {
        $customer = $customer_result->fetch_assoc();
        $customer_id = $customer['id'];
        $email_sent = $customer['email_sent'];
    } else {
        die("Customer not found.");
    }

    // Fetch related orders using customer_id
    $order_sql = "SELECT * FROM orders WHERE customer_id = '$customer_id'";
    $order_result = $conn->query($order_sql);

    // Calculate total price
    $total_price = 0;

    if ($email_sent == 0) {
        // Create the Transport
        $transport = (new Swift_SmtpTransport('mail.xparklingtouch.com', 465, 'ssl'))
            ->setUsername('orders@xparklingtouch.com')
            ->setPassword('%FaBw-)+)62P');

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message('New Booking Order'))
            ->setFrom(['orders@xparklingtouch.com' => 'Xparkling Touch Unisex Salon'])
            ->setTo(['orders@xparklingtouch.com'])
            ->setBody("A new booking order has been made.<br>Customer Name: " . htmlspecialchars($customer['name']) . "<br>Customer Email: " . htmlspecialchars($customer['email']) . "<br>Customer Number: " . htmlspecialchars($customer['number']) . "<br>Order Date: " . htmlspecialchars($customer['date']), 'text/html');

        // Send the message
        $result = $mailer->send($message);

        if ($result) {
            // Update email_sent column
            $update_sql = "UPDATE customers SET email_sent = 1 WHERE trx_ref = '$trx_ref'";
            $conn->query($update_sql);
        } else {
            echo "Failed to send email.";
        }
    }

    // Generate order details string
    $order_details = "Ref No.: " . htmlspecialchars($trx_ref) . "\n";
    $order_details .= "Customer Name: " . htmlspecialchars($customer['name']) . "\n";
    $order_details .= "Customer Email: " . htmlspecialchars($customer['email']) . "\n";
    $order_details .= "Customer Number: " . htmlspecialchars($customer['number']) . "\n";
    $order_details .= "Appointment Date: " . htmlspecialchars(date('l jS F, Y', strtotime($customer['date']))) . "\n";
    $order_details .= "Time: " . htmlspecialchars($customer['time']) . "\n\n";
    $order_details .= "Order Details:\n";

    while ($order = $order_result->fetch_assoc()) {
        $order_details .= "Item Name: " . htmlspecialchars($order['item_name']) . "\n";
        $order_details .= "Item Price: " . htmlspecialchars(number_format($order['item_price'])) . "\n";
        $total_price += $order['item_price']; // Add the item price to the total price
    }

    // Handle form submission for downloading details
    if (isset($_POST['download_details'])) {
        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set background color
        $pdf->SetFillColor(255, 249, 221);
        $pdf->Rect(0, 0, 210, 297, 'F');

        // Add logo
        $pdf->Image('https://xparklingtouch.com/assets/imgs/logo-2.png', 10, 10, 30);
        
        // Set font for the title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetXY(50, 10);
        $pdf->Cell(0, 10, 'Order Details', 0, 1, 'C');
        
        // Set font for the order details
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(10, 40); // Adjust the position as needed
        $pdf->MultiCell(0, 10, $order_details);

        // Add the total price
        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Total Price: ' . number_format($total_price, 2), 0, 1, 'C');

        // Output the PDF
        $pdf->Output('D', 'order_details.pdf');
        exit;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../favicon.png">
    <title>BOOKING CONFIRMATION &vert; XPARKLING TOUCH UNISEX SALON</title>
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

    <section id="order-info">
        <h3>Order Confirmation</h3>
        <div class="alert alert-info">
            Order submitted successfully and your appointment is awaiting confirmation. You will be notified via email
        </div>
        <div class="order-details">
            <div class="customer-info">
                <h5 class="mt-4 mb-2">Customer Info</h5>
                <ul class="customer-info-1">
                    <li><span  class="fw-bold">Customer Name: </span> <?php echo htmlspecialchars($customer['name']); ?></li>
                    <li><span  class="fw-bold">Customer Email: </span> <?php echo htmlspecialchars($customer['email']); ?></li>
                    <li><span  class="fw-bold">Customer Number: </span> <?php echo htmlspecialchars($customer['number']); ?></li>
                    <li><span  class="fw-bold">Appointment Date: </span> <?php echo htmlspecialchars(date('l jS F, Y', strtotime($customer['date']))); ?></li>
                    <li><span  class="fw-bold">Time: </span> <?php echo htmlspecialchars($customer['time']); ?></li>
                </ul>
            </div>
            <div class="main-orders">
                <h5>Order Details</h5>
                <form method="post">
                    <button type="submit" class="d-pdf" name="download_details">Download Details <i class="fa-solid fa-download"></i></button>
                </form>
                <div class="order-table">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">Service</th>
                            <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Reset pointer and fetch data again for display
                            $order_result->data_seek(0);
                            while ($order = $order_result->fetch_assoc()): ?>
                            <tr>
                            <td><?php echo htmlspecialchars($order['item_name']); ?></td>
                            <td>&#8358;<?php echo htmlspecialchars(number_format($order['item_price'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <h6 class="my-2">Total: <?php echo number_format($total_price, 0); ?></h6>
                </div>
            </div>
        </div>
    </section>
    
</body>
</html>