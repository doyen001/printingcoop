{{-- CI: application/views/MyAccounts/edit_account.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Edit Account')

@section('content')
<div class="account-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="account-section-inner">
            @include('elements.my-account-menu')
            
            <div class="account-area">
                <div class="universal-dark-title">
                    <span>{{ $language_name == 'french' ? 'Vos informations personnelles' : 'Your Personal Details' }}</span>
                    @if(isset($postData['email_verification']) && $postData['email_verification'] == 0)
                        <div class="verify">
                            <span class="verify-email mt-5" style="color:red">
                                <small>{{ $language_name == 'french' ? 'Vérifiez votre e-mail' : 'Verify your email' }}</small>
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                </div>
                <div class="text-center" style="color:green">
                    {{ session('message_success') }}
                </div>
                
                <div class="shipping-form">
                    <form method="POST" action="{{ url('MyAccounts/EditAccount') }}" class="form-horizontal">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Votre nom *' : 'Your Name *' }}</label>
                                    <input type="text" name="fname" value="{{ old('fname', $postData['fname'] ?? '') }}">
                                    <span class="text-danger">{{ $errors->first('fname') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Votre nom de famille *' : 'Your Last Name *' }}</label>
                                    <input type="text" name="lname" value="{{ old('lname', $postData['lname'] ?? '') }}">
                                    <span class="text-danger">{{ $errors->first('lname') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Votre adresse email *' : 'Your Email Address *' }}</label>
                                    <input type="text" name="email" value="{{ $postData['email'] ?? '' }}" readonly>
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <label>{{ $language_name == 'french' ? 'Ton téléphone *' : 'Your Phone *' }}</label>
                                    <input type="text" name="mobile" value="{{ old('mobile', $postData['mobile'] ?? '') }}">
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-btn">
                            <button type="submit">{{ $language_name == 'french' ? 'sauver' : 'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
