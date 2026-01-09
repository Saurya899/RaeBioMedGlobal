<?php
include("./RBMG_admin/config/db.php");
$success = false;
$error = false;

include("./RBMG_admin/config/db.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './RBMG_admin/PHPMailer/PHPMailer.php';
require './RBMG_admin/PHPMailer/SMTP.php';
require './RBMG_admin/PHPMailer/Exception.php';

$success = false;
$error = false;

// ---------- Form Submit ----------
if (isset($_POST['submit'])) {

    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // ---------- Insert into DB ----------
    $sql = "INSERT INTO messages (name, email, phone, subject, message)
            VALUES('$name', '$email', '$phone', '$subject', '$message')";

    if (mysqli_query($conn, $sql)) {

        // ---------- Send Emails ----------
        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host       = 'mail.digicoders.in'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'alerts@digicoders.in';   //  Email
            $mail->Password   = 'nB%U4cB}$Tdu58AX';                 // SMTP Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // ----------------------------------------------------------------------
            // 1️ ADMIN EMAIL (Contact Form Details)
            // ----------------------------------------------------------------------
            $mail->setFrom('alerts@digicoders.in', 'RaeBioMedGlobal');
            $mail->addAddress('raebiomedglobal@gmail.com', 'Admin'); 

            $mail->isHTML(true);
            $mail->Subject = "New Contact Form Message Received";

            $mail->Body = "
                <h2>New Contact Message</h2>
                <p><b>Name:</b> $name</p>
                <p><b>Email:</b> $email</p>
                <p><b>Phone:</b> $phone</p>
                <p><b>Subject:</b> $subject</p>
                <p><b>Message:</b><br>$message</p>
            ";

            $mail->send();

            // ----------------------------------------------------------------------
            // 2️ USER CONFIRMATION EMAIL
            // ----------------------------------------------------------------------
            // $mail->clearAddresses();
            // $mail->addAddress($email, $name);

            // $mail->Subject = "We Received Your Message!";
            // $mail->Body = "
            //     <h3>Hello $name,</h3>
            //     <p>Thank you for contacting us!</p>
            //     <p>We have received your message and our support team will reply soon.</p>
            //     <br>
            //     <p><b>Your Message:</b></p>
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


?>
<!DOCTYPE html>
<html lang="en">

<head>

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Primary Meta Tags -->
    <title>Contact Us | RaeBioMedGlobal  - Leading Medical Equipment Supplier in India</title>
    <?php include("includes/header.php") ?>


    <!-- ======= HEADER ======= -->
    <section class="contact-header">
      <h1>Contact Us</h1>
      <p>We’d love to hear from you! Connect with any of our offices below.</p>
    </section>

    <!-- ======= OFFICE INFO ======= -->
    <section class="contact-office-info">
      <div class="info-box">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Lucknow Office</h3>
        <p>C-37 Alkapuri Colony, Aliganj<br>Lucknow, 226024, Uttar Pradesh</p><br>
        <a href="tel:+918004752804" class="call-link">+91 8004752804</a>
        <a href="mailto:raebiomedglobal@gmail.com" class="mail-link">raebiomedglobal@gmail.com</a><br> <br>
        <a class="map-link" href="https://maps.app.goo.gl/tztzjcZSYunaQhCw9" target="_blank">
          📍 View on Google Map
        </a>
      </div>

      <div class="info-box">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Kanpur Office</h3>
        <p>1188 Vakil Nagar, Kalyanpur<br>Kanpur Nagar, Uttar Pradesh</p><br>
        <a href="tel:+916394365653" class="call-link">+91 6394365653</a>
         <a href="mailto:raebiomedglobal@gmail.com" class="mail-link">raebiomedglobal@gmail.com</a><br> <br>
        <a class="map-link" href="https://maps.app.goo.gl/tztzjcZSYunaQhCw9" target="_blank">
          📍 View on Google Map
        </a>
      </div>

      <div class="info-box">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Gorakhpur Office</h3>
        <p>Betiyahata South, Near Falmandi No-31,Infront of Indian Oil Petrol Pump,Gorakhpur, Uttar Pradesh</p>
        <a href="tel:+919511150618" class="call-link">+91 9511150618</a><br>
         <a href="mailto:raebiomedglobal@gmail.com" class="mail-link">raebiomedglobal@gmail.com</a><br> <br>
        <a class="map-link" href="https://maps.app.goo.gl/tztzjcZSYunaQhCw9" target="_blank">
          📍 View on Google Map
        </a>
      </div>
    </section>

    <!-- ======= CONTACT FORM ======= -->
    <section class="contact-form-section">
      <h2>Send Us a Message</h2>
      <form class="contact-form" method="post">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="number" name="phone" placeholder="Your Phone" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <textarea class="contact-textarea" name="message" placeholder="Your Message" required></textarea>
        <button type="submit" name="submit">Send Message</button>
      </form>
    </section>

    <?php if ($success): ?>
      <script>
        Swal.fire({
          title: "Message Sent!",
          text: "Your message has been successfully sent. We'll get back to you soon.",
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