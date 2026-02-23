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
            <form method="POST" action="{{ url('admin/Users/changePassword/' . $id . '/' . $page_status) }}" class="form-horizontal">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">

                <div class="control-group info">
                  <label class="span2" for="password">Enter New Password</label>
                  <div class="controls">
                    <input class="form-control" name="password" id="password" type="password" placeholder="Enter New Password" value="" maxlength="50">
                    @error('password')
                        <div class="form_vl_error">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="text-right">
                <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                <a href="{{ url('admin/Users/index/' . $page_status) }}"
                class="btn btn-success">Back</a>
                </div>
             </form>
          </div>
        </div><!-- /.box -->
      </div><!-- /.col-->
    </div><!-- ./row -->
  </section><!-- /.content -->
 </div>
@endsection
