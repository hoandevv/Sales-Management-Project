<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define PHPMailer path
$phpmailerPath = realpath(__DIR__ . '/PHPMailer/src/');

// Verify the path exists and is readable
if ($phpmailerPath === false || !is_dir($phpmailerPath)) {
    throw new Exception("PHPMailer directory not found at: " . __DIR__ . '/PHPMailer/src/');
}

// Check if PHPMailer files exist
$requiredFiles = [
    $phpmailerPath . DIRECTORY_SEPARATOR . 'Exception.php',
    $phpmailerPath . DIRECTORY_SEPARATOR . 'PHPMailer.php',
    $phpmailerPath . DIRECTORY_SEPARATOR . 'SMTP.php'
];

foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        throw new Exception("PHPMailer file not found: " . $file . " (Current working directory: " . getcwd() . ")");
    }
    if (!is_readable($file)) {
        throw new Exception("PHPMailer file not readable (check permissions): " . $file);
    }
    require_once $file;
}

// Import the necessary classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP as PHPMailerSMTP;

class Mailer {
    private $mail;

    public function __construct() {
        $this->configure();
    }

    private function configure() {
        try {
            // Initialize PHPMailer
            $this->mail = new PHPMailer(true);
            
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'dohoanc3ngoctao@gmail.com';
            $this->mail->Password = 'whqh jsps bhyf vsjc';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
            $this->mail->CharSet = 'UTF-8';
            $this->mail->SMTPDebug = 2; // Enable verbose debug output
            
            // Log debug output to error log
            $this->mail->Debugoutput = function($str, $level) {
                $logFile = __DIR__ . '/mail_debug.log';
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - $str\n", FILE_APPEND);
                error_log("PHPMailer: $str");
            };
            
            // Sender info
            $this->mail->setFrom('dohoanc3ngoctao@gmail.com', 'GearShop');
            $this->mail->addReplyTo('dohoanc3ngoctao@gmail.com', 'GearShop');
        } catch (Exception $e) {
            throw new Exception("Mailer Error: ". $e->getMessage());
        }
    }

    public function dathangmail($tieude, $noidung, $email, $tenkhachhang) {
        try {
            // Clear all addresses from previous emails
            $this->mail->clearAddresses();
            $this->mail->clearAllRecipients();
            
            // Validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Địa chỉ email không hợp lệ: " . $email);
            }
            
            // Add recipient
            $this->mail->addAddress($email, $tenkhachhang);
            
            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $tieude;
            $this->mail->Body = $noidung;
            $this->mail->AltBody = strip_tags($noidung);
            
            // Send the email
            $this->mail->send();
            
            // Log success
            $logMessage = "[SUCCESS] Email sent to: $email | Subject: $tieude";
            error_log($logMessage);
            file_put_contents(__DIR__ . '/mail_success.log', date('Y-m-d H:i:s') . " - $logMessage\n", FILE_APPEND);
            
            return true;
        } catch (Exception $e) {
            $errorMsg = "Lỗi gửi email đến $email: " . $e->getMessage();
            error_log($errorMsg);
            throw new Exception($errorMsg);
        }
    }
}
?>
