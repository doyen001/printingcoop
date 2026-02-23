{{-- CI: application/views/MyAccounts/change_password.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Change Password')

@section('content')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="account-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="account-section-inner">
            @if(session('loginId'))
                @include('elements.my-account-menu')
            @endif
            
            <div class="row">
                <div class="col-md-6">
                    <div class="account-area">
                        <div class="shipping-area-title universal-dark-title">
                            <span>
                                @if(session('loginId'))
                                    {{ $language_name == 'french' ? 'Changez votre mot de passe' : 'Change Your Password' }}
                                @else
                                    {{ $language_name == 'french' ? 'Mot de passe oublié' : 'Forget Password' }}
                                @endif
                            </span>
                        </div>
                        
                        <form id="password-form" method="post">
                            @csrf
                            <div class="customer-fields pad-for-span" style="min-height: initial;">
                                <div id="forgot_msg" class="col-md-12 text-center"></div>
                            </div>
                            
                            <div class="shipping-form">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Adresse électronique:' : 'Email Address:' }}</label>
                                    <input type="email" name="account_email" id="account-email" 
                                           value="{{ session('loginEmail') ?? '' }}" 
                                           {{ session('loginEmail') ? 'readonly' : '' }}>
                                    <label id="account-email-error" style="color:red"></label>
                                    <input type="hidden" name="send_otp" id="send-otp" value="">
                                </div>
                                
                                <div class="single-review">
                                    <div class="g-recaptcha" data-sitekey="6LcXjt4UAAAAAMf-gtro8dDUsHGFBOtpfePKAifa"></div>
                                    <label id="g-recaptcha-error" style="color:red"></label>
                                </div>
                                
                                <div class="order-btn login-btn">
                                    <button type="button" class="login" id="account-change-pswd" onclick="sendOptToEmail()">
                                        {{ $language_name == 'french' ? 'Continuer' : 'Continue' }}
                                    </button>
                                </div>
                                
                                <div class="change-pswd-field-show" style="display:none;" id="otp-container">
                                    <div class="single-review">
                                        <label>
                                            {{ $language_name == 'french' ? 'Entrez OTP' : 'Enter OTP' }} 
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="input_otp" id="input-otp" placeholder="Enter Otp" maxlength="6">
                                        <label id="input-otp-error" style="color:red"></label>
                                    </div>
                                    
                                    <div class="single-review">
                                        <label>
                                            {{ $language_name == 'french' ? 'Definir un nouveau mot de passe' : 'Set New Password' }} 
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="password" placeholder="Set Password" name="new_password" 
                                               id="new-password" maxlength="20" minlength="8">
                                        <label id="new-password-error" style="color:red"></label>
                                    </div>
                                    
                                    <div class="order-btn">
                                        <button type="submit" id="Fsubmit">
                                            {{ $language_name == 'french' ? 'Soumettre' : 'Submit' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
