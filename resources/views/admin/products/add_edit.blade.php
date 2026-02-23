@extends('layouts.admin')

@php
// Define variables that were passed from CI controller
$class_name = 'admin/products/';
$main_page_url = '';

function showValue($val)
{
    $explode = explode(".", $val);
    $newVal = '';
    if (empty($val) || $val == '0' || $val == '0.0' || $val == '0.00' || $val == '0.000' || $val == '0.0000') {
        return $newVal;
    } else if (empty($explode[1]) || $explode[1] == '0' || $explode[1] == '00' || $explode[1] == '000' || $explode[1] == '0000') {
        $newVal = $explode[0];
    } else if (!empty($explode[1])) {
        $d1 = substr($explode[1], 0, 1);
        $d2 = substr($explode[1], 1, 1);
        $d3 = substr($explode[1], 2, 1);
        $d4 = substr($explode[1], 3, 1);

        if ($d1 != 0 && $d2 == 0 && $d3 == 0 && $d4 == 0) {
            $newVal = $explode[0] . "." . $d1;
        } else if ($d1 == 0 && $d2 != 0 && $d3 == 0 && $d4 == 0) {
            $newVal = $explode[0] . "." . $d1 . $d2;
        } else if ($d1 == 0 && $d2 == 0 && $d3 != 0 && $d4 == 0) {
            $newVal = $explode[0] . "." . $d1 . $d2 . $d3;
        } else if ($d1 == 0 && $d2 == 0 && $d3 == 0 && $d4 != 0) {
            $newVal = $explode[0] . "." . $d1 . $d2 . $d3 . $d4;
        } else if ($d1 != 0 && $d2 != 0 && $d3 != 0 && $d4 != 0) {
            $newVal = $val;
        } else if ($d1 != 0 && $d2 != 0 && $d3 == 0 && $d4 == 0) {
            $newVal = $explode[0] . "." . $d1 . $d2;
        } else if ($d1 == 0 && $d2 != 0 && $d3 != 0 && $d4 == 0) {
            $newVal = $explode[0] . "." . $d1 . $d2 . $d3;
        } else if ($d1 == 0 && $d2 != 0 && $d3 == 0 && $d4 != 0) {
            $newVal = $explode[0] . "." . $d1 . $d2 . $d3 . $d4;
        }
    }
    if (!empty($newVal)) {
        return $newVal;
    } else {
        return $val;
    }
}
@endphp

@push('styles')
<style type="text/css">
.controls.small-control {
  position: relative;
}

.entrynew.input-group .form-control {
  width: 100px;
}

.attribute-inner,
.attribute-info-inner {
  text-align: center;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: flex-end;
}

.attribute-info .row .col-md-6:nth-child(2) .attribute-info-inner {
  justify-content: flex-start;
}

#WidthAndLengthSection .attribute-info .row .col-md-6:nth-child(2) .attribute-info-inner {
  justify-content: flex-end;
}

.for-att-multi .attribute-info .row .col-md-6:nth-child(2) .attribute-info-inner {
  justify-content: flex-end;
}

.for-att-multi .attribute-info .row .col-md-12 .attribute-info-inner {
  padding-left: 0px;
  margin-top: 5px;
}

.attribute-inner label,
.attribute-info-inner label {
  margin: 0px !important;
  padding-right: 5px;
}

.control-group .attribute-inner input,
.control-group .attribute-info-inner input {
  height: 30px !important;
  padding: 5px 5px !important;
  color: #000;
  background-color: rgb(255, 255, 255);
  background-image: none;
  border: 1px solid #ccc !important;
  border-radius: 4px !important;
  font-size: 13px;
  width: 80px;
  text-align: center;
}

.control-group .attribute-inner input[type="checkbox"],
.control-group .attribute-info-inner input[type="checkbox"] {
  width: auto;
  height: auto !important;
}

.control-group .controls.small-controls .attribute-info-inner label.span2 {
  margin: 0px !important;
  display: flex;
  justify-content: flex-end;
  width: 100%;
  height: auto !important;
}

.control-group .attribute-info-inner select {
  height: 30px !important;
  padding: 5px 5px !important;
  color: #000;
  background-color: rgb(255, 255, 255);
  background-image: none;
  border: 1px solid #ccc !important;
  border-radius: 4px !important;
  font-size: 13px;
  width: 100%;
  text-align: left;
}

.attribute-row {
  padding: 0px;
  background: #f9f9f9;
  height: 0px;
  overflow: hidden;
  margin-bottom: 0px;
}

.attribute-row.field-area {
  padding: 10px 10px 10px 10px;
  background: #f9f9f9;
  height: auto;
}

.attribute.active .attribute-row {
  padding: 10px 10px 10px 25px;
  background: #f9f9f9;
  height: auto;
  margin-bottom: 10px;
}

.attribute-info {
  background: #fff;
  padding: 5px 5px;
  margin-bottom: 10px;
}

.attribute-info-inner {
  padding: 0px 0px 0px 20px;
}

.attribute-title {
  background: #f1f1f1;
  padding: 5px 10px;
}

.attribute {
  padding-bottom: 10px;
}

.controls.small-controls .attribute:last-child {
  margin: 0px;
  padding: 0px;
}

.control-group .controls.small-controls .attribute-title .span2 {
  margin-bottom: 0px !important;
}
</style>
@endpush

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
                  <form method="POST" action="{{ url('admin/Products/addEdit') . ($postData['id'] ?? '' ? '/' . $postData['id'] : '') }}" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <input class="form-control" name="id" type="hidden"
                      value="{{ $postData['id'] ?? '' }}" id="product_id">
                    <div class="form-role-area">

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-3">
                            <label class="span2" for="name">Product Name</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <input class="form-control" name="name" id="name" type="text"
                                placeholder="Product Name"
                                value="{{ $postData['name'] ?? '' }}"
                                required>
                              @error('name')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-3">
                            <label class="span2" for="name_french">French Product Name</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <input class="form-control" name="name_french" id="name_french"
                                type="text" placeholder="French Product Name"
                                value="{{ $postData['name_french'] ?? '' }}"
                                required>
                              @error('name_french')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-3">
                            <label class="span2" for="short_description">Product Short Description</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <textarea class="form-control"
                                name="short_description">{{ $postData['short_description'] ?? '' }}</textarea>
                              @error('short_description')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-3">
                            <label class="span2" for="short_description_french">French Product Short Description</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <textarea class="form-control"
                                name="short_description_french">{{ $postData['short_description_french'] ?? '' }}</textarea>
                              @error('short_description_french')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="full_description">Product Full Description</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <textarea class="form-control" name="full_description"
                                id="content">{{ $postData['full_description'] ?? '' }}</textarea>
                              @error('full_description')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="page_title">product Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title" id="page_title" type="text"
                                placeholder="Page Title"
                                value="{{ $postData['page_title'] ?? '' }}"
                                maxlength="250">
                              @error('page_title')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="page_title_french">French product Page Title</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="page_title_french" id="page_title_french" type="text"
                                placeholder="French Page Title"
                                value="{{ $postData['page_title_french'] ?? '' }}"
                                maxlength="250">
                              @error('page_title_french')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content">product Page Meta Description Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_description_content" id="meta_description_content"
                                rows="100">{{ $postData['meta_description_content'] ?? '' }}</textarea>

                              @error('meta_description_content')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_description_content_french">product Category Page Meta Description Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_description_content_french" id="meta_description_content_french"
                                rows="100">{{ $postData['meta_description_content_french'] ?? '' }}</textarea>

                              @error('meta_description_content_french')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_keywords_content">product Page Meta Keywords Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_keywords_content" id="meta_keywords_content"
                                rows="100">{{ $postData['meta_keywords_content'] ?? '' }}</textarea>

                              @error('meta_keywords_content')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="meta_keywords_content_french">France product Page Meta Keywords Content</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="meta_keywords_content_french" id="meta_keywords_content_french"
                                rows="100">{{ $postData['meta_keywords_content_french'] ?? '' }}</textarea>

                              @error('meta_keywords_content_french')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="full_description_french">French Product Full Description</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <textarea class="form-control" name="full_description_french"
                                id="content1">{{ $postData['full_description_french'] ?? '' }}</textarea>
                              @error('full_description_french')
                                <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="description">Extra Product Full Description</label>
                          </div>
                          <div class="col-md-9 description-data">
                            @php
                            $DescriptionsIds = 1;
                            @endphp
                            @if(!empty($ProductDescriptions))
                              @php
                              $last = count($ProductDescriptions);
                              $last = $last - 1;
                              @endphp

                              @foreach($ProductDescriptions as $key => $val)
                                <div class="controls description-class ddata">
                                  <div class="description-single">
                                    @php
                                    $displayplusnbtn = 'none';
                                    $displayminusbtn = '';
                                    if ($last == 0) {
                                      $displayplusnbtn = '';
                                      $displayminusbtn = 'none';
                                    } else if ($last == $key) {
                                      $displayplusnbtn = '';
                                      $displayminusbtn = '';
                                    }
                                    @endphp
                                    <div class="add-new-btn">
                                      <button class="btn-danger dbtn-remove" type="button"
                                        style="display:{{ $displayminusbtn }}">
                                        <i class="fa fa-minus"></i>
                                      </button>

                                      <button class="btn-success dbtn-add" type="button"
                                        style="display:{{ $displayplusnbtn }}">
                                        <i class="fa fa-plus"></i>
                                      </button>
                                    </div>
                                    <label>Description Title</label>
                                    <input type="text" class="form-control"
                                      placeholder="Description Title" name="title[]"
                                      value="{{ is_array($val) ? $val['title'] : $val->title }}">

                                    <label>French Description Title</label>

                                    <input type="text" class="form-control"
                                      placeholder="Title french" name="title_french[]"
                                      value="{{ is_array($val) ? $val['title_french'] : $val->title_french }}">

                                    <label>Description</label>

                                    <textarea class="form-control description ckeditor"
                                      name="description[]" placeholder="Full Description"
                                      id="editor{{ $DescriptionsIds }}">{{ is_array($val) ? $val['description'] : $val->description }}</textarea>
                                    <label>French Description</label>
                                    <textarea class="form-control description-f ckeditor"
                                      name="description_french[]"
                                      placeholder="Description French"
                                      id="editorf{{ $DescriptionsIds }}">{{ is_array($val) ? $val['description_french'] : $val->description_french }}</textarea>
                                  </div>
                                </div>

                                @php
                                $DescriptionsIds++;
                                @endphp
                              @endforeach
                            @else
                              <div class="controls description-class ddata">
                                <div class="description-single">
                                  <div class="add-new-btn">
                                    <button class="btn-danger dbtn-remove" type="button" style="display:none">
                                      <i class="fa fa-minus"></i>
                                    </button>
                                    <button class="btn-success dbtn-add" type="button">
                                      <i class="fa fa-plus"></i>
                                    </button>
                                  </div>
                                  <label>Description Title</label>
                                  <input type="text" class="form-control" placeholder="Description Title" name="title[]">
                                  <label>French Description Title</label>

                                  <input type="text" class="form-control" placeholder="Title french" name="title_french[]">
                                  <label>Description</label>
                                  <textarea class="form-control description ckeditor" name="description[]" placeholder="Full Description" id="editor"></textarea>
                                  <label>French Description</label>
                                  <textarea class="form-control ckeditor description-f" name="description_french[]" placeholder="Description French" id="editorf"></textarea>
                                </div>
                              </div>
                            @endif

                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="template">Product Templates</label>
                          </div>
                          <div class="col-md-9 template-description">
                            @if(!empty($ProductTemplates))
                              @php
                              $last = count($ProductTemplates);
                              $last = $last - 1;
                              @endphp

                              @foreach($ProductTemplates as $key => $val)
                                <div class="controls description-class tmds">
                                  <div class="description-single">
                                    @php
                                    $displayplusnbtn = 'none';
                                    $displayminusbtn = '';
                                    if ($last == 0) {
                                      $displayplusnbtn = '';
                                      $displayminusbtn = 'none';
                                    } else if ($last == $key) {
                                      $displayplusnbtn = '';
                                      $displayminusbtn = '';
                                    }
                                    @endphp
                                    <div class="add-new-btn">
                                      <button class="btn-danger tdtn-remove" type="button"
                                        style="display:{{ $displayminusbtn }}">
                                        <i class="fa fa-minus"></i>
                                      </button>

                                      <button class="btn-success tdtn-add" type="button"
                                        style="display:{{ $displayplusnbtn }}">
                                        <i class="fa fa-plus"></i>
                                      </button>
                                    </div>

                                    <label>Final Dimensions</label>
                                    <input type="text" class="form-control"
                                      placeholder="Final Dimensions" name="final_dimensions[]"
                                      value='{{ is_array($val) ? $val['final_dimensions'] : $val->final_dimensions }}'>

                                    <label>French Final Dimensions</label>
                                    <input type="text" class="form-control"
                                      placeholder="Final Dimensions French"
                                      name="final_dimensions_french[]"
                                      value='{{ is_array($val) ? $val['final_dimensions_french'] : $val->final_dimensions_french }}'>

                                    <label>Template Description</label>
                                    <textarea class="form-control" name="template_description[]"
                                      placeholder="Template Description">{{ is_array($val) ? $val['template_description'] : $val->template_description }}</textarea>

                                    <label>French Template Description</label>
                                    <textarea class="form-control"
                                      name="template_description_french[]"
                                      placeholder="French Template Description">{{ is_array($val) ? $val['template_description_french'] : $val->template_description_french }}</textarea>

                                    <input class="btn btn-primary" name="template_file_old[]"
                                      type="hidden" value="{{ is_array($val) ? $val['template_file'] : $val->template_file }}" />
                                    @if(is_array($val) ? $val['template_file'] : $val->template_file)
                                      @php
                                      $link = url('admin/orders/download/' . urlencode(config('constants.TEMPLATE_FILE_BASE_PATH') . (is_array($val) ? $val['template_file'] : $val->template_file)) . '/' . urlencode(is_array($val) ? $val['template_file'] : $val->template_file));
                                      @endphp
                                      <label class="file_name">File
                                        Name: {{ is_array($val) ? $val['template_file'] : $val->template_file }}
                                        <a href="{{ $link }}">
                                          <i class="fa fa-download" aria-hidden="true"></i>
                                        </a>
                                      </label>
                                    @endif<br>
                                    <input class="btn btn-primary" name="template_file[]"
                                      type="file"
                                      style="background-color:#3c8dbc !important;" />
                                  </div>
                                </div>
                              @endforeach
                            @else
                              <div class="controls description-class tmds">
                                <div class="description-single">
                                  <div class="add-new-btn">
                                    <button class="btn-danger tdtn-remove" type="button"
                                      style="display:none">
                                      <i class="fa fa-minus"></i>
                                    </button>

                                    <button class="btn-success tdtn-add" type="button">
                                      <i class="fa fa-plus"></i>
                                    </button>
                                  </div>
                                  <label>Final Dimensions</label>
                                  <input type="text" class="form-control"
                                    placeholder="Final Dimensions"
                                    name="final_dimensions[]">

                                  <label>French Final Dimensions</label>
                                  <input type="text" class="form-control"
                                    placeholder="Final Dimensions French"
                                    name="final_dimensions_french[]">
                                  <label>Template Description</label>
                                  <textarea class="form-control" name="template_description[]"
                                    placeholder="Template Description"></textarea>
                                  <label>French Template Description</label>
                                  <textarea class="form-control"
                                    name="template_description_french[]"
                                    placeholder="French Template Description"></textarea>

                                  <br>
                                  <input class="btn btn-primary" name="template_file_old[]"
                                    type="hidden" />
                                  <input class="btn btn-primary" name="template_file[]"
                                    type="file"
                                    style="background-color:#3c8dbc !important;" />
                                </div>
                              </div>
                            @endif

                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="price">Product Price</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <div class="row align-items-center">

                                <div class="col-md-6">
                                  <input class="form-control" name="price" id="price"
                                    type="text" placeholder="List Price CAD"
                                    value="{{ isset($postData['price']) ? showValue($postData['price']) : '' }}"
                                    required>
                                  <label class="form-inner-label">List Price CAD</label>
                                  @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="code">Product Attributes</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              <div class="row">
                                <div class="col-md-6">
                                  <input class="form-control" name="code" id="code"
                                    type="text" placeholder="Product Code"
                                    value="{{ $postData['code'] ?? '' }}">
                                  <label class="form-inner-label">Code</label>
                                  @error('code')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                                <div class="col-md-6">
                                  <input class="form-control" name="code_french"
                                    id="code_french" type="text"
                                    placeholder="Product Code"
                                    value="{{ $postData['code_french'] ?? '' }}">
                                  <label class="form-inner-label">French Code</label>
                                  @error('code_french')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>

                                <div class="col-md-6">
                                  <input class="form-control" name="model" id="model"
                                    type="text" placeholder="Product Model"
                                    value="{{ $postData['model'] ?? '' }}">
                                  <label class="form-inner-label">Models</label>
                                  @error('model')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                                <div class="col-md-6">
                                  <input class="form-control" name="model_french"
                                    id="model_french" type="text"
                                    placeholder="Product model french"
                                    value="{{ $postData['model_french'] ?? '' }}">
                                  <label class="form-inner-label">French Models</label>
                                  @error('model_french')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>

                                <div class="col-md-6">
                                  @php
                                  $is_stock = $postData['is_stock'] ?? '';
                                  $cehecked = '';
                                  if ($is_stock == 1) {
                                    $cehecked = 'checked';
                                  }
                                  @endphp
                                  <label class="span2"><input name="is_stock"
                                      id="is_stock" type="checkbox" value="1"
                                      {{ $cehecked }}>Show Out of Stock</label>
                                  @error('is_stock')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="tag">Product Tags</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls small-controls">

                              <div class="row">
                                @php
                                $product_tags = isset($postData['product_tag']) ? explode(',', $postData['product_tag']) : array();
                                @endphp
                                @foreach($tagList as $key => $val)
                                  @php
                                  $tag_id = is_array($val) ? $val['id'] : $val->id;
                                  $tag_name = is_array($val) ? $val['name'] : $val->name;
                                  @endphp
                                  <div class="col-md-4">
                                    @php
                                    $cehecked = '';
                                    if (in_array($tag_id, $product_tags)) {
                                      $cehecked = 'checked';
                                    }
                                    @endphp
                                    <label class="span2"><input name="product_tag[]"
                                        type="checkbox" value="{{ $tag_id }}"
                                        {{ $cehecked }}>
                                      {{ $tag_name }}
                                    </label>
                                    @error('product_tag[]')
                                      <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                  </div>
                                @endforeach

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="product_call">Call</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls small-controls">
                              <div class="row">
                                <div class="col-md-12">
                                  @php
                                  $cehecked = '';
                                  if (($postData['call'] ?? 0) == 1) {
                                    $cehecked = 'checked';
                                  }
                                  @endphp
                                  <label class="span2"><input name="call" type="checkbox"
                                      value="1" {{ $cehecked }}
                                      onchange="pageShowCall(product_call)"
                                      id="product_call">Add
                                  </label>
                                  @error('call')
                                    <span class="text-danger">{{ $message }}</span>
                                  @enderror
                                </div>
                              </div>
                              <div class="attribute-row field-area"
                                style="display:{{ $cehecked == 'checked' ? '' : 'none' }}"
                                id="PagePhoneSection">
                                <div class="row">
                                  <div class="col-md-6 col-lg-6 col-xl-6">
                                    <div class="attribute-info">
                                      <div class="row align-items-center">
                                        <div class="col-md-6">
                                          <label class="form-inner-label">Phone
                                            Number</label>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="attribute-info-inner">
                                            <input type="text"
                                              value="{{ $postData['phone_number'] ?? '1-877-384-8043' }}"
                                              name="phone_number"
                                              maxlength="15"
                                              style="width:100%">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3">
                            <label class="span2" for="shipping_box_length">Shipping Box Size</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls small-controls">
                              <div class="row">
                              </div>
                              <div class="attribute-row field-area">
                                <div class="row">
                                  <div class="col-md-6 col-lg-6 col-xl-6">
                                    <div class="attribute-info">
                                      <div class="row align-items-center">
                                        <div class="col-md-6">
                                          <label class="form-inner-label">Box
                                            Length (Inch)</label>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="attribute-info-inner">
                                            <input type="text"
                                              value="{{ showValue($postData['shipping_box_length'] ?? '') }}"
                                              id="shipping_box_length"
                                              name="shipping_box_length"
                                              onkeypress="javascript:return isNumber(event)"
                                              placeholder="Length"
                                              class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 col-lg-6 col-xl-6">
                                    <div class="attribute-info">
                                      <div class="row align-items-center">
                                        <div class="col-md-6">
                                          <label class="shipping_box_width">Box Width (Inch)</label>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="attribute-info-inner">
                                            <input type="text"
                                              value="{{ showValue($postData['shipping_box_width'] ?? '') }}"
                                              name="shipping_box_width"
                                              onkeypress="javascript:return isNumber(event)"
                                              placeholder="Width"
                                              class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 col-lg-6 col-xl-6">
                                    <div class="attribute-info">
                                      <div class="row align-items-center">
                                        <div class="col-md-6">
                                          <label class="form-inner-label">Box
                                            Height (Inch)</label>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="attribute-info-inner">
                                            <input type="text"
                                              value="{{ showValue($postData['shipping_box_height'] ?? '') }}"
                                              name="shipping_box_height"
                                              onkeypress="javascript:return isNumber(event)"
                                              placeholder="Height"
                                              class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 col-lg-6 col-xl-6">
                                    <div class="attribute-info">
                                      <div class="row align-items-center">
                                        <div class="col-md-6">
                                          <label class="form-inner-label">Box
                                            Weight (LB)</label>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="attribute-info-inner">
                                            <input type="text"
                                              value="{{ showValue($postData['shipping_box_weight'] ?? '') }}"
                                              name="shipping_box_weight"
                                              onkeypress="javascript:return isNumber(event)"
                                              placeholder="Weight"
                                              class="form-control">
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3" style="">
                            <label class="span2" for="category_id">Select Product Multiple Category</label>
                          </div>

                          <div class="col-md-9">
                            <div class="controls small-controls">
                              @foreach($Categoty as $qkey => $qval)
                                @php
                                $category_id = is_array($qval) ? $qval['id'] : $qval->id;
                                $category_name = is_array($qval) ? $qval['name'] : $qval->name;
                                $sub_categories = is_array($qval) ? $qval['sub_categories'] : $qval->sub_categories;
                                $ProductSubCategory = isset($ProductCategory[$category_id]) ? $ProductCategory[$category_id] : array();
                                @endphp
                                <div class="attribute">
                                  <div class="attribute-title">
                                    <div class="row align-items-center">
                                      <div class="col-md-12">
                                        <label class="span2">
                                          <input type="checkbox"
                                            value="{{ $category_id }}"
                                            name="category_id_{{ $category_id }}"
                                            id="category_id_{{ $category_id }}"
                                            @if(array_key_exists($category_id, $ProductCategory)) checked @endif
                                            onchange="addActiveCategory('{{ $category_id }}')"
                                            class="Category-Ids">
                                          {{ $category_name }}
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="attribute"
                                    id="quantity_attribute_id_div_{{ $category_id }}"
                                    style="display:{{ array_key_exists($category_id, $ProductCategory) ? '' : 'none' }}; padding: 10px 10px 10px 25px; background: #f5f5f5">
                                    @foreach($sub_categories as $key => $val)
                                      @php
                                      $sub_category_id = is_array($val) ? $val['id'] : $val->id;
                                      $sub_category_name = is_array($val) ? $val['name'] : $val->name;
                                      @endphp
                                      <div class="attribute">

                                        <div class="attribute-title">
                                          <div class="row align-items-center">
                                            <div class="col-md-12">
                                              <label class="span2">
                                                <input type="checkbox"
                                                  value="{{ $sub_category_id }}"
                                                  name="sub_category_id_{{ $category_id }}_{{ $sub_category_id }}"
                                                  id="sub_category_id_{{ $category_id }}_{{ $sub_category_id }}"
                                                  @if(in_array($sub_category_id, $ProductSubCategory)) checked @endif>
                                                {{ $sub_category_name }}
                                              </label>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    @endforeach
                                  </div>
                                </div>
                              @endforeach
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-3" style="">
                            <label class="span2" for="upload_image">Upload Product Image</label>
                          </div>
                          <div class="col-md-9">
                            <div class="controls">
                              @foreach($ProductImages as $key => $list)
                                <div class="col-xs-4" style="margin-bottom:15px;"
                                  id="img_{{ is_array($list) ? $list['id'] : $list->id }}">
                                  @php $imageurl = getProductImage(is_array($list) ? $list['image'] : $list->image); @endphp
                                  <img src="{{ $imageurl }}" width="100" height="80">
                                  <input name="old_image[]" value="{{ is_array($list) ? $list['image'] : $list->image }}" type="hidden">
                                  &nbsp;&nbsp;
                                  <span class="input-group-btn">
                                    <button class="btn btn-danger" type="button"
                                      title="remove image"
                                      onclick="remove_image('{{ is_array($list) ? $list['id'] : $list->id }}','{{ is_array($list) ? $list['image'] : $list->image }}')"
                                      id="img_remove_btn">
                                      <span class="fa fa-minus"></span>
                                    </button>
                                  </span>
                                </div>
                              @endforeach
                            </div>
                            <div class="controls file-data">
                              <div class="image-info col-xs-12" style="margin-bottom: 10px;">
                                <span>
                                  Allowed image type : <b> (jpg, png, gif)</b>
                                </span><br>
                              </div>
                              <div class="entry input-group col-xs-12"
                                style="margin-bottom:15px;">
                                <img id="fileUpload-1-Image"
                                  src="{{ asset('assets/images/no-image.png') }}"
                                  alt="preview image" width="100" height="80" />
                                <input class="btn btn-primary" name="files[]" type="file"
                                  accept="image/x-png,image/gif,image/jpeg,image/jpg"
                                  id="fileUpload-1"
                                  onchange="return Upload('fileUpload-1')" />
                                &nbsp;&nbsp;
                                <span class="input-group-btn">

                                  <button class="btn btn-danger btn-remove" type="button"
                                    style="display:none">
                                    <span class="fa fa-minus"></span>
                                  </button>
                                  <button class="btn btn-success btn-add" type="button">
                                    <span class="fa fa-plus"></span>
                                  </button>
                                </span>
                              </div>
                              <div style="color:red">
                                @if(session('file_message_error'))
                                  {{ session('file_message_error') }}
                                @endif
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="product-actions-btn text-right">
                        <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                        <a href="{{ url($class_name . $main_page_url) }}"
                          class="btn btn-success">Back</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
<script>
$(document).ready(function() {
function isNumber(evt) {
  var iKeyCode = (evt.which) ? evt.which : evt.keyCode
  if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
    return false;

  return true;
}

function addActiveClass(id) {
  if ($('#attribute_id_' + id).prop('checked') == true) {
    $('#attribute_id_' + id).parents('.attribute').find('.attribute-inner').addClass('active');
  } else {
    $('#attribute_id_' + id).parents('.attribute').find('.attribute-inner').removeClass('active');
  }
}

var default_url_image = '{{ asset('assets/images/no-image.png') }}'

$('#menu_id').on('change', function(e) {
  var menu_id = $(this).val();
  $('#category_id').html('<option value="">Select Category</option>');
  $('#sub_category_id').html('<option value="">Select Sub Category</option>');
  $.ajax({
    type: 'GET',
    dataType: 'html',
    url: '{{ url('admin/ajax/getCategoryDropDownListByAjax') }}/' + menu_id,
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
      $('#category_id').html(data);
    }
  });
});

$('#category_id').on('change', function(e) {
  $('#sub_category_id').html('<option value="">Select Sub Category</option>');

  var menu_id = $('#menu_id').val();
  var category_id = $(this).val();
  $('#sub_category_id').html('<option value="">Select Sub Category</option>');
  $.ajax({
    type: 'GET',
    dataType: 'html',
    url: '{{ url('admin/ajax/getSubCategoryDropDownListByAjax') }}/' + category_id,
    cache: false,
    contentType: false,
    processData: false,
    success: function(data) {
      $('#sub_category_id').html(data);
    }
  });
});

$(function() {
  $(document).on('click', '.btn-add', function(e) {
    e.preventDefault();

    var controlForm = $('.file-data:first'),
      currentEntry = $(this).parents('.entry:first'),
      newEntry = $(currentEntry.clone()).appendTo(controlForm);
    newEntry.find('input').val('');
    newEntry.find('img').attr('src', default_url_image);
    var timestamp = new Date().getUTCMilliseconds();
    newEntry.find('input').attr('id', timestamp);
    newEntry.find('img').attr('id', timestamp + "-Image");
    var str = 'return Upload(' + timestamp + ')';
    newEntry.find('input').attr('onchange', str);

    newEntry.find('.btn-remove').show();
    controlForm.find('.btn-remove').show();
    controlForm.find('.btn-add').hide();
    newEntry.find('.btn-add').show();

  }).on('click', '.btn-remove', function(e) {
    var numItems = $('.file-data .entry').length;

    if (numItems == 1) {

      var controlForm = $('.file-data .entry').last();
      controlForm.find('input').val('');
      controlForm.find('img').attr('src', default_url_image);
      controlForm.find('.btn-remove').hide();
      controlForm.find('.btn-add').show();
    } else {
      $(this).parents('.entry:first').remove();
      e.preventDefault();
      var controlForm = $('.file-data .entry').last();
      controlForm.find('.btn-remove').show();
      controlForm.find('.btn-add').show();
    }
    return false;
  });
});

function remove_image(id, image_name) {
  $('#submitBtn').attr("disabled", true);
  $('#img_remove_btn').attr("disabled", true);
  var product_id = $('#product_id').val();
  $.ajax({
    type: 'POST',
    dataType: 'html',
    url: '{{ url('admin/ajax/removeProductImage') }}',
    data: {
      '_token': '{{ csrf_token() }}',
      'product_id': product_id,
      'id': id,
      'image_name': image_name
    },
    success: function(data) {
      if (data == 1) {
        $('#img_' + id).remove();
        $('#img_remove_btn').attr("disabled", false);
      } else {
        $('#img_remove_btn').attr("disabled", false);
      }

      $('#submitBtn').attr("disabled", false);
    },
    error: function(error) {
      $('img_remove_btn').attr("disabled", false);
      $('#submitBtn').attr("disabled", false);
    }
  });
});

function bntInActive(id) {
  $('#' + id).attr("disabled", true);
}
</script>
<script>
function Upload(imageId) {
  var fileUpload = document.getElementById(imageId);
  if (typeof(fileUpload.files) != "undefined") {
    var reader = new FileReader();
    reader.readAsDataURL(fileUpload.files[0]);
    reader.onload = function(e) {
      var image = new Image();

      image.src = e.target.result;
      $('#' + imageId + "-Image").attr('src', e.target.result);
      image.onload = function() {
        var height = this.height;
        var width = this.width;
        var imagesize = fileUpload.files[0].size;
        var FILE_MAX_SIZE_JS = '{{ config('constants.FILE_MAX_SIZE_JS', '') }}'
      };
    }
  }
}
</script>
<script>
$(document).ready(function() {
  $('#show-shipping-amount').click(function() {
    $('.shipping-amount-area').show();
  });
  $('#hide-shipping-amount').click(function() {
    $('.shipping-amount-area').hide();
  });
});

$(document).on('click', '.dbtn-add', function(e) {
  e.preventDefault();
  var controlForm = $('.description-data:first'),
    currentEntry = $(this).parents('.ddata:first'),
    newEntry = $(currentEntry.clone()).appendTo(controlForm);

  newEntry.find('input').val('');
  newEntry.find('textarea').val('');

  var timestamp = new Date().getTime() + Math.floor(Math.random() * 1000);
  newEntry.find('input').attr('id', timestamp);
  newEntry.find('.description').attr('id', "editor" + timestamp);
  newEntry.find('.description-f').attr('id', "editorf" + timestamp);
  newEntry.find('.cke_reset').remove();

  CKEDITOR.replace("editor" + timestamp, {
    height: 300,
    filebrowserUploadUrl: "{{ url('upload.php') }}",
    allowedContent: true,
    extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
  });
  CKEDITOR.dtd.$removeEmpty.i = 0;

  CKEDITOR.replace("editorf" + timestamp, {
    height: 300,
    filebrowserUploadUrl: "{{ url('upload.php') }}",
    allowedContent: true,
    extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
  });
  CKEDITOR.dtd.$removeEmpty.i = 0;

  newEntry.find('.dbtn-remove').show();
  controlForm.find('.dbtn-remove').show();
  controlForm.find('.dbtn-add').hide();
  newEntry.find('.dbtn-add').show();
}).on('click', '.dbtn-remove', function(e) {
  $(this).parents('.ddata:first').remove();
  e.preventDefault();

  var numItems = $('.description-data .ddata').length;

  var controlForm = $('.description-data .ddata').last();

  if (numItems == 1) {
    controlForm.find('.dbtn-remove').hide();
    controlForm.find('.dbtn-add').show();
  } else {
    controlForm.find('.dbtn-remove').show();
    controlForm.find('.dbtn-add').show();
  }
  return false;
});

$(document).ready(function() {
function AddRow(cr, id) {
  var controlForm = $('.' + id + 'SizeQuantity:first'),
    currentEntry = cr.parents('.' + id + 'sqddata:first'),
    newEntry = $(currentEntry.clone()).appendTo(controlForm);

  newEntry.find('input').val('');
  newEntry.find('textarea').val('');
  newEntry.find('select').val('');

  var timestamp = new Date().getUTCMilliseconds();
  newEntry.find('input').attr('id', timestamp);

  newEntry.find('.' + id + 'sqbtn-remove').show();
  controlForm.find('.' + id + 'sqbtn-remove').show();
  controlForm.find('.' + id + 'sqbtn-add').hide();
  newEntry.find('.' + id + 'sqbtn-add').show();
}

function RemoveRow(cr, id) {
  cr.parents('.' + id + 'sqddata:first').remove();

  var numItems = $('.' + id + 'SizeQuantity .' + id + 'sqddata').length;

  var controlForm = $('.' + id + 'SizeQuantity .' + id + 'sqddata').last();

  if (numItems == 1) {
    controlForm.find('.' + id + 'sqbtn-remove').hide();
    controlForm.find('.' + id + 'sqbtn-add').show();
  } else {
    controlForm.find('.' + id + 'sqbtn-remove').show();
    controlForm.find('.' + id + 'sqbtn-add').show();
  }
  return false;
}

$(document).on('click', '.tdtn-add', function(e) {
  e.preventDefault();
  var controlForm = $('.template-description:first'),
    currentEntry = $(this).parents('.tmds:first'),
    newEntry = $(currentEntry.clone()).appendTo(controlForm);

  newEntry.find('input').val('');
  newEntry.find('textarea').val('');
  newEntry.find('.file_name').remove();

  var timestamp = new Date().getTime() + Math.floor(Math.random() * 1000);
  newEntry.find('input').attr('id', timestamp);

  newEntry.find('.tdtn-remove').show();
  controlForm.find('.tdtn-remove').show();
  controlForm.find('.tdtn-add').hide();
  newEntry.find('.tdtn-add').show();
}).on('click', '.tdtn-remove', function(e) {
  $(this).parents('.tmds:first').remove();
  e.preventDefault();

  var numItems = $('.template-description .tmds').length;

  var controlForm = $('.template-description .tmds').last();

  if (numItems == 1) {
    controlForm.find('.tdtn-remove').hide();
    controlForm.find('.tdtn-add').show();
  } else {
    controlForm.find('.tdtn-remove').show();
    controlForm.find('.tdtn-add').show();
  }
  return false;
});

function isNumber(evt) {
  var iKeyCode = (evt.which) ? evt.which : evt.keyCode
  if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
    return false;

  return true;
}

function addActiveClass(id) {
  if ($('#attribute_id_' + id).prop('checked') == true) {
    $('#attribute_id_' + id).parents('.attribute').find('.attribute-inner').addClass('active');
  } else {
    $('#attribute_id_' + id).parents('.attribute').find('.attribute-inner').removeClass('active');
  }
}

function showWidthAndLength(id) {
  if ($(id).prop('checked') == true) {
    $('#WidthAndLengthSection').show();
  } else {
    $('#WidthAndLengthSection').hide();
  }
}

function showDepthWidthAndLength(id) {
  if ($(id).prop('checked') == true) {
    $('#DepthWidthAndLengthSection').show();
  } else {
    $('#DepthWidthAndLengthSection').hide();
  }
}

function pageShowWidthAndLength(id) {
  if ($(id).prop('checked') == true) {
    $('#PageWidthAndLengthSection').show();
  } else {
    $('#PageWidthAndLengthSection').hide();
  }
}

function pageShowCall(id) {
  if ($(id).prop('checked') == true) {
    $('#PagePhoneSection').show();
  } else {
    $('#PagePhoneSection').hide();
  }
}

function setAttributesetItemId(id) {
  if ($('#' + id).prop('checked') == true) {
    $('#hidden_' + id).val($('#' + id).val());
  } else {
    $('#hidden_' + id).val('');
  }
}

function RectoVersoSection(id) {
  if ($(id).prop('checked') == true) {
    $('#RectoVersoSection').show();
  } else {
    $('#RectoVersoSection').hide();
  }
}

function addActiveCategory(id) {
  if ($('#category_id_' + id).prop('checked') == true) {
    $('#quantity_attribute_id_div_' + id).show();
  } else {
    $('#quantity_attribute_id_div_' + id).hide();
  }
}
// Temporarily disabled category validation for testing
/*$('form.form-horizontal').submit(function(e) {
  var numberOfChecked = $('.Category-Ids:checked').length;
  if (numberOfChecked == 0) {
    alert('Please selected at least one product category');
    return false;
  }
});*/
});
</script>
<script>
CKEDITOR.replace('content', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

CKEDITOR.replace('content1', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

@php
for ($i = 1; $i <= $DescriptionsIds; $i++) {
@endphp
CKEDITOR.replace('editor{{ $i }}', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

CKEDITOR.replace('editorf{{ $i }}', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

@php } @endphp
CKEDITOR.replace('editor', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
CKEDITOR.replace('editorf', {
  height: 300,
  filebrowserUploadUrl: "{{ url('upload.php') }}",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
</script>
@endpush
