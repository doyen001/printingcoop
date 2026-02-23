{{-- CI: application/views/Logins/forgot_password.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Forgot Password')

@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="login-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="login-section-inner">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    @if($language_name == 'french')
                        <div class="login-area">
                            <div class="universal-dark-title">
                                <span>Récupérez votre mot de passe</span>
                            </div>
                            <div class="universal-dark-info">
                                <span>Saisissez votre email ci-dessous. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</span>
                            </div>
                            <form id="password-form" method="post">
                                @csrf
                                <div class="customer-fields pad-for-span" style="min-height: initial;">
                                    <div id="forgot_msg" class="col-md-12 text-center"></div>
                                </div>
                                <div class="shipping-form">
                                    <div class="single-review">
                                        <label>Adresse électronique:</label>
                                        <input type="email" name="account_email" id="account-email">
                                        <label id="account-email-error" style="color:red"></label>
                                        <input type="hidden" name="send_otp" id="send-otp" value="">
                                    </div>
                                    <div class="single-review">
                                        <div class="g-recaptcha" data-sitekey="6LcXjt4UAAAAAMf-gtro8dDUsHGFBOtpfePKAifa"></div>
                                        <label id="g-recaptcha-error" style="color:red"></label>
                                    </div>
                                    <div class="change-pswd-field-show" style="display: none;" id="otp-container">
                                        <div class="resend-otp">
                                            <span style="padding-top: 0px;">OTP envoyé au mobile</span>
                                            <span style="padding-top: 0px;" class="for-resend-otp" onclick="sendOptToEmail()">Renvoyer?</span>
                                        </div>
                                        <div class="single-review">
                                            <label>Entrez OTP</label>
                                            <input type="text" name="input_otp" id="input-otp" maxlength="6">
                                            <label id="input-otp-error" style="color:red"></label>
                                        </div>
                                        <div class="single-review">
                                            <label>Définir le mot de passe</label>
                                            <input type="password" name="new_password" id="new-password" maxlength="20" minlength="8">
                                            <label id="new-password-error" style="color:red"></label>
                                        </div>
                                    </div>
                                    <div class="login-btn">
                                        <button type="button" id="account-change-pswd" onclick="sendOptToEmail()">Continuer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="login-area">
                            <div class="universal-dark-title">
                                <span>Retrieve your password here</span>
                            </div>
                            <div class="universal-dark-info">
                                <span>Please enter your email address below. You will receive a link to reset your password.</span>
                            </div>
                            <form id="password-form" method="post">
                                @csrf
                                <div class="customer-fields pad-for-span" style="min-height: initial;">
                                    <div id="forgot_msg" class="col-md-12 text-center"></div>
                                </div>
                                <div class="shipping-form">
                                    <div class="single-review">
                                        <label>Email Address:</label>
                                        <input type="email" name="account_email" id="account-email">
                                        <label id="account-email-error" style="color:red"></label>
                                        <input type="hidden" name="send_otp" id="send-otp" value="">
                                    </div>
                                    <div class="single-review">
                                        <div class="g-recaptcha" data-sitekey="6LcXjt4UAAAAAMf-gtro8dDUsHGFBOtpfePKAifa"></div>
                                        <label id="g-recaptcha-error" style="color:red"></label>
                                    </div>
                                    <div class="change-pswd-field-show" style="display: none;" id="otp-container">
                                        <div class="resend-otp">
                                            <span style="padding-top: 0px;">OTP sent to Mobile</span>
                                            <span style="padding-top: 0px;" class="for-resend-otp" onclick="sendOptToEmail()">Resend?</span>
                                        </div>
                                        <div class="single-review">
                                            <label>Enter OTP</label>
                                            <input type="text" name="input_otp" id="input-otp" maxlength="6">
                                            <label id="input-otp-error" style="color:red"></label>
                                        </div>
                                        <div class="single-review">
                                            <label>Set Password</label>
                                            <input type="password" name="new_password" id="new-password" maxlength="20" minlength="8">
                                            <label id="new-password-error" style="color:red"></label>
                                        </div>
                                    </div>
                                    <div class="login-btn">
                                        <button type="button" id="account-change-pswd" onclick="sendOptToEmail()">Continue</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var otpSent = '';

function sendOptToEmail() {
    var email = $('#account-email').val();
    var sendOtp = $('#send-otp').val();
    
    // Clear previous errors
    $('#account-email-error').html('');
    $('#input-otp-error').html('');
    $('#new-password-error').html('');
    $('#g-recaptcha-error').html('');
    $('#forgot_msg').html('');
    
    // Validate email
    if (email == '') {
        $('#account-email-error').html('{{ $language_name == "french" ? "Veuillez entrer votre adresse e-mail" : "Please enter your email address" }}');
        return false;
    }
    
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        $('#account-email-error').html('{{ $language_name == "french" ? "Veuillez entrer une adresse e-mail valide" : "Please enter a valid email address" }}');
        return false;
    }
    
    // If OTP not sent yet, send it
    if (sendOtp == '') {
        // Validate reCAPTCHA
        var recaptchaResponse = grecaptcha.getResponse();
        if (recaptchaResponse.length == 0) {
            $('#g-recaptcha-error').html('{{ $language_name == "french" ? "Veuillez vérifier le reCAPTCHA" : "Please verify the reCAPTCHA" }}');
            return false;
        }
        
        $('#account-change-pswd').prop('disabled', true);
        $('#account-change-pswd').html('{{ $language_name == "french" ? "Envoi en cours..." : "Sending..." }}');
        
        $.ajax({
            type: 'POST',
            url: '{{ url("Logins/sendOtp") }}',
            data: {
                mobile: email,
                type: 'forgot',
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
                $('#account-change-pswd').prop('disabled', false);
                $('#account-change-pswd').html('{{ $language_name == "french" ? "Réinitialiser le mot de passe" : "Reset Password" }}');
                
                if (response.status == 1) {
                    otpSent = response.otp;
                    $('#send-otp').val(response.otp);
                    $('#otp-container').show();
                    $('#forgot_msg').html('<span style="color:green">' + response.msg + '</span>');
                    $('#account-change-pswd').attr('onclick', 'resetPassword()');
                } else {
                    $('#forgot_msg').html('<span style="color:red">' + response.msg + '</span>');
                }
            },
            error: function() {
                $('#account-change-pswd').prop('disabled', false);
                $('#account-change-pswd').html('{{ $language_name == "french" ? "Continuer" : "Continue" }}');
                $('#forgot_msg').html('<span style="color:red">{{ $language_name == "french" ? "Une erreur s\'est produite. Veuillez réessayer." : "An error occurred. Please try again." }}</span>');
            }
        });
    } else {
        resetPassword();
    }
}

function resetPassword() {
    var email = $('#account-email').val();
    var inputOtp = $('#input-otp').val();
    var newPassword = $('#new-password').val();
    var sendOtp = $('#send-otp').val();
    
    // Clear previous errors
    $('#input-otp-error').html('');
    $('#new-password-error').html('');
    $('#forgot_msg').html('');
    
    // Validate OTP
    if (inputOtp == '') {
        $('#input-otp-error').html('{{ $language_name == "french" ? "Veuillez entrer l\'OTP" : "Please enter OTP" }}');
        return false;
    }
    
    if (inputOtp != sendOtp) {
        $('#input-otp-error').html('{{ $language_name == "french" ? "OTP invalide" : "Invalid OTP" }}');
        return false;
    }
    
    // Validate password
    if (newPassword == '') {
        $('#new-password-error').html('{{ $language_name == "french" ? "Veuillez entrer un nouveau mot de passe" : "Please enter new password" }}');
        return false;
    }
    
    if (newPassword.length < 8) {
        $('#new-password-error').html('{{ $language_name == "french" ? "Le mot de passe doit contenir au moins 8 caractères" : "Password must be at least 8 characters" }}');
        return false;
    }
    
    $('#account-change-pswd').prop('disabled', true);
    $('#account-change-pswd').html('{{ $language_name == "french" ? "Réinitialisation..." : "Resetting..." }}');
    
    $.ajax({
        type: 'POST',
        url: '{{ url("Logins/resetPassword") }}',
        data: {
            email: email,
            otp: inputOtp,
            password: newPassword,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(response) {
            $('#account-change-pswd').prop('disabled', false);
            $('#account-change-pswd').html('{{ $language_name == "french" ? "Réinitialiser le mot de passe" : "Reset Password" }}');
            
            if (response.status == 1) {
                $('#forgot_msg').html('<span style="color:green">' + response.msg + '</span>');
                setTimeout(function() {
                    window.location.href = '{{ url("Logins") }}';
                }, 2000);
            } else {
                $('#forgot_msg').html('<span style="color:red">' + response.msg + '</span>');
            }
        },
        error: function() {
            $('#account-change-pswd').prop('disabled', false);
            $('#account-change-pswd').html('{{ $language_name == "french" ? "Réinitialiser le mot de passe" : "Reset Password" }}');
            $('#forgot_msg').html('<span style="color:red">{{ $language_name == "french" ? "Une erreur s\'est produite. Veuillez réessayer." : "An error occurred. Please try again." }}</span>');
        }
    });
}
</script>
@endsection
