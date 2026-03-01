{{-- CI: application/views/Logins/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Login')

@section('content')
<style>
    /* Modern login card - modal-like */
    .login-section {
        padding: 80px 16px;
        background: radial-gradient(circle at top left, #fff7f0 0%, #fef9f5 45%, #fff3e7 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-section-inner {
        width: 100%;
        max-width: 420px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 26px 70px rgba(15, 23, 42, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.25);
        padding: 32px 28px 26px;
    }

    .login-area {
        padding: 0;
        background: transparent;
        height: 100% !important;
    }

    .login-avatar-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }

    .login-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 0, #fff7f0 0, #ffb166 40%, #f28738 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 30px rgba(242, 135, 56, 0.55);
        color: #ffffff;
        font-size: 1.4rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .universal-dark-title {
        margin-bottom: 12px;
    }

    .universal-dark-title span {
        font-size: 24px;
        font-weight: 600;
        color: #111827;
        display: block;
        line-height: 1.3;
        text-align: center;
    }

    .universal-dark-info {
        margin-bottom: 24px;
    }

    .universal-dark-info span {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
        display: block;
    }

    /* Form Styles */
    .shipping-form {
        margin-top: 20px;
    }

    .single-review {
        margin-bottom: 20px;
    }

    .single-review label {
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        line-height: 1.4;
    }

    .single-review input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        color: #495057;
        background: #ffffff;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .single-review input:focus {
        outline: none;
        border-color: #f28738;
        box-shadow: 0 0 0 1px rgba(242, 135, 56, 0.55);
    }

    .single-review input::placeholder {
        color: #adb5bd;
    }

    /* Button Styles */
    .login-btn {
        margin-top: 8px;
    }

    .login-btn button {
        width: 100%;
        padding: 12px 24px;
        color: #ffffff;
        border: none;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: linear-gradient(135deg, #f28738, #ff6b35);
        box-shadow: 0 16px 32px rgba(242, 135, 56, 0.45);
    }

    .login-btn button:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #e67628, #ff6b35);
    }

    .login-btn button:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Secondary link button */
    .secondary-link-btn {
        margin-top: 18px;
        text-align: center;
        font-size: 13px;
        color: #6b7280;
    }

    .secondary-link-btn a {
        color: #f28738;
        font-weight: 600;
        text-decoration: none;
    }

    .secondary-link-btn a:hover {
        text-decoration: underline;
    }

    /* Forgot Password Link */
    .forgot-password {
        text-align: right;
        margin-top: 8px;
    }

    .forgot-password a {
        color: #4b5563;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s ease;
    }

    .forgot-password a:hover {
        color: #111827;
        text-decoration: underline;
    }

    /* Message Styles */
    #login-msg {
        margin-bottom: 16px;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.4;
    }

    #login-msg label {
        margin: 0;
        font-weight: 400;
    }

    .universal-dark-title span::before,
    .universal-dark-title span::after {
        display: none;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .login-section {
            padding: 48px 16px;
        }
    }

    @media (max-width: 767px) {
        .login-section {
            padding: 30px 0;
        }

        .universal-dark-title span {
            font-size: 20px;
        }

        .single-review input {
            padding: 10px 14px;
        }

        .login-btn button {
            padding: 10px 20px;
            font-size: 13px;
        }
    }

    /* Password field security */
    #login-password {
        -webkit-text-security: disc;
    }

    .single-review--with-icon {
        position: relative;
    }

    .single-review--with-icon .field-icon {
        position: absolute;
        left: 14px;
        top: 38px;
        font-size: 16px;
        color: #9ca3af;
        pointer-events: none;
        margin-left: 0;
    }

    .single-review--with-icon input {
        padding-left: 42px;
    }
</style>

<div class="login-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="login-section-inner">
            <div class="login-area">
                <!-- <div class -->

                @if($language_name == 'french')
                    <div class="universal-dark-title">
                        <span>Bienvenue</span>
                    </div>
                    <div class="universal-dark-info">
                        <span>Entrez vos informations pour vous connecter.</span>
                    </div>
                    <div class="universal-dark-info" id="login-msg"></div>
                    <div class="text-center" style="color:green">
                        {{ session('message_success') }}
                    </div>
                    <form id="login-form" method="post" autocomplete="off">
                        @csrf
                        <div class="shipping-form">
                            <div class="single-review single-review--with-icon">
                                <label>Adresse électronique <span class="text-danger">*</span></label>
                                <span class="field-icon"><i class="las la-user"></i></span>
                                <input type="email" name="loginemail" value="">
                            </div>
                            <div class="single-review single-review--with-icon">
                                <label>Mot de passe <span class="text-danger">*</span></label>
                                <span class="field-icon"><i class="las la-lock"></i></span>
                                <input type="password" name="loginpassword" id="login-password" value="">
                            </div>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    {{-- Reserved space for \"keep me logged in\" if needed --}}
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password">
                                        <a href="{{ url('Logins/forgotPassword') }}">mot de passe oublié?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn">
                                <button type="submit">S'identifier</button>
                            </div>
                        </div>
                    </form>
                    <div class="secondary-link-btn">
                        Vous n'avez pas de compte ?
                        <a href="{{ url('Logins/register') }}">Créer un compte</a>
                    </div>
                @else
                    <div class="universal-dark-title">
                        <span>Welcome to Printing Coop</span>
                    </div>
                    <div class="universal-dark-info">
                        <span>Enter your details to login.</span>
                    </div>
                    <div class="universal-dark-info" id="login-msg"></div>
                    <div class="text-center" style="color:green">
                        {{ session('message_success') }}
                    </div>
                    <form id="login-form" method="post" autocomplete="off">
                        @csrf
                        <div class="shipping-form">
                            <div class="single-review single-review--with-icon">
                                <label>Email address <span class="text-danger">*</span></label>
                                <span class="field-icon"><i class="las la-user"></i></span>
                                <input type="email" name="loginemail" value="">
                            </div>
                            <div class="single-review single-review--with-icon">
                                <label>Password <span class="text-danger">*</span></label>
                                <span class="field-icon"><i class="las la-lock"></i></span>
                                <input type="password" name="loginpassword" id="login-password" value="">
                            </div>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    {{-- Placeholder for \"Keep me logged in\" --}}
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password">
                                        <a href="{{ url('Logins/forgotPassword') }}">Forgot password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="login-btn">
                                <button type="submit">Login</button>
                            </div>
                        </div>
                    </form>
                    <div class="secondary-link-btn">
                        Don’t have an account?
                        <a href="{{ url('Logins/register') }}">Register</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var language_name = '{{ $language_name }}';

    if (language_name == 'french') {
        $('#login-form').validate({
            rules: {
                loginemail: {
                    required: true,
                    email: true
                },
                loginpassword: {
                    required: true,
                }
            },
            messages: {
                loginemail: {
                    required: 'Veuillez saisir un e-mail',
                    email: "S'il vous plaît, mettez une adresse email valide",
                },
                loginpassword: {
                    required: 'Veuillez entrer le mot de passe',
                },
            },
            submitHandler: function(form) {
                var url = '{{ url("Logins/checkLoginByAjax") }}';
                $('#login-msg').html('');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('button[type=submit]').attr('disabled', true);
                    },
                    success: function(data) {
                        $('button[type=submit]').attr('disabled', false);
                        let response = typeof data === 'string' ? JSON.parse(data) : data;
                        let errors = response.errors;
                        let msg = response.msg;
                        let status = response.status;
                        $('#login-password').val('');
                        if (errors && Object.keys(errors).length) {
                            var validator = $(form).validate();
                            $.each(response.errors, function(key, value) {
                                validator.showErrors({
                                    [key]: value
                                });
                            });
                        } else if (status === 'success') {
                            let url = response.url;
                            location.assign(url);
                        } else {
                            $('#login-msg').html('<span><label style="color:red">' + msg + '</label></span>');
                        }
                    },
                    error: function(error) {
                        $('button[type=submit]').attr('disabled', false);
                        $('#login-msg').html('<span><label style="color:red">Une erreur s\'est produite</label></span>');
                    },
                });
            },
        });
    } else {
        $('#login-form').validate({
            rules: {
                loginemail: {
                    required: true,
                    email: true
                },
                loginpassword: {
                    required: true,
                }
            },
            messages: {
                loginemail: {
                    required: 'Please Enter Email',
                },
                loginpassword: {
                    required: 'Please Enter Password',
                },
            },
            submitHandler: function(form) {
                var url = '{{ url("Logins/checkLoginByAjax") }}';
                $('#login-msg').html('');
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: $(form).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        $('button[type=submit]').attr('disabled', true);
                    },
                    success: function(data) {
                        $('button[type=submit]').attr('disabled', false);
                        let response = typeof data === 'string' ? JSON.parse(data) : data;
                        let errors = response.errors;
                        let msg = response.msg;
                        let status = response.status;
                        $('#login-password').val('');
                        if (errors && Object.keys(errors).length) {
                            var validator = $(form).validate();
                            $.each(response.errors, function(key, value) {
                                validator.showErrors({
                                    [key]: value
                                });
                            });
                        } else if (status === 'success') {
                            let url = response.url;
                            location.assign(url);
                        } else {
                            $('#login-msg').html('<span><label style="color:red">' + msg + '</label></span>');
                        }
                    },
                    error: function(error) {
                        $('button[type=submit]').attr('disabled', false);
                        $('#login-msg').html('<span><label style="color:red">An error occurred</label></span>');
                    },
                });
            },
        });
    }
}); // End document.ready
</script>
@endsection
