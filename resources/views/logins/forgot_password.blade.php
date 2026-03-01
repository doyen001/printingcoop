{{-- CI: application/views/Logins/forgot_password.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Forgot Password')

@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
    .forgot-section {
        padding: 80px 16px;
        background: radial-gradient(circle at top left, #fff7f0 0%, #fef9f5 45%, #fff3e7 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .forgot-card {
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 26px 70px rgba(15, 23, 42, 0.18);
        border: 1px solid rgba(148, 163, 184, 0.25);
        padding: 32px 28px 26px;
    }

    .forgot-avatar-wrapper {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }

    .forgot-avatar {
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

    .forgot-title {
        font-size: 22px;
        font-weight: 600;
        color: #111827;
        text-align: center;
        margin-bottom: 6px;
    }

    .forgot-subtitle {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
        text-align: center;
        margin-bottom: 20px;
    }

    #forgot_msg {
        margin-bottom: 12px;
        font-size: 14px;
    }

    .forgot-form {
        margin-top: 8px;
    }

    .single-review {
        margin-bottom: 18px;
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

    .otp-container {
        display: none;
        padding: 16px 16px 14px;
        border-radius: 14px;
        border: 1px solid #e5e7eb;
        background: #f9fafb;
        margin-top: 4px;
        margin-bottom: 8px;
    }

    .otp-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        font-size: 13px;
        color: #6b7280;
    }

    .otp-resend-btn {
        background: none;
        border: none;
        padding: 0;
        font-size: 13px;
        color: #f28738;
        font-weight: 600;
        cursor: pointer;
    }

    .otp-resend-btn:hover {
        text-decoration: underline;
    }

    .otp-input {
        text-align: center;
        font-size: 16px;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    .error-label {
        color: #dc2626;
        font-size: 13px;
        margin-top: 4px;
        display: block;
    }

    .forgot-btn {
        margin-top: 4px;
    }

    .forgot-btn button {
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

    .forgot-btn button:hover {
        transform: translateY(-1px);
        background: linear-gradient(135deg, #e67628, #ff6b35);
    }

    .forgot-btn button:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .forgot-back-link {
        margin-top: 18px;
        text-align: center;
        font-size: 13px;
        color: #6b7280;
    }

    .forgot-back-link a {
        color: #f28738;
        font-weight: 600;
        text-decoration: none;
    }

    .forgot-back-link a:hover {
        text-decoration: underline;
    }

    @media (max-width: 767px) {
        .forgot-section {
            padding: 30px 0;
        }
    }
</style>

<div class="forgot-section">
    <div class="forgot-card">
        <!-- <div class="forgot-avatar-wrapper">
            <div class="forgot-avatar">
                {{ strtoupper(substr(config('store.name', 'PC'), 0, 2)) }}
            </div>
        </div> -->

        @if($language_name == 'french')
            <div class="forgot-title">Récupérez votre mot de passe</div>
            <div class="forgot-subtitle">
                Saisissez votre email ci-dessous. Nous vous enverrons un code pour réinitialiser votre mot de passe.
            </div>
        @else
            <div class="forgot-title">Retrieve your password</div>
            <div class="forgot-subtitle">
                Please enter your email address below. We’ll send you a code to reset your password.
            </div>
        @endif

        <div id="forgot_msg" class="text-center text-sm font-medium"></div>

        <form id="password-form" method="post" class="forgot-form">
            @csrf
            <div class="single-review">
                <label for="account-email">
                    {{ $language_name == 'french' ? 'Adresse électronique:' : 'Email Address:' }}
                </label>
                <input type="email" name="account_email" id="account-email" placeholder="you@example.com">
                <label id="account-email-error" class="error-label"></label>
                <input type="hidden" name="send_otp" id="send-otp" value="">
            </div>

            <div class="single-review">
                <div class="g-recaptcha" data-sitekey="6LcXjt4UAAAAAMf-gtro8dDUsHGFBOtpfePKAifa"></div>
                <label id="g-recaptcha-error" class="error-label"></label>
            </div>

            <div id="otp-container" class="otp-container">
                <div class="otp-header">
                    <span>
                        {{ $language_name == 'french' ? 'OTP envoyé à votre email' : 'OTP sent to your email' }}
                    </span>
                    <button type="button" class="otp-resend-btn" onclick="sendOptToEmail()">
                        {{ $language_name == 'french' ? 'Renvoyer ?' : 'Resend?' }}
                    </button>
                </div>
                <div class="single-review">
                    <label for="input-otp">
                        {{ $language_name == 'french' ? 'Entrez OTP' : 'Enter OTP' }}
                    </label>
                    <input type="text" name="input_otp" id="input-otp" maxlength="6" class="otp-input" placeholder="000000">
                    <label id="input-otp-error" class="error-label"></label>
                </div>
                <div class="single-review">
                    <label for="new-password">
                        {{ $language_name == 'french' ? 'Définir le mot de passe' : 'Set Password' }}
                    </label>
                    <input type="password" name="new_password" id="new-password" maxlength="20" minlength="8" placeholder="••••••••">
                    <label id="new-password-error" class="error-label"></label>
                </div>
            </div>

            <div class="forgot-btn">
                <button type="button" id="account-change-pswd" onclick="sendOptToEmail()">
                    {{ $language_name == 'french' ? 'Continuer' : 'Continue' }}
                </button>
            </div>
        </form>

        <div class="forgot-back-link">
            @if($language_name == 'french')
                Vous vous souvenez de votre mot de passe ?
                <a href="{{ url('Logins') }}">Retour à la connexion</a>
            @else
                Remember your password?
                <a href="{{ url('Logins') }}">Back to login</a>
            @endif
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
                    $('#forgot_msg').html('<span class="text-green-600">' + response.msg + '</span>');
                    $('#account-change-pswd').attr('onclick', 'resetPassword()');
                } else {
                    $('#forgot_msg').html('<span class="text-red-600">' + response.msg + '</span>');
                }
            },
            error: function() {
                $('#account-change-pswd').prop('disabled', false);
                $('#account-change-pswd').html('{{ $language_name == "french" ? "Continuer" : "Continue" }}');
                $('#forgot_msg').html('<span class="text-red-600">{{ $language_name == "french" ? "Une erreur s\'est produite. Veuillez réessayer." : "An error occurred. Please try again." }}</span>');
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
                $('#forgot_msg').html('<span class="text-green-600">' + response.msg + '</span>');
                setTimeout(function() {
                    window.location.href = '{{ url("Logins") }}';
                }, 2000);
            } else {
                $('#forgot_msg').html('<span class="text-red-600">' + response.msg + '</span>');
            }
        },
        error: function() {
            $('#account-change-pswd').prop('disabled', false);
            $('#account-change-pswd').html('{{ $language_name == "french" ? "Réinitialiser le mot de passe" : "Reset Password" }}');
            $('#forgot_msg').html('<span class="text-red-600">{{ $language_name == "french" ? "Une erreur s\'est produite. Veuillez réessayer." : "An error occurred. Please try again." }}</span>');
        }
    });
}
</script>
@endsection
