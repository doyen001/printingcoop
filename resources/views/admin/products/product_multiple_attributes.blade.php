@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $page_title }}</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Back to Products
                            </a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="text-center" style="color:red">
                            {{ session('message_error') }}
                        </div>
                        <div class="text-center" style="color:green">
                            {{ session('message_success') }}
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h4>Product: {{ $product->name }}</h4>
                            </div>
                        </div>

                        @if(!empty($productSizes))
                            <div class="attribute-container">
                                @foreach($productSizes as $quantity)
                                    <div class="quantity-section">
                                        <div class="quantity-header">
                                            <h5>
                                                <i class="fa fa-cubes"></i>
                                                {{ $quantity['qty_name'] }}
                                                <button class="btn btn-primary btn-sm pull-right" 
                                                        onclick="addSize({{ $product->id }}, {{ $quantity['qty_id'] }})">
                                                    <i class="fa fa-plus"></i> Add Size
                                                </button>
                                            </h5>
                                        </div>
                                        
                                        @if(!empty($quantity['sizes']))
                                            <div class="sizes-container">
                                                @foreach($quantity['sizes'] as $size)
                                                    <div class="size-section">
                                                        <div class="size-header">
                                                            <h6>
                                                                <i class="fa fa-expand"></i>
                                                                {{ $size['size_name'] }}
                                                                <button class="btn btn-info btn-sm pull-right" 
                                                                        onclick="addAttribute({{ $product->id }}, {{ $quantity['qty_id'] }}, {{ $size['size_id'] }})">
                                                                    <i class="fa fa-plus"></i> Add Attribute
                                                                </button>
                                                            </h6>
                                                        </div>
                                                        
                                                        @if(!empty($size['attributes']))
                                                            <div class="attributes-container">
                                                                @foreach($size['attributes'] as $attribute)
                                                                    <div class="attribute-item">
                                                                        <span class="attribute-name">
                                                                            {{ $attribute['attribute_name'] }} - {{ $attribute['attribute_item_name'] }}
                                                                        </span>
                                                                        <span class="attribute-price">
                                                                            ${{ number_format($attribute['extra_price'], 2) }}
                                                                        </span>
                                                                        <button class="btn btn-danger btn-xs" 
                                                                                onclick="deleteAttribute({{ $attribute['id'] }})">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h4>No attributes configured yet</h4>
                                <p>Start by adding quantities, then sizes, then attributes to build your product pricing structure.</p>
                                <button class="btn btn-primary" onclick="addQuantity({{ $product->id }})">
                                    <i class="fa fa-plus"></i> Add First Quantity
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Add/Edit Quantity -->
<div class="modal fade" id="quantityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add/Edit Quantity</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="quantityForm">
                    <input type="hidden" name="product_id" id="quantity_product_id">
                    <input type="hidden" name="id" id="quantity_id">
                    
                    <div class="form-group">
                        <label for="quantity_id_select">Quantity</label>
                        <select name="quantity_id" id="quantity_id_select" class="form-control" required>
                            <option value="">Select Quantity</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="quantity_price">Extra Price</label>
                        <input type="text" name="quantity_price" id="quantity_price" 
                               class="form-control" placeholder="0.00" 
                               onkeypress="return isNumber(event)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveQuantity()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Size -->
<div class="modal fade" id="sizeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add/Edit Size</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="sizeForm">
                    <input type="hidden" name="product_id" id="size_product_id">
                    <input type="hidden" name="quantity_id" id="size_quantity_id">
                    <input type="hidden" name="id" id="size_id">
                    
                    <div class="form-group">
                        <label for="size_id_select">Size</label>
                        <select name="size_id" id="size_id_select" class="form-control" required>
                            <option value="">Select Size</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="size_price">Extra Price</label>
                        <input type="text" name="size_price" id="size_price" 
                               class="form-control" placeholder="0.00" 
                               onkeypress="return isNumber(event)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveSize()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Attribute -->
<div class="modal fade" id="attributeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add/Edit Attribute</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="attributeForm">
                    <input type="hidden" name="product_id" id="attribute_product_id">
                    <input type="hidden" name="quantity_id" id="attribute_quantity_id">
                    <input type="hidden" name="size_id" id="attribute_size_id">
                    <input type="hidden" name="attribute_id" id="attribute_id">
                    <input type="hidden" name="id" id="attribute_id_field">
                    
                    <div class="form-group">
                        <label for="attribute_id_select">Attribute</label>
                        <select name="attribute_id" id="attribute_id_select" class="form-control" required>
                            <option value="">Select Attribute</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="attribute_item_id_select">Attribute Item</label>
                        <select name="attribute_item_id" id="attribute_item_id_select" class="form-control" required>
                            <option value="">Select Item</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="attribute_price">Extra Price</label>
                        <input type="text" name="extra_price" id="attribute_price" 
                               class="form-control" placeholder="0.00" 
                               onkeypress="return isNumber(event)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAttribute()">Save</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.attribute-container {
    margin: 20px 0;
}

.quantity-section, .size-section {
    border: 1px solid #ddd;
    margin: 10px 0;
    padding: 15px;
    border-radius: 5px;
}

.quantity-header, .size-header {
    background: #f5f5f5;
    padding: 10px;
    margin: -15px -15px 15px -15px;
    border-bottom: 1px solid #ddd;
    border-radius: 5px 5px 0 0;
}

.attributes-container {
    margin: 10px 0;
}

.attribute-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 10px;
    margin: 5px 0;
    background: #f9f9f9;
    border-radius: 3px;
}

.attribute-name {
    font-weight: bold;
}

.attribute-price {
    color: #28a745;
    font-weight: bold;
}

.alert {
    margin: 20px 0;
}
</style>
@endsection

@push('scripts')
<script>
let currentProductId = {{ $product->id }};
let currentQuantityId = null;
let currentSizeId = null;
let currentAttributeId = null;

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function addQuantity(productId) {
    currentProductId = productId;
    currentQuantityId = null;
    currentSizeId = null;
    currentAttributeId = null;
    
    $('#quantity_product_id').val(productId);
    $('#quantity_id').val('');
    $('#quantity_id_select').val('');
    $('#quantity_price').val('');
    
    // Load quantity options
    $.get('/admin/products/get-quantity-options', function(data) {
        $('#quantity_id_select').html('<option value="">Select Quantity</option>');
        $.each(data, function(key, val) {
            $('#quantity_id_select').append('<option value="' + key + '">' + val + '</option>');
        });
    });
    
    $('#quantityModal').modal('show');
}

function addSize(productId, quantityId) {
    currentProductId = productId;
    currentQuantityId = quantityId;
    currentSizeId = null;
    currentAttributeId = null;
    
    $('#size_product_id').val(productId);
    $('#size_quantity_id').val(quantityId);
    $('#size_id').val('');
    $('#size_id_select').val('');
    $('#size_price').val('');
    
    // Load size options for this quantity
    $.get('/admin/products/get-size-options/' + productId + '/' + quantityId, function(data) {
        $('#size_id_select').html('<option value="">Select Size</option>');
        $.each(data, function(key, val) {
            $('#size_id_select').append('<option value="' + key + '">' + val + '</option>');
        });
    });
    
    $('#sizeModal').modal('show');
}

function addAttribute(productId, quantityId, sizeId) {
    currentProductId = productId;
    currentQuantityId = quantityId;
    currentSizeId = sizeId;
    currentAttributeId = null;
    
    $('#attribute_product_id').val(productId);
    $('#attribute_quantity_id').val(quantityId);
    $('#attribute_size_id').val(sizeId);
    $('#attribute_id').val('');
    $('#attribute_id_select').val('');
    $('#attribute_item_id_select').val('');
    $('#attribute_price').val('');
    
    // Load attribute options
    $.get('/admin/products/get-attribute-options', function(data) {
        $('#attribute_id_select').html('<option value="">Select Attribute</option>');
        $.each(data, function(key, val) {
            $('#attribute_id_select').append('<option value="' + val.id + '">' + val.name + '</option>');
        });
    });
    
    $('#attribute_id_select').change(function() {
        var attributeId = $(this).val();
        $('#attribute_item_id_select').html('<option value="">Select Item</option>');
        
        if (attributeId) {
            $.each(data, function(key, val) {
                if (val.id == attributeId) {
                    $.each(val.items, function(itemKey, itemVal) {
                        $('#attribute_item_id_select').append('<option value="' + itemKey + '">' + itemVal + '</option>');
                    });
                }
            });
        }
    });
    
    $('#attributeModal').modal('show');
}

function saveQuantity() {
    var formData = $('#quantityForm').serialize();
    
    $.post('/admin/products/add-edit-product-quantity/' + currentProductId, formData, function(response) {
        if (response.success) {
            $('#quantityModal').modal('hide');
            location.reload();
        } else {
            alert(response.message);
        }
    }, 'json');
}

function saveSize() {
    var formData = $('#sizeForm').serialize();
    
    $.post('/admin/products/add-edit-product-size/' + currentProductId + '/' + currentQuantityId, formData, function(response) {
        if (response.success) {
            $('#sizeModal').modal('hide');
            location.reload();
        } else {
            alert(response.message);
        }
    }, 'json');
}

function saveAttribute() {
    var formData = $('#attributeForm').serialize();
    
    $.post('/admin/products/add-edit-product-attribute/' + currentProductId + '/' + currentQuantityId + '/' + currentSizeId + '/' + currentAttributeId, formData, function(response) {
        if (response.success) {
            $('#attributeModal').modal('hide');
            location.reload();
        } else {
            alert(response.message);
        }
    }, 'json');
}

function deleteAttribute(attributeId) {
    if (confirm('Are you sure you want to delete this attribute?')) {
        $.post('/admin/products/delete-product-multiple-attribute/' + attributeId, function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message);
            }
        }, 'json');
    }
}
</script>
@endsection
