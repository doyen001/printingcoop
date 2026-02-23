@extends('layouts.admin')

@section('content')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>

<style>
.dashboardcode-bsmultiselect .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0px !important;
    transform: translate(0, 0) !important;
    width: 100%;
    max-height: 500px;
    overflow-y: scroll;
    min-width: 100% !important;
    overflow-x: hidden;
}

.dashboardcode-bsmultiselect .custom-control-label {
    font-size: 13px !important;
    margin: 0px !important;
    color: #222;
}

.dashboardcode-bsmultiselect .custom-control.custom-checkbox {
    display: flex;
    align-items: center;
}

.dashboardcode-bsmultiselect .dropdown-menu li {
    margin-bottom: 2px;
}

.dashboardcode-bsmultiselect .form-control .badge {
    position: relative;
    background: #f3f3f3;
    padding: 5px 20px 5px 5px !important;
    font-size: 12px;
    font-weight: 400;
    margin: 0px 5px 5px 0px;
}

.dashboardcode-bsmultiselect .form-control .badge .close {
    background: transparent !important;
    position: absolute;
    top: 45%;
    right: 0px;
    transform: translate(0%, -50%);
    padding: 0px !important;
    height: 15px !important;
    width: 15px !important;
    font-size: 22px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: normal !important;
    color: #000;
}

.dashboardcode-bsmultiselect .form-control {
    overflow: scroll;
    overflow-x: hidden;
    box-shadow: none !important;
    overflow: auto;
}

.dashboardcode-bsmultiselect {
    position: relative;
}

.table-filter-home-area {
    background: #fff;
    border-radius: 5px;
    padding: 20px 15px 5px 15px;
    width: 100%;
    box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03), 0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05), 0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
}

.control-group {
    margin-bottom: 20px;
}

.table-filter-fields label {
    text-align: left !important;
    margin: 0px 0px 2px 5px !important;
    display: flex;
    justify-content: flex-start;
    color: #555;
    font-weight: 500;
}

.control-group .table-filter-fields select {
    padding: 2px 2px !important;
    font-size: 14px;
    -webkit-appearance: none;
    text-align: left;
    margin: 0px;
}

.table-filter-inner .col-md-1:last-child .table-filter-fields {
    padding-top: 25px;
}

.table-filter-fields button,
.table-filter-fields button:focus,
.table-filter-fields button:hover {
    border: none;
    background: #ff9200;
    color: #fff;
    padding: 5px 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 3px;
    height: 40px;
}

.loder {
    padding: 150px 0px;
}

.loder img {
    height: 70px;
}

#mainData {
    padding-top: 40px;
}

.custom-order-info {
    background: #f9f9f9;
    padding: 0px;
    height: 0px;
    overflow: hidden;
    margin-bottom: 00px;
}

.custom-order-card.active .custom-order-info {
    padding: 20px 20px 20px 20px;
    height: auto;
    overflow: visible;
    margin-bottom: 30px;
}

.custom-order-header-inner {
    width: 100%;
    background: #152746;
    padding: 10px 40px 10px 20px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    position: relative;
}

.create-tab-action {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translate(0%, -50%);
}

.create-tab-action svg {
    color: #fff;
    font-size: 12px;
    width: 20px !important;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 4px;
    cursor: pointer;
}

.custom-order-card {
    position: relative;
    margin-top: 10px;
    box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, 0.03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, 0.03), 0 0.25rem 0.53125rem rgba(4, 9, 20, 0.05), 0 0.125rem 0.1875rem rgba(4, 9, 20, 0.03);
    border-radius: 5px;
}

.product-number {
    background: #ff9200;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 22px !important;
    color: #fff !important;
    text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
    font-weight: 700 !important;
    border-radius: 2px;
    margin-right: 15px;
}

.custom-order-header-inner span {
    color: #fff;
    font-size: 18px;
    font-weight: 500;
}

.product-fields .table-filter-fields {
    margin-bottom: 15px;
}

.set-price-area {
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    padding: 15px 0px;
    margin: 10px 0px 15px 0px;
}

.shop-product-price span {
    font-size: 24px;
    font-weight: 400;
    color: #aaa;
}

.shop-product-price .new-price {
    color: #183e73;
    font-weight: 600;
}

.set-price-area .quant-cart {
    margin: 0px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
}

.set-price-area .quant-cart label {
    margin: 0px;
    font-size: 14px;
    color: #555;
    text-transform: capitalize;
    font-weight: 400;
    margin: 0px;
    letter-spacing: 0.5px;
    margin-right: 20px;
}

.quant-cart input {
    width: 100px;
    text-align: center;
    background: transparent;
    color: #303030;
    border: 2px solid #f58634;
    height: 40px;
    padding: 5px 5px 5px 5px;
    font-size: 16px;
    margin-right: 10px;
}

.product-fields.control-group {
    margin: 0px;
}

.file-upload-area .info-span {
    color: #666;
    font-size: 14px;
    font-weight: 400;
    display: inline-block;
    line-height: 28px;
}

.upload-file {
    display: flex;
    align-items: center;
    border: 2px dashed #e5e5e5;
    padding: 10px 10px;
    position: relative;
    margin-top: 5px;
}

.upload-file .file-btn {
    border: 1px solid #183e73;
    height: 35px;
    color: #fff;
    background: #183e73;
    padding: 2px 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-file span {
    font-size: 18px;
    font-weight: 600;
    color: #ccc;
    padding-left: 20px;
}

.upload-file input {
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    width: 100%;
    height: 100%;
    opacity: 0;
}

.uploaded-file-single {
    background: #f1f1f1;
    padding: 10px 10px;
    margin-top: 10px;
}

.uploaded-file-single-inner {
    position: relative;
    padding-left: 110px;
}

.uploaded-file-img {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100px;
    height: 100px;
    background-size: cover;
    background-position: center;
}

.uploaded-file-name span {
    color: #303030;
    font-size: 14px;
    font-weight: 600;
    line-height: 28px;
    text-overflow: ellipsis;
    overflow: hidden;
    width: 100%;
    white-space: nowrap;
    display: block;
}

.upload-action-btn {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.upload-action-btn button {
    border: 1px solid #000;
    height: 30px;
    color: #fff;
    background: #000;
    padding: 0px 10px;
    font-size: 12px;
    font-weight: 400;
    text-transform: uppercase;
    transition: 0.3s;
    letter-spacing: 0.5px;
    margin-left: 5px;
}

.upload-action-btn button:first-child {
    margin-left: 0px;
}

.upload-field {
    margin-top: 10px;
}

.upload-field textarea {
    width: 100%;
    height: 60px;
    border-radius: 3px;
    color: #303030;
    padding: 5px 10px 5px 10px;
    font-size: 14px;
    border: none;
    background: #fff;
}

.custom-order-info-title {
    text-align: center;
}

.custom-order-info-title span {
    font-size: 30px;
    font-weight: 700;
    color: #152746;
}

.custom-order-info-section {
    margin-top: 50px;
}

.step-fields .step-fields-inner.universal-bg-white {
    padding: 25px 15px 15px 15px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    margin-top: 30px;
    background: #fff;
}

.step-fields .universal-small-dark-title {
    padding-bottom: 20px;
    border-bottom: 2px dashed rgba(0, 0, 0, 0.1);
}

.step-fields-inner .universal-small-dark-title span {
    color: #0c63a6;
    font-weight: 500;
    font-size: 20px;
    text-transform: uppercase;
    display: inline-block;
    line-height: 25px;
    transition: 0.3s;
    letter-spacing: 0.5px;
}

.step-fields-inner .quote-bottom-row {
    margin-top: 25px;
}

.summary-deatil-inner.control-group {
    margin: 0px;
}

.summary-deatil-inner.control-group .table-filter-fields {
    margin-bottom: 15px;
}

.shipping-metthod-single {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.shipping-metthod-single label {
    width: 100%;
    margin: 0px;
}

.shipping-metthod-single input {
    position: absolute;
    top: 50%;
    left: 0px;
    transform: translate(0%, -50%);
}

.shipping-metthod-single strong {
    color: #000;
    font-size: 16px;
    font-weight: 600;
    line-height: 20px;
}

.shipping-metthod-single span {
    color: #666;
    font-size: 14px;
    font-weight: 400;
    line-height: 20px;
}

.universal-border-sitecolor-btn button {
    border: none;
    background: #ff9200;
    color: #fff;
    padding: 5px 20px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 3px;
    height: 50px;
    text-transform: uppercase;
    letter-spacing: 1px;
    word-spacing: 5px;
}

.next-step-btn {
    text-align: center;
    margin-top: 40px;
    margin-bottom: 20px;
}

.custom-save-btn button {
    border: none;
    background: #ff9200;
    color: #fff;
    padding: 5px 20px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border-radius: 3px;
    height: 40px;
}

.custom-save-btn {
    text-align: right;
    margin-top: 20px;
}

#product_error,
#coupon_code_error {
    color: #f95050;
    font-size: 14px;
}
</style>

<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row" style="display: flex;justify-content: center;align-items: center;">
            <div class="col-md-12 col-xs-12">
                <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                </div>
                <div class="text-center" style="color:green">
                    {{ session('message_success') }}
                </div>
                <div class="table-filter-home-area">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="table-filter-inner control-group">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="table-filter-fields">
                                                    <label>Category</label>
                                                    <div class="controls">
                                                        <select class="form-control" name="category_id" id="category_id">
                                                            <option value="">Select Category</option>
                                                            @foreach($categories as $key => $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="table-filter-fields">
                                                    <label>Sub Category</label>
                                                    <div class="controls">
                                                        <select class="form-control" name="sub_category_id" id="sub_category_id">
                                                            <option value="">Select Sub Category</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <div class="table-filter-fields">
                                                    <label>Product</label>
                                                    <div class="controls">
                                                        <select class="form-control" name="name" id="product_id">
                                                            <option value="">Select Product</option>
                                                        </select>
                                                        <label id="product_error" class="product_error"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="table-filter-fields">
                                            <button class="" type="button" id="btnsubmit" onclick="AddSingleProduct()">Add</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="mainData"></div>
                @include('admin.orders.get_order_order_information')
            </div>
        </div>
    </section>
</div>
<script>
getSingleProduct();
$('#category_id').on('change', function(e) {
    $('#loader-img').show();
    $('#sub_category_id').html('<option value="">Select Sub Category</option>');
    $('#product_id').html('<option value="">Select Product</option>');
    var category_id = $(this).val();

    $.ajax({
        type: 'GET',
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        url: '{{ url("admin/Ajax/getSubCategoryAndProductDropDownListByAjax") }}/' + category_id,
        success: function(data) {
            var json = JSON.parse(data);
            $('#loader-img').hide();
            $('#sub_category_id').html(json.sub_category);
            $('#product_id').html(json.product_list);
        }
    });
});

$('#sub_category_id').on('change', function(e) {
    $('#loader-img').show();

    $('#product_id').html('<option value="">Select Product</option>');
    var category_id = $('#category_id').val();
    var sub_category_id = $(this).val();

    url = '{{ url("admin/Ajax/getActiveProductDropDownListByAjax") }}/' + sub_category_id;
    if (category_id != '' && sub_category_id == '') {
        url = '{{ url("admin/Ajax/getSubCategoryAndProductDropDownListByAjax") }}/' + category_id;
    }

    $.ajax({
        type: 'GET',
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false,
        url: url,
        success: function(data) {
            var json = JSON.parse(data);
            $('#loader-img').hide();
            $('#product_id').html(json.product_list);
        }
    });
});

function AddSingleProduct() {
    var product_id = $('#product_id').val();
    $('#product_error').html('');

    if (product_id == '') {
        $('#product_error').html('Please Select Product');
        return false;
    }

    $('#loader-img').show();
    $('input[type="submit"]').prop('disabled', true);
    $('input[type="button"]').prop('disabled', true);
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: '{{ url("admin/Orders/AddSingleProduct") }}/' + product_id,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            $('input[type="submit"]').prop('disabled', false);
            $('input[type="button"]').prop('disabled', false);
            $('#mainData').html(data);
        }
    });
}

function getSingleProduct() {
    $('#loader-img').show();
    $('input[type="submit"]').prop('disabled', true);
    $('input[type="button"]').prop('disabled', true);
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: '{{ url("admin/Orders/getProductList") }}',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            $('input[type="submit"]').prop('disabled', false);
            $('input[type="button"]').prop('disabled', false);
            $('#mainData').html(data);
        }
    });
}

function removeProduct(product_id_key) {
    if (product_id_key == '') {
        return false;
    }
    $('#loader-img').show();
    $('input[type="submit"]').prop('disabled', true);
    $('input[type="button"]').prop('disabled', true);
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: '{{ url("admin/Orders/RemoveSingleProduct") }}/' + product_id_key,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            $('input[type="submit"]').prop('disabled', false);
            $('input[type="button"]').prop('disabled', false);
            var json = JSON.parse(data);
            if (json.success == 1) {
                $('#mainData').html(json.product_html);
                $('#Order-Information').html(json.orderinformation);
                $('#confirmbtn').html(json.confirmbtn);
            }
        }
    });
}

function openCart(product_id_key) {
    if (product_id_key == '') {
        return false;
    }
    $('#open-' + product_id_key).hide();
    $('#close-' + product_id_key).show();
    $('#' + product_id_key).addClass('active');
}

function closeCart(product_id_key) {
    if (product_id_key == '') {
        return false;
    }
    $('#open-' + product_id_key).show();
    $('#close-' + product_id_key).hide();
    $('#' + product_id_key).removeClass('active');
}

function setQuantity(product_id_key) {
    if (product_id_key == '') {
        return false;
    }
    quantity = $('#quantity' + product_id_key).val();
    if (quantity == '' || quantity == 0) {
        $('#quantity').val('1');
    }
    var myForm = document.getElementById('cartForm-' + product_id_key);
    var formData = new FormData(myForm);
    $('#loader-img').show();
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("admin/Orders/calculatePrice") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            var json = JSON.parse(data);
            if (json.success == 1) {
                $('#total-price-' + product_id_key).html(json.price);
            }
        }
    });
}

function showAttribute(cid, nid, product_id_key) {
    $('#loader-img').show();
    var item_val = $('#attribute_id_' + cid).val();
    var myForm = document.getElementById('cartForm-' + product_id_key);
    var formData = new FormData(myForm);
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("admin/Orders/calculatePrice") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            var json = JSON.parse(data);
            if (json.success == 1) {
                $('#attribute_id_' + nid).attr('disabled', false);
                $('#total-price-' + product_id_key).html(json.price);
            }
        }
    });
}

function showQuantity(product_id_key) {
    var myForm = document.getElementById('cartForm-' + product_id_key);
    var formData = new FormData(myForm);
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("admin/Orders/GetQuantity") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            var json = JSON.parse(data);
            $('#loader-img').hide();
            $('.new-price-img').show();
            if (json.success == 1) {
                $('#SizeOptions' + product_id_key).html(json.sizeoptions);
                $('#total-price-' + product_id_key).html(json.price);
            }
        }
    });
}

function getSizeOptions(product_id, make_a_default_qty_id, product_id_key) {
    $('#loader-img').show();
    $.ajax({
        type: 'GET',
        dataType: 'html',
        url: '{{ url("admin/Orders/getSizeOptions") }}/' + product_id + '/' + make_a_default_qty_id + '/' + product_id_key,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            $('#SizeOptions' + product_id_key).html(data);
        }
    });
}

function showLoder() {
    $('#loader-img').show();
}

// Sending AJAX request and upload file
function uploadData(formdata, product_id_key) {
    $.ajax({
        url: '{{ url("admin/Orders/uploadImage") }}',
        type: 'post',
        data: formdata,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
            addThumbnail(response, product_id_key);
        }
    });
}

// Added thumbnail
function addThumbnail(data, product_id_key) {
    $('#file' + product_id_key).prop('disabled', false);
    $('#file-btn' + product_id_key).show();
    $('#file-drop' + product_id_key).text('Drag & Drop Files');
    var len = $('#uploadfile' + product_id_key + 'div.thumbnail').length;
    var num = Number(len);
    num = num + 1;
    var name = data.name;
    var size = convertSize(data.size);
    var src = data.src;
    var skey = data.skey;
    var product_id = data.product_id;
    $('#upload-file-data' + product_id_key).append(data.html);
}

// Bytes conversion
function convertSize(size) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (size == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(size) / Math.log(1024)));
    return Math.round(size / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function update_cumment(skey, product_id, product_key_id) {
    $('#loader-img').show();
    var cumment = $('#cumment-' + skey).val();
    if (cumment == '') {
        alert('Enter comment');
        return false
    }

    $('#smc-' + skey).prop('disabled', true);
    $('#smc-' + skey).html('<img src="{{ asset("assets/images/loder.gif") }}" width=20>');
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("admin/Orders/updateCumment") }}',
        data: ({
            'cumment': cumment,
            'product_id': product_id,
            'skey': skey,
            'product_key_id': product_key_id
        }),
        success: function(data) {
            $('#loader-img').hide();
            $('#smc-' + skey).prop('disabled', false);
            $('#smc-' + skey).html('Update Note');
        }
    });
}

function delete_image(skey, product_id, product_key_id) {
    var location = $('#location-' + skey).val();
    if (location == '') {
        return false
    }

    $('#smd-' + skey).prop('disabled', true);
    $('#smd-' + skey).html('<img src="{{ asset("assets/images/loder.gif") }}" width=20>');
    $('#loader-img').show();

    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("admin/Orders/deleteImage") }}',
        data: ({
            'location': location,
            'product_id': product_id,
            'product_key_id': product_key_id,
            'skey': skey
        }),
        success: function(data) {
            $('#loader-img').hide();
            $('#upload-file-data' + product_key_id + ' #teb-' + skey).remove();
        }
    });
}

function getLengthWidthPrice(product_id_key) {
    $('#loader-img').show();
    $('.new-price-img').hide();
    var myForm = document.getElementById('cartForm-' + product_id_key);
    var formData = new FormData(myForm);
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: '{{ url("admin/Orders/calculatePrice") }}',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#loader-img').hide();
            $('.new-price-img').show();

            var json = JSON.parse(data);
            if (json.success == 1) {
                $('#total-price-' + product_id_key).html(json.price);

                $('#product_width_' + product_id_key).val(json.product_width);
                $('#product_length_' + product_id_key).val(json.product_length);

                $('#product_width_error_' + product_id_key).html(json.product_width_error);
                $('#product_length_error_' + product_id_key).html(json.product_length_error);
            }
        }
    });
}

function isNumber(evt) {
    var iKeyCode = (evt.which) ? evt.which : evt.keyCode
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;

    return true;
}
</script>
@endsection
