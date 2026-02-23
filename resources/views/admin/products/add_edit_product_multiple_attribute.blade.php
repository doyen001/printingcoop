@extends('admin.layout')

@section('content')
<div class="inner-content-area">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="text-center" style="color:red">
                {{ session('message_error') }}
            </div>
            <div class="text-center" style="color:green">
                {{ session('message_success') }}
            </div>
            
            <form method="POST" action="{{ url()->current() }}" class="form-horizontal" id="AddEditProductAttribute">
                @csrf
                <input class="form-control" name="id" type="hidden" value="{{ $id ?? '' }}" id="id">
                <input class="form-control" type="hidden" value="{{ $product_id }}" id="product_id" name="product_id">
                <input class="form-control" type="hidden" value="{{ $quantity_id }}" id="quantity_id" name="quantity_id">
                <input class="form-control" type="hidden" value="{{ $size_id }}" id="size_id" name="size_id">
                <input class="form-control" type="hidden" value="{{ $attribute_id }}" id="attribute_id" name="attribute_id">

                <div class="form-role-area">
                    <div class="control-group info">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label class="span2" for="attribute_id_select">Attribute</label>
                            </div>
                            <div class="col-md-8">
                                <div class="controls">
                                    <select name="attribute_id" id="attribute_id_select" class="form-control" required>
                                        <option value="">Select Attribute</option>
                                        @foreach($multipleAttributes as $attribute)
                                            <option value="{{ $attribute['id'] }}" {{ ($attributeData->attribute_id ?? null) == $attribute['id'] ? 'selected="selected"' : '' }}>
                                                {{ $attribute['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group info">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label class="span2" for="attribute_item_id">Attribute Item</label>
                            </div>
                            <div class="col-md-8">
                                <div class="controls">
                                    <select name="attribute_item_id" id="attribute_item_id" class="form-control" required>
                                        <option value="">Select Item</option>
                                        @if($attributeData)
                                            @foreach($multipleAttributes as $attribute)
                                                @if($attribute['id'] == ($attributeData->attribute_id ?? null))
                                                    @foreach($attribute['items'] as $itemKey => $itemVal)
                                                        <option value="{{ $itemKey }}" {{ ($attributeData->attribute_item_id ?? null) == $itemKey ? 'selected="selected"' : '' }}>
                                                            {{ $itemVal }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group info">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label class="span2" for="extra_price">Extra Price</label>
                            </div>
                            <div class="col-md-8">
                                <div class="controls">
                                    <input type="text" value="{{ $attributeData->extra_price ?? '' }}" 
                                           name="extra_price" onkeypress="javascript:return isNumber(event)"
                                           placeholder="Extra Price" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="control-group info">
                        <div class="row align-items-center">
                            <div class="col-md-12 text-center">
                                <div class="controls">
                                    <button type="submit" class="btn btn-primary">
                                        {{ $id ? 'Update' : 'Add' }} Attribute
                                    </button>
                                    <a href="{{ url('/admin/products/SetMultipleAttributes/' . $product_id) }}" class="btn btn-default">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

// Attribute items data
var attributeItems = @json($multipleAttributes);

$(document).ready(function() {
    // Load attribute items when attribute changes
    $('#attribute_id_select').change(function() {
        var attributeId = $(this).val();
        $('#attribute_item_id').html('<option value="">Select Item</option>');
        
        if (attributeId) {
            $.each(attributeItems, function(key, val) {
                if (val.id == attributeId) {
                    $.each(val.items, function(itemKey, itemVal) {
                        $('#attribute_item_id').append('<option value="' + itemKey + '">' + itemVal + '</option>');
                    });
                }
            });
        }
    });
    
    // Initialize attribute items if editing
    if ($('#attribute_id_select').val()) {
        $('#attribute_id_select').trigger('change');
    }
    
    $('#AddEditProductAttribute').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var url = $(this).attr('action');
        
        $.post(url, formData, function(response) {
            if (response.success) {
                alert(response.message);
                window.location.href = '{{ url('/admin/products/SetMultipleAttributes/' . $product_id) }}';
            } else {
                alert(response.message);
            }
        }, 'json');
    });
});
</script>
@endsection
