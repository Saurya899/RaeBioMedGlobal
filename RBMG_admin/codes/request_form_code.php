  <?php 
         
include('../config/db.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

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


            echo "<script>alert('Your enquiry has been submitted successfully. We will get back to you soon!'); window.location.href='../../index.php';</script>";

        } catch (Exception $e) {
           echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.location.href='../../index.php';</script>"; 
        }

    } else {
       echo "<script>alert('Error submitting your enquiry. Please try again later.'); window.location.href='../../index.php';</script>";
    }
}
?>
