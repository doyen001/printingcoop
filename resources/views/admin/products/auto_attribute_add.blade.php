<div class="inner-content-area">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="text-center" style="color:red">
        @if(session('message_error'))
          {{ session('message_error') }}
        @endif
      </div>
      <div class="text-center" style="color:green">
        @if(session('message_success'))
          {{ session('message_success') }}
        @endif
      </div>
      <form method="POST" action="{{ url('admin/Products/AutoAttributeAdd') }}" enctype="multipart/form-data" class="form-horizontal" id="auto_attribute_add_form">
        @csrf
        <input class="form-control" name="id" type="hidden" value="{{ $id ?? '' }}" id="id">
        <input class="form-control" type="hidden" value="{{ $product_id ?? '' }}" id="product_id" name="product_id">
        <div class="form-role-area">
          <div class="control-group info">
            <div class="row align-items-center">
              <div class="col-md-4">
                <label class="span2" for="attribute_id">Attributes</label>
              </div>
              <div class="col-md-8">
                <div class="controls">
                  <select name="attribute_id" class="form-control" required>
                    <option value="">Select Attribute</option>
                    @if(isset($attributes))
                      @foreach($attributes as $attribute)
                        <option value="{{ $attribute['id'] }}" {{ ($attribute['id'] == ($attribute_id ?? '')) ? 'selected="selected"' : '' }}>
                          {{ $attribute['name'] }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-right">
          <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script src="{{ asset('assets/js/validation.js') }}"></script>
<script>
success = '{{ $success ?? 0 }}';
$('#auto_attribute_add_form').validate({
  rules: {
    attribute_id: {
      required: true,
    },
  },
  messages: {
    attribute_id: {
      required: 'Please select attribute',
    },
  },
  submitHandler: function(form) {
    $('#loader-img').show();
    $.ajax({
      type: "POST",
      url: '{{ url("admin/Products/AutoAttributeAdd") }}',
      data: $(form).serialize(),
      beforeSend: function() {
        $('button[type=submit]').attr('disabled', true);
      },
      success: function(data) {
        $('button[type=submit]').attr('disabled', false);
        $('#loader-img').hide();
        $('#ItemModal .modal-body').html(data);
        if (success == 1) {
          location.reload();
        }
      }
    });
  },
});
</script>
