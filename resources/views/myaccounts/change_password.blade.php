{{-- CI: application/views/Logins/forgot_password.php --}}
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
