<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin SalesReportsController - Complete sales reporting system
 */
class SalesReportsController extends Controller
{
    /**
     * Sales dashboard with overview
     */
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Total sales
        $totalSales = DB::table('product_orders')
            ->whereBetween('created', [$dateFrom, $dateTo])
            ->where('order_status', '!=', 'cancelled')
            ->sum('total_amount');
        
        // Total orders
        $totalOrders = DB::table('product_orders')
            ->whereBetween('created', [$dateFrom, $dateTo])
            ->where('order_status', '!=', 'cancelled')
            ->count();
        
        // Average order value
        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        
        // Sales by status
        $salesByStatus = DB::table('product_orders')
            ->select('order_status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->whereBetween('created', [$dateFrom, $dateTo])
            ->groupBy('order_status')
            ->get();
        
        // Daily sales trend
        $dailySales = DB::table('product_orders')
            ->select(DB::raw('DATE(created) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created', [$dateFrom, $dateTo])
            ->where('order_status', '!=', 'cancelled')
            ->groupBy(DB::raw('DATE(created)'))
            ->orderBy('date', 'asc')
            ->get();
        
        // Top products
        $topProducts = DB::table('product_order_items')
            ->join('product_orders', 'product_order_items.order_id', '=', 'product_orders.id')
            ->join('products', 'product_order_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(product_order_items.quantity) as total_quantity'),
                DB::raw('SUM(product_order_items.quantity * product_order_items.price) as total_revenue')
            )
            ->whereBetween('product_orders.created', [$dateFrom, $dateTo])
            ->where('product_orders.order_status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
        
        $data = [
            'page_title' => 'Sales Reports Dashboard',
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'avg_order_value' => $avgOrderValue,
            'sales_by_status' => $salesByStatus,
            'daily_sales' => $dailySales,
            'top_products' => $topProducts,
        ];
        
        return view('admin.sales_reports.index', $data);
    }
    
    /**
     * Sales by product report
     */
    public function salesByProduct(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $products = DB::table('product_order_items')
            ->join('product_orders', 'product_order_items.order_id', '=', 'product_orders.id')
            ->join('products', 'product_order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.product_code',
                DB::raw('COUNT(DISTINCT product_order_items.order_id) as order_count'),
                DB::raw('SUM(product_order_items.quantity) as total_quantity'),
                DB::raw('SUM(product_order_items.quantity * product_order_items.price) as total_revenue'),
                DB::raw('AVG(product_order_items.price) as avg_price')
            )
            ->whereBetween('product_orders.created', [$dateFrom, $dateTo])
            ->where('product_orders.order_status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.product_code')
            ->orderBy('total_revenue', 'desc')
            ->paginate(50);
        
        $data = [
            'page_title' => 'Sales by Product',
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'products' => $products,
        ];
        
        return view('admin.sales_reports.by_product', $data);
    }
    
    /**
     * Sales by category report
     */
    public function salesByCategory(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $categories = DB::table('product_order_items')
            ->join('product_orders', 'product_order_items.order_id', '=', 'product_orders.id')
            ->join('products', 'product_order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('COUNT(DISTINCT product_order_items.order_id) as order_count'),
                DB::raw('SUM(product_order_items.quantity) as total_quantity'),
                DB::raw('SUM(product_order_items.quantity * product_order_items.price) as total_revenue')
            )
            ->whereBetween('product_orders.created', [$dateFrom, $dateTo])
            ->where('product_orders.order_status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
        
        $data = [
            'page_title' => 'Sales by Category',
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'categories' => $categories,
        ];
        
        return view('admin.sales_reports.by_category', $data);
    }
    
    /**
     * Revenue report
     */
    public function revenueReport(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $groupBy = $request->input('group_by', 'day'); // day, week, month
        
        $query = DB::table('product_orders')
            ->whereBetween('created', [$dateFrom, $dateTo])
            ->where('order_status', '!=', 'cancelled');
        
        switch ($groupBy) {
            case 'week':
                $revenue = $query->select(
                    DB::raw('YEARWEEK(created) as period'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('AVG(total_amount) as avg_order_value')
                )
                ->groupBy(DB::raw('YEARWEEK(created)'))
                ->orderBy('period', 'asc')
                ->get();
                break;
            
            case 'month':
                $revenue = $query->select(
                    DB::raw('DATE_FORMAT(created, "%Y-%m") as period'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('AVG(total_amount) as avg_order_value')
                )
                ->groupBy(DB::raw('DATE_FORMAT(created, "%Y-%m")'))
                ->orderBy('period', 'asc')
                ->get();
                break;
            
            default: // day
                $revenue = $query->select(
                    DB::raw('DATE(created) as period'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue'),
                    DB::raw('AVG(total_amount) as avg_order_value')
                )
                ->groupBy(DB::raw('DATE(created)'))
                ->orderBy('period', 'asc')
                ->get();
                break;
        }
        
        $data = [
            'page_title' => 'Revenue Report',
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'group_by' => $groupBy,
            'revenue' => $revenue,
        ];
        
        return view('admin.sales_reports.revenue', $data);
    }
    
    /**
     * Export sales report to CSV
     */
    public function exportCSV(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $reportType = $request->input('report_type', 'orders');
        
        $filename = 'sales_report_' . $reportType . '_' . date('Y-m-d_His') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $handle = fopen('php://output', 'w');
        
        switch ($reportType) {
            case 'products':
                fputcsv($handle, ['Product ID', 'Product Name', 'Product Code', 'Orders', 'Quantity Sold', 'Total Revenue', 'Avg Price']);
                
                $products = DB::table('product_order_items')
                    ->join('product_orders', 'product_order_items.order_id', '=', 'product_orders.id')
                    ->join('products', 'product_order_items.product_id', '=', 'products.id')
                    ->select(
                        'products.id',
                        'products.name',
                        'products.product_code',
                        DB::raw('COUNT(DISTINCT product_order_items.order_id) as order_count'),
                        DB::raw('SUM(product_order_items.quantity) as total_quantity'),
                        DB::raw('SUM(product_order_items.quantity * product_order_items.price) as total_revenue'),
                        DB::raw('AVG(product_order_items.price) as avg_price')
                    )
                    ->whereBetween('product_orders.created', [$dateFrom, $dateTo])
                    ->where('product_orders.order_status', '!=', 'cancelled')
                    ->groupBy('products.id', 'products.name', 'products.product_code')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
                
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->id,
                        $product->name,
                        $product->product_code,
                        $product->order_count,
                        $product->total_quantity,
                        number_format($product->total_revenue, 2),
                        number_format($product->avg_price, 2),
                    ]);
                }
                break;
            
            case 'categories':
                fputcsv($handle, ['Category ID', 'Category Name', 'Orders', 'Quantity Sold', 'Total Revenue']);
                
                $categories = DB::table('product_order_items')
                    ->join('product_orders', 'product_order_items.order_id', '=', 'product_orders.id')
                    ->join('products', 'product_order_items.product_id', '=', 'products.id')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->select(
                        'categories.id',
                        'categories.name',
                        DB::raw('COUNT(DISTINCT product_order_items.order_id) as order_count'),
                        DB::raw('SUM(product_order_items.quantity) as total_quantity'),
                        DB::raw('SUM(product_order_items.quantity * product_order_items.price) as total_revenue')
                    )
                    ->whereBetween('product_orders.created', [$dateFrom, $dateTo])
                    ->where('product_orders.order_status', '!=', 'cancelled')
                    ->groupBy('categories.id', 'categories.name')
                    ->orderBy('total_revenue', 'desc')
                    ->get();
                
                foreach ($categories as $category) {
                    fputcsv($handle, [
                        $category->id,
                        $category->name,
                        $category->order_count,
                        $category->total_quantity,
                        number_format($category->total_revenue, 2),
                    ]);
                }
                break;
            
            default: // orders
                fputcsv($handle, ['Order ID', 'Order Date', 'Customer', 'Email', 'Status', 'Payment Status', 'Total Amount']);
                
                $orders = DB::table('product_orders')
                    ->whereBetween('created', [$dateFrom, $dateTo])
                    ->orderBy('created', 'desc')
                    ->get();
                
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_id,
                        $order->created,
                        $order->name,
                        $order->email,
                        $order->order_status,
                        $order->payment_status ?? 'N/A',
                        number_format($order->total_amount, 2),
                    ]);
                }
                break;
        }
        
        fclose($handle);
        exit;
    }
    
    /**
     * Export sales report to PDF
     */
    public function exportPDF(Request $request)
    {
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $reportType = $request->input('report_type', 'summary');
        
        // TODO: Implement PDF generation using a library like DomPDF or TCPDF
        // For now, return a placeholder message
        
        return redirect()->back()->with('message_info', 'PDF export feature will be implemented with a PDF library');
    }
}
