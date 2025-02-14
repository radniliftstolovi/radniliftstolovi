<?php
// Uključivanje PHPMailer biblioteke
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Email na koji šaljemo
    $pdfData = $_POST['pdf']; // PDF podaci u base64 formatu

    // Dekodiranje base64 podataka
    $pdfDecoded = base64_decode(substr($pdfData, strpos($pdfData, ",") + 1));

    // Spremanje PDF-a na server
    $pdfFile = 'tmp/stranica.pdf';
    file_put_contents($pdfFile, $pdfDecoded);

    // Kreiranje PHPMailer objekta
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Postavi svoj SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // Tvoj email
        $mail->Password = 'your-email-password'; // Tvoja lozinka
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Primaoci
        $mail->setFrom('your-email@example.com', 'Web aplikacija');
        $mail->addAddress($email); // Dodaj korisnikov email

        // Prilog
        $mail->addAttachment($pdfFile);

        // Sadržaj emaila
        $mail->isHTML(true);
        $mail->Subject = 'Sadržaj stranice u PDF formatu';
        $mail->Body    = 'Pozdrav! Prilog je PDF sa sadržajem stranice.';

        // Slanje emaila
        $mail->send();
        echo json_encode(['message' => 'PDF je uspešno poslat na email.']);
    } catch (Exception $e) {
        echo json_encode(['message' => 'Greška pri slanju emaila: ' . $mail->ErrorInfo]);
    }
}
?>