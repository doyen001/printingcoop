@extends('elements.app')

@section('title', $page_title ?? 'Change Password')

@section('content')
<style>
    /* Simple and Clean Change Password Styles */
    .account-section {
        padding: 60px 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .account-section-inner {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
    }

    /* Title Styling */
    .universal-dark-title {
        margin-bottom: 30px;
        text-align: center;
        position: relative;
    }

    .universal-dark-title span {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        position: relative;
        display: inline-block;
    }

    .universal-dark-title span::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: #f28738;
        border-radius: 2px;
    }

    /* Form Container */
    .shipping-form {
        background: #ffffff;
        border-radius: 8px;
        padding: 30px;
        border: 1px solid #e9ecef;
        margin-top: 20px;
    }

    /* Form Field Styling */
    .single-review {
        margin-bottom: 25px;
    }

    .single-review label {
        display: block;
        margin-bottom: 8px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
    }

    .single-review input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .single-review input:focus {
        outline: none;
        border-color: #f28738;
        box-shadow: 0 0 0 2px rgba(242, 135, 56, 0.1);
    }

    .single-review input[readonly] {
        background: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* Error Label Styling */
    .single-review label[style*="color:red"] {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        font-weight: 500;
        color: #dc3545 !important;
    }

    /* Message Container */
    /* #forgot_msg {
        color: #dc3545;
        font-weight: 500;
        margin-bottom: 20px;
        padding: 12px;
        border-radius: 6px;
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
    } */

    /* reCAPTCHA Styling */
    .g-recaptcha {
        margin: 15px 0;
        transform: scale(0.95);
        transform-origin: left top;
    }

    /* Button Styling */
    .order-btn {
        text-align: center;
        margin-top: 30px;
    }

    /* .order-btn button {
        background: #f28738;
        color: #ffffff;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        min-width: 120px;
    }

    .order-btn button:hover {
        background: #e67628;
        transform: translateY(-1px);
        text-decoration: none;
        color: #ffffff;
    }

    .order-btn button:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(242, 135, 56, 0.3);
    } */

    /* OTP Container */
    .change-pswd-field-show {
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid #e9ecef;
    }

    /* Required Field Indicator */
    .text-danger {
        color: #dc3545 !important;
        font-weight: 600;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .account-section {
            padding: 40px 0;
        }

        .account-section-inner {
            padding: 20px;
        }

        .shipping-form {
            padding: 20px;
        }

        .universal-dark-title span {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 767px) {
        .account-section {
            padding: 20px 0;
        }

        .account-section-inner {
            padding: 15px;
            border-radius: 8px;
        }

        .shipping-form {
            padding: 15px;
        }

        .universal-dark-title span {
            font-size: 1.6rem;
        }

        .single-review {
            margin-bottom: 20px;
        }

        .single-review input {
            padding: 10px 14px;
            font-size: 13px;
        }

        .order-btn button {
            width: 100%;
            padding: 14px 20px;
        }

        .g-recaptcha {
            transform: scale(0.85);
        }
    }

    @media (max-width: 480px) {
        .universal-dark-title span {
            font-size: 1.4rem;
        }

        .single-review label {
            font-size: 13px;
        }

        .single-review input {
            padding: 8px 12px;
            font-size: 12px;
        }

        .g-recaptcha {
            transform: scale(0.75);
        }
    }
</style>

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
