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
                <div class="col-md-12">
                  <div class="text-center" style="color:red">
                    @if(session('message_error'))
                      {{ session('message_error') }}
                    @endif
                  </div>
                  <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}">
                    <div class="form-role-area">
                       <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                              <label class="span2" for="main_store_id">WebSite</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                 <select name="main_store_id" class="form-control">
                                 <option value="">Select WebSite</option>
                                 @foreach($mainStoreList as $key => $val)
                                  <option value="{{ $key }}" {{ ($postData['main_store_id'] ?? '') == $key ? 'selected="selected"' : '' }}>{{ $val }}</option>
                                 @endforeach
                                 </select>
                                 {{ $errors->first('main_store_id') ? '<label class="mt-2 text-danger">' . $errors->first('main_store_id') . '</label>' : '' }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                              <label class="span2" for="name">Service Name</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <input class="form-control" name="name" id="name" type="text" placeholder="Name" value="{{ $postData['name'] ?? '' }}" maxlength="50">
                                {{ $errors->first('name') ? '<label class="mt-2 text-danger">' . $errors->first('name') . '</label>' : '' }}
                              </div>
                            </div>
                          </div>
                        </div>
                         <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                              <label class="span2" for="name_french">French Service Name</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <input class="form-control" name="name_french" id="name_french" type="text" placeholder="Name" value="{{ $postData['name_french'] ?? '' }}" maxlength="50">
                                {{ $errors->first('name_french') ? '<label class="mt-2 text-danger">' . $errors->first('name_french') . '</label>' : '' }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                              <label class="span2" for="description">Service Description</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <textarea class="form-control" rows="4" name="description" placeholder="Description">{{ $postData['description'] ?? '' }}</textarea>
                                {{ $errors->first('description') ? '<label class="mt-2 text-danger">' . $errors->first('description') . '</label>' : '' }}
                              </div>
                            </div>
                          </div>
                        </div>
                         <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                              <label class="span2" for="description_french">French Service Description</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <textarea class="form-control" rows="4" name="description_french" placeholder="Description">{{ $postData['description_french'] ?? '' }}</textarea>
                                {{ $errors->first('description_french') ? '<label class="mt-2 text-danger">' . $errors->first('description_french') . '</label>' : '' }}
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                                <label class="span2" for="old_image">Service Image</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <div class="col-xs-3" style="margin-bottom:15px;">
                                  @php $old_image = $postData['service_image'] ?? ''; @endphp
                                  @if($old_image != '')
                                    <img src="{{ url('uploads/banners/small/' . $old_image) }}" width="100" height="80"
                                         onerror="this.src='{{ url('uploads/services/' . $old_image) }}';">
                                  @endif
                                  <input name="old_image" value="{{ $old_image }}" type="hidden">
                                </div>
                              </div>
                              <div class="controls file-data">
                                <div class="image-info col-xs-12" style="margin-bottom: 10px;">
                                  <span>
                                  Allowed image type  : <b> (jpg, png, gif)</b>
                                  </span>
                                </div>
                                <div class="entry input-group col-xs-3" style="margin-bottom:15px;">
                                  <input class="btn btn-primary" name="files" type="file" accept="image/x-png,image/gif,image/jpeg" id="upload" onchange="Upload('upload')"/>
                                </div>
                                <div style="color:red">
                                  {{ session('file_message_error') }}
                                  {{ $errors->first('files') ? '<label class="mt-2 text-danger">' . $errors->first('files') . '</label>' : '' }}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="control-group info">
                          <div class="row">
                            <div class="col-md-4">
                                <label class="span2" for="old_image_french">Service Image French</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <div class="col-xs-3" style="margin-bottom:15px;">
                                  @php $old_image_french = $postData['service_image_french'] ?? ''; @endphp
                                  @if($old_image_french != '')
                                    <img src="{{ url('uploads/services/large/' . $old_image_french) }}" width="100" height="80"
                                         onerror="this.src='{{ url('uploads/services/' . $old_image_french) }}';">
                                  @endif
                                  <input name="old_image_french" value="{{ $old_image_french }}" type="hidden">
                                </div>
                              </div>
                              <div class="controls file-data">
                                <div class="image-info col-xs-12" style="margin-bottom: 10px;">
                                  <span>
                                  Allowed image type  : <b> (jpg, png, gif)</b>
                                  </span>
                                </div>
                                <div class="entry input-group col-xs-3" style="margin-bottom:15px;">
                                  <input class="btn btn-primary" name="files_french" type="file" accept="image/x-png,image/gif,image/jpeg" id="upload_french" onchange="Upload('upload_french')"/>
                                </div>
                                <div style="color:red">
                                  {{ session('file_message_error_french') }}
                                  {{ $errors->first('files_french') ? '<label class="mt-2 text-danger">' . $errors->first('files_french') . '</label>' : '' }}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="text-right">
                          <button type="submit" class="btn btn-success">Submit</button>
                          <a href="{{ url('admin/Services') }}" class="btn btn-success">Back</a>
                        </div>
                      </form>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div><!-- /.box -->
      </div>
      <!-- /.col-->
    </div>
    <!-- ./row -->
  </section>
  <!-- /.content -->
</div>
<script>
function Upload(imageId) {
  var fileUpload = document.getElementById(imageId);
  //Check whether the file is valid Image.
  if (typeof (fileUpload.files) != "undefined") {
    var file = fileUpload.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      var image = new Image();
      image.src = e.target.result;
      image.onload = function() {
        var height = this.height;
        var width = this.width;
        var imagesize = fileUpload.files[0].size;
        var FILE_MAX_SIZE_JS = 1048576; // 1MB in bytes

        if (FILE_MAX_SIZE_JS < imagesize) {
          alert('Allowed image size maximum: 1Mb');
          return false;
        }
      };
    };
    reader.readAsDataURL(file);
  }
}
</script>
@endsection
