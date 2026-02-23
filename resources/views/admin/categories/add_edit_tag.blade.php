@extends('layouts.admin')

@section('content')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
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
                    <input class="form-control" name="id" type="hidden"
                      value="{{ $tag->id ?? '' }}">
                    <div class="form-role-area">

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="name">Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Name"
                                value="{{ old('name', $tag->name ?? '') }}"
                                maxlength="50">
                              {{ $errors->first('name') ? '<label class="mt-2 text-danger">' . $errors->first('name') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="name_french">French Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name_french" id="name_french" type="text"
                                placeholder="name french"
                                value="{{ old('name_french', $tag->name_french ?? '') }}"
                                maxlength="50">
                              {{ $errors->first('name_french') ? '<label class="mt-2 text-danger">' . $errors->first('name_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="tag_order">Tag Order</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="tag_order" id="tag_order" type="number"
                                placeholder="Tag Order"
                                value="{{ old('tag_order', $tag->tag_order ?? '') }}">
                              {{ $errors->first('tag_order') ? '<label class="mt-2 text-danger">' . $errors->first('tag_order') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="font_class">Tag Font Awesome Class</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="font_class" id="font_class" type="text"
                                placeholder="Font Awesome Class"
                                value="{{ old('font_class', $tag->font_class ?? '') }}">
                              {{ $errors->first('font_class') ? '<label class="mt-2 text-danger">' . $errors->first('font_class') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="proudly_display_your_brand">Show Tag</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input type="checkbox" name="proudly_display_your_brand" value="1"
                                {{ old('proudly_display_your_brand', $tag->proudly_display_your_brand ?? 0) ? 'checked' : '' }}> Proudly Display
                              Your Brand
                              {{ $errors->first('proudly_display_your_brand') ? '<label class="mt-2 text-danger">' . $errors->first('proudly_display_your_brand') . '</label>' : '' }}
                              <input type="checkbox" name="montreal_book_printing" value="1"
                                {{ old('montreal_book_printing', $tag->montreal_book_printing ?? 0) ? 'checked' : '' }}> Montreal Book Printing
                              {{ $errors->first('montreal_book_printing') ? '<label class="mt-2 text-danger">' . $errors->first('montreal_book_printing') . '</label>' : '' }}
                              <input type="checkbox" name="footer" value="1"
                                {{ old('footer', $tag->footer ?? 0) ? 'checked' : '' }}> Show Footer
                              {{ $errors->first('footer') ? '<label class="mt-2 text-danger">' . $errors->first('footer') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="image">Tag Image</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <div style="margin-bottom:15px;">
                                @php $old_image = old('image', $tag->image ?? '');@endphp
                                @if($old_image != '')
                                  <img src="{{ url('uploads/category/large/' . $old_image) }}" width="100" height="80" 
                                       onerror="this.src='{{ url('assets/admin/images/no-image.png') }}';">
                                @endif
                                <input name="old_image" value="{{ $old_image }}" type="hidden">
                              </div>
                              <div class="image-info">
                                <span>
                                  Allowed image type : <b> (jpg, png, gif)</b>
                                </span>

                                <div class="entry input-group col-xs-3" style="margin-bottom:15px;">
                                  <input class="btn btn-primary" name="files" type="file"
                                    accept="image/x-png,image/gif,image/jpeg" id="upload" onchange="Upload('upload')" />
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
                            <label class="span2" for="image_french">Tag Image French</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <div style="margin-bottom:15px;">
                                @php $old_image_french = old('image_french', $tag->image_french ?? ''); @endphp
                                @if($old_image_french != '')
                                  <img src="{{ url('uploads/category/large/' . $old_image_french) }}" width="100" height="80"
                                       onerror="this.src='{{ url('assets/admin/images/no-image.png') }}';">
                                @endif
                                <input name="old_image_french" value="{{ $old_image_french }}" type="hidden">
                              </div>
                              <div class="image-info">
                                <span>
                                  Allowed image type : <b> (jpg, png, gif)</b>
                                </span>

                                <div class="entry input-group col-xs-3" style="margin-bottom:15px;">
                                  <input class="btn btn-primary" name="files_french" type="file"
                                    accept="image/x-png,image/gif,image/jpeg" id="upload_french" onchange="Upload('upload_french')" />
                                </div>
                                <div style="color:red">
                                  {{ session('file_message_error_french') }}
                                  {{ $errors->first('files_french') ? '<label class="mt-2 text-danger">' . $errors->first('files_french') . '</label>' : '' }}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <button type="submit" class="btn btn-success">Submit</button>
                      <a href="{{ url('admin/Categories/tag') }}" class="btn btn-success">Back</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box -->
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
