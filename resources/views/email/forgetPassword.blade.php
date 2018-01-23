<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Request for Reset Password</h2>

<div>
    As per your request your we are sending your reset password link.
    Please follow the link below to verify your email address
    {!! env('PARTNER_FRONTEND_URL').'reset/reset-password?email='.urlencode($email).'&code='.urlencode($confirmation_code) !!}.<br/>

</div>

</body>
</html>