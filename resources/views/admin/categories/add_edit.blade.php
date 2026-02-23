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
                  <div class="text-center text-danger">
                    {{ session('message_error') }}
                  </div>
                  <form method="POST" action="{{ route('categories.addEdit', $category->id ?? null) }}" class="form-horizontal" enctype="multipart/form-data">
                  @csrf
                  <input class="form-control" name="id" type="hidden" value="{{ $category->id ?? '' }}">
                  <div class="form-role-area">
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="name">Name</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" name="name" id="name" type="text" placeholder="Category Name"
                              value="{{ old('name', $category->name ?? '') }}" maxlength="50">
                            <label class="mt-2 text-danger">{{ $errors->first('name') }}</label>
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
                              placeholder="Category Name"
                              value="{{ old('name_french', $category->name_french ?? '') }}" maxlength="50">
                            <label class="mt-2 text-danger">{{ $errors->first('name_french') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="category_order">Category Order</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" name="category_order" id="category_order" type="number"
                              placeholder="Category Order"
                              value="{{ old('category_order', $category->category_order ?? '') }}">
                            <label class="mt-2 text-danger">{{ $errors->first('category_order') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="page_title">Category Page Title</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" name="page_title" id="page_title" type="text"
                              placeholder="Page Title"
                              value="{{ old('page_title', $category->page_title ?? '') }}" maxlength="250">
                            <label class="mt-2 text-danger">{{ $errors->first('page_title') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="page_title_french">French Category Page Title</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input class="form-control" name="page_title_french" id="page_title_french" type="text"
                              placeholder="French Page Title"
                              value="{{ old('page_title_french', $category->page_title_french ?? '') }}" maxlength="250">
                            <label class="mt-2 text-danger">{{ $errors->first('page_title_french') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="meta_description_content">Category Page Meta Description Content</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <textarea name="meta_description_content" id="meta_description_content"
                              rows="4">{{ old('meta_description_content', $category->meta_description_content ?? '') }}</textarea>
                            <label class="mt-2 text-danger">{{ $errors->first('meta_description_content') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="meta_description_content_french">French Category Page Meta Description Content</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <textarea name="meta_description_content_french" id="meta_description_content_french"
                              rows="4">{{ old('meta_description_content_french', $category->meta_description_content_french ?? '') }}</textarea>
                            <label class="mt-2 text-danger">{{ $errors->first('meta_description_content_french') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="meta_keywords_content">Category Page Meta Keywords Content</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <textarea name="meta_keywords_content" id="meta_keywords_content"
                              rows="4">{{ old('meta_keywords_content', $category->meta_keywords_content ?? '') }}</textarea>
                            <label class="mt-2 text-danger">{{ $errors->first('meta_keywords_content') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="meta_keywords_content_french">French Category Page Meta Keywords Content</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <textarea name="meta_keywords_content_french" id="meta_keywords_content_french"
                              rows="4">{{ old('meta_keywords_content_french', $category->meta_keywords_content_french ?? '') }}</textarea>
                            <label class="mt-2 text-danger">{{ $errors->first('meta_keywords_content_french') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="category_dispersion">Category Dispersion</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <textarea class="form-control" name="category_dispersion"
                              id="category_dispersion">{{ old('category_dispersion', $category->category_dispersion ?? '') }}</textarea>
                            <label class="mt-2 text-danger">{{ $errors->first('category_dispersion') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="category_dispersion_french">French Category Dispersion</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <textarea class="form-control" name="category_dispersion_french"
                              id="category_dispersion_french">{{ old('category_dispersion_french', $category->category_dispersion_french ?? '') }}</textarea>
                            <label class="mt-2 text-danger">{{ $errors->first('category_dispersion_french') }}</label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2">Display Options</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <input type="checkbox" name="show_our_printed_product" value="1"
                              {{ old('show_our_printed_product', $category->show_our_printed_product ?? 0) ? 'checked' : '' }}> Our Printed Product {{ $errors->first('category_dispersion') }}
                            <input type="checkbox" name="show_main_menu" value="1"
                              {{ old('show_main_menu', $category->show_main_menu ?? 1) ? 'checked' : '' }}> Show Main Menu {{ $errors->first('show_main_menu') }}
                            <input type="checkbox" name="show_footer_menu" value="1"
                              {{ old('show_footer_menu', $category->show_footer_menu ?? 1) ? 'checked' : '' }}> Show Footer Menu {{ $errors->first('show_footer_menu') }}
                          </div>
                        </div>
                      </div>
                    </div>

                    @if(isset($stores) && count($stores) > 0)
                    @foreach($stores as $store)
                    @php $key = $store->id; @endphp
                    <input type="hidden" name="{{ $key }}category_image_id" value="{{ $categoryImages[$key]['id'] ?? '' }}">
                    <div class="control-group info">
                      <div class="row">
                        <div class="col-md-4">
                          <label class="span2" for="old_image">{{ $store->name }} Image</label>
                        </div>
                        <div class="col-md-8">
                          <div class="controls">
                            <div style="margin-bottom:15px;">
                              @php 
                              $categoryImageData = $categoryImages[$key] ?? ['id' => 0, 'image' => ''];
                              $old_image = $categoryImageData['image'] ?? '';
                              @endphp
                              @if($old_image != '')
                              <img src="{{ asset('uploads/category/large/' . $old_image) }}" width="100" height="80" 
                                   onerror="this.src='{{ asset('uploads/category/' . $old_image) }}'">
                              @endif
                              <input name="{{ $key }}old_image" value="{{ $old_image }}" type="hidden">
                            </div>
                            <div class="image-info">
                              <span>
                                Allowed image type : <b> (jpg, png, gif)</b>
                              </span>

                              <div class="entry input-group col-xs-3" style="margin-bottom:15px;">
                                <input class="btn btn-primary" name="{{ $key }}files" type="file"
                                  accept="image/x-png,image/gif,image/jpeg" id="{{ $key }}upload"
                                  onchange="Upload('{{ $key }}upload')" />
                              </div>
                              <div style="color:red">
                                {{ session($key . 'file_message_error') }}
                                {{ $errors->first('files') }}
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endforeach
                    @endif

                    <div class="text-right">
                      <button type="submit" class="btn btn-success">Submit</button>
                      <a href="{{ route('categories.index') }}" class="btn btn-success">Back</a>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
function Upload(imageId) {
  var fileUpload = document.getElementById(imageId);
  //Check whether the file is valid Image.
  var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|jpge|.png|.gif)$");
  if (regex.test(fileUpload.value.toLowerCase())) {
    if (typeof(fileUpload.files) != "undefined") {
      //Initiate the FileReader object.
      var reader = new FileReader();
      //Read the contents of Image File.
      reader.readAsDataURL(fileUpload.files[0]);
      reader.onload = function(e) {
        //Initiate the JavaScript Image object.
        var image = new Image();
        //Set the Base64 string return from FileReader as source.
        image.src = e.target.result;
        //Validate the File Height and Width.
        image.onload = function() {
          var height = this.height;
          var width = this.width;
          var imagesize = fileUpload.files[0].size;
          var FILE_MAX_SIZE_JS = '2097152'; // 2MB
        };
      }
    }
  }
}

CKEDITOR.replace('category_dispersion', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});

CKEDITOR.dtd.$removeEmpty.i = 0;

CKEDITOR.replace('category_dispersion_french', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*]',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
</script>
@endsection
