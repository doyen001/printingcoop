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
                  @if ($errors->any())
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                  @endif
                  
                  <form method="POST" action="{{ route('categories.addEdit', $category->id ?? null) }}" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $category->id ?? '' }}">
                    
                    <div class="form-role-area">
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="name">Name <span class="text-danger">*</span></label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Category Name"
                                value="{{ old('name', $category->name ?? '') }}" maxlength="50" required>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="name_french">French Name <span class="text-danger">*</span></label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name_french" id="name_french" type="text"
                                placeholder="French Category Name"
                                value="{{ old('name_french', $category->name_french ?? '') }}" maxlength="50" required>
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
                                value="{{ old('category_order', $category->category_order ?? 0) }}">
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
                              <div class="form-check">
                                <input type="checkbox" name="show_our_printed_product" value="1" 
                                  {{ old('show_our_printed_product', $category->show_our_printed_product ?? 0) ? 'checked' : '' }}>
                                <label class="form-check-label">Our Printed Product</label>
                              </div>
                              <div class="form-check">
                                <input type="checkbox" name="show_main_menu" value="1"
                                  {{ old('show_main_menu', $category->show_main_menu ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label">Show Main Menu</label>
                              </div>
                              <div class="form-check">
                                <input type="checkbox" name="show_footer_menu" value="1"
                                  {{ old('show_footer_menu', $category->show_footer_menu ?? 1) ? 'checked' : '' }}>
                                <label class="form-check-label">Show Footer Menu</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

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
CKEDITOR.replace('category_dispersion', {
  height: 300,
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});

CKEDITOR.replace('category_dispersion_french', {
  height: 300,
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
</script>
@endsection
