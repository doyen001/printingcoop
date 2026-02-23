@extends('layouts.admin_login')

@section('content')
<div class="login-section" style="background-image: url('{{ url('assets/admin/images/69e89dcc83ef49118e39006936c7e0dc.jpg') }}');">
    <div class="login-box">
        <div class="login-box-spacing">
            <div class="logo">
                <img src="{{ url('assets/admin/images/printing.coopLogo.png') }}">
            </div>
            <div class="login-field-section">
                <div class="text-center" style="color:red">
                {{ session('message_error') }}</div>
                <div class="text-center" style="color:green">
                {{ session('message_success') }}
                </div>

                <form method="POST" action="{{ url()->current() }}">
                    @csrf
                    <div class="login-fields">
                        <input type="text" placeholder="Username" name="username">
                        @error('username')
                            <span style="color:red">{{ $message }}</span>
                        @enderror
                        <input type="password" placeholder="Password" name="password">
                        @error('password')
                            <span style="color:red">{{ $message }}</span>
                        @enderror
                        <div class="login-btn">
                            <button type="submit" name="login">Login</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="forgot-password">
                <span><a href="{{ url('pcoopadmin/forgot-password') }}">Forgot Password?</a></span>
            </div>
        </div>
    </div>
</div>
@endsection
