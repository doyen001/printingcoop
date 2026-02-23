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
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="text-center" style="color:red">
                                        {{ session('message_error') }}
                                    </div>
                                    <div class="text-center" style="color:green">
                                        {{ session('message_success') }}
                                    </div>
                                    <form method="POST" action="{{ url('admin/Configrations/saveConfigrations') }}" class="form-horizontal" enctype="multipart/form-data">
                                        @csrf
                                        <input class="form-control" name="id" type="hidden" value="{{ $configrations['id'] ?? '' }}">
                                        <div class="form-role-area">
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="contact-no">Contact No</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                            <input class="form-control" name="contact_no" id="contact-no" type="text" placeholder="Contact Number" value="{{ $configrations['contact_no'] ?? '' }}" maxlength="50">
                                                            @error('contact_no')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="contact-no">French Contact No</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                            <input class="form-control" name="contact_no_french" id="contact_no_french" type="text" placeholder="Contact Number" value="{{ $configrations['contact_no_french'] ?? '' }}" maxlength="50">
                                                            @error('contact_no_french')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="office-timing">Office Timing</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                            <input class="form-control" name="office_timing" id="office-timing" type="text" placeholder="Office Timing" value="{{ $configrations['office_timing'] ?? '' }}" maxlength="50">
                                                            @error('office_timing')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="office-timing">French Office Timing</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                            <input class="form-control" name="office_timing_french" id="office_timing_french" type="text" placeholder="Office Timing" value="{{ $configrations['office_timing_french'] ?? '' }}" maxlength="50">
                                                            @error('office_timing_french')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="announcement">Announcement</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                          <textarea class="form-control" rows="4" name="announcement" placeholder="Announcement">{{ $configrations['announcement'] ?? '' }}</textarea>
                                                          @error('announcement')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="announcement">French Announcement</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                          <textarea class="form-control" rows="4" name="announcement_french" placeholder="Announcement">{{ $configrations['announcement_french'] ?? '' }}</textarea>
                                                          @error('announcement_french')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="copy-right">Copy Right</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                          <textarea class="form-control" rows="4" name="copy_right" placeholder="Copy Right">{{ $configrations['copy_right'] ?? '' }}</textarea>
                                                          @error('copy_right')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="copy-right">French Copy Right</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                          <textarea class="form-control" rows="4" name="copy_right_french" placeholder="Copy Right">{{ $configrations['copy_right_french'] ?? '' }}</textarea>
                                                          @error('copy_right_french')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="address">Address</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                          <textarea class="form-control" rows="4" name="address_one" placeholder="address" id="content">{{ $configrations['address_one'] ?? '' }}</textarea>
                                                          @error('address_one')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="address">French Address</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                          <textarea class="form-control" rows="4" name="address_one_french" placeholder="address" id="content1">{{ $configrations['address_one_french'] ?? '' }}</textarea>
                                                          @error('address_one_french')
                                                                <span style="color:red">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="drilling_price">Drilling Price ($ per unit)</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                            <input class="form-control" name="custom_pricing[drilling_price]" id="drilling_price" type="number" step="0.01" min="0" placeholder="0.02" value="{{ $custom_pricing['drilling_price'] ?? '0.02' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label class="span2" for="collate_price">Collate Price ($ per unit)</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <div class="controls">
                                                            <input class="form-control" name="custom_pricing[collate_price]" id="collate_price" type="number" step="0.01" min="0" placeholder="0.01" value="{{ $custom_pricing['collate_price'] ?? '0.01' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="text-right">
                                                <button type="submit" class="btn btn-success">Save</button>
                                                <a href="{{ url('admin/Configrations') }}" class="btn btn-success">Back</a>
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
    allowedContent:true,
    extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
 });
 CKEDITOR.dtd.$removeEmpty.i = 0;

 CKEDITOR.replace('content1', {
    height: 300,
    filebrowserUploadUrl: "upload.php",
    allowedContent:true,
    extraAllowedContent: 'p(*)[*]{*};div(*)[*]{*};li(*)[*]{*};ul(*)[*]{*}',
 });
 CKEDITOR.dtd.$removeEmpty.i = 0;

</script>
@endsection
