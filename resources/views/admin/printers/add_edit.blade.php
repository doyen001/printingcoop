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
                  <div class="text-center" style="color:red ;margin-bottom:10px;">
                    {{ session('message_error') }}
                  </div>
                  <form method="POST" action="{{ route('printers.addEdit', [$postData->id ?? 0, $type]) }}" class="form-horizontal">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $postData->id ?? '' }}">
                    <input class="form-control" name="type" type="hidden" value="{{ $type }}">

                  <div class="form-role-area">
                    @if($type == 'printer_series' || $type == 'printermodels')
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="printer_brand_id">Printer Brands</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <select name="printer_brand_id" class="form-control" id="printer_brand_id">
                              <option value="">Select Printer Brands</option>
                              @foreach($printerBrandLists as $list)
                                <option value="{{ $list->id }}" {{ ($list->id == ($postData->printer_brand_id ?? '')) ? 'selected="selected"' : '' }}>{{ $list->name }}</option>
                              @endforeach
                            </select>
                            @error('printer_brand_id')
                              <div class="text-danger">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    
                    @if($type == 'printermodels')
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="printer_series_id">Printer Series</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select name="printer_series_id" class="form-control" id="printer_series_id">
                                <option value="">Select Printer Series</option>
                                @foreach($printerSeriesLists as $list)
                                  <option value="{{ $list->id }}" {{ ($list->id == ($postData->printer_series_id ?? '')) ? 'selected="selected"' : '' }}>{{ $list->name }}</option>
                                @endforeach
                              </select>
                              @error('printer_series_id')
                                <div class="text-danger">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                    
                    <div class="control-group info">
                      <div class="row align-items-center">
                        <div class="col-md-4">
                          <label class="span2" for="name">Name</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" type="text" name="name"
                              value="{{ $postData->name ?? '' }}" maxlength="50">
                            @error('name')
                              <div class="text-danger">{{ $message }}</div>
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
                            <input class="form-control" type="text" name="name_french"
                              value="{{ $postData->name_french ?? '' }}" maxlength="50">
                            @error('name_french')
                              <div class="text-danger">{{ $message }}</div>
                            @enderror
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="text-right">
                    <button type="submit" class="btn btn-success">Submit</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.col-->
    </div><!-- ./row -->
  </section><!-- /.content -->
 </div>
<script>
$('#printer_brand_id').on('change', function(e) {
  var printer_brand_id = $(this).val();
  $('#printer_series_id').html('<option value="">Select Printer Series</option>');
  $.ajax({
    type: 'GET',
    dataType: 'html',
    url: '{{ url("admin/Ajax/getPrinterSeriesListByAjax/") }}' + printer_brand_id,
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
      $('#printer_series_id').html(data);
    }
  });
});
</script>
@endsection
