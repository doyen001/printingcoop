@extends('layouts.admin')

@section('content')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

<div class="content-wrapper dd">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="x_panel light form-fit popup-window">
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
                            <button class="btn btn-primary" id="export-csv">
                                <i class="fa fa-download"></i> Export CSV
                            </button>
                        </div>
                    </div>
                    
                    <form id="search-order-form" method="post" action="{{ url('admin/Orders/list') }}">
                        @csrf
                        <div class="x_content form">
                            <div class="form-horizontal">
                                <div class="form-body">
                                    <div class="col-12 px-0">
                                        <div class="row align-items-end">
                                            <div class="col-md-4 col-ms-6 col-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="from">From #</label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input class="form-control" type="text"
                                                            value="{{ request('from_no') }}" id="from_no"
                                                            name="from_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-ms-6 col-6">
                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label" for="to">To #</label>
                                                    <div class="col-md-9 col-sm-9">
                                                        <input class="form-control" type="text"
                                                            value="{{ request('to_no') }}" id="to_no"
                                                            name="to_no">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-4">
                                                <div class="form-actions">
                                                    <div class="form-group">
                                                        <button class="btn btn-success filter-submit"
                                                            id="search-orders">
                                                            <i class="fa fa-search"></i> Search
                                                        </button>                                                        
                                                        <button class="btn btn-info" type="button"
                                                            data-toggle="collapse" data-target="#filterCollapse"
                                                            aria-expanded="false" aria-controls="filterCollapse">
                                                            <i class="fa fa-filter"></i>&nbsp; Filters
                                                        </button>
                                                        <br>
                                                        <label>Monthly Report (Pdf)</label>
                                                        <input type="checkbox" class="form-check-input"
                                                            id="monthly_report" name="monthly_report"
                                                            {{ request('monthly_report') ? 'checked' : '' }}
                                                             style="margin-left: 5px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse" id="filterCollapse">
                                    <div class="drop-filters-container w-100">
                                        
                                        <div class="form-group w-100">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6">
                                                    <label class="control-label" for="Year">Year</label>
                                                    <select class="form-control" id="year" name="year">
                                                        <option value="">Select Year</option>
                                                        @for ($i = date('Y'); $i >= 2000; $i--)
                                                        <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                        @endfor
                                                    </select>    
                                                </div>
                                                <div class="col-md-6 col-sm-6">
                                                    <label class="control-label" for="Month">Month</label>
                                                    <select class="form-control" id="month" name="month" onChange="setFromdateAndToDate()">
                                                        <option value="">Select Month</option>
                                                        <option value="1" {{ request('month') == 1 ? 'selected' : '' }}>JAN</option>
                                                        <option value="2" {{ request('month') == 2 ? 'selected' : '' }}>FEB</option>
                                                        <option value="3" {{ request('month') == 3 ? 'selected' : '' }}>MAR</option>
                                                        <option value="4" {{ request('month') == 4 ? 'selected' : '' }}>APR</option>
                                                        <option value="5" {{ request('month') == 5 ? 'selected' : '' }}>MAY</option>
                                                        <option value="6" {{ request('month') == 6 ? 'selected' : '' }}>JUN</option>
                                                        <option value="7" {{ request('month') == 7 ? 'selected' : '' }}>JUL</option>
                                                        <option value="8" {{ request('month') == 8 ? 'selected' : '' }}>AUG</option>
                                                        <option value="9" {{ request('month') == 9 ? 'selected' : '' }}>SEP</option>
                                                        <option value="10" {{ request('month') == 10 ? 'selected' : '' }}>OCT</option>
                                                        <option value="11" {{ request('month') == 11 ? 'selected' : '' }}>NOV</option>
                                                        <option value="12" {{ request('month') == 12 ? 'selected' : '' }}>DEC</option>
                                                    </select>
                                                </div>
                                            </div>   
                                        </div>
                                        <div class="form-group"></div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="from">From Date</label>
                                            <div class="col-md-9 col-sm-9">
                                                <input type="date" class="form-control" name="from" value="{{ request('from') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="to">To Date</label>
                                            <div class="col-md-9 col-sm-9">
                                                <input type="date" class="form-control" name="to" value="{{ request('to') }}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label" for="status">Order Status</label>
                                            <div class="col-md-9 col-sm-9">
                                                <select class="form-control multi-select" name="status[]" multiple="multiple">
                                                    <option value="1" {{ in_array('1', request('status', [])) ? 'selected' : '' }}>New</option>
                                                    <option value="2" {{ in_array('2', request('status', [])) ? 'selected' : '' }}>Processing</option>
                                                    <option value="3" {{ in_array('3', request('status', [])) ? 'selected' : '' }}>Shipped</option>
                                                    <option value="4" {{ in_array('4', request('status', [])) ? 'selected' : '' }}>Delivered</option>
                                                    <option value="5" {{ in_array('5', request('status', [])) ? 'selected' : '' }}>Cancelled</option>
                                                    <option value="6" {{ in_array('6', request('status', [])) ? 'selected' : '' }}>Ready for Pickup</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="x_content">
                                    <div id="orders-grid" class="tight"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Kendo UI CSS -->
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.common.min.css"/>
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.bootstrap.min.css"/>
<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.3.914/styles/kendo.bootstrap.mobile.min.css"/>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Kendo UI -->
<script src="https://kendo.cdn.telerik.com/2021.3.914/js/kendo.all.min.js"></script>
<!-- Bootstrap Multiselect -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.css">

<script>
function ucwords(str) {
    if (str)
        return str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
    else
        return '';
}

function ucfirst(str) {
    if (str)
        return str.charAt(0).toUpperCase() + str.slice(1);;
    return '';
}

$(document).ready(function() {
    // Initialize multi-select
    $('.multi-select').multiselect({
        maxHeight: 200,
        buttonWidth: '100%',
        includeSelectAllOption: true,
        selectAllText: 'Select All',
        nonSelectedText: 'Select Status',
        nSelectedText: 'selected',
        allSelectedText: 'All selected'
    });

    // Initialize Kendo Grid (CI project style)
    $('#orders-grid').kendoGrid({
        dataSource: {
            transport: {
                read: {
                    url: '{{ url("admin/Orders/list") }}',
                    type: 'POST',
                    dataType: 'json',
                    data: additionalData
                }
            },
            schema: {
                data: 'data',
                total: 'total',
                errors: 'errors',
            },
            error: function(e) {
                if (e.errors) {
                    alert(e.errors);
                } else {
                    alert("Error loading data");
                }
            },
            pageSize: 10,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true
        },
        pageable: {
            refresh: true,
            pageSizes: [10, 15, 20, 50, 100],
            change: function(e) {
                var stateurl = new URL(location.href);
                stateurl.searchParams.set('page', e.index);
                window.history.replaceState({
                    path: stateurl.href
                }, '', stateurl.href);
            }
        },
        scrollable: false,
        columns: [
            { field: 'order_id', title: '#', template: '#=order_id#' },
            { field: 'provider_order_id', title: 'Provider Order #' },
            { field: 'store_name', title: 'Store Name' },
            { field: 'name', title: 'Customer Name', template: '#=ucwords(name)#' },
            { title: 'Subtotal Amount', template: '$#=Number(sub_total_amount).toFixed(2)#' },
            { title: 'Preffered Customer Discount', template: '#=preffered_customer_discount == 0 ? "-" : ("$" + Number(preffered_customer_discount).toFixed(2))#' },
            { title: 'Coupon Discount', template: '#=coupon_discount_amount == 0 ? "-" : ("$" + Number(coupon_discount_amount).toFixed(2))#' },
            { title: 'Shipping Fee', template: '#=delivery_charge == 0 ? "-" : ("$" + Number(delivery_charge).toFixed(2))#' },
            { title: 'Total Sales Tax', template: '#=total_sales_tax == 0 ? "-" : ("$" + Number(total_sales_tax).toFixed(2))#' },
            { field: 'total_amount', title: 'Order Amount', template: '$#=Number(total_amount).toFixed(2)#' },
            { field: 'total_items', title: 'Total Items' },
            { field: 'payment_type', title: 'Payment Method' },
            { title: 'Payment Status', template: '#=paymentStatusChangeOptions(id, payment_status)#' },
            { field: 'transition_id', title: 'Transition Id' },
            { field: 'created', title: 'Created On' },
            { field: 'updated', title: 'Updated On' },
            { title: 'Order Status', template: '#=orderStatusChangeOptions(id, order_id, payment_status, status)#' },
            { title: 'View Orders', template: '<a class="view-btn" href="/admin/Orders/viewOrder/#=id#"><i class="fa far fa-eye fa-lg"></i></a>' },
            { title: 'Action', template: '#=itemActions(id, status, shipment_id, tracking_number, labels_regular, labels_thermal)#' }
        ]
    });

    $('#search-order-form').submit(function(e) {
        e.preventDefault();
        $('#search-orders').click();
        return false;
    });

    // Search button
    $('#search-orders').click(function() {
        var grid = $('#orders-grid').data('kendoGrid');
        grid.dataSource.page(1);

        var params = additionalData();
        var stateurl = new URL(location.href);
        stateurl.searchParams.set('page', 1);
        for (const item of Object.entries(params)) {
            if (item[0] != '_token') {
                if (item[1] != undefined && item[1] != '')
                    stateurl.searchParams.set(item[0], item[1]);
                else {
                    stateurl.searchParams.delete(item[0]);
                }
            }
        }
        stateurl.searchParams.delete('timestamp');
        window.history.replaceState({
            path: stateurl.href
        }, '', stateurl.href);
        return false;
    });

    // Export CSV functionality
    $('#export-csv').click(function() {
        var params = $('#search-order-form').serialize();
        window.open('{{ url("admin/Orders/exportCSV") }}?' + params, '_blank');
    });

    // Set from and to dates when month is selected
    window.setFromdateAndToDate = function() {
        var year = $('#year').val();
        var month = $('#month').val();
        
        if (year && month) {
            var firstDay = new Date(year, month - 1, 1);
            var lastDay = new Date(year, month, 0);
            
            $('input[name="from"]').val(firstDay.toISOString().split('T')[0]);
            $('input[name="to"]').val(lastDay.toISOString().split('T')[0]);
        } else {
            $('input[name="from"]').val('');
            $('input[name="to"]').val('');
        }
    };

    // Additional data function for Kendo Grid
    function additionalData() {
        var monthly_report = '';
        if($('#search-order-form #monthly_report').is(':checked')) {
            $('#search-order-form #monthly_report').val('true');
            monthly_report = 'true';
            
            // Generate monthly report PDF (CI project style)
            $.ajax({
                url: '{{ url("admin/Orders/generateMonthlyReport") }}',
                type: 'POST',
                data: {
                    from_no: $('#search-order-form #from_no').val(),
                    to_no: $('#search-order-form #to_no').val(),
                    from: $('#search-order-form #from').val(),
                    to: $('#search-order-form #to').val(),
                    status: $('#search-order-form .multi-select').val(),
                    monthly_report: monthly_report,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 1) {
                        // Open PDF in new window
                        window.open(response.pdf_url, '_blank');
                    } else {
                        alert('Error generating monthly report: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error generating monthly report. Please try again.');
                }
            });
            
        } else {
            $('#search-order-form #monthly_report').val('false');
            monthly_report = 'false';
        }

        // Get status from URL parameter for page-specific filtering
        var urlParams = new URLSearchParams(window.location.search);
        var pathParts = window.location.pathname.split('/');
        var urlStatus = null;
        
        // Check if URL has status parameter like /admin/Orders/index/New
        if (pathParts.length >= 5) {
            var statusFromUrl = pathParts[4]; // Get the status part
            if (statusFromUrl && statusFromUrl !== 'index') {
                // Map URL status to status ID (CI project style)
                var statusMap = {
                    'new': '2',
                    'processing': '3', 
                    'shipped': '4',
                    'delivered': '5',
                    'cancelled': '6',
                    'failed': '7',
                    'complete': '8',
                    'ready-for-pickup': '9'
                };
                urlStatus = statusMap[statusFromUrl.toLowerCase()];
            }
        }

        return {
            from_no: $('#search-order-form #from_no').val(),
            to_no: $('#search-order-form #to_no').val(),
            from: $('#search-order-form #from').val(),
            to: $('#search-order-form #to').val(),
            status: urlStatus || $('#search-order-form .multi-select').val(),
            monthly_report: monthly_report,
            _token: $('meta[name="csrf-token"]').attr('content')
        };
    }
});

function editOrder(id) {
    window.location.href = '/admin/Orders/edit/' + id;
}

function itemActions(id, status, shipment_id, tracking_number, labels_regular, labels_thermal) {
    var result = '';
    if (status == 4 || status == 5 || status == 6) { // Delivered, Cancelled, Failed
        result += `<a class="view-btn" href="/admin/Orders/deleteOrder/${id}/all" style="color:#d71b23" title="delete" onclick="return confirm('Are you sure you want to delete this order?');">
        <i class="fa fa-trash fa-lg"></i>
    </a>`;
    }
    if ((status == 3 || status == 4) && // Shipped, Delivered
        shipment_id != null && tracking_number != null && labels_regular != null && labels_thermal != null) {
        result += `<a href="${labels_regular}" target="_blank" title="Shipping Label (Regular)" style="margin-right:4px;">
            <i class="fa fa-image fa-lg"></i>
        </a>
        <a href="${labels_thermal}" target="_blank" title="Shipping Label (Thermal)" style="margin-right:4px;">
            <i class="fa fa-image fa-lg"></i>
        </a>
        <a href="javascript:void(0)" title="Tracking Order" style="margin-right:4px;" onclick="OrderTracking('${id}')">
            <i class="fa fa-shipping-fast fa-lg"></i>
        </a>`;
    }
    return result;
}

function OrderTracking(id) {
    // Function to track order
    console.log('Track order:', id);
}

function paymentStatusChangeOptions(id, payment_status) {
    // Payment status constants (CI project style)
    const Pending = 1;
    const Success = 2;
    const Failed = 3;
    
    if (payment_status != Success) {
        var result = `<select class="form-control" onChange="changeOrderPaymentStatus(${id}, $(this).val())" style="width: 150px">
        <option value="">Change Payment Status</option>`;
        
        if (payment_status != Failed) {
            result += '<option value="' + Pending + '" ' + (payment_status == Pending ? 'selected="selected"' : '') + '>Pending</option>';
        }
        result += '<option value="' + Success + '" ' + (payment_status == Success ? 'selected="selected"' : '') + '>Success</option>';
        result += '</select>';
        return result;
    } else {
        // Return payment status text for successful payments
        const paymentStatusText = {
            1: 'Pending',
            2: 'Success',
            3: 'Failed'
        };
        return paymentStatusText[payment_status] || 'Unknown';
    }
}

function changeOrderPaymentStatus(orderId, newStatus) {
    if (!newStatus) return;
    
    $.ajax({
        url: '/admin/Orders/changeOrderPaymentStatus',
        type: 'POST',
        data: {
            order_id: orderId,
            payment_status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status == 1) {
                // Refresh the grid to show updated status
                var grid = $('#orders-grid').data('kendoGrid');
                grid.dataSource.read();
            } else {
                alert(response.msg || 'Error updating payment status');
            }
        },
        error: function() {
            alert('Error updating payment status');
        }
    });
}

function orderStatusChangeOptions(id, order_id, payment_status, status) {
    // Order status constants (CI project style)
    const Incomplete = 1;
    const New = 2;
    const Processing = 3;
    const Shipped = 4;
    const Delivered = 5;
    const Cancelled = 6;
    const Failed = 7;
    const Complete = 8;
    const ReadyForPickup = 9;
    
    // Available status names (CI project style)
    var availables = {
        2: 'New Order',
        3: 'Processing', 
        4: 'Shipped',
        5: 'Delivered',
        6: 'Cancelled',
        9: 'Ready for Pickup'
    };
    
    // Remove unavailable statuses based on CI project logic
    delete availables[Incomplete];
    delete availables[Failed];
    delete availables[Complete];

    // Conditional removal based on current status
    if (status == Processing)
        delete availables[New];
    if (status == Shipped) {
        delete availables[New];
        delete availables[Processing];
        delete availables[ReadyForPickup];
    }
    if (status == ReadyForPickup) {
        delete availables[New];
        delete availables[Processing];
        delete availables[Shipped];
    }
    if (status == Delivered) {
        delete availables[New];
        delete availables[Processing];
        delete availables[Shipped];
        delete availables[Delivered];
        delete availables[Cancelled];
        delete availables[ReadyForPickup];
    }
    if (status == Cancelled) {
        delete availables[New];
        delete availables[Processing];
        delete availables[Shipped];
        delete availables[Delivered];
        delete availables[Cancelled];
        delete availables[ReadyForPickup];
    }
    
    var result = '';
    if ([New, Processing, Shipped, ReadyForPickup].indexOf(Number(status)) >= 0) {
        result += `<select class="form-control" onChange="changeOrderStatus(${id}, $(this).val(), 'all', '${order_id}','${payment_status}')" id="select-${id}" style="width: 150px">`;
        for (const [key, value] of Object.entries(availables)) {
            var selected = '';
            if (status == key)
                selected = 'selected="selected"';
            result += `<option value="${key}" ${selected}>${value}</option>`;
        }
        result += '</select>';
    } else {
        // Return status text for locked orders
        var orderStatusText = {
            1: 'Incomplete',
            2: 'New Order',
            3: 'Processing',
            4: 'Shipped',
            5: 'Delivered',
            6: 'Cancelled',
            7: 'Failed',
            8: 'Complete',
            9: 'Ready for Pickup'
        };
        result = orderStatusText[status] || 'Unknown';
    }
    return result;
}

function changeOrderStatus(orderId, newStatus, pageStatus, orderId, paymentStatus) {
    if (!newStatus) return;
    
    $.ajax({
        url: '/admin/Orders/changeOrderStatus',
        type: 'POST',
        data: {
            order_id: orderId,
            status: newStatus,
            page_status: pageStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.status == 1) {
                // Refresh the grid to show updated status
                var grid = $('#orders-grid').data('kendoGrid');
                grid.dataSource.read();
            } else {
                alert(response.msg || 'Error updating order status');
            }
        },
        error: function() {
            alert('Error updating order status');
        }
    });
}
</script>
@endsection
