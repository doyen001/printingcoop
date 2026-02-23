@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="text-center" style="color:red">
                            {{ session('message_error') }}
                        </div>
                        <div class="text-center" style="color:green">
                            {{ session('message_success') }}
                        </div>

                        <div class="inner-head-section">
                            <div class="row align-items-end">
                                <div class="col-md-12 col-lg-12 col-xl-12 col-xs-12 text-left">
                                    <div class="inner-title" style="margin-bottom: 20px;">
                                        <span>{{ ucfirst($page_title) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-7 col-xl-4 col-xs-12 text-left">
                                    <!----Serach Product Html-->
                                    <div class="search-box-area">
                                        <div class="search-sugg">
                                            <label class="span2">Search</label>
                                            <input class="form-control" type="text" placeholder="Search Product" id="searchSgedProductTextBox" name="searchSgedProductTextBox">
                                            <!--<button type="button"><i class="fa fas fa-search"></i></button>-->
                                        </div>
                                        <div class="search-result" id="searchDiv" style="display:none">
                                            <!-- Add "active" class to show -->
                                            <a href="javascript:void(0)" onclick="hidesearchDiv()">
                                                <i class="fa fas fa-times"></i>
                                            </a>
                                            <ul id="ProductListUl"></ul>
                                        </div>
                                    </div>
                                    <!----End Serach Product Html-->
                                </div>
                                <div class="col-md-12 col-lg-5 col-xl-4 col-xs-12 text-left">
                                    <div class="select-options">
                                        <form action="{{ url('admin/Products/index') }}" method="post" style="margin-bottom: 0px;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product_id }}">
                                            <div>
                                                <label class="span2">Order By</label>
                                                <select class="form-control" onchange="this.form.submit()" name="order">
                                                    <option value="desc" {{ $order == 'desc' ? 'selected="selected"' : '' }}>Latest Product</option>
                                                    <option value="asc" {{ $order == 'asc' ? 'selected="selected"' : '' }}>Oldest Product</option>
                                                </select>
                                            </div>
                                            <div style="margin-left: 10px;">
                                                <a href="{{ url('admin/Products') }}">
                                                    <button type="button">Reset</button>
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-xl-4 col-xs-12 text-right">
                                    <div class="all-vol-btn">
                                        <form action="{{ url('admin/Products/deleteAllProduct') }}" method="post">
                                            @csrf
                                            <a href="{{ url('admin/Products/addEdit') }}"
                                                style="margin-right: 5px;">
                                                <button type="button">
                                                    <i class="fa fas fa-plus-circle"></i> Add
                                                </button>
                                            </a>
                                            <a href="{{ url('admin/Products/updatePrintAuto') }}">
                                                <button type="button">
                                                <i class="fa fas fa fa-cog fa-lg"></i>    
                                                Auto
                                                </button>
                                            </a>
                                            <button><i class="fa fas fa-trash fa-lg"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="custom-mini-table">
                                <table id="example3" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example3">
                                    <thead>
                                        <tr role="row">
                                            <th><input type="checkbox" id="select-all"></th>
                                            <th>Product Name</th>
                                            <th>Image</th>
                                            <th>List Price CAD</th>
                                            <th>Sub Category</th>
                                            <th>Category</th>
                                            <th>Stock</th>
                                            <th>Code</th>
                                            <th>Model</th>
                                            <th>Updated On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($lists && $lists->count() > 0)
                                            @foreach($lists as $list)
                                                <tr>
                                                    <td><input type="checkbox" name="product_ids[]" class="product_ids"
                                                            value="{{ $list->id }}">
                                                    </td>
                                                    <td>
                                                        {{ ucfirst($list->name) }}
                                                    </td>
                                                    <td>
                                                        @if($list->product_image)
                                                            <img src="{{ asset('uploads/products/' . $list->product_image) }}" width="auto" height="80">
                                                        @else
                                                            <img src="{{ asset('images/no-image.png') }}" width="auto" height="80">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ number_format($list->price, 2) }}
                                                    </td>
                                                    <td>
                                                        {{ ucfirst($list->sub_category_name ?? '') }}
                                                    </td>
                                                    <td>
                                                        {{ ucfirst($list->category_name ?? '') }}
                                                    </td>
                                                    <td>
                                                        @if(empty($list->is_stock))
                                                            In Stock
                                                        @else
                                                            Out of Stock
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $list->code }}
                                                    </td>
                                                    <td>
                                                        {{ $list->model }}
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($list->updated)->format('M d, Y h:i A') }}
                                                    </td>

                                                    <td>
                                                        @if($list->status == 1)
                                                        <a href="{{ url('admin/Products/activeInactive/' . $list->id . '/0') }}">
                                                            <button type="button" class="custon-active">Active</button>
                                                        </a>
                                                        @else
                                                        <a href="{{ url('admin/Products/activeInactive/' . $list->id . '/1') }}">
                                                            <button type="button" class="custon-delete">Inactive</button>
                                                        </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="action-btns">
                                                            <a href="{{ url('admin/Products/viewProduct/' . $list->id) }}" style="color:#3c8dbc;padding: 5px;" title="view">
                                                                <i class="fa fa-eye fa-lg"></i>
                                                            </a>
                                                            <a href="{{ url('admin/Products/addEdit/' . $list->id) }}" style="color:green;padding: 5px;" title="edit">
                                                                <i class="fa fa-edit fa-lg"></i>
                                                            </a>
                                                            <a href="{{ url('admin/Products/ProductAttributes/' . $list->id) }}" style="color:green;padding: 5px;" title="attributes">
                                                                <i class="fa fa-cog fa-lg"></i>
                                                            </a>
                                                            <a href="{{ url('admin/Products/ProductCopy/' . $list->id) }}" style="color:green;padding: 5px;" title="copy">
                                                                <i class="fa fa-copy fa-lg"></i>
                                                            </a>
                                                            <a href="{{ url('admin/Products/deleteProduct/' . $list->id) }}" style="color:#d71b23;padding: 5px;" title="delete" onclick="return confirm('Are you sure you want to delete this product?');">
                                                                <i class="fa fa-trash fa-lg"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="10" class="text-center">List Empty.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <p>{{ $lists->links() }}</p>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
</div>
@endsection

@push('styles')
<style>
/* CI Project Styles */
.inner-head-section {
    margin-bottom: 30px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 15px;
}

.inner-title span {
    font-size: 18px;
    text-transform: uppercase;
    letter-spacing: 1px;
    word-spacing: 2px;
    font-weight: 600;
}

.search-box-area {
    position: relative;
    margin-top: 20px;
}

.search-sugg label.span2, .select-options div label.span2 {
    font-size: 13px;
    margin: 0px;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 400;
}

/* CI Project Input Styles */
.form-control {
    height: 35px !important;
    padding: 5px 5px !important;
    border-radius: 2px !important;
    border: 1px solid #ccc;
    font-size: 12px !important;
    color: #000;
    background-color: #fff !important;
}

.form-control:focus {
    border-color: #ff9200;
    box-shadow: 0 0 0 0.2rem rgba(255, 146, 0, 0.25);
    outline: 0;
}

.select-options .form-control {
    height: 35px !important;
    padding: 5px 5px !important;
    border-radius: 2px !important;
    font-size: 12px !important;
}

.select-options form {
    display: flex;
    align-items: flex-end;
}

.select-options form div:first-child {
    width: 100%;
}

.select-options form div:last-child a button {
    border: none;
    background: #ff9200;
    color: #fff;
    cursor: pointer;
    border-radius: 3px;
    padding: 8px 15px;
    font-size: 12px;
    height: 35px;
}

.all-vol-btn {
    display: flex;
    justify-content: flex-start;
    margin-top: 10px;
}

.all-vol-btn a {
    color: #fff !important;
}

.all-vol-btn button {
    border: none;
    background: #ff9200;
    color: #fff;
    cursor: pointer;
    border-radius: 3px;
    padding: 10px 10px;
    font-size: 12px;
    height: 35px;
    margin-right: 5px;
}

.all-vol-btn button i {
    margin-right: 5px;
}

.custon-active {
    border: none;
    background: #00c292;
    color: #fff;
    cursor: pointer;
    border-radius: 3px;
    font-weight: 600;
    padding: 5px 10px;
    font-size: 12px;
}

.custon-delete {
    border: none;
    background: #ff0000;
    color: #fff;
    cursor: pointer;
    border-radius: 3px;
    font-weight: 600;
    padding: 5px 10px;
    font-size: 12px;
}

.dataTables_wrapper .table th {
    background-color: #152746;
    color: #fff;
    border-radius: 2px !important;
    font-size: 12px;
}

.box-body {
    padding: 10px !important;
}

.search-result {
    position: absolute;
    top: 60px;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 1000;
}

.search-result a {
    position: absolute;
    top: 5px;
    right: 5px;
    color: #999;
}

.search-result ul {
    list-style: none;
    margin: 0;
    padding: 10px;
    max-height: 200px;
    overflow-y: auto;
}

.search-result ul li {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}

.search-result ul li:last-child {
    border-bottom: none;
}

.search-result ul li a {
    color: #333;
    text-decoration: none;
}

.search-result ul li a:hover {
    color: #ff9200;
}

.action-btns {
    display: flex;
    gap: 5px;
}

.action-btns a {
    text-decoration: none;
    padding: 5px;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.action-btns a:hover {
    opacity: 0.8;
}

.custom-mini-table {
    overflow-x: auto;
}

.table img {
    max-width: 80px;
    height: auto;
    border-radius: 3px;
}

/* CI Project Checkbox Styles */
input[type="checkbox"] {
    width: 15px;
    height: 15px;
    margin: 0;
    cursor: pointer;
}

#select-all {
    width: 15px;
    height: 15px;
    margin: 0;
    cursor: pointer;
}

.product_ids {
    width: 15px;
    height: 15px;
    margin: 0;
    cursor: pointer;
}

/* CI Project Button Reset Styles */
button {
    font-family: 'Raleway', sans-serif !important;
}

/* CI Project Table Cell Styling */
.table td {
    vertical-align: middle;
    padding: 8px;
    font-size: 12px;
}

.table th {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}
</style>
@endpush
@push('scripts')
<script>
// Make functions globally available
function searchProduct(searchtext) {
    if (searchtext != '') {
        console.log('Searching for:', searchtext);
        $('#loader-img').show();
        var url = '{{ url("admin/Products/searchProduct") }}';
        $('#searchDiv').show();
        $('#ProductListUl').html('');
        $.ajax({
            type: "GET",
            url: url,
            data: {
                'searchtext': searchtext
            },
            success: function(data) {
                console.log('Search response:', data);
                $('#loader-img').hide();
                $('#ProductListUl').html(data);
            },
            error: function(xhr, status, error) {
                console.log('Search error:', xhr.responseText);
                console.log('Status:', status);
                console.log('Error:', error);
                $('#loader-img').hide();
                $('#ProductListUl').html('<li><i class="fas fa-exclamation-triangle"></i> Search error occurred</li>');
            }
        });
    } else {
        $('#searchDiv').hide();
        $('#ProductListUl').html('');
        $('#searchSgedProductTextBox').val('');
    }
}

function hidesearchDiv() {
    $('#searchDiv').hide();
    $('#ProductListUl').html('');
}

$(document).ready(function() {
    // Bind event handlers after jQuery is ready
    $('#select-all').click(function() {
        if ($(this).prop('checked') == true) {
            $('.product_ids').prop('checked', true);
        } else {
            $('.product_ids').prop('checked', false);
        }
    });

    // Bind search input keyup event
    $('#searchSgedProductTextBox').on('keyup', function() {
        searchProduct($(this).val());
    });
});
</script>
@endpush
