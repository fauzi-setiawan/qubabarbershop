<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password Quba Barbershop</title>
</head>
<body>
    <p>Hallo Bang Bro, {{ $user->username }} 👋</p>
    <p>Kamu meminta reset password. Gunakan kode OTP berikut untuk melanjutkan:</p>
    <h2 style="color: #2d3748;">{{ $otp }}</h2>
    <p>Kode ini berlaku 5 menit. Jika kamu tidak meminta ini, abaikan email ini.</p>
    <p>Terima kasih,<br>Quba Barbershop</p>
</body>
</html>
