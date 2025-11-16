<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class Mailer {
    // Phương thức gửi email cho khách hàng
    public function dathangmail($tieude, $noidung, $maildathang, $tenkhachhang) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            // Cấu hình server gửi email
            $mail->SMTPDebug = 0 ; 
            $mail->isSMTP(); // Gửi qua SMTP
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'abc@gmail.com'; 
            $mail->Password   = 'whqh jsps bhyf vsjc'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587; 

            // Cấu hình người gửi và người nhận
            $mail->setFrom('abc@gmail.com', 'GearShop.com'); 
            $mail->addAddress($maildathang, $tenkhachhang); 

           
            $mail->isHTML(true); 
            $mail->Subject = $tieude;
            $mail->Body    = $noidung;
            $mail->AltBody = strip_tags($noidung);

           
            $mail->send();
            echo 'Email đã được gửi thành công cho khách hàng!';
        } catch (Exception $e) {
            echo "Không thể gửi email cho khách hàng. Lỗi: {$mail->ErrorInfo}";
        }
    }

    // Phương thức gửi email cho người bán
    public function dathangmailSeller($tieudeseller, $noidung, $maildathang, $tenkhachhang) {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        try {
            // Cấu hình server gửi email
            $mail->SMTPDebug = 0; 
            $mail->isSMTP(); // Gửi qua SMTP
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true; 
            $mail->Username   = 'abc@gmail.com'; 
            $mail->Password   = 'whqh jsps bhyf vsjc'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port       = 587; 

            $mail->setFrom('abc@gmail.com', 'GearShop.com');
            $mail->addAddress('abc@gmail.com', 'GearShop.com');  

           
            $mail->isHTML(true); 
            $mail->Subject = $tieudeseller;
            $mail->Body    = $noidung; // Nội dung chi tiết đơn hàng
            $mail->AltBody = strip_tags($noidung); 

            // Gửi email
            $mail->send();
            echo 'Email đã được gửi thành công cho người bán!';
        } catch (Exception $e) {
            echo "Không thể gửi email cho người bán. Lỗi: {$mail->ErrorInfo}";
        }
    }
}

?>
