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
                      value="{{ $postData['id'] ?? '' }}">
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
                            <label class="span2" for="title">Page Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="title" id="title" type="text" placeholder="Page Name"
                                value="{{ $postData['title'] ?? '' }}" maxlength="50">
                              {{ $errors->first('title') ? '<label class="mt-2 text-danger">' . $errors->first('title') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="title_french">French Page Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="title_french" id="title_french" type="text"
                                placeholder="Page Name"
                                value="{{ $postData['title_french'] ?? '' }}"
                                maxlength="50">
                              {{ $errors->first('title_french') ? '<label class="mt-2 text-danger">' . $errors->first('title_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="shortOrder">Page Order</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="shortOrder" id="shortOrder" type="number"
                                placeholder="Page Order"
                                value="{{ $postData['shortOrder'] ?? '' }}" maxlength="50">
                              {{ $errors->first('shortOrder') ? '<label class="mt-2 text-danger">' . $errors->first('shortOrder') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="display_on_top_menu">Show On Menu</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input type="checkbox" name="display_on_top_menu" value="1"
                                {{ ($postData['display_on_top_menu'] ?? 0) ? 'checked' : '' }}>
                              {{ $errors->first('display_on_top_menu') ? '<label class="mt-2 text-danger">' . $errors->first('display_on_top_menu') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="slug">Page Slug</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="slug" id="slug" type="text"
                                placeholder="Page Slug"
                                value="{{ $postData['slug'] ?? '' }}"
                                maxlength="250">
                              {{ $errors->first('slug') ? '<label class="mt-2 text-danger">' . $errors->first('slug') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="page_title">Page Title</label>
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
                            <label class="span2" for="page_title_french">French Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title_french" id="page_title_french" type="text"
                                placeholder="French Page Title"
                                value="{{ $postData['page_title_french'] ?? '' }}"
                                maxlength="50">
                              {{ $errors->first('page_title_french') ? '<label class="mt-2 text-danger">' . $errors->first('page_title_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content">Page Meta Description Content</label>
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
                            <label class="span2" for="meta_description_content_french">France Page Meta Description Content</label>
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
                            <label class="span2" for="meta_keywords_content">Page Meta Keywords Content</label>
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
                            <label class="span2" for="meta_keywords_content_french">France Page Meta Keywords Content</label>
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
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-12">
                            <label class="span2" for="content">Page Description</label>
                          </div>
                          <div class="col-md-12">
                            <div class="controls">
                              <textarea name="description" id="content"
                                rows="100">{{ $postData['description'] ?? '' }}</textarea>
                              {{ $errors->first('description') ? '<label class="mt-2 text-danger">' . $errors->first('description') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-12">
                            <label class="span2" for="content1">French Page Description</label>
                          </div>
                          <div class="col-md-12">
                            <div class="controls">
                              <textarea name="description_french" id="content1"
                                rows="100">{{ $postData['description_french'] ?? '' }}</textarea>
                              {{ $errors->first('description_french') ? '<label class="mt-2 text-danger">' . $errors->first('description_french') . '</label>' : '' }}
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="text-right">
                      <button type="submit" class="btn btn-success">Submit</button>
                      <a href="{{ url('admin/Pages') }}" class="btn btn-success">Back</a>
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
CKEDITOR.replace('content', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
  removePlugins: 'uploadwidget,uploadimage,filetools',
  filebrowserBrowseUrl: '',
  filebrowserImageBrowseUrl: '',
  filebrowserFlashBrowseUrl: ''
});

CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('content1', {
  height: 300,
  filebrowserUploadUrl: "{{ url('admin/uploadImage') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
  removePlugins: 'uploadwidget,uploadimage,filetools',
  filebrowserBrowseUrl: '',
  filebrowserImageBrowseUrl: '',
  filebrowserFlashBrowseUrl: ''
});
CKEDITOR.dtd.$removeEmpty.i = 0;
</script>
@endsection
