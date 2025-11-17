<!DOCTYPE html>
<html>

<head>
    <title>Your Account Verification Code</title>
</head>

<body>
    <p>Hello {{ $name }},</p>
    <p>Your OTP code is: <strong>{{ $otp }}</strong></p>
    <p>This OTP is valid for 5 minutes.</p>
    <br>
    <p>Thank you</p>
    <p>{{ config('app.name') }}</p>
</body>

</html>
