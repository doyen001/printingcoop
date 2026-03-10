<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .billing-info, .shipping-info {
            margin-bottom: 20px;
            float: left;
            width: 48%;
        }
        .billing-info {
            margin-right: 2%;
        }
        .info-section {
            margin-bottom: 20px;
            clear: both;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-section p {
            margin: 5px 0;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-items th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        .order-items td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }
        .order-items .text-right {
            text-align: right;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-row {
            margin-bottom: 5px;
        }
        .total-row strong {
            display: inline-block;
            width: 150px;
            text-align: right;
            margin-right: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p><strong>Invoice #:</strong> {{ $order->order_id }}</p>
        <p><strong>Date:</strong> {{ $orderDate }}</p>
        @if($store)
            <p><strong>Store:</strong> {{ $store->name }}</p>
        @endif
    </div>

    <div class="clearfix">
        <div class="billing-info">
            <div class="info-section">
                <h3>Bill To:</h3>
                <p><strong>{{ ucfirst($order->billing_name) }}</strong></p>
                <p>{{ $order->billing_address }}</p>
                <p>{{ $order->billing_city }}, {{ $order->billing_state }}, {{ $order->billing_country }}</p>
                <p>{{ $order->billing_pin_code }}</p>
                <p>Phone: {{ $order->billing_mobile }}</p>
                <p>Email: {{ $order->email }}</p>
            </div>
        </div>

        <div class="shipping-info">
            <div class="info-section">
                <h3>Ship To:</h3>
                <p><strong>{{ ucfirst($order->shipping_name) }}</strong></p>
                <p>{{ $order->shipping_address }}</p>
                <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}, {{ $order->shipping_country }}</p>
                <p>{{ $order->shipping_pin_code }}</p>
                <p>Phone: {{ $order->shipping_mobile }}</p>
            </div>
        </div>
    </div>

    <div class="info-section">
        <h3>Order Information</h3>
        <p><strong>Order Status:</strong> {{ $order->status }}</p>
        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_type ?? 'N/A') }}</p>
        <p><strong>Payment Status:</strong> {{ $order->payment_status }}</p>
        @if($order->transition_id)
            <p><strong>Transaction ID:</strong> {{ $order->transition_id }}</p>
        @endif
    </div>

    <table class="order-items">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderItems as $item)
                <tr>
                    <td>
                        <strong>{{ ucfirst($item->name) }}</strong>
                        @php
                        $attributes = [];
                        if (!empty($item->attribute_ids)) {
                            $decoded = json_decode($item->attribute_ids, true);
                            if (is_array($decoded) && isset($decoded['options'])) {
                                foreach ($decoded['options'] as $option) {
                                    if (isset($option['label']) && isset($option['value'])) {
                                        $attributes[] = $option['label'] . ': ' . $option['value'];
                                    }
                                }
                            }
                        }
                        @endphp
                        @if(!empty($attributes))
                            <br><small>{{ implode(' | ', $attributes) }}</small>
                        @endif
                        @if(!empty($item->votre_text))
                            <br><small>Custom Text: {{ $item->votre_text }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $currencySymbol }}{{ number_format($item->price, 2) }}</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <strong>Subtotal:</strong>
            {{ $currencySymbol }}{{ number_format($order->sub_total_amount ?? $order->total_amount, 2) }}
        </div>
        @if($order->delivery_charge > 0)
            <div class="total-row">
                <strong>Shipping:</strong>
                {{ $currencySymbol }}{{ number_format($order->delivery_charge, 2) }}
            </div>
        @endif
        @if($order->preffered_customer_discount > 0)
            <div class="total-row">
                <strong>Discount:</strong>
                -{{ $currencySymbol }}{{ number_format($order->preffered_customer_discount, 2) }}
            </div>
        @endif
        <div class="total-row" style="font-size: 16px; font-weight: bold; border-top: 2px solid #333; padding-top: 10px; margin-top: 10px;">
            <strong>Total:</strong>
            {{ $currencySymbol }}{{ number_format($order->total_amount, 2) }}
        </div>
    </div>

    <div class="footer">
        <p>Thank you for your business!</p>
        @if($store)
            <p>{{ $store->name }} | {{ $store->address ?? '' }} | {{ $store->phone ?? '' }}</p>
        @endif
        <p>This is a computer-generated invoice and does not require a signature.</p>
    </div>
</body>
</html>
