    <?php
    class User {
        private $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function register($name, $email, $phone, $address, $password) {
            // Check if email or phone already exists
            $stmt = $this->db->prepare("SELECT id_dangki FROM tbl_dangki WHERE email = ? OR dienthoai = ?");
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                throw new Exception("Email hoặc số điện thoại đã được đăng ký");
            }

            $otp = Auth::generateOTP();
            $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
            $passwordHash = Auth::hashPassword($password);

            $stmt = $this->db->prepare("INSERT INTO tbl_dangki 
                (tenkhachhang, email, diachi, matkhau, dienthoai, otp_code, otp_expires_at, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $stmt->bind_param("sssssss", $name, $email, $address, $passwordHash, $phone, $otp, $otpExpiry);
            
            if (!$stmt->execute()) {
                throw new Exception("Có lỗi xảy ra khi đăng ký: " . $stmt->error);
            }

            return [
                'id' => $stmt->insert_id,
                'otp' => $otp
            ];
        }

        public function verifyOTP($userId, $otp) {
            $currentTime = date('Y-m-d H:i:s');
            $stmt = $this->db->prepare("
                SELECT id_dangki 
                FROM tbl_dangki 
                WHERE id_dangki = ? 
                AND otp_code = ? 
                AND otp_expires_at > ? 
                AND is_active = 0
            ");
            $stmt->bind_param("iss", $userId, $otp, $currentTime);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $updateStmt = $this->db->prepare("
                    UPDATE tbl_dangki 
                    SET is_active = 1, 
                        otp_code = NULL, 
                        otp_expires_at = NULL 
                    WHERE id_dangki = ?
                ");
                $updateStmt->bind_param("i", $userId);
                return $updateStmt->execute();
            }
            return false;
        }
    }