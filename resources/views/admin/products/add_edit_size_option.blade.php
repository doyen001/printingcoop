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
                  <form method="POST" action="{{ route('admin.products.sizeOptions.addEdit', ['id' => $id, 'type' => $type]) }}" class="form-horizontal">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}" id="id">
                    
                    <div class="form-role-area">
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="name">Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Name" value="{{ old('name', $postData['name'] ?? '') }}" maxlength="50">
                              @error('name')
                                <div class="form_vl_error">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="name_french">French Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name_french" id="name_french" type="text" placeholder="French Name" value="{{ old('name_french', $postData['name_french'] ?? '') }}" maxlength="50">
                              @error('name_french')
                                <div class="form_vl_error">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    @if($type == 'page_size')
                    <div class="form-role-area">
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="total_page">Total Page</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input type="number" class="form-control" name="total_page" id="total_page" placeholder="Total Page" value="{{ old('total_page', $postData['total_page'] ?? '') }}" required>
                              @error('total_page')
                                <div class="form_vl_error">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif

                    <div class="text-right">
                      <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                      <a href="{{ route('admin.products.sizeOptions', ['type' => $type]) }}" class="btn btn-success">Back</a>
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
    // Basic form validation for page_quantity type
    @if($type == 'page_quantity')
    $('form').submit(function(e) {
        var name = $('#name').val().trim();
        
        if (name === '') {
            alert('Enter Quantity');
            $('#name').focus();
            e.preventDefault();
            return false;
        }
        
        // Check if it's a valid number
        if (isNaN(name) || parseInt(name) < 1) {
            alert('Quantity must be a positive number');
            $('#name').focus();
            e.preventDefault();
            return false;
        }
    });
    @endif
});
</script>
@endsection
