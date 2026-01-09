<?php
include('./RBMG_admin/config/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './RBMG_admin/PHPMailer/PHPMailer.php';
require './RBMG_admin/PHPMailer/SMTP.php';
require './RBMG_admin/PHPMailer/Exception.php';

$success = false;
$error = false;

// ---------- Handle Form Submission ----------
if (isset($_POST['submit'])) {

    $first_name     = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name      = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email          = mysqli_real_escape_string($conn, $_POST['email']);
    $phone          = mysqli_real_escape_string($conn, $_POST['phone']);
    $address        = mysqli_real_escape_string($conn, $_POST['address']);
    $city           = mysqli_real_escape_string($conn, $_POST['city']);
    $state          = mysqli_real_escape_string($conn, $_POST['state']);
    $pincode        = mysqli_real_escape_string($conn, $_POST['pin_code']);
    $about_us       = mysqli_real_escape_string($conn, $_POST['about_us']);
    $about_product  = mysqli_real_escape_string($conn, $_POST['product_details']);

    // ---------- Handle Image Upload ----------
    $image_name = "";
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "RBMG_admin/assets/images/sellers";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . '/' . $image_name;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $error = true;
            }
        } else {
            $error = true;
        }
    }

    if (!$error) {

        // ---------- Insert into Database ----------
        $sql = "INSERT INTO sellers(first_name, last_name, email, phone, address, city, state, pincode, about_us, about_product, image)
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$address', '$city', '$state', '$pincode', '$about_us', '$about_product', '$image_name')";

        if (mysqli_query($conn, $sql)) {

            // ---------- SEND EMAIL ----------
            $mail = new PHPMailer(true);

            try {
                // SMTP SETTINGS
                $mail->isSMTP();
                $mail->Host       = 'mail.digicoders.in'; 
                $mail->SMTPAuth   = true;
                $mail->Username   = 'alerts@digicoders.in';   //  Email
                $mail->Password   = 'nB%U4cB}$Tdu58AX';                 // SMTP Password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                // ----------------------------------------------------------------------
                // 1️⃣ ADMIN EMAIL
                // ----------------------------------------------------------------------
                $mail->setFrom('alerts@digicoders.in', 'RaeBioMedGlobal');
                $mail->addAddress('raebiomedglobal@gmail.com', 'Admin');

                // Attach Seller Image if available
                if (!empty($image_name)) {
                    $mail->addAttachment("RBMG_admin/assets/images/sellers/$image_name");
                }

                $mail->isHTML(true);
                $mail->Subject = "New Seller Request Received";

                $mail->Body = "
                    <h2>New Seller Request</h2>
                    <p><b>Name:</b> $first_name $last_name</p>
                    <p><b>Email:</b> $email</p>
                    <p><b>Phone:</b> $phone</p>
                    <p><b>Address:</b> $address, $city, $state - $pincode</p>
                    <p><b>About Seller:</b> $about_us</p>
                    <p><b>Product Details:</b><br>$about_product</p>
                ";

                $mail->send();

                // ----------------------------------------------------------------------
                // 2️⃣ USER CONFIRMATION EMAIL
                // ----------------------------------------------------------------------
                // $mail->clearAddresses();
                // $mail->addAddress($email, $first_name);

                // $mail->Subject = "Your Sell Request Has Been Received!";
                // $mail->Body = "
                //     <h3>Hello $first_name,</h3>
                //     <p>Thank you for your interest in selling your product with us!</p>
                //     <p>We have received your request and our team will contact you soon.</p>

                //     <p><b>Your Details:</b></p>
                //     <p>Name: $first_name $last_name</p>
                //     <p>Phone: $phone</p>
                //     <p>City: $city</p>

                //     <p><b>Your Product Details:</b></p>
                //     <p>$about_product</p>
                    
                //     <br>
                //     <p>Regards,<br>RBMG Team</p>
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
}


    ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>

      <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">

          <!-- Primary Meta Tags -->
          <title>Sell to us | RaeBioMedGlobal Healthtech - Leading Medical Equipment Supplier in India</title>

          <?php include("includes/header.php") ?>

          <div class="container">
              <!-- Breadcrumb -->
              <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                      <li class="breadcrumb-item active" aria-current="page">Sell To Us</li>
                  </ol>
              </nav>

              <!-- Page Title -->
              <h1 class="page-title">Sell To Us</h1>
          </div>
          <!-- Hero Section -->
          <section class="services-section">
              <div class="services-container">
                  <div class="services-hero-content">
                      <div class="services-text-content">
                          <p class="services-subtitle">Selling Used Medical Equipment Just Got Easier</p>
                          <h1 class="main-title">RaebioMedGlobal HEALTHCARE</h1>
                          <p class="services-description ">
                              Surplus medical equipment is a big commodity for RaebioMedGlobal Healthcare. What emerges is a dynamic program of reclaiming and refurbishing medical equipment, which has been at the core of the company's activities for twenty years. This mode of operation sets the highest standards for sustainable business models and provides a path for healthy medical business practices.
                          </p>
                          <p class="services-description">
                              RaebioMedGlobal Healthcare makes it easy for hospitals and surgery centers to decide what to do with their surplus medical equipment. Responsible disposal of unmarketable equipment is the second target with Surplus business impacts the environment. Managing the removal and logistics of medical equipment is a vital service provided by Soma. As the result hospitals can focus on patient care, and Soma can continue to grow, while tons of equipment stays out of landfills.
                          </p>
                      </div>
                      <div class="services-image-content">
                          <div class="services-image-wrapper">
                              <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=800&h=600&fit=crop" alt="Medical Equipment">
                              <div class="image-overlay"></div>
                          </div>
                      </div>
                  </div>
              </div>
          </section>

          <!-- Form Section -->
          <section class="services-form-section">
              <div class="form-services-container">
                  <!-- Steps Sidebar -->
                  <div class="steps-sidebar">
                      <div class="step-item">
                          <div class="step-number">1</div>
                          <div class="step-content">
                              <h3 class="step-title">Submit A Request</h3>
                              <p class="step-description">Submit your request to sell your equipment.</p>
                          </div>
                      </div>
                      <div class="step-item">
                          <div class="step-number">2</div>
                          <div class="step-content">
                              <h3 class="step-title">Representative Contact</h3>
                              <p class="step-description">A representative will contact you within 24 business hours.</p>
                          </div>
                      </div>
                      <div class="step-item">
                          <div class="step-number">3</div>
                          <div class="step-content">
                              <h3 class="step-title">Confirm Your Agreement</h3>
                              <p class="step-description">Confirm the agreement you and our representative discussed.</p>
                          </div>
                      </div>
                  </div>

                  <!-- Form Content -->
                  <div class="form-content">
                      <div class="form-wrapper">
                          <form method="POST" enctype="multipart/form-data">
                              <div class="form-row">
                                  <div class="form-group">
                                      <label class="form-label">First Name</label>
                                      <input type="text" class="form-input" name="first_name" placeholder="First Name" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">Last Name</label>
                                      <input type="text" class="form-input" name="last_name" placeholder="Last Name" required>
                                  </div>
                              </div>

                              <div class="form-group full-width">
                                  <label class="form-label">Address</label>
                                  <input type="text" class="form-input" name="address" placeholder="Address" required>
                              </div>

                              <div class="form-row">
                                  <div class="form-group">
                                      <label class="form-label">City</label>
                                      <input type="text" class="form-input" name="city" placeholder="City" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">State</label>
                                      <input type="text" class="form-input" name="state" placeholder="State" required>
                                  </div>
                              </div>

                              <div class="form-row">
                                  <div class="form-group">
                                      <label class="form-label">Pin Code</label>
                                      <input type="text" class="form-input" name="pin_code" placeholder="Zip Code" required>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-label">Phone No.</label>
                                      <input type="tel" class="form-input" name="phone" placeholder="Phone Number" required>
                                  </div>
                              </div>

                              <div class="form-group full-width">
                                  <label class="form-label">Email</label>
                                  <input type="email" class="form-input" name="email" placeholder="Email" required>
                              </div>

                              <div class="form-group full-width">
                                  <label class="form-label">How did you hear about us?</label>
                                  <input type="text" class="form-input" name="about_us" placeholder="How did you hear about us?">
                              </div>

                              <div class="form-group full-width">
                                  <label class="form-label">Please tell us about your product <span>*</span></label>
                                  <textarea class="form-textarea" name="product_details" placeholder="Please tell us about your product *" required></textarea>
                              </div>

                              <div class="form-group full-width">
                                  <label class="form-label">Upload Image</label>
                                  <input type="file" id="fileInput" name="image" accept="image/*">
                              </div>

                              <div class="form-group full-width">
                                  <button type="submit" class="submit-btn" name="submit">Send</button>
                              </div>
                          </form>

                      </div>
                  </div>
              </div>
          </section>

          <!-- CTA Section -->
          <section class="cta-section">
              <div class="cta-content">
                  <h2 class="cta-title">Ready To Move? Get A Quote Now!</h2>
                  <a href="contact_us.php" class="cta-btn">Get A Quote</a>
              </div>
          </section>

          <?php if ($success): ?>
              <script>
                  Swal.fire({
                      title: "Message Sent!",
                      text: "Your details has been successfully sent. We'll get back to you soon.",
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

          <!-- footer  -->

          <?php include("includes/footer.php") ?>