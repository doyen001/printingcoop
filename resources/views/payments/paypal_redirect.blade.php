<!DOCTYPE html>
<html>
<head>
    <title>{{ $language_name == 'french' ? 'Redirection vers PayPal' : 'Redirecting to PayPal' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f5f5f5;
        }
        .redirect-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0070ba;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="redirect-box">
        <h2>{{ $language_name == 'french' ? 'Redirection vers PayPal...' : 'Redirecting to PayPal...' }}</h2>
        <div class="spinner"></div>
        <p>{{ $language_name == 'french' ? 'Veuillez patienter pendant que nous vous redirigeons vers PayPal pour finaliser votre paiement.' : 'Please wait while we redirect you to PayPal to complete your payment.' }}</p>
        
        <form id="paypal_form" action="https://www.{{ $paypal_mode == 'sandbox' ? 'sandbox.' : '' }}paypal.com/cgi-bin/webscr" method="post">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="{{ $paypal_client_id }}">
            <input type="hidden" name="item_name" value="Order #{{ $ProductOrder->order_id ?? $ProductOrder->id }}">
            <input type="hidden" name="item_number" value="{{ $ProductOrder->id }}">
            <input type="hidden" name="amount" value="{{ number_format($ProductOrder->total_amount, 2, '.', '') }}">
            <input type="hidden" name="currency_code" value="CAD">
            <input type="hidden" name="return" value="{{ $return_url }}">
            <input type="hidden" name="cancel_return" value="{{ $cancel_url }}">
            <input type="hidden" name="notify_url" value="{{ url('Payments/paypal_ipn/' . $ProductOrder->id) }}">
            <input type="hidden" name="custom" value="{{ $ProductOrder->id }}">
            
            <noscript>
                <button type="submit" class="btn btn-primary">
                    {{ $language_name == 'french' ? 'Cliquez ici si vous n\'êtes pas redirigé automatiquement' : 'Click here if you are not redirected automatically' }}
                </button>
            </noscript>
        </form>
    </div>
    
    <script>
        // Auto-submit form after 2 seconds
        setTimeout(function() {
            document.getElementById('paypal_form').submit();
        }, 2000);
    </script>
</body>
</html>
