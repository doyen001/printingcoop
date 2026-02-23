@extends('layouts.admin_login')

@section('content')
<div class="login-section" style="background-image: url('{{ url('assets/admin/images/summer_bg.jpg') }}');">
    <div class="login-box">
        <div class="login-box-spacing">
            <div class="logo">
                <img src="{{ url('assets/admin/images/printing.coopLogo.png') }}">
            </div>
            <div class="login-field-section">
                {{--<div class="login-logo">
                    <span>{{ config('app.name', 'Printing Coop') }}</span>
                </div>--}}
                <div class="text-center" style="color:red">
                {{ session('message_error') }}</div>
                <div class="text-center" style="color:green">
                {{ session('message_success') }}
                </div>
                <form method="POST" action="" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="login-fields">
                        <input type="password" placeholder="New Password" name="password">
                        @error('password')
                            <span style="color:red">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="login-fields">
                        <input type="password" placeholder="Re Enter Password" name="passconf">
                        @error('passconf')
                            <span style="color:red">{{ $message }}</span>
                        @enderror
                        <button type="submit" name="login">Submit</button>
                    </div>
                </form>
            </div>
            <div class="forgot-password">
                <span><a href="{{ url('pcoopadmin') }}">Back  To Login</a></span>
            </div>
        </div>
    </div>
</div>
@endsection