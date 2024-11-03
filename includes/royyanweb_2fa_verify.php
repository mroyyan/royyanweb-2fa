<?php
// Pastikan pengguna diarahkan ke halaman verifikasi hanya setelah login dan ada kode 2FA yang dikirim
if (!is_user_logged_in() || empty($_SESSION['royyanweb_2fa_code'])) {
    wp_redirect(home_url());
    exit;
}

// Proses verifikasi kode 2FA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_code = implode('', array_map('sanitize_text_field', $_POST['2fa_code']));

    if ($entered_code == $_SESSION['royyanweb_2fa_code']) {
        unset($_SESSION['royyanweb_2fa_code']);
        wp_redirect(admin_url());
        exit;
    } else {
        $error_message = "Wrong OTP code, please try again.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>OTP Verification</title>
    <link rel="stylesheet" href="<?php echo plugins_url('../assets/royyanweb_style.css', __FILE__); ?>">
</head>

<body>
    <div class="container">
        <div class="verify-container">
            <div class="titleotp">OTP Verification</div>
            <div class="infosmall">Enter the 6 digit code sent to your device</div>
            <?php if (!empty($error_message)) : ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" id="twofa-form">
                <div class="input-container">
                    <input type="text" name="2fa_code[]" class="input-box" maxlength="1" min="0" max="9" required autofocus>
                    <input type="text" name="2fa_code[]" class="input-box" maxlength="1" min="0" max="9" required>
                    <input type="text" name="2fa_code[]" class="input-box" maxlength="1" min="0" max="9" required>
                    <input type="text" name="2fa_code[]" class="input-box" maxlength="1" min="0" max="9" required>
                    <input type="text" name="2fa_code[]" class="input-box" maxlength="1" min="0" max="9" required>
                    <input type="text" name="2fa_code[]" class="input-box" maxlength="1" min="0" max="9" required>
                </div>
                <button type="submit">Verify</button>
            </form>
        </div>
    </div>
    <script src="<?php echo plugins_url('../assets/royyanweb.js', __FILE__); ?>"></script>
</body>

</html>