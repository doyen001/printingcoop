{{-- CI: application/views/MyAccounts/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'My Account')

@section('content')
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
