<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details #{{ $order['order_id'] }}</title>
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
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            color: #333;
        }
        .info-section p {
            margin: 5px 0;
        }
        .info-section strong {
            display: inline-block;
            min-width: 120px;
            color: #555;
        }
        .order-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-items th {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        .order-items td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            vertical-align: top;
            font-size: 11px;
        }
        .order-items .text-right {
            text-align: right;
        }
        .product-details {
            max-width: 300px;
        }
        .product-details strong {
            display: block;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ORDER DETAILS</h1>
        <p><strong>Order #:</strong> {{ $order['order_id'] }}</p>
        <p><strong>Date:</strong> {{ $orderDate }}</p>
        @if($store)
            <p><strong>Store:</strong> {{ $store->name }}</p>
        @endif
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h3>Customer Information</h3>
            <p><strong>Customer Name:</strong> {{ ucfirst($order['name']) }}</p>
            <p><strong>Email:</strong> {{ $order['email'] }}</p>
            <p><strong>Phone:</strong> {{ $order['mobile'] }}</p>
            <p><strong>Customer Code:</strong> 
                @if (!empty($order['user_id']))
                    CUST{{ str_pad($order['user_id'], 6, '0', STR_PAD_LEFT) }}
                @else
                    -
                @endif
            </p>
        </div>

        <div class="info-section">
            <h3>Order Information</h3>
            <p><strong>Order Status:</strong> {{ $order['status'] }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order['payment_type'] ?? 'N/A') }}</p>
            <p><strong>Payment Status:</strong> {{ $order['payment_status'] }}</p>
            <p><strong>Total Amount:</strong> {{ $currencySymbol }}{{ number_format($order['total_amount'], 2) }}</p>
            @if($order['transition_id'])
                <p><strong>Transaction ID:</strong> {{ $order['transition_id'] }}</p>
            @endif
        </div>

        <div class="info-section">
            <h3>Billing Address</h3>
            <p><strong>Name:</strong> {{ ucfirst($order['billing_name']) }}</p>
            <p><strong>Address:</strong> {{ $order['billing_address'] }}</p>
            <p><strong>City:</strong> {{ $cityData['name'] ?? '' }}</p>
            <p><strong>State:</strong> {{ $stateData['name'] ?? '' }}</p>
            <p><strong>Country:</strong> {{ $countryData['name'] ?? '' }}</p>
            <p><strong>Pin Code:</strong> {{ $order['billing_pin_code'] }}</p>
            <p><strong>Phone:</strong> {{ $order['billing_mobile'] }}</p>
        </div>

        <div class="info-section">
            <h3>Shipping Address</h3>
            <p><strong>Name:</strong> {{ ucfirst($order['shipping_name']) }}</p>
            <p><strong>Address:</strong> {{ $order['shipping_address'] }}</p>
            <p><strong>City:</strong> {{ $cityData['name'] ?? '' }}</p>
            <p><strong>State:</strong> {{ $stateData['name'] ?? '' }}</p>
            <p><strong>Country:</strong> {{ $countryData['name'] ?? '' }}</p>
            <p><strong>Pin Code:</strong> {{ $order['shipping_pin_code'] }}</p>
            <p><strong>Phone:</strong> {{ $order['shipping_mobile'] }}</p>
        </div>
    </div>

    <h3 style="margin-bottom: 15px; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Order Items</h3>
    <table class="order-items">
        <thead>
            <tr>
                <th>Product Details</th>
                <th>Custom Text</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orderItems as $item)
                <tr>
                    <td class="product-details">
                        <strong>{{ ucfirst($item['name']) }}</strong>
                        <small>Product ID: {{ $item['product_id'] }}</small>
                        @php
                        // Safe attribute display
                        $attributes = [];
                        if (!empty($item['attribute_ids']) && is_array($item['attribute_ids'])) {
                            if (isset($item['attribute_ids']['options']) && is_array($item['attribute_ids']['options'])) {
                                foreach ($item['attribute_ids']['options'] as $option) {
                                    if (isset($option['label']) && isset($option['value'])) {
                                        $attributes[] = $option['label'] . ': ' . $option['value'];
                                    }
                                }
                            }
                        }
                        @endphp
                        @if(!empty($attributes))
                            <small>{{ implode(' | ', $attributes) }}</small>
                        @endif
                    </td>
                    <td>
                        @if(!empty($item['votre_text']))
                            {{ $item['votre_text'] }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($item['price'], 2) }}</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="grand-total">
            <strong>Grand Total:</strong>
            {{ $currencySymbol }}{{ number_format($order['total_amount'], 2) }}
        </div>
    </div>

    <div class="footer">
        <p><strong>Order Details Generated:</strong> {{ date('M d, Y H:i:s') }}</p>
        @if($store)
            <p>{{ $store->name }} | {{ $store->address ?? '' }} | {{ $store->phone ?? '' }}</p>
        @endif
        <p>This is a computer-generated document and does not require a signature.</p>
    </div>
</body>
</html>
