<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <style>
        .button {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 0;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.42857143;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-image: none;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .button-custom {
            color: #000;
            background-color: #FFCD05;
            border-color: #FFCD05;
            text-decoration: none;
        }
    </style>
</head>
<body>
<h2>Verify Your Email Address</h2>

<div>
    <p>Thanks for creating an account with the ServoQuick Service Provider App.</p>
    <p>Please click on the below activate button to verify your email address.</p>
    <a class="button button-custom" href="{!! env('PARTNER_FRONTEND_URL').'/register/verify-email?email='.urlencode($email).'&code='.urlencode($confirmation_code) !!}">Acitvate account</a>

</div>

</body>
</html>