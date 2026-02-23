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
                  <form method="POST" action="" class="form-horizontal">
                    @csrf
                    <input class="form-control" name="id" type="hidden"
                      value="{{ $subCategory->id ?? '' }}">
                    <div class="form-role-area">
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="category_id">Parent Category</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select class="form-control" name="category_id" id="category_id">
                                <option value="">Select Parent Category</option>
                                @foreach($categories as $key => $category)
                                  <option value="{{ $key }}" {{ (old('category_id', $subCategory->category_id ?? '') == $key) ? 'selected="selected"' : '' }}>{{ $category }}</option>
                                @endforeach
                              </select>
                              <label class="mt-2 text-danger">{{ $errors->first('category_id') }}</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="name">Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Name"
                                value="{{ old('name', $subCategory->name ?? '') }}" maxlength="50">
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
                                placeholder="Name" value="{{ old('name_french', $subCategory->name_french ?? '') }}" maxlength="50">
                              <label class="mt-2 text-danger">{{ $errors->first('name_french') }}</label>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="sub_category_order">Sub Category Order</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="sub_category_order" id="sub_category_order" type="number"
                                placeholder="Sub Category Order" value="{{ old('sub_category_order', $subCategory->sub_category_order ?? '') }}">
                              <label class="mt-2 text-danger">{{ $errors->first('sub_category_order') }}</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="content">Sub Category Dispersion</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea class="form-control" name="sub_category_dispersion"
                              id="content">{{ old('sub_category_dispersion', $subCategory->sub_category_dispersion ?? '') }}</textarea>
                            {{ $errors->first('sub_category_dispersion') ? '<label class="mt-2 text-danger">' . $errors->first('sub_category_dispersion') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="content1">French Sub Category Dispersion</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea class="form-control" name="sub_category_dispersion_french" id="content1">{{ old('sub_category_dispersion_french', $subCategory->sub_category_dispersion_french ?? '') }}</textarea>
                            {{ $errors->first('sub_category_dispersion_french') ? '<label class="mt-2 text-danger">' . $errors->first('sub_category_dispersion_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="page_title">SubCategory Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title" id="page_title" type="text"
                              placeholder="Page Title"
                              value="{{ old('page_title', $subCategory->page_title ?? '') }}"
                              maxlength="250">
                            {{ $errors->first('page_title') ? '<label class="mt-2 text-danger">' . $errors->first('page_title') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="page_title_french">French SubCategory Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title_french" id="page_title_french" type="text"
                              placeholder="French Page Title"
                              value="{{ old('page_title_french', $subCategory->page_title_french ?? '') }}"
                              maxlength="250">
                            {{ $errors->first('page_title_french') ? '<label class="mt-2 text-danger">' . $errors->first('page_title_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content">SubCategory Page Meta Description Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_description_content" id="meta_description_content"
                              rows="100">{{ old('meta_description_content', $subCategory->meta_description_content ?? '') }}</textarea>
                            {{ $errors->first('meta_description_content') ? '<label class="mt-2 text-danger">' . $errors->first('meta_description_content') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content_french">France SubCategory Page Meta Description Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_description_content_french" id="meta_description_content_french"
                              rows="100">{{ old('meta_description_content_french', $subCategory->meta_description_content_french ?? '') }}</textarea>
                            {{ $errors->first('meta_description_content_french') ? '<label class="mt-2 text-danger">' . $errors->first('meta_description_content_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_keywords_content">SubCategory Page Meta Keywords Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_keywords_content" id="meta_keywords_content"
                              rows="100">{{ old('meta_keywords_content', $subCategory->meta_keywords_content ?? '') }}</textarea>
                            {{ $errors->first('meta_keywords_content') ? '<label class="mt-2 text-danger">' . $errors->first('meta_keywords_content') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_keywords_content_french">France SubCategory Page Meta Keywords Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_keywords_content_french" id="meta_keywords_content_french"
                              rows="100">{{ old('meta_keywords_content_french', $subCategory->meta_keywords_content_french ?? '') }}</textarea>
                            {{ $errors->first('meta_keywords_content_french') ? '<label class="mt-2 text-danger">' . $errors->first('meta_keywords_content_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="show_main_menu">Show Sub Category</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input type="checkbox" name="show_main_menu" value="1"
                              {{ old('show_main_menu', $subCategory->show_main_menu ?? 0) ? 'checked' : '' }}> Show Main Menu
                            {{ $errors->first('show_main_menu') ? '<label class="mt-2 text-danger">' . $errors->first('show_main_menu') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="text-right">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="{{ url('admin/Categories/subCategories') }}" class="btn btn-success">Back</a>
                      </div>
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
CKEDITOR.replace('content', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*]',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

CKEDITOR.replace('content1', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*]',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
</script>
@endsection
