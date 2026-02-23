@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
  <section class="content">
    <div class="row" style="display: flex;justify-content: center;align-items: center;">
      <div class="col-md-12 col-xs-12">
        <div class="box box-success box-solid">
          <div class="box-body">
            <div class="inner-head-section">
              <div class="inner-title">
                <span>{{ $page_title }}</span>
              </div>
            </div>
            <div class="inner-content-area">
              <div class="row justify-content-center">
                <div class="col-md-7">
                  <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                  </div>
                  @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                  
                  <form method="POST" action="{{ route('admin.products.sizes.addEdit', ['id' => $id]) }}" class="form-horizontal">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}" id="id">
                    
                    <div class="form-role-area">
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="size_name">Size Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="size_name" id="size_name" type="text" placeholder="Size Name" value="{{ old('size_name', $postData['size_name'] ?? '') }}" maxlength="50">
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="size_name_french">French Size Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="size_name_french" id="size_name_french" type="text" placeholder="French Size Name" value="{{ old('size_name_french', $postData['size_name_french'] ?? '') }}" maxlength="50">
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="set_order">Set Order</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="set_order" id="set_order" type="number" placeholder="Set Order" value="{{ old('set_order', $postData['set_order'] ?? 0) }}" min="0">
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="status">Status</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select class="form-control" name="status" id="status">
                                <option value="1" {{ old('status', $postData['status'] ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $postData['status'] ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                        <a href="{{ route('admin.products.sizes.index') }}" class="btn btn-success">Back</a>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      </div><!-- /.col-->
    </div><!-- ./row -->
  </section><!-- /.content -->
</div>

<script>
// Form validation and other JavaScript can be added here if needed
$(document).ready(function() {
    // Basic form validation
    $('form').submit(function(e) {
        var sizeName = $('#size_name').val().trim();
        if (sizeName === '') {
            alert('Size Name is required');
            $('#size_name').focus();
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endsection
