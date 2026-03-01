@extends('elements.app')

@section('title', $page_title ?? 'Register')

@section('content')
<style>
    .register-section {
        padding: 80px 16px;
        background: radial-gradient(circle at top left, #fff7f0 0%, #fef9f5 45%, #fff3e7 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-section-inner {
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 26px 70px rgba(15, 23, 42, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.25);
        padding: 32px 28px 26px;
    }

    .register-avatar-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }

    .register-avatar {
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
        text-align: left;
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

    #signup-msg {
        margin-bottom: 16px;
        padding: 12px 16px;
        border-radius: 6px;
        font-size: 14px;
        line-height: 1.4;
    }

    #signup-msg label {
        margin: 0;
        font-weight: 400;
    }

    .universal-dark-title span::before,
    .universal-dark-title span::after {
        display: none;
    }

    @media (max-width: 991px) {
        .register-section {
            padding: 48px 16px;
        }
    }

    @media (max-width: 767px) {
        .register-section {
            padding: 30px 0;
        }
    }

    .single-review--half {
        width: 100%;
    }

    @media (min-width: 576px) {
        .register-name-row {
            display: flex;
            gap: 12px;
        }

        .register-name-row .single-review--half {
            width: 50%;
        }
    }
</style>

<div class="register-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="register-section-inner">
            <!-- <div class="register-avatar-wrapper">
                <div class="register-avatar">
                    {{ strtoupper(substr(config('store.name', 'PC'), 0, 2)) }}
                </div>
            </div> -->

            <div class="universal-dark-title">
                <span>
                    {{ $language_name == 'french' ? 'Créer un nouveau compte' : 'Create new account' }}
                </span>
            </div>
            <div class="universal-dark-info">
                <span>
                    @if($language_name == 'french')
                        Commencez gratuitement et gérez toutes vos commandes d'impression à partir d'un seul compte.
                    @else
                        Start for free and manage all your printing orders from a single account.
                    @endif
                </span>
            </div>
            <div class="universal-dark-info" id="signup-msg"></div>

            @if($language_name == 'french')
                <form id="signup-form" method="post" autocomplete="off">
                    @csrf
                    <div class="shipping-form">
                        <div class="register-name-row">
                            <div class="single-review single-review--half">
                                <label>Ton prénom <span class="text-danger">*</span></label>
                                <input type="text" name="fname">
                            </div>
                            <div class="single-review single-review--half">
                                <label>Votre nom de famille <span class="text-danger">*</span></label>
                                <input type="text" name="lname">
                            </div>
                        </div>
                        <div class="single-review">
                            <label>Votre adresse email <span class="text-danger">*</span></label>
                            <input type="email" name="email">
                        </div>
                        <div class="register-name-row">
                            <div class="single-review single-review--half">
                                <label>Choisissez un mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="signup-password">
                            </div>
                            <div class="single-review single-review--half">
                                <label>Confirmez le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" id="confirm-password">
                            </div>
                        </div>
                        <div class="login-btn">
                            <button type="submit">S'inscrire maintenant</button>
                        </div>
                    </div>
                </form>
                <div class="secondary-link-btn">
                    Déjà client ?
                    <a href="{{ url('Logins') }}">S'identifier</a>
                </div>
            @else
                <form id="signup-form" method="post" autocomplete="off">
                    @csrf
                    <div class="shipping-form">
                        <div class="register-name-row">
                            <div class="single-review single-review--half">
                                <label>First name <span class="text-danger">*</span></label>
                                <input type="text" name="fname">
                            </div>
                            <div class="single-review single-review--half">
                                <label>Last name <span class="text-danger">*</span></label>
                                <input type="text" name="lname">
                            </div>
                        </div>
                        <div class="single-review">
                            <label>Email address <span class="text-danger">*</span></label>
                            <input type="email" name="email">
                        </div>
                        <div class="register-name-row">
                            <div class="single-review single-review--half">
                                <label>Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="signup-password">
                            </div>
                            <div class="single-review single-review--half">
                                <label>Confirm password <span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" id="confirm-password">
                            </div>
                        </div>
                        <div class="login-btn">
                            <button type="submit">Create account</button>
                        </div>
                    </div>
                </form>
                <div class="secondary-link-btn">
                    Already a member?
                    <a href="{{ url('Logins') }}">Log in</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var language_name = '{{ $language_name }}';

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
                                window.location.href = '{{ url("Logins") }}';
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
                                window.location.href = '{{ url("Logins") }}';
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
}); // End document.ready
</script>
@endsection

