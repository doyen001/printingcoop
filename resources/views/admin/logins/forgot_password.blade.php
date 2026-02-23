@extends('layouts.admin_login')

@section('content')
<div class="login-section" style="background-image: url('{{ url('assets/admin/images/summer_bg.jpg') }}');">
    <div class="login-box">
        <div class="login-box-spacing">
            <div class="logo">
                <img src="{{ url('assets/admin/images/printing.coopLogo.png') }}">
            </div>
            <div class="login-field-section">
                @if(session('message_error'))
                    <div class="text-center" style="color:red">
                        {{ session('message_error') }}
                    </div>
                @endif
                @if(session('message_success'))
                    <div class="text-center" style="color:green">
                        {{ session('message_success') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ url('pcoopadmin/forgot-password') }}" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="login-fields">
                        <input type="email" placeholder="Enter Email Id" name="email" value="{{ old('email') }}">
                        @error('email')
                            <span style="color:red">{{ $message }}</span>
                        @enderror
                        <button type="submit" name="login">Submit</button>
                    </div>
                </form>
            </div>
            <div class="forgot-password">
                <span><a href="{{ url('pcoopadmin') }}">Back To Login</a></span>
            </div>
        </div>
    </div>
</div>
@endsection
