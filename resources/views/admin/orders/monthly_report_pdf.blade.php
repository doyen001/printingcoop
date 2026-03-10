<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Monthly Orders Report</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            margin: 20px;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 30px;
            color: #333;
        }
        .report-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .report-info p {
            margin: 5px 0;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary { 
            margin-top: 30px; 
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary p {
            margin: 8px 0;
            font-weight: bold;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .no-orders {
            text-align: center;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Monthly Orders Report</h1>
    
    <div class="report-info">
        <p><strong>Period:</strong> {{ date("F d, Y", strtotime($from)) }} - {{ date("F d, Y", strtotime($to)) }}</p>
        <p><strong>Generated on:</strong> {{ date('Y-m-d H:i:s') }}</p>
        @if(isset($status) && $status)
            <p><strong>Filter Status:</strong> {{ ucfirst($status) }}</p>
        @endif
    </div>

    @php
        // Initialize totals
        $total_revenue = 0;
        $sub_total_amount = 0;
        $total_orders = 0;
        $total_tax = 0; 
        $total_gst_tax = 0;
        $total_qst_tax = 0;
    @endphp

    <table>
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Status</th>   
                <th class="text-right">Price (before Tax)</th>             
                <th class="text-right">Tax GST 5%</th>
                <th class="text-right">Tax QST 9.975%</th>
                <th class="text-right">Tax (GST + QST)</th>                
                <th class="text-right">Total Amount (After Tax)</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($orders) && count($orders) > 0)
                @foreach($orders as $i => $order)
                    @php
                        $gst_tax = $order->sub_total_amount * 0.05; // 5% GST
                        $qst_tax = $order->sub_total_amount * 0.09975; // 9.975% QST
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $order->order_id }}</td>
                        <td>{{ $order->name }}</td>
                        <td>{{ date('M d, Y', strtotime($order->created ?? $order->order_date)) }}</td>
                        <td>{{ ucfirst($order->status ?? $order->order_status) }}</td>      
                        <td class="text-right">{{ number_format($order->sub_total_amount, 2) }}</td>                   
                        <td class="text-right">{{ number_format($gst_tax, 2) }}</td>
                        <td class="text-right">{{ number_format($qst_tax, 2) }}</td>
                        <td class="text-right">{{ number_format($order->total_sales_tax ?? ($gst_tax + $qst_tax), 2) }}</td>                        
                        <td class="text-right">{{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    @php
                        $total_revenue += $order->total_amount;
                        $total_orders++;
                        $total_tax = $total_tax + ($order->total_sales_tax ?? ($gst_tax + $qst_tax));
                        $total_gst_tax = $total_gst_tax + $gst_tax;
                        $total_qst_tax = $total_qst_tax + $qst_tax;
                        $sub_total_amount = $sub_total_amount + $order->sub_total_amount;
                    @endphp
                @endforeach
                <tr class="total-row">
                    <td colspan="5" class="text-right"><strong>Total:</strong></td>
                    <td class="text-right">{{ number_format($sub_total_amount, 2) }}</td>
                    <td class="text-right">{{ number_format($total_gst_tax, 2) }}</td>
                    <td class="text-right">{{ number_format($total_qst_tax, 2) }}</td>
                    <td class="text-right">{{ number_format($total_tax, 2) }}</td>
                    <td class="text-right">{{ number_format($total_revenue, 2) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="10" class="no-orders">No orders found for this period.</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if(!empty($orders) && count($orders) > 0)
        <div class="summary">
            <h3>Report Summary</h3>
            <p><strong>Total GST Tax (5%):</strong> ${{ number_format($total_gst_tax, 2) }}</p>
            <p><strong>Total QST Tax (9.975%):</strong> ${{ number_format($total_qst_tax, 2) }}</p>
            <p><strong>Total Sales Tax (GST + QST):</strong> ${{ number_format($total_tax, 2) }}</p>       
            <p><strong>Total Orders:</strong> {{ $total_orders }}</p>
            <p><strong>Total Amount (Before Tax):</strong> ${{ number_format($sub_total_amount, 2) }}</p>
            <p><strong>Total Revenue (After Tax):</strong> ${{ number_format($total_revenue, 2) }}</p>
        </div>
    @endif

</body>
</html>
