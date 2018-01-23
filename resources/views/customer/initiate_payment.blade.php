@extends('base_view')

@section('content')
    @if($error)
        <h2 style="color:red">Error: {{$error}}</h2>
    @else
        <h2> Directing you to Payment Gateway...</h2>
        <form 
            action="{{$payment_url}}" 
            id="payuform" 
            name="payuform" 
            method=POST
            style="visibility:hidden"
            >
            <input type="hidden" name="key" value="{{$key}}" />
            <input type="hidden" name="hash_string" value="{{$hash}}" />
            <input type="hidden" name="hash" value="{{$hash}}"/>
            <input type="hidden" name="txnid" value="{{$txn_id}}"/>
            <table>
                <tr>
                    <td><b>Mandatory Parameters</b></td>
                </tr>
                
                <tr>
                    <td>Amount: </td>
                    <td><input name="amount" value="{{$amount}}"/></td>
                    <td>First Name: </td>
                    <td><input name="firstname" id="firstname"  value="{{$first_name}}"/></td>
                </tr>
            
                <tr>
                    <td>Email: </td>
                    <td><input name="email" id="email" value="{{$email}}"/></td>
                    <td>Phone: </td>
                    <td><input name="phone" value="{{$mobile}}"/></td>
                </tr>
            
                <tr>
                    <td>Product Info: </td>
                    <td colspan="3"><input type="text" name="productinfo" value="{{$product_info}}"></td>
                </tr>
            
                <tr>
                    <td>Success URI: </td>
                    <td colspan="3"><input name="surl" size="64" value="{{$success_url}}"  /></td>
                </tr>
            
                <tr>
                    <td>Failure URI: </td>
                    <td colspan="3"><input name="furl" size="64" value="{{$failure_url}}"/></td>
                </tr>
                
                <tr>
                    <td><b>Optional Parameters</b></td>
                </tr>
            
                <tr>
                <td>Last Name: </td>
                <td><input name="lastname" id="lastname"  /></td>
                <td>Cancel URI: </td>
                <td><input name="curl" value="" /></td>
                </tr>
                <tr>
                <td>Address1: </td>
                <td><input name="address1" /></td>
                <td>Address2: </td>
                <td><input name="address2"  /></td>
                </tr>
                <tr>
                <td>City: </td>
                <td><input name="city"  /></td>
                <td>State: </td>
                <td><input name="state"  /></td>
                </tr>
                <tr>
                <td>Country: </td>
                <td><input name="country"  /></td>
                <td>Zipcode: </td>
                <td><input name="zipcode"  /></td>
                </tr>
                <tr>
                <td>UDF1: </td>
                <td><input name="udf1" value="nothing"/></td>
                <td>UDF2: </td>
                <td><input name="udf2" value="nothing" /></td>
                </tr>
                <tr>
                <td>UDF3: </td>
                <td><input name="udf3" value="nothing"  /></td>
                <td>UDF4: </td>
                <td><input name="udf4" value="nothing" /></td>
                </tr>
                <tr>
                <td>UDF5: </td>
                <td><input name="udf5" value="nothing" /></td>
                <td>PG: </td>
                <td><input name="pg"  /></td>
                </tr>
                {{--  <td colspan="4"><input type="submit" value="Submit"/></td>
                </tr>  --}}
                </table>
            </form>

        <!--Submit the form once everything is done -->
        <?php echo '<script>
                $(document).ready(function(){
                    $("#payuform").submit();
                });
            </script>'; ?>
    @endif 
@endsection