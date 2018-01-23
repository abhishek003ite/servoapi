@extends('base_view')

@section('content')
    <h2 style="color:red">Payment failed! Redirecting to your account ...</h2>
    <?php
        echo '
        <script>
            $(document).ready(function(){
                window.location.reload();
            });
        </script>
        ';
    ?>
@endsection