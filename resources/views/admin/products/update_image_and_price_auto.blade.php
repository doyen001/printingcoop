@extends('layouts.admin')

<link href="{{ asset('assets/admin/css/product.css') }}" rel="stylesheet" type="text/css">
@section('content')
<div class="content-wrapper dd" style="min-height: 687px;">
    <section class="content">
        <div class="row" style="display: flex;justify-content: center;align-items: center;">
            <div class="col-md-12 col-xs-12">
                <div class="box box-success box-solid">
                    <div class="box-body">
                        <div class="inner-head-section">
                            <div class="inner-title">
                                <span>{{ $page_title }} for <span title="Ink, Toner Cartridges & Drum"><b>Ink, Toner Cartridges & Drum</b></span></span>
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
                                    <form method="POST" action="" enctype="multipart/form-data" class="form-horizontal">
                                        @csrf
                                        <div class="form-role-area">
                                            <hr>
                                            <div class="control-group info">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="controls small-controls">
                                                            <div class="single-for-verify">
                                                                <input id="upload-attributes" type="file" class="hidden" name="fileUpload" onchange="uploadInkImage(this.files[0])" />
                                                                <label for="upload-attributes" id="file-drag" style="width: 100%;background-color: #367fa9;">
                                                                    <span id="upload-attributes-btn" class="btn btn-primary">
                                                                        densisource Image (zip) <i class="fa fa-upload" aria-hidden="true"></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="controls small-controls">
                                                            <div class="single-for-verify">
                                                                <input id="upload-densi-pricelist" type="file" class="hidden" name="fileUpload" onchange="uploadInkPriceList(this.files[0],'densi')" />
                                                                <label for="upload-densi-pricelist" id="file-drag" style="width: 100%;background-color: #367fa9;">
                                                                    <span id="upload-densi-pricelist-btn" class="btn btn-primary">
                                                                        densisource Price List (csv)
                                                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="controls small-controls">
                                                            <div class="single-for-verify">
                                                                <input id="upload-nuton-pricelist" type="file" class="hidden" name="fileUpload" onchange="uploadInkPriceList(this.files[0],'nuton')" />
                                                                <label for="upload-nuton-pricelist" id="file-drag" style="width: 100%;background-color: #367fa9;">
                                                                    <span id="upload-nuton-pricelist-btn" class="btn btn-primary">
                                                                        nutondensi Price List (xls)
                                                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
<div class="modal" tabindex="-1" role="dialog" id="ItemModal">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Quantity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>loading... please wait</p>
            </div>
        </div>
    </div>
</div>
<script>
function uploadInkImage(file) {
    if (file == null)
        return;

    var formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');

    $('#loader-img').show();
    $.ajax({
        url: '{{ url("admin/Products/uploadInkImage") }}',
        type: 'POST',
        data: formData,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        complete: function(data, status) {
            $('#loader-img').hide();
            var msg = '';
           // var obj = JSON.parse(data);
            //if (obj.result == 1) {
               // if (obj.failed == 0)
                    msg = 'All Images are updated.';
               // else
                 //  msg = obj.failed + ' attributes are failed.';
           // } else
            //    msg = 'Error occurred.';
            $('#upload-attributes').files = [null];

            $('#ItemModal .modal-title').html(msg);
            $('#ItemModal .modal-body').html(
                '<div class="inner-content-area"><div class="row justify-content-center"><div class="col-md-12 center"><button type="button" class="btn btn-success" onclick="$(\'#ItemModal\').modal(\'hide\');return false;">Ok</button></div></div>'
                );
            $('#ItemModal').modal('show');
        }
    });
}

function uploadInkPriceList(file, flag) {
    if (file == null)
        return;

    var formData = new FormData();
    formData.append('file', file);
    formData.append('_token', '{{ csrf_token() }}');
    $('#loader-img').show();
    $.ajax({
        url: '{{ url("admin/Products/updateAutoPrice") }}/' + flag,
        type: 'POST',
        data: formData,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        complete: function(data) {
			console.log(data);
            $('#loader-img').hide();
            var msg = '';
            //var obj = JSON.parse(data);
            //if (obj.result == 1) {
              //  if (obj.failed == 0)
                    msg = 'All prices are updated.';
               // else
                 //   msg = obj.failed + ' prices are failed.';
          //  } else
          //      msg = 'Error occurred.';
            $('#upload-full-pricelist').files = [null];

            $('#ItemModal .modal-title').html(msg);
            $('#ItemModal .modal-body').html(
                '<div class="inner-content-area"><div class="row justify-content-center"><div class="col-md-12 center"><button type="button" class="btn btn-success" onclick="$(\'#ItemModal\').modal(\'hide\');return false;">Ok</button></div></div>'
                );
            $('#ItemModal').modal('show');
        }
    });
}
</script>
@endsection
