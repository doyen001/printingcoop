@extends('layouts.admin')

@section('content')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel light form-fit popup-window">
                    {{-- Flash Messages --}}
                    @if(session('message_error'))
                    <div class="alert alert-danger">
                        {{ session('message_error') }}
                    </div>
                    @endif
                    
                    @if(session('message_success'))
                    <div class="alert alert-success">
                        {{ session('message_success') }}
                    </div>
                    @endif
                    
                    <div class="x_title">
                        <div class="caption">
                            <i class="fa fa-shopping-cart"></i>
                            {{ ucfirst($page_title) }}
                        </div>
                        <div class="actions btn-group btn-group-devided util-btn-margin-bottom-5">
                            <button class="btn btn-primary" id="export-csv" onclick="exportOrders()">
                                <i class="fa fa-download"></i> Export CSV
                            </button>
                        </div>
                    </div>
                    
                    {{-- Search and Filter Form --}}
                    <form id="search-order-form" method="GET" action="{{ url('admin/Orders') }}">
                        <div class="x_content form">
                            <div class="form-horizontal">
                                <div class="form-body">
                                    <div class="col-12 px-0">
                                        <div class="row align-items-end">
                                            <div class="col-md-4 col-ms-6 col-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">From #</label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input class="form-control" type="text" 
                                                               value="{{ request('from_no') }}" 
                                                               id="from_no" name="from_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-ms-6 col-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label">To #</label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input class="form-control" type="text" 
                                                               value="{{ request('to_no') }}" 
                                                               id="to_no" name="to_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-4">
                                                <div class="form-actions">
                                                    <div class="form-group">
                                                        <button class="btn btn-success filter-submit" type="submit">
                                                            <i class="fa fa-search"></i> Search
                                                        </button>
                                                        <button class="btn btn-info" type="button" 
                                                                data-toggle="collapse" data-target="#filterCollapse">
                                                            <i class="fa fa-filter"></i> Filters
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Advanced Filters --}}
                                <div class="collapse" id="filterCollapse">
                                    <div class="drop-filters-container w-100">
                                        <div class="form-group w-100">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <label class="control-label">Status</label>
                                                    <select class="form-control" name="status">
                                                        <option value="">All Statuses</option>
                                                        <option value="pending" {{ $statusStr == 'new' ? 'selected' : '' }}>New/Pending</option>
                                                        <option value="processing" {{ $statusStr == 'processing' ? 'selected' : '' }}>Processing</option>
                                                        <option value="shipped" {{ $statusStr == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                        <option value="delivered" {{ $statusStr == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                        <option value="cancelled" {{ $statusStr == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                        <option value="ready_for_pickup" {{ $statusStr == 'ready-for-pickup' ? 'selected' : '' }}>Ready for Pickup</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <label class="control-label">Date From</label>
                                                    <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6 col-sm-6">
                                                    <label class="control-label">Date To</label>
                                                    <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <label class="control-label">Customer</label>
                                                    <input type="text" class="form-control" name="customer" 
                                                           value="{{ request('customer') }}" 
                                                           placeholder="Customer name or email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    {{-- Orders Table --}}
                    <div class="x_content">
                        <div class="table-responsive">
                            <table id="ordersTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Total Amount</th>
                                        <th>Order Status</th>
                                        <th>Payment Status</th>
                                        <th>Payment Method</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($orders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_id }}</strong></td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->mobile }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ getOrderStatusBadge($order->status) }}">
                                                {{ getOrderStatusText($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ getPaymentStatusBadge($order->payment_status) }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->payment_method ?? 'N/A' }}</td>
                                        <td>{{ date('Y-m-d H:i', strtotime($order->created)) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ url('admin/Orders/viewOrder/' . $order->id) }}" 
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <button onclick="changeOrderStatus({{ $order->id }})" 
                                                        class="btn btn-sm btn-warning" title="Change Status">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="{{ url('admin/Orders/downloadInvoice/' . $order->id) }}" 
                                                   class="btn btn-sm btn-success" title="Download Invoice">
                                                    <i class="fa fa-file-pdf"></i>
                                                </a>
                                                <button onclick="deleteOrder({{ $order->id }})" 
                                                        class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No orders found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Change Order Status Modal --}}
<div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Order Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="changeStatusForm">
                    <input type="hidden" id="order_id" name="order_id">
                    
                    <div class="form-group">
                        <label>Order Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="ready_for_pickup">Ready for Pickup</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Email Message (Optional)</label>
                        <textarea class="form-control" id="emailMsg" name="emailMsg" rows="4" 
                                  placeholder="Custom message to include in status change email"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitStatusChange()">Update Status</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#ordersTable').DataTable({
        "order": [[8, "desc"]], // Sort by created date
        "pageLength": 20,
        "searching": true,
        "paging": true,
        "info": true
    });
});

// Export orders to CSV
function exportOrders() {
    var params = $('#search-order-form').serialize();
    window.location.href = '{{ url("admin/Orders/exportOrders") }}?' + params;
}

// Change order status
function changeOrderStatus(orderId) {
    $('#order_id').val(orderId);
    $('#changeStatusModal').modal('show');
}

// Submit status change
function submitStatusChange() {
    var formData = {
        order_id: $('#order_id').val(),
        status: $('#status').val(),
        emailMsg: $('#emailMsg').val(),
        _token: '{{ csrf_token() }}'
    };
    
    $.ajax({
        url: '{{ url("admin/Orders/changeOrderStatus") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.status == 1) {
                alert(response.msg);
                $('#changeStatusModal').modal('hide');
                location.reload();
            } else {
                alert(response.msg);
            }
        },
        error: function() {
            alert('An error occurred while updating the order status');
        }
    });
}

// Delete order
function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order?')) {
        window.location.href = '{{ url("admin/Orders/deleteOrder") }}/' + orderId + '/{{ $statusStr }}';
    }
}

// Helper function for status badge colors
function getOrderStatusBadge(status) {
    const badges = {
        'pending': 'warning',
        'processing': 'info',
        'shipped': 'primary',
        'delivered': 'success',
        'cancelled': 'danger',
        'ready_for_pickup': 'secondary'
    };
    return badges[status] || 'secondary';
}

function getPaymentStatusBadge(status) {
    const badges = {
        'pending': 'warning',
        'completed': 'success',
        'failed': 'danger',
        'cancelled': 'secondary'
    };
    return badges[status] || 'secondary';
}
</script>

@php
function getOrderStatusBadge($status) {
    $badges = [
        1 => 'secondary',  // incomplete
        2 => 'warning',   // new
        3 => 'info',      // processing
        4 => 'success',   // delivered
        5 => 'danger',    // cancelled
        6 => 'danger',    // failed
    ];
    return $badges[$status] ?? 'secondary';
}

function getOrderStatusText($status) {
    $statuses = [
        1 => 'Incomplete',
        2 => 'New',
        3 => 'Processing',
        4 => 'Delivered',
        5 => 'Cancelled',
        6 => 'Failed',
    ];
    return $statuses[$status] ?? 'Unknown';
}

function getPaymentStatusBadge($status) {
    $badges = [
        1 => 'warning',   // pending
        2 => 'success',   // success
        3 => 'danger',    // failed
    ];
    return $badges[$status] ?? 'secondary';
}
@endphp
@endsection
