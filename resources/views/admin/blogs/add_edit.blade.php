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
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center" style="color:red">
                    @if(session('message_error'))
                      {{ session('message_error') }}
                    @endif
                  </div>
                  <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}" id="id">
                    <div class="form-role-area">

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="website">WebSite</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              @php
                                $store_ids = $postData['store_id'] ?? '';
                                if (!empty($store_ids)) {
                                  $store_ids = explode(',', $store_ids);
                                } else {
                                  $store_ids = array();
                                }
                              @endphp

                              @foreach($storeList as $key => $val)
                                @php
                                  $checked = '';
                                  if (in_array($key, $store_ids)) {
                                    $checked = 'checked';
                                  }
                                @endphp
                                <input name="store_id[]" type="checkbox" value="{{ $key }}" {{ $checked }}>
                                <label style="margin-left:5px;">{{ $val['name'] }}</label>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="title">Blog Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="title" id="title" type="text" placeholder="Blog Title" value="{{ $postData['title'] ?? '' }}">
                              {{ $errors->first('title') ? '<label class="mt-2 text-danger">' . $errors->first('title') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="title_french">Blog Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="title_french" id="title_french" type="text" placeholder="Blog Title" value="{{ $postData['title_french'] ?? '' }}">
                              {{ $errors->first('title_french') ? '<label class="mt-2 text-danger">' . $errors->first('title_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="category_id">Blog Category</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select class="form-control" name="category_id">
                                <option value="">Select Category</option>
                                @if(isset($categoryData))
                                  @foreach($categoryData as $key => $val)
                                    <option value="{{ $val['id'] }}" {{ ($postData['category_id'] ?? '') == $val['id'] ? 'selected="selected"' : '' }}>
                                      {{ $val['category_name'] }}
                                    </option>
                                  @endforeach
                                @endif
                              </select>
                              {{ $errors->first('category_id') ? '<label class="mt-2 text-danger">' . $errors->first('category_id') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="populer">Populer</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input name="populer" id="populer" type="checkbox" value="1" {{ ($postData['populer'] ?? '') == 1 ? 'checked' : '' }}>
                              {{ $errors->first('populer') ? '<label class="mt-2 text-danger">' . $errors->first('populer') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="content">Blog Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="content" id="content">{{ $postData['content'] ?? '' }}</textarea>
                              {{ $errors->first('content') ? '<label class="mt-2 text-danger">' . $errors->first('content') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="content1">French Blog Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="content_french" id="content1">{{ $postData['content_french'] ?? '' }}</textarea>
                              {{ $errors->first('content_french') ? '<label class="mt-2 text-danger">' . $errors->first('content_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="image">Blog Image</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <div class="col-xs-3" style="margin-bottom:15px;">
                                @php $old_image = $postData['image'] ?? ''; @endphp
                                @if($old_image != '')
                                  <img src="{{ url('uploads/blogs/' . $old_image) }}" width="100" height="80"
                                       onerror="this.src='{{ url('uploads/blogs/large/' . $old_image) }}';">
                                @endif
                                <input name="old_image" value="{{ $old_image }}" type="hidden">
                              </div>
                            </div>
                            <div class="controls file-data">
                              <div class="image-info col-xs-12" style="margin-bottom: 10px;">
                                <span>Allowed image type: <b> (jpg, png, gif)</b></span>
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
                            <label class="span2" for="page_title">blog Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title" id="page_title" type="text"
                                placeholder="Page Title"
                                value="{{ $postData['page_title'] ?? '' }}"
                                maxlength="250">
                              {{ $errors->first('page_title') ? '<label class="mt-2 text-danger">' . $errors->first('page_title') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="page_title_french">French blog Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title_french" id="page_title_french" type="text"
                                placeholder="French Page Title"
                                value="{{ $postData['page_title_french'] ?? '' }}"
                                maxlength="250">
                              {{ $errors->first('page_title_french') ? '<label class="mt-2 text-danger">' . $errors->first('page_title_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content">blog Page Meta Description Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_description_content" id="meta_description_content"
                                rows="100">{{ $postData['meta_description_content'] ?? '' }}</textarea>
                              {{ $errors->first('meta_description_content') ? '<label class="mt-2 text-danger">' . $errors->first('meta_description_content') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content_french">France blog Page Meta Description Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_description_content_french" id="meta_description_content_french"
                                rows="100">{{ $postData['meta_description_content_french'] ?? '' }}</textarea>
                              {{ $errors->first('meta_description_content_french') ? '<label class="mt-2 text-danger">' . $errors->first('meta_description_content_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_keywords_content">blog Page Meta Keywords Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_keywords_content" id="meta_keywords_content"
                                rows="100">{{ $postData['meta_keywords_content'] ?? '' }}</textarea>
                              {{ $errors->first('meta_keywords_content') ? '<label class="mt-2 text-danger">' . $errors->first('meta_keywords_content') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_keywords_content_french">France blog Page Meta Keywords Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_keywords_content_french" id="meta_keywords_content_french"
                                rows="100">{{ $postData['meta_keywords_content_french'] ?? '' }}</textarea>
                              {{ $errors->first('meta_keywords_content_french') ? '<label class="mt-2 text-danger">' . $errors->first('meta_keywords_content_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                        <a href="{{ url('admin/Blogs') }}" class="btn btn-success">Back</a>
                      </div>
                    </form>
                  </div>
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
    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|jpge|.png|.gif)$");
    if (regex.test(fileUpload.value.toLowerCase())) {
      if (typeof (fileUpload.files) != "undefined") {
        //Initiate the FileReader object.
        var reader = new FileReader();
        //Read the contents of Image File.
        reader.readAsDataURL(fileUpload.files[0]);
        reader.onload = function (e) {
        //Initiate the JavaScript Image object.
        var image = new Image();

        //Set the Base64 string return from FileReader as source.
        image.src = e.target.result;
        //Validate the File Height and Width.
        image.onload = function () {
            var height = this.height;
            var width = this.width;
            var imagesize = fileUpload.files[0].size;
            var FILE_MAX_SIZE_JS = 1048576; // 1MB in bytes

            if (FILE_MAX_SIZE_JS < imagesize) {
              alert('Allowed image size maximum: 1Mb');
              return false;
            }
          };
        }
      }
    }
}

CKEDITOR.replace('content', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent:true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('content_french', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent:true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('meta_description_content', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent:true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('meta_description_content_french', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent:true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('meta_keywords_content', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent:true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('meta_keywords_content_french', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent:true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
</script>
@endsection
