@extends('base_view')

@section('content')
    <h2>Payment successful! Redirecting to your account ...</h2>
    <?php
        $url = env('CUSTOMER_FRONTEND_URL') . env('CUSTOMER_PAYMENT_SUCCESS_URL');
        echo '
        <script>
            $(document).ready(function(){
                window.location="' . $url . '";
            });
        </script>
        ';
    ?>
@endsection