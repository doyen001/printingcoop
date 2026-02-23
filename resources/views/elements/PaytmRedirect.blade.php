<html>
<head>
<title>Paypal Check Out Page</title>
</head>
<body>
    @php
        // Debug: pr($ProductOrder); die('OK');
    @endphp
    <center><h1>Please do not refresh this page...</h1></center>
        <form method="post" action="https://www.paypal.com/cgi-bin/webscr" name="f1">
        <table border="1">
            <tbody>
               <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                  <input type="hidden" name="cmd" value="_cart">
                  <input type="hidden" name="business" value="seller@designerfotos.com">
                  <input type="hidden" name="item_name" value="hat">
                  <input type="hidden" name="item_number" value="123">
                  <input type="hidden" name="amount" value="15.00">
                  <input type="hidden" name="first_name" value="John">
                  <input type="hidden" name="last_name" value="Doe">
                  <input type="hidden" name="address1" value="9 Elm Street">
                  <input type="hidden" name="address2" value="Apt 5">
                  <input type="hidden" name="city" value="Berwyn">
                  <input type="hidden" name="state" value="PA">
                  <input type="hidden" name="zip" value="19312">
                  <input type="hidden" name="night_phone_a" value="610">
                  <input type="hidden" name="night_phone_b" value="555">
                  <input type="hidden" name="night_phone_c" value="1234">
                  <input type="hidden" name="email" value="jdoe@zyzzyu.com">
                  <input type="image" name="submit"
                    src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
                    alt="PayPal - The safer, easier way to pay online">
                </form>

            </tbody>
        </table>
        <script type="text/javascript">
            //document.f1.submit();
        </script>
    </form>
</body>
</html>
