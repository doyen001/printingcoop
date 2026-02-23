<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
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
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                  </div>
                  <form method="POST" action="{{ url()->current() }}" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}" id="id">
                    <div class="form-role-area">
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="name">Store Name</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="name" id="name" type="text" placeholder="Name" value="{{ $postData['name'] ?? '' }}">
                              @error('name')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="phone">Store Phone</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="phone" id="phone" type="text" placeholder="Phone" value="{{ $postData['phone'] ?? '' }}">
                              @error('phone')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="email">Store Email</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="email" id="email" type="email" placeholder="Email" value="{{ $postData['email'] ?? '' }}">
                              @error('email')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="url">Store Url</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="url" id="url" type="url" placeholder="Url" value="{{ $postData['url'] ?? '' }}">
                              @error('url')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="langue_id">Store Langue</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select class="form-control" name="langue_id">
                                <option value="">Select Category</option>
                                @foreach($language as $key => $val)
                                  <option value="{{ $key }}" {{ ($postData['langue_id'] ?? 0) == $key ? 'selected="selected"' : '' }}>
                                    {{ $val }}
                                  </option>
                                @endforeach
                              </select>
                              @error('langue_id')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2" for="address">Store Address</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="address" id="address">{{ $postData['address'] ?? '' }}</textarea>
                              @error('address')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="order_id_prefix">Store Order Id Prefix</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="order_id_prefix" id="order_id_prefix" type="text" placeholder="Email" value="{{ $postData['order_id_prefix'] ?? '' }}">
                              @error('order_id_prefix')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="show_language_translation">Store Show Language Translation</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select name="show_language_translation" class="form-control">
                                <option value="1" {{ ($postData['show_language_translation'] ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($postData['show_language_translation'] ?? 0) == 0 ? 'selected' : '' }}>No</option>
                              </select>
                              @error('show_language_translation')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="show_all_categories">Store Show All Categories</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select name="show_all_categories" class="form-control">
                                <option value="1" {{ ($postData['show_all_categories'] ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ ($postData['show_all_categories'] ?? 0) == 0 ? 'selected' : '' }}>No</option>
                              </select>
                              @error('show_all_categories')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="from_email">Store From Email</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="from_email" id="from_email" type="email" placeholder="Email" value="{{ $postData['from_email'] ?? '' }}">
                              @error('from_email')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="admin_email1">Store Admin Email-1</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="admin_email1" id="admin_email1" type="email" placeholder="Email" value="{{ $postData['admin_email1'] ?? '' }}">
                              @error('admin_email1')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="admin_email2">Store Admin Email-2</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="admin_email2" id="admin_email2" type="email" placeholder="Email" value="{{ $postData['admin_email2'] ?? '' }}">
                              @error('admin_email2')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="admin_email3">Store Admin Email-3</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="admin_email3" id="admin_email3" type="email" placeholder="Email" value="{{ $postData['admin_email3'] ?? '' }}">
                              @error('admin_email3')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- CLOVER POS -->
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="clover_mode">Clover Payment Mode</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select name="clover_mode" class="form-control">
                                <option value="0" {{ ($postData['clover_mode'] ?? 0) == 0 ? 'selected' : '' }}>Sandbox</option>
                                <option value="1" {{ ($postData['clover_mode'] ?? 0) == 1 ? 'selected' : '' }}>Live</option>
                              </select>
                              @error('clover_mode')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="clover_sandbox_api_key">Clover Sandbox Payment Api Key</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="clover_sandbox_api_key" id="clover_sandbox_api_key" type="text" value="{{ $postData['clover_sandbox_api_key'] ?? '' }}">
                              @error('clover_sandbox_api_key')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="clover_sandbox_secret">Clover Sandbox Payment Secret</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="clover_sandbox_secret" id="clover_sandbox_secret" type="text" value="{{ $postData['clover_sandbox_secret'] ?? '' }}">
                              @error('clover_sandbox_secret')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="clover_api_key">Clover Live Payment Api Key</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="clover_api_key" id="clover_api_key" type="text" value="{{ $postData['clover_api_key'] ?? '' }}">
                              @error('clover_api_key')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="clover_secret">Clover Live Payment Secret</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="clover_secret" id="clover_secret" type="text" value="{{ $postData['clover_secret'] ?? '' }}">
                              @error('clover_secret')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- CLOVER POS END -->
                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="paypal_payment_mode">Paypal Payment Mode</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select name="paypal_payment_mode" class="form-control">
                                <option value="sandbox" {{ ($postData['paypal_payment_mode'] ?? 'sandbox') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                <option value="live" {{ ($postData['paypal_payment_mode'] ?? 'sandbox') == 'live' ? 'selected' : '' }}>Live</option>
                              </select>
                              @error('paypal_payment_mode')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="paypal_sandbox_business_email">Sandbox Payment Paypal Business Email</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="paypal_sandbox_business_email" id="paypal_sandbox_business_email" type="email" placeholder="Email" value="{{ $postData['paypal_sandbox_business_email'] ?? '' }}">
                              @error('paypal_sandbox_business_email')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="paypal_business_email">Live Payment Paypal Business Email</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <input class="form-control" name="paypal_business_email" id="paypal_business_email" type="email" placeholder="Email" value="{{ $postData['paypal_business_email'] ?? '' }}">
                              @error('paypal_business_email')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                    <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="flag_ship">Show Flagship</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <select name="flag_ship" class="form-control">
                                <option value="no" {{ ($postData['flag_ship'] ?? 'no') == 'no' ? 'selected' : '' }}>No</option>
                                <option value="yes" {{ ($postData['flag_ship'] ?? 'no') == 'yes' ? 'selected' : '' }}>Yes</option>
                              </select>
                              @error('flag_ship')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="content">Store Email Footer Line</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="email_footer_line" id="content">{{ $postData['email_footer_line'] ?? '' }}</textarea>
                              @error('email_footer_line')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2">Store Email Template Logo</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <div class="col-xs-3" style="margin-bottom:15px;">
                                @if(!empty($postData['email_template_logo']))
                                  <img src="{{ asset('uploads/logo/' . $postData['email_template_logo']) }}" width="100" height="80">
                                @endif
                                <input name="old_image" value="{{ $postData['email_template_logo'] ?? '' }}" type="hidden">
                              </div>
                              <div class="entry input-group col-xs-12" style="margin-bottom:15px;">
                                <input class="btn btn-primary" name="logo_image" type="file" accept="image/x-png,image/gif,image/jpeg" id="fileUpload-1">
                                &nbsp;&nbsp;
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="content1">Store Company Details For Invoice Pdf</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="invoice_pdf_company" id="content1">{{ $postData['invoice_pdf_company'] ?? '' }}</textarea>
                              @error('invoice_pdf_company')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>
                    <div class="control-group info">
                        <div class="row align-items-center">
                          <div class="col-md-4">
                            <label class="span2" for="content2">Store Company Details For Order Pdf</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <textarea name="order_pdf_company" id="content2">{{ $postData['order_pdf_company'] ?? '' }}</textarea>
                              @error('order_pdf_company')
                                <span style="color:red">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="control-group info">
                        <div class="row">
                          <div class="col-md-4">
                            <label class="span2">Store Pdf Logo</label>
                          </div>
                          <div class="col-md-8">
                            <div class="controls">
                              <div class="col-xs-3" style="margin-bottom:15px;">
                                @if(!empty($postData['pdf_template_logo']))
                                  <img src="{{ asset('uploads/logo/' . $postData['pdf_template_logo']) }}" width="100" height="80">
                                @endif
                                <input name="old_pdf_template_logo" value="{{ $postData['pdf_template_logo'] ?? '' }}" type="hidden">
                              </div>
                              <div class="entry input-group col-xs-12" style="margin-bottom:15px;">
                                <input class="btn btn-primary" name="pdf_template_logo" type="file" accept="image/x-png,image/gif,image/jpeg" id="fileUpload-2">
                                &nbsp;&nbsp;
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="text-right">
                        <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                        <a href="{{ url('admin/Stores') }}" class="btn btn-success">Back</a>
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
<script>
CKEDITOR.replace('content', {
  height: 300,
  filebrowserUploadUrl: "upload.php",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*]',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

CKEDITOR.replace('content1', {
  height: 300,
  filebrowserUploadUrl: "upload.php",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;

CKEDITOR.replace('content2', {
  height: 300,
  filebrowserUploadUrl: "upload.php",
  allowedContent: true,
  extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
});
CKEDITOR.dtd.$removeEmpty.i = 0;
</script>
@endsection
