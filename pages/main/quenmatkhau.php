<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include(__DIR__ . '/../../config.php');

// Xác định bước hiện tại
$current_step = $_SESSION['reset_step'] ?? 1;

// Reset về bước 1 nếu không có session hoặc có lỗi
if ($current_step < 1 || $current_step > 3) {
    $current_step = 1;
    unset($_SESSION['reset_step'], $_SESSION['reset_email']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - GearShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg #c8c4cbff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .reset-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
        }
        .step-indicator {
            background: #f8f9fa;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #e9ecef;
            z-index: 1;
        }
        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            position: relative;
            z-index: 2;
        }
        .step.active .step-circle {
            background: #0d6efd;
            color: white;
        }
        .step.completed .step-circle {
            background: #28a745;
            color: white;
        }
        .step.pending .step-circle {
            background: #e9ecef;
            color: #6c757d;
        }
        .step.completed:not(:last-child)::after {
            background: #28a745;
        }
        .step-label {
            font-size: 12px;
            text-align: center;
            color: #6c757d;
        }
        .step.active .step-label {
            color: #0d6efd;
            font-weight: 500;
        }
        .step.completed .step-label {
            color: #28a745;
        }
        .card-body {
            padding: 40px;
        }
        .btn-primary {
            background: #0d6efd;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
        .btn-outline-secondary {
            border-color: #dee2e6;
            color: #6c757d;
        }
        .btn-outline-secondary:hover {
            background: #f8f9fa;
            border-color: #adb5bd;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .alert {
            border-radius: 8px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="reset-card">
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step <?php echo $current_step >= 1 ? 'active' : 'pending'; ?> <?php echo $current_step > 1 ? 'completed' : ''; ?>">
                            <div class="step-circle">
                                <?php if ($current_step > 1): ?>
                                    <i class="fas fa-check"></i>
                                <?php else: ?>
                                    1
                                <?php endif; ?>
                            </div>
                            <div class="step-label">Nhập Email</div>
                        </div>
                        <div class="step <?php echo $current_step >= 2 ? 'active' : 'pending'; ?> <?php echo $current_step > 2 ? 'completed' : ''; ?>">
                            <div class="step-circle">
                                <?php if ($current_step > 2): ?>
                                    <i class="fas fa-check"></i>
                                <?php else: ?>
                                    2
                                <?php endif; ?>
                            </div>
                            <div class="step-label">Xác nhận OTP</div>
                        </div>
                        <div class="step <?php echo $current_step >= 3 ? 'active' : 'pending'; ?>">
                            <div class="step-circle">3</div>
                            <div class="step-label">Đặt mật khẩu mới</div>
                        </div>
                    </div>

                    <div class="card-body">
                        <?php if(isset($_SESSION['thongbao'])): ?>
                            <div class="alert alert-<?php echo $_SESSION['thongbao_type'] ?? 'info'; ?> alert-dismissible fade show" role="alert">
                                <i class="fas fa-<?php echo $_SESSION['thongbao_type'] == 'success' ? 'check-circle' : ($_SESSION['thongbao_type'] == 'danger' ? 'exclamation-circle' : 'info-circle'); ?> me-2"></i>
                                <?php
                                    echo $_SESSION['thongbao'];
                                    unset($_SESSION['thongbao']);
                                    unset($_SESSION['thongbao_type']);
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if ($current_step == 1): ?>
                            <!-- Bước 1: Nhập Email -->
                            <div class="text-center mb-4">
                                <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                <h4>Quên mật khẩu?</h4>
                                <p class="text-muted">Nhập email đăng ký để nhận mã OTP</p>
                            </div>

                            <form id="emailForm">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email đăng ký <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-lg" id="email" name="email" required placeholder="example@email.com">
                                    <div class="form-text">Chúng tôi sẽ gửi mã OTP đến email này</div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-lg" id="sendOtpBtn" onclick="sendOTP()">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi mã OTP
                                    </button>
                                    <a href="../../index.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
                                    </a>
                                </div>
                            </form>

                        <?php elseif ($current_step == 2): ?>
                            <!-- Bước 2: Nhập OTP -->
                            <div class="text-center mb-4">
                                <i class="fas fa-key fa-3x text-primary mb-3"></i>
                                <h4>Xác nhận mã OTP</h4>
                                <p class="text-muted">Nhập mã OTP đã gửi đến email <strong><?php echo htmlspecialchars($_SESSION['reset_email'] ?? ''); ?></strong></p>
                            </div>

                            <form action="verify_otp.php" method="POST" id="otpForm">
                                <div class="mb-3">
                                    <label for="otp" class="form-label">Mã OTP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg text-center" id="otp" name="otp" required placeholder="000000" maxlength="6" pattern="\d{6}">
                                    <div class="form-text text-center">Mã OTP gồm 6 chữ số</div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg" name="verify_otp">
                                        <i class="fas fa-check-circle me-2"></i>Xác nhận OTP
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="goBackToStep1()">
                                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                                    </button>
                                </div>
                                <div class="text-center mt-3">
                                    <small class="text-muted">Không nhận được mã? <a href="#" id="resendOtp">Gửi lại</a></small>
                                </div>
                            </form>

                        <?php elseif ($current_step == 3): ?>
                            <!-- Bước 3: Đặt mật khẩu mới -->
                            <div class="text-center mb-4">
                                <i class="fas fa-lock fa-3x text-primary mb-3"></i>
                                <h4>Đặt mật khẩu mới</h4>
                                <p class="text-muted">Tạo mật khẩu mới cho tài khoản của bạn</p>
                            </div>

                            <form action="xulymatkhaumoi.php" method="POST">
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-lg" id="new_password" name="new_password" required placeholder="Nhập mật khẩu mới">
                                    <div class="form-text">Tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường và số</div>
                                </div>
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" required placeholder="Nhập lại mật khẩu mới">
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg" name="reset_password">
                                        <i class="fas fa-save me-2"></i>Đặt lại mật khẩu
                                    </button>
                                    <a href="../../index.php" class="btn btn-outline-secondary">
                                        <i class="fas fa-home me-2"></i>Về trang chủ
                                    </a>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let countdown = 0;
        let countdownInterval;

        function sendOTP() {
            const email = document.getElementById('email').value;
            const btn = document.getElementById('sendOtpBtn');

            if (!email) {
                showAlert('Vui lòng nhập email!', 'danger');
                return;
            }

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showAlert('Email không hợp lệ!', 'danger');
                return;
            }

            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang gửi...';

            // Send AJAX request
            fetch('send_otp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent(email)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Reload page to show step 2
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert(data.message || 'Có lỗi xảy ra!', 'danger');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Gửi mã OTP';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi gửi OTP!', 'danger');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Gửi mã OTP';
            });
        }

        function goBackToStep1() {
            if (confirm('Bạn có chắc muốn quay lại bước đầu?')) {
                fetch('reset_session.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=reset_step'
                }).then(() => {
                    window.location.reload();
                });
            }
        }

        // Handle resend OTP
        document.getElementById('resendOtp')?.addEventListener('click', function(e) {
            e.preventDefault();
            const btn = this;
            const originalText = btn.textContent;

            btn.textContent = 'Đang gửi...';
            btn.style.pointerEvents = 'none';

            fetch('send_otp.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'email=' + encodeURIComponent('<?php echo $_SESSION['reset_email'] ?? ''; ?>')
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Mã OTP mới đã được gửi!', 'success');
                } else {
                    showAlert(data.message || 'Không thể gửi lại mã OTP', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Có lỗi xảy ra khi gửi lại mã OTP', 'danger');
            })
            .finally(() => {
                btn.textContent = originalText;
                btn.style.pointerEvents = 'auto';
            });
        });

        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            const cardBody = document.querySelector('.card-body');
            cardBody.insertBefore(alertDiv, cardBody.firstChild);
        }

        // Auto-focus on input fields
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const otpInput = document.getElementById('otp');

            if (emailInput) emailInput.focus();
            if (otpInput) {
                otpInput.focus();
                // Only allow numbers in OTP input
                otpInput.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });
            }
        });
    </script>
</body>
</html>
<?php
// Clear any output buffers
if (ob_get_level() > 0) {
    ob_end_flush();
}
