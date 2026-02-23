@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row" style="display: flex;justify-content: center;align-items: center;">
            <div class="col-md-9 col-xs-12">
                <div class="box box-success box-solid">
                    <div class="box-body">
                        <h3 class="text-center" style="color:#555 !important;">{{ $page_title }}</h3>
                        <div class="text-center" style="color:red">
                        {{ session('message_error') }}</div>
                        <div class="text-center" style="color:green">
                        {{ session('message_success') }}

                        @if($success ?? false)
                            <script>
                            setTimeout(function() {
                                window.location='{{ url('pcoopadmin') }}';
                            }, 3000
                            );

                            </script>
                        @endif
                        </div>

                        <form method="POST" action="{{ url('admin/Accounts/changePassword') }}" class="form-horizontal" enctype="multipart/form-data">
                            @csrf
                             <input class="form-control" name="id" type="hidden" value="{{ $postData['id'] ?? '' }}" id="product_id">
                                <div class="control-group info">
                                    <label class="span2" for="email">Email Id</label>
                                    <div class="controls">
                                        <input class="form-control" name="email" id="email" type="text" placeholder="Email Id" value="{{ $postData['email'] ?? '' }}" maxlength="50" readonly>
                                        @error('email')
                                            <label class="mt-2 text-danger">{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>
                                <div class="text-right">
                                <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                                </div>
                         </form>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col-->
        </div><!-- ./row -->
    </section><!-- /.content -->
 </div>
@endsection