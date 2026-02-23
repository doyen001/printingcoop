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
            
            <form method="POST" action="{{ url()->current() }}" class="form-horizontal" id="AddEditProductQuantity">
                @csrf
                <input class="form-control" name="id" type="hidden" value="{{ $id ?? '' }}" id="id">
                <input class="form-control" type="hidden" value="{{ $product_id }}" id="product_id" name="product_id">

                <div class="form-role-area">
                    <div class="control-group info">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <label class="span2" for="quantity_id">Quantity</label>
                            </div>
                            <div class="col-md-8">
                                <div class="controls">
                                    <select name="quantity_id" class="form-control" required>
                                        <option value="">Select Quantity</option>
                                        @foreach($quantities as $key => $val)
                                            <option value="{{ $key }}" {{ ($quantityData->qty ?? null) == $key ? 'selected="selected"' : '' }}>
                                                {{ $val }}
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
                                <label class="span2" for="quantity_price">Extra Price</label>
                            </div>
                            <div class="col-md-8">
                                <div class="controls">
                                    <input type="text" value="{{ $quantityData->price ?? '' }}" 
                                           name="quantity_price" onkeypress="javascript:return isNumber(event)"
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
                                        {{ $id ? 'Update' : 'Add' }} Quantity
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

$(document).ready(function() {
    $('#AddEditProductQuantity').submit(function(e) {
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
@endpush
@endsection
