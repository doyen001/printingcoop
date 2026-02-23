<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PayUmoney BOLT</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<!-- this meta viewport is required for BOLT //-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
<!-- BOLT Sandbox/test //-->
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>

<!-- BOLT Production/Live //-->
<!--// script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script //-->

</head>
<style type="text/css">
    .main {
        margin-left:30px;
        font-family:Verdana, Geneva, sans-serif, serif;
    }
    .text {
        float:left;
        width:180px;
    }
    .dv {
        margin-bottom:5px;
    }
    .hidden{
        display:none;
    }
    .sbtn{
        width: 150px;
        height: 33px;
        font-size: 15px;
    }
</style>
<body>
<div class="main">
    <div style="text-align: center;">
        <img src="{{ url('/') }}/assets/images/payumoney.png" />
    </div>
    <div style="text-align: center;">
        <h3>Please do not refresh this page...</h3>
    </div>
    <form action="#" id="payment_form">
    <input type="hidden" id="udf5" name="udf5" value="{{ $paramList['udf5'] }}"/>
    <input type="hidden" id="surl" name="surl" value="{{ $paramList['CALLBACK_URL'] }}" />
    <div class="dv">
    <span><input type="hidden" id="key" name="key" placeholder="Merchant Key" value="{{ $paramList['key'] }}" /></span>
    </div>
    <div class="dv">
    <span class="hidden"><label>Merchant Salt:</label></span>
    <span><input type="hidden" id="salt" name="salt" placeholder="Merchant Salt" value="{{ $paramList['salt'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>Transaction/Order ID:</label></span>
    <span><input type="hidden" id="txnid" name="txnid" placeholder="Transaction ID" value="{{ $paramList['ORDER_ID'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>Amount:</label></span>
    <span><input type="hidden" id="amount" name="amount" placeholder="Amount" value="{{ $paramList['TXN_AMOUNT'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>Product Info:</label></span>
    <span><input type="hidden" id="pinfo" name="pinfo" placeholder="Product Info" value="{{ $paramList['product_info'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>First Name:</label></span>
    <span><input type="hidden" id="fname" name="fname" placeholder="First Name" value="{{ $paramList['name'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>Email ID:</label></span>
    <span><input type="hidden" id="email" name="email" placeholder="Email ID" value="{{ $paramList['email'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>Mobile/Cell Number:</label></span>
    <span><input type="hidden" id="mobile" name="mobile" placeholder="Mobile/Cell Number" value="{{ $paramList['mobile'] }}" /></span>
    </div>

    <div class="dv">
    <span class="hidden"><label>Hash:</label></span>
    <span><input type="hidden" id="hash" name="hash" placeholder="Hash" value="{{ $hash }}" /></span>
    </div>

    </form>

</div>
<script type="text/javascript"><!--
$('#payment_form').bind('keyup blur', function() {
    $.ajax({
          url: 'index.php',
          type: 'post',
          data: JSON.stringify({
            key: $('#key').val(),
            salt: $('#salt').val(),
            txnid: $('#txnid').val(),
            amount: $('#amount').val(),
            pinfo: $('#pinfo').val(),
            fname: $('#fname').val(),
            email: $('#email').val(),
            mobile: $('#mobile').val(),
            udf5: $('#udf5').val()
          }),
          contentType: "application/json",
          dataType: 'json',
          success: function(json) {
            if (json['error']) {
             $('#alertinfo').html('<i class="fa fa-info-circle"></i>'+json['error']);
            }
            else if (json['success']) {
                $('#hash').val(json['success']);
            }
          }
        });
});
//-->
</script>
<script type="text/javascript">
function launchBOLT()
{
    bolt.launch({
    key: $('#key').val(),
    txnid: $('#txnid').val(),
    hash: $('#hash').val(),
    amount: $('#amount').val(),
    firstname: $('#fname').val(),
    email: $('#email').val(),
    phone: $('#mobile').val(),
    productinfo: $('#pinfo').val(),
    udf5: $('#udf5').val(),
    surl : $('#surl').val(),
    furl: $('#surl').val(),
    mode: 'dropout'
},{ responseHandler: function(BOLT) {
    console.log( BOLT.response.txnStatus);
    if (BOLT.response.txnStatus != 'CANCEL')
    {
        //Salt is passd here for demo purpose only. For practical use keep salt at server side only.
        var fr = '<form action="'+$('#surl').val()+'" method="post">' +
        '<input type="hidden" name="key" value="'+BOLT.response.key+'" />' +
        '<input type="hidden" name="salt" value="'+$('#salt').val()+'" />' +
        '<input type="hidden" name="txnid" value="'+BOLT.response.txnid+'" />' +
        '<input type="hidden" name="amount" value="'+BOLT.response.amount+'" />' +
        '<input type="hidden" name="productinfo" value="'+BOLT.response.productinfo+'" />' +
        '<input type="hidden" name="firstname" value="'+BOLT.response.firstname+'" />' +
        '<input type="hidden" name="email" value="'+BOLT.response.email+'" />' +
        '<input type="hidden" name="udf5" value="'+BOLT.response.udf5+'" />' +
        '<input type="hidden" name="mihpayid" value="'+BOLT.response.mihpayid+'" />' +
        '<input type="hidden" name="status" value="'+BOLT.response.status+'" />' +
        '<input type="hidden" name="hash" value="'+BOLT.response.hash+'" />' +
        '</form>';
        var form = jQuery(fr);
        jQuery('body').append(form);
        form.submit();
    }

    if (BOLT.response.txnStatus = 'CANCEL') {
        window.location="{{ $paramList['CALLBACK_URL'] }}";
    }
},
    catchException: function(BOLT) {
         alert( BOLT.message );
    }
});
}
launchBOLT();
</script>
</body>
</html>
