<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $pdf = $_POST['pdf'];

    // Pretvori PDF sa base64 u binarni format
    $pdfData = base64_decode(str_replace('data:application/pdf;base64,', '', $pdf));

    $mail = new PHPMailer(true);
    
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ako koristiš Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'bojan_n91@yahoo.com'; // Tvoj Gmail
        $mail->Password = 'bojan_n91@yahoo.com'; // Tvoja lozinka
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('bojan_n91@yahoo.com', 'Your Name');
        $mail->addAddress($email); // Primaoc

        $mail->Subject = 'Vaš PDF dokument';
        $mail->Body    = 'Ovdje je PDF koji ste zatražili.';

        // Dodaj PDF kao prilog
        $mail->addStringAttachment($pdfData, 'document.pdf');

        $mail->send();
        echo 'PDF je uspešno poslat na vaš email!';
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}
?>