@extends('layouts.admin')

@section('content')
<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row" style="display: flex;justify-content: center;align-items: center;">
            <div class="col-md-9 col-xs-12">
                <div class="box box-success box-solid">
                    <div class="box-body">
                        <h3 class="text-center" style="color:#555 !important;">Change Password</h3>
                        <div class="text-center" style="color:red">
                            {{ session('message_error') }}
                        </div>
                        <div class="text-center" style="color:green">
                            {{ session('message_success') }}
                        </div>

                        <form method="POST" action="{{ route('admin.accounts.changePassword') }}" class="form-horizontal">
                            @csrf
                            <div class="control-group info">
                                <label class="span2" for="password">New Password</label>
                                <div class="controls">
                                    <input class="form-control" name="password" id="password" type="password" placeholder="New Password" required minlength="6">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="control-group info">
                                <label class="span2" for="password_confirmation">Confirm Password</label>
                                <div class="controls">
                                    <input class="form-control" name="password_confirmation" id="password_confirmation" type="password" placeholder="Confirm Password" required minlength="6">
                                    @error('password_confirmation')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-success" id="submitBtn">Submit</button>
                                <a href="{{ url('admin/dashboard') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box -->
            </div><!-- /.col-->
        </div><!-- ./row -->
    </section><!-- /.content -->
</div>
@endsection
