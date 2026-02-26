@extends('elements.app')

@section('title', $page_title ?? 'My Account')

@section('content')
<style>
    /* Simple and Clean My Account Styles */
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

    /* Verification Messages */
    .verify {
        margin-top: 15px;
        text-align: center;
    }

    /* .verify-email {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .verify-email[style*="color:green"] {
        background: rgba(40, 167, 69, 0.1);
        border: 1px solid rgba(40, 167, 69, 0.2);
        color: #28a745 !important;
    } */

    /* Message Styling */
    /* .text-center[style*="color:red"] {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 20px;
        border-radius: 6px;
        border: 1px solid #f5c6cb;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .text-center[style*="color:green"] {
        background: #d4edda;
        color: #155724;
        padding: 12px 20px;
        border-radius: 6px;
        border: 1px solid #c3e6cb;
        margin-bottom: 20px;
        font-weight: 500;
    } */

    /* Form Styling */
    .shipping-form {
        background: #ffffff;
        border-radius: 8px;
        padding: 30px;
        border: 1px solid #e9ecef;
        margin-top: 20px;
    }

    .form-horizontal {
        margin: 0;
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
        background: #f8f9fa;
    }

    .single-review input:focus {
        outline: none;
        border-color: #f28738;
        box-shadow: 0 0 0 2px rgba(242, 135, 56, 0.1);
        background: #ffffff;
    }

    .single-review input[readonly] {
        background: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* Row Spacing */
    .row {
        margin-bottom: 20px;
    }

    .row:last-child {
        margin-bottom: 0;
    }

    /* Edit Button Styling */
    .order-btn {
        text-align: center;
        margin-top: 30px;
    }

    .order-btn a {
        text-decoration: none;
        display: inline-block;
    }

    .order-btn button {
        background: #f28738;
        color: #ffffff;
        border: none;
        padding: 9px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .order-btn button:hover {
        background: #e67628;
        transform: translateY(-1px);
        text-decoration: none;
        color: #ffffff;
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
    }
</style>

<div class="account-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="account-section-inner">
            @include('elements.my-account-menu')
            
            <div class="account-area">
                <div class="universal-dark-title">
                    <span>
                        {{ $language_name == 'french' ? 'Vos informations personnelles' : 'Your Personal Details' }}
                    </span>
                    @if(isset($postData->email_verification) && $postData->email_verification == 0)
                        <div class="verify">
                            <span class="verify-email mt-5" style="color:red">
                                <small>{{ $language_name == 'french' ? 'Vérifiez votre e-mail' : 'Verify your email' }}</small>
                            </span>
                        </div>
                    @endif
                    @if(isset($postData->user_type) && $postData->user_type == 2)
                        <div class="verify">
                            <span class="verify-email mt-5" style="color:green">
                                <small>{{ $language_name == 'french' ? 'Custome préféré' : 'Preferred Customer' }}</small>
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                </div>
                <div class="text-center" style="color:green">
                    {{ session('message_success') }}
                </div><br>
                
                <div class="shipping-form">
                    <form class="form-horizontal">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Code client' : 'Customer Code' }}</label>
                                    <input type="text" name="customer_code" value="{{ isset($postData->id) ? 'C' . str_pad($postData->id, 6, '0', STR_PAD_LEFT) : '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'votre nom' : 'Your Name' }} *</label>
                                    <input type="text" name="fname" value="{{ $postData->fname ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Votre nom de famille' : 'Your Last Name' }} *</label>
                                    <input type="text" name="lname" value="{{ $postData->lname ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Votre adresse email' : 'Your Email Address' }} *</label>
                                    <input type="text" name="email" value="{{ $postData->email ?? '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Ton téléphone' : 'Your Phone' }} *</label>
                                    <input type="text" name="mobile" value="{{ $postData->mobile ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="order-btn">
                            <a href="{{ url('MyAccounts/EditAccount') }}">
                                <button type="button">{{ $language_name == 'french' ? 'Éditer' : 'Edit' }}</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
