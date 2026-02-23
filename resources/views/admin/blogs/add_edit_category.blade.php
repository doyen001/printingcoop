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
                              <label class="span2" for="store_ids">WebSite</label>
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

                                  <input id="store_ids" name="store_id[]" type="checkbox" value="{{ $key }}" {{ $checked }}>
                                  <label style="margin-left:5px;">{{ $val['name'] }}</label>
                                @endforeach
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="control-group info">
                          <div class="row align-items-center">
                            <div class="col-md-4">
                              <label class="span2" for="category_name">Category Name</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <input class="form-control" name="category_name" id="category_name" type="text" placeholder="category name" value="{{ $postData['category_name'] ?? '' }}">
                                {{ $errors->first('category_name') ? '<label class="mt-2 text-danger">' . $errors->first('category_name') . '</label>' : '' }}
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="control-group info">
                          <div class="row align-items-center">
                            <div class="col-md-4">
                              <label class="span2" for="category_name_french">French Category Name</label>
                            </div>
                            <div class="col-md-8">
                              <div class="controls">
                                <input class="form-control" name="category_name_french" id="category_name_french" type="text" placeholder="category name french" value="{{ $postData['category_name_french'] ?? '' }}">
                                {{ $errors->first('category_name_french') ? '<label class="mt-2 text-danger">' . $errors->first('category_name_french') . '</label>' : '' }}
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
                              <label class="span2" for="page_title_french">French Category Page Title</label>
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
                              <label class="span2" for="meta_description_content">Category Page Meta Description Content</label>
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
                              <label class="span2" for="meta_description_content_french">France Category Page Meta Description Content</label>
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
                              <label class="span2" for="meta_keywords_content">Category Page Meta Keywords Content</label>
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
                              <label class="span2" for="meta_keywords_content_french">France Category Page Meta Keywords Content</label>
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
                          <div class="text-right">
                            <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                            <a href="{{ url('admin/Blogs/category') }}" class="btn btn-success">Back</a>
                          </div>
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
