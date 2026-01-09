<?php

include("./RBMG_admin/config/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './RBMG_admin/PHPMailer/PHPMailer.php';
require './RBMG_admin/PHPMailer/SMTP.php';
require './RBMG_admin/PHPMailer/Exception.php';

$success = false;
$error = false;

if (isset($_POST['enquiry'])) {

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // ---------- Save Into Database ----------
    $sql = "INSERT INTO enquiries(name, email, phone, subject, message) 
            VALUES('$name', '$email', '$phone', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {

        // ---------- Send Email (Admin + User) ----------
        $mail = new PHPMailer(true);

        try {
            // SMTP Config
            $mail->isSMTP();
           $mail->Host       = 'mail.digicoders.in'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alerts@digicoders.in';   //  Email
            $mail->Password   = 'nB%U4cB}$Tdu58AX';                 // SMTP Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // ======================================================
            // 1️⃣ ADMIN EMAIL (Full Details)
            // ======================================================
            $mail->setFrom('alerts@digicoders.in', 'RaeBioMedGlobal');
            $mail->addAddress('raebiomedglobal@gmail.com');       // Admin email

            $mail->isHTML(true);
            $mail->Subject = "New Enquiry Received";

            $mail->Body = "
                <h2>New Enquiry Submitted</h2>
                <p><b>Name:</b> $name</p>
                <p><b>Email:</b> $email</p>
                <p><b>Phone:</b> $phone</p>
                <p><b>Subject:</b> $subject</p>
                <p><b>Message:</b><br>$message</p>
            ";

            $mail->send();


            // ======================================================
            // 2️⃣ USER CONFIRMATION EMAIL
            // ======================================================
            // $mail->clearAddresses();   // Clear admin email

            // $mail->addAddress($email); // Send to user
            // $mail->Subject = "Thank you for contacting us!";

            // $mail->Body = "
            //     <h3>Hi $name,</h3>
            //     <p>Thank you for contacting us. We have received your enquiry and our team will get back to you shortly.</p>
            //     <br>
            //     <p><b>Your Submitted Details:</b></p>
            //     <p><b>Subject:</b> $subject</p>
            //     <p><b>Message:</b><br>$message</p>
            //     <br>
            //     <p>Regards,<br>Support Team</p>
            // ";

            // $mail->send();


            $success = true;

        } catch (Exception $e) {
            $error = true;
        }

    } else {
        $error = true;
    }
}



// Get product id
$id = $_GET['product_id'] ?? 0;

// Fetch product
$query = mysqli_query($conn, "SELECT p.*, c.cat_name FROM products p 
                              JOIN categories c ON p.category_id = c.id 
                              WHERE p.id='$id'");
$product = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Primary Meta Tags -->
        <title>Product Details | RaeBioMedGlobal Healthtech - Leading Medical Equipment Supplier in India</title>
        <?php include("includes/header.php") ?>

        <!-- Breadcrumb -->
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="product-details-breadcrumb breadcrumb">
                    <li class="product-details-breadcrumb-item breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="product-details-breadcrumb-item breadcrumb-item"><a href="products.php">Products</a></li>
                    <li class="product-details-breadcrumb-item breadcrumb-item active" aria-current="page"><?php echo $product['name'] ?></li>
                </ol>
            </nav>

            <a href="products.php" class="product-details-back-button">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>

        <!-- Product Details -->
        <div class="container">
            <div class="product-details-container">
                <div class="row">
                    <!-- Product Images -->
                    <div class="col-lg-6 col-md-6 product-details-image-section">
                        <img src="RBMG_admin/assets/images/products/<?php echo $product['image'] ?>" alt="<?php echo $product['name'] ?>" class="product-details-main-image" id="mainImage">
                    </div>

                    <!-- Product Info -->
                    <div class="col-lg-6 col-md-6 product-details-info-section">
                        <h1 class="product-details-title"><?php echo $product['name'] ?></h1>
                        <div class="d-none product-details-price">&#8377;<?php echo $product['price'] ?></div>
                        <h4>About the Product:</h4>
                        <p class="product-details-description">
                            <?php echo $product['description'] ?>
                        </p>
                        <!-- Book Enquiry Button -->
                        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                            <i class="fas fa-paper-plane me-2"></i> Book Enquiry Now
                        </button>

                        <!-- Enquiry Modal -->
                        <div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="enquiryModalLabel">
                                            <i class="fas fa-envelope-open-text me-2"></i>Book Enquiry Form
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <!-- Modal Body -->
                                    <div class="modal-body">
                                        <form id="enquiryForm" method="post">
                                            <div class="mb-3">
                                                <label for="name" class="form-label fw-bold">Full Name</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Enter your full name" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="email" class="form-label fw-bold">Email Address</label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                                <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter your phone number" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="subject" class="form-label fw-bold">Subject</label>
                                                <input type="text" name="subject" class="form-control" id="subject" placeholder="Enter subject" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="message" class="form-label fw-bold">Message</label>
                                                <textarea class="form-control" name="message" id="message" rows="3" placeholder="Write your message..." required></textarea>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" name="enquiry" class="btn btn-success">
                                                    <i class="fas fa-paper-plane me-2"></i>Send Enquiry
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Full Specifications Section -->
            <div class="product-specifications-section mt-4">
                <h4 class="mb-3 text-primary fw-bold">
                    <i class="fas fa-clipboard-list me-2"></i> Full Specifications
                </h4>

                <?php
                if (!empty($product['specification'])) {
                    $specs = explode("\n", $product['specification']);

                    echo '<div class="specs-container">';
                    foreach ($specs as $spec) {
                        $spec = trim($spec);
                        if (!empty($spec)) {
                            // Split each line by ":" for Name and Value
                            $parts = explode(':', $spec, 2);
                            $name = htmlspecialchars(trim($parts[0]));
                            $value = isset($parts[1]) ? htmlspecialchars(trim($parts[1])) : '';

                            echo '
                            <div class="spec-item">
                                <div class="spec-name">
                                    <i class="fas fa-check-circle text-primary me-2"></i>' . $name . '
                                </div>
                                <div class="spec-value">' . $value . '</div>
                            </div>';
                        }
                    }
                    echo '</div>';
                } else {
                    echo '<p class="text-muted">No specifications available for this product.</p>';
                }
                ?>
            </div>
        </div>

    <?php if ($success): ?>
      <script>
        Swal.fire({
          title: "Message Sent!",
          text: "Your enquiry has been successfully sent. We'll get back to you soon.",
          icon: "success",
          confirmButtonColor: "#007bff"
        });
      </script>
    <?php elseif ($error): ?>
      <script>
        Swal.fire({
          title: "Error!",
          text: "Something went wrong. Please try again later.",
          icon: "error",
          confirmButtonColor: "#d33"
        });
      </script>
    <?php endif; ?>
        <?php include("./includes/footer.php") ?>