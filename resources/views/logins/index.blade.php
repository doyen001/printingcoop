{{-- CI: application/views/Logins/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Login/Register')

@section('content')
<style>
    /* Simple and Clean Login Page Styles */
    .login-section {
        padding: 60px 0;
        background: #f8f9fa;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .login-section-inner {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e9ecef;
    }

    .login-area, .register-area {
        padding: 40px;
    }

    .login-area {
        border-right: 1px solid #e9ecef;
        background: #fafbfc;
        height: 100% !important;
    }

    .universal-dark-title {
        margin-bottom: 12px;
    }

    .universal-dark-title span {
        font-size: 24px;
        font-weight: 600;
        color: #2c3e50;
        display: block;
        line-height: 1.3;
        text-align: center;
    }

    .universal-dark-info {
        margin-bottom: 24px;
    }

    .universal-dark-info span {
        font-size: 14px;
        color: #6c757d;
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
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        color: #495057;
        background: #ffffff;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .single-review input:focus {
        outline: none;
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
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .login-btn button:hover {
        transform: translateY(-1px);
    }

    .login-btn button:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Forgot Password Link */
    .forgot-password {
        text-align: right;
        margin-top: 8px;
    }

    .forgot-password a {
        color: #007bff;
        text-decoration: none;
        font-size: 13px;
        transition: color 0.2s ease;
    }

    .forgot-password a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Message Styles */
    #login-msg, #signup-msg {
        margin-bottom: 16px;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.4;
    }

    #login-msg label, #signup-msg label {
        margin: 0;
        font-weight: 400;
    }

    /* .text-center[style*="color:green"] {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 16px;
        font-size: 14px;
    } */

    /* Responsive Design */
    @media (max-width: 767px) {
        .login-section {
            padding: 30px 0;
        }

        .login-area, .register-area {
            padding: 30px 20px;
        }

        .login-area {
            border-right: none;
            border-bottom: 1px solid #e9ecef;
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
</style>

<div class="login-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="login-section-inner">
            <div class="row">
                {{-- Login Section (CI lines 10-90) --}}
                <div class="col-md-5">
                    @if($language_name == 'french')
                        <div class="login-area">
                            <div class="universal-dark-title">
                                <span>Clients enregistrés</span>
                            </div>
                            <div class="universal-dark-info" id="login-msg"></div>
                            <div class="text-center" style="color:green">
                                {{ session('message_success') }}
                            </div>
                            <div class="universal-dark-info">
                                <span>Si vous avez déjà un compte, veuillez vous identifier.</span>
                            </div>
                            <form id="login-form" method="post" autocomplete="off">
                                @csrf
                                <div class="shipping-form">
                                    <div class="single-review">
                                        <label>Adresse électronique <span class="text-danger">*</span></label>
                                        <input type="email" name="loginemail" value="">
                                    </div>
                                    <div class="single-review">
                                        <label>Mot de passe <span class="text-danger">*</span></label>
                                        <input type="password" name="loginpassword" id="login-password" value="">
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-5 col-md-6">
                                            <div class="login-btn">
                                                <button type="submit">S'identifier</button>
                                            </div>
                                        </div>
                                        <div class="col-7 col-md-6">
                                            <div class="forgot-password">
                                                <a href="{{ url('Logins/forgotPassword') }}">mot de passe oublié?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="login-area">
                            <div class="universal-dark-title">
                                <span>Registered Customers</span>
                            </div>
                            <div class="universal-dark-info" id="login-msg"></div>
                            <div class="text-center" style="color:green">
                                {{ session('message_success') }}
                            </div>
                            <div class="universal-dark-info">
                                <span>If you have an account with us, please log in.</span>
                            </div>
                            <form id="login-form" method="post" autocomplete="off">
                                @csrf
                                <div class="shipping-form">
                                    <div class="single-review">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="loginemail" value="">
                                    </div>
                                    <div class="single-review">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="password" name="loginpassword" id="login-password" value="">
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-5 col-md-6">
                                            <div class="login-btn">
                                                <button type="submit">Login</button>
                                            </div>
                                        </div>
                                        <div class="col-7 col-md-6">
                                            <div class="forgot-password">
                                                <a href="{{ url('Logins/forgotPassword') }}">Forgot password?</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                
                {{-- Register Section (CI lines 91-200) --}}
                <div class="col-md-7">
                    <div class="register-area">
                        <div class="universal-dark-title">
                            <span>
                                {{ $language_name == 'french' ? 'Nouveaux clients' : 'New Customers' }}
                            </span>
                        </div>
                        <div class="universal-dark-info">
                            <span>
                                @if($language_name == 'french')
                                    En créant un compte sur notre boutique, vous pourrez passer vos commandes plus rapidement, enregistrer plusieurs adresses de livraison, consulter et suivre vos commandes, et plein d'autres choses encore.
                                @else
                                    By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.
                                @endif
                            </span>
                        </div>
                        <div class="universal-dark-info" id="signup-msg"></div>
                        
                        @if($language_name == 'french')
                            <form id="signup-form" method="post" autocomplete="off">
                                @csrf
                                <div class="shipping-form">
                                    <div class="row">
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Ton prénom <span class="text-danger">*</span></label>
                                                <input type="text" name="fname">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Votre nom de famille <span class="text-danger">*</span></label>
                                                <input type="text" name="lname">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-review">
                                                <label>Votre adresse email <span class="text-danger">*</span></label>
                                                <input type="email" name="email">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Choisissez un mot de passe <span class="text-danger">*</span></label>
                                                <input type="password" name="password" id="signup-password">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Confirmez le mot de passe <span class="text-danger">*</span></label>
                                                <input type="password" name="confirm_password" id="confirm-password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="login-btn">
                                        <button type="submit">S'inscrire maintenant</button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <form id="signup-form" method="post" autocomplete="off">
                                @csrf
                                <div class="shipping-form">
                                    <div class="row">
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Your First Name <span class="text-danger">*</span></label>
                                                <input type="text" name="fname">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Your Last Name <span class="text-danger">*</span></label>
                                                <input type="text" name="lname">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-review">
                                                <label>Your Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Choose Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password" id="signup-password">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6">
                                            <div class="single-review">
                                                <label>Confirm Password <span class="text-danger">*</span></label>
                                                <input type="password" name="confirm_password" id="confirm-password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="login-btn">
                                        <button type="submit">Register Now</button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
var language_name = '{{ $language_name }}';

if (language_name == 'french') {
    /*login code start*/
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
/*login code end*/

/*signup code start*/
if (language_name == 'french') {
    $('#signup-form').validate({
        rules: {
            fname: {
                required: true,
            },
            lname: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            confirm_password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                equalTo: '#signup-password',
            },
        },
        messages: {
            fname: {
                required: 'Veuillez entrer le prénom',
            },
            lname: {
                required: 'Veuillez saisir votre nom',
            },
            email: {
                required: 'Veuillez saisir un e-mail',
                email: "S'il vous plaît, mettez une adresse email valide",
            },
            password: {
                required: 'Veuillez entrer le mot de passe',
                minlength: 'Veuillez saisir au moins 8 caractères.',
                maxlength: 'Veuillez ne pas saisir plus de 20 caractères'
            },
            confirm_password: {
                required: 'Veuillez saisir le mot de passe',
                equalTo: 'Le champ Confirmer le mot de passe ne correspond pas au champ Mot de passe',
                minlength: 'Veuillez saisir au moins 8 caractères.',
                maxlength: 'Veuillez ne pas saisir plus de 20 caractères'
            },
        },
        submitHandler: function(form) {
            var url = '{{ url("Logins/signup") }}';
            $('#signup-msg').html('');
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
                    $('#signup-password').val('');
                    $('#confirm-password').val('');
                    if (errors && Object.keys(errors).length) {
                        var validator = $(form).validate();
                        $.each(response.errors, function(key, value) {
                            validator.showErrors({
                                [key]: value
                            });
                        });
                    } else if (status === 'success') {
                        $('#signup-msg').html('<span style="color:green">' + msg + '</span>');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        $('#signup-msg').html('<span><label style="color:red">' + msg + '</label></span>');
                    }
                },
                error: function(error) {
                    $('button[type=submit]').attr('disabled', false);
                    $('#signup-msg').html('<span><label style="color:red">Une erreur s\'est produite</label></span>');
                },
            });
        },
    });
} else {
    $('#signup-form').validate({
        rules: {
            fname: {
                required: true,
            },
            lname: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 20,
            },
            confirm_password: {
                required: true,
                minlength: 8,
                maxlength: 20,
                equalTo: '#signup-password',
            },
        },
        messages: {
            fname: {
                required: 'Please Enter First Name',
            },
            lname: {
                required: 'Please Enter Last Name',
            },
            email: {
                required: 'Please Enter Email',
            },
            password: {
                required: 'Please Enter Password',
            },
            confirm_password: {
                required: 'Please Enter Confirm Password',
                equalTo: 'Confirm Password Field Does Not Match The Password Field'
            },
        },
        submitHandler: function(form) {
            var url = '{{ url("Logins/signup") }}';
            $('#signup-msg').html('');
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
                    $('#signup-password').val('');
                    $('#confirm-password').val('');
                    if (errors && Object.keys(errors).length) {
                        var validator = $(form).validate();
                        $.each(response.errors, function(key, value) {
                            validator.showErrors({
                                [key]: value
                            });
                        });
                    } else if (status === 'success') {
                        $('#signup-msg').html('<span style="color:green">' + msg + '</span>');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        $('#signup-msg').html('<span><label style="color:red">' + msg + '</label></span>');
                    }
                },
                error: function(error) {
                    $('button[type=submit]').attr('disabled', false);
                    $('#signup-msg').html('<span><label style="color:red">An error occurred</label></span>');
                },
            });
        },
    });
}
/*signup code end*/
}); // End document.ready
</script>
@endsection
