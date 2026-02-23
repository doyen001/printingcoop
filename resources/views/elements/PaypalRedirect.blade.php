@php
    $language_name = config('store.language_name', 'english');
    $currency_id = $ProductOrder['currency_id'] ?? 1;
    if (empty($currency_id)) {
        $currency_id = 1;
    }
    $OrderCurrencyData = $CurrencyList[$currency_id] ?? null;
    $paypal_payment_mode = $MainStoreData['paypal_payment_mode'] ?? 'live';
    $paypal_business_email = $MainStoreData['paypal_business_email'] ?? '';
    $paypal_sandbox_business_email = $MainStoreData['paypal_sandbox_business_email'] ?? '';
    $url = 'https://www.paypal.com/cgi-bin/webscr';

    $paypal_email = !empty($paypal_business_email) ? $paypal_business_email : 'imprimeur.coop@gmail.com';
    if ($paypal_payment_mode == 'sandbox') {
        $paypal_email = !empty($paypal_sandbox_business_email) ? $paypal_sandbox_business_email : 'sb-ks2ro721209@business.example.com';
        $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }
    $store_name = $MainStoreData['name'] ?? '';
    $store_url = $MainStoreData['url'] ?? url('/');
@endphp
<html>
<head>
    <title>{{ $language_name == 'french' ? 'Page de paiement PayPal' : 'PayPal Check Out Page' }}</title>
</head>
<body>
<center><h1>{{ $language_name == 'french' ? 'Veuillez ne pas actualiser cette page ...' : 'Please do not refresh this page...' }}</h1></center>
<form action="{{ $url }}" method="post" name="f1">
    <table border="1">
        <tbody>
            <input type="hidden" name="order_id" value="{{ $ProductOrder['id'] }}">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="{{ $paypal_email }}">
            <input type="hidden" name="item_number" value="{{ $ProductOrder['id'] }}">
            <input type="hidden" name="item_total" value="{{ $ProductOrder['total_items'] }}">
            <input type="hidden" name="amount" value="{{ $ProductOrder['total_amount'] }}">
            <input type="hidden" name="first_name" value="{{ $ProductOrder['shipping_name'] }}">
            <input type="hidden" name="return" value="{{ $store_url }}Checkouts/PayPalSuccessResponse/{{ $ProductOrder['id'] }}">
            <input type="hidden" name="cancel_return" value="{{ $store_url }}Checkouts/PayPalCancelResponse/{{ $ProductOrder['id'] }}">
            <input type="hidden" name="email" value="{{ $ProductOrder['email'] }}" >
            <input type="hidden" name="currency_code" value="CAD">
            <input type="hidden" name="notify_url" value="{{ $store_url }}Checkouts/PayPalIPNResponse/{{ $ProductOrder['id'] }}">
            <input type="hidden" name="cbt" value="Return to Merchant">
            <input type="hidden" name="rm" value="2">
        </tbody>
    </table>
    <script type="text/javascript">
        document.f1.submit();
    </script>
</form>

<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="business" value="sb-ks2ro721209@business.example.com">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="item_name" value="Hot Sauce-12oz. Bottle">
    <input type="hidden" name="amount" value="5.95">
    <input type="hidden" name="currency_code" value="USD">

    <!-- Display the payment button. -->
    <input type="image" name="submit" border="0"
        src="https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif"
        alt="Buy Now">
    <img alt="" border="0" width="1" height="1"
        src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" >
    </form>
</body>
</html>
