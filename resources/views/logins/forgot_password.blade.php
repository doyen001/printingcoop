{{-- CI: application/views/Logins/forgot_password.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Forgot Password')

@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-100">
                    @if($language_name == 'french')
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-3xl font-bold text-gray-900 mb-2">Récupérez votre mot de passe</h2>
                                <p class="text-gray-600 text-sm leading-relaxed">Saisissez votre email ci-dessous. Nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>
                            </div>
                            <form id="password-form" method="post" class="space-y-6">
                                @csrf
                                <div id="forgot_msg" class="text-center text-sm font-medium"></div>
                                <div class="space-y-5">
                                    <div>
                                        <label for="account-email" class="block text-sm font-medium text-gray-700 mb-2">Adresse électronique:</label>
                                        <input type="email" name="account_email" id="account-email" 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                               placeholder="votre@email.com">
                                        <label id="account-email-error" class="text-red-500 text-sm mt-1 block"></label>
                                        <input type="hidden" name="send_otp" id="send-otp" value="">
                                    </div>
                                    <div>
                                        <div class="g-recaptcha" data-sitekey="6LcXjt4UAAAAAMf-gtro8dDUsHGFBOtpfePKAifa"></div>
                                        <label id="g-recaptcha-error" class="text-red-500 text-sm mt-1 block"></label>
                                    </div>
                                    <div id="otp-container" class="hidden space-y-5 p-5 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">OTP envoyé au mobile</span>
                                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition duration-200" onclick="sendOptToEmail()">Renvoyer?</button>
                                        </div>
                                        <div>
                                            <label for="input-otp" class="block text-sm font-medium text-gray-700 mb-2">Entrez OTP</label>
                                            <input type="text" name="input_otp" id="input-otp" maxlength="6"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-center text-lg font-mono"
                                                   placeholder="000000">
                                            <label id="input-otp-error" class="text-red-500 text-sm mt-1 block"></label>
                                        </div>
                                        <div>
                                            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">Définir le mot de passe</label>
                                            <input type="password" name="new_password" id="new-password" maxlength="20" minlength="8"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                   placeholder="••••••••">
                                            <label id="new-password-error" class="text-red-500 text-sm mt-1 block"></label>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" id="account-change-pswd" onclick="sendOptToEmail()"
                                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]">
                                            Continuer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-3xl font-bold text-gray-900 mb-2">Retrieve your password here</h2>
                                <p class="text-gray-600 text-sm leading-relaxed">Please enter your email address below. You will receive a link to reset your password.</p>
                            </div>
                            <form id="password-form" method="post" class="space-y-6">
                                @csrf
                                <div id="forgot_msg" class="text-center text-sm font-medium"></div>
                                <div class="space-y-5">
                                    <div>
                                        <label for="account-email" class="block text-sm font-medium text-gray-700 mb-2">Email Address:</label>
                                        <input type="email" name="account_email" id="account-email"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                               placeholder="your@email.com">
                                        <label id="account-email-error" class="text-red-500 text-sm mt-1 block"></label>
                                        <input type="hidden" name="send_otp" id="send-otp" value="">
                                    </div>
                                    <div>
                                        <div class="g-recaptcha" data-sitekey="6LcXjt4UAAAAAMf-gtro8dDUsHGFBOtpfePKAifa"></div>
                                        <label id="g-recaptcha-error" class="text-red-500 text-sm mt-1 block"></label>
                                    </div>
                                    <div id="otp-container" class="hidden space-y-5 p-5 bg-gray-50 rounded-lg border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">OTP sent to Mobile</span>
                                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition duration-200" onclick="sendOptToEmail()">Resend?</button>
                                        </div>
                                        <div>
                                            <label for="input-otp" class="block text-sm font-medium text-gray-700 mb-2">Enter OTP</label>
                                            <input type="text" name="input_otp" id="input-otp" maxlength="6"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 text-center text-lg font-mono"
                                                   placeholder="000000">
                                            <label id="input-otp-error" class="text-red-500 text-sm mt-1 block"></label>
                                        </div>
                                        <div>
                                            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">Set Password</label>
                                            <input type="password" name="new_password" id="new-password" maxlength="20" minlength="8"
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                                   placeholder="••••••••">
                                            <label id="new-password-error" class="text-red-500 text-sm mt-1 block"></label>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="button" id="account-change-pswd" onclick="sendOptToEmail()"
                                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]">
                                            Continue
                                        </button>
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
