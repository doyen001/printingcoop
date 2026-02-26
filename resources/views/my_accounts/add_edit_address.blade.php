@extends('elements.app')

@section('title', $page_title ?? 'Add/Edit Address')

@section('content')
<style>
    /* Simple and Clean Add/Edit Address Styles */
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

    /* Edit Address Container */
    .edit-heading {
        background: #f28738;
        color: #ffffff;
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 30px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .edit-heading:hover {
        background: #e67628;
        transform: translateY(-1px);
    }

    /* .edit-heading i {
        font-size: 16px;
    }

    .edit-heading {
        margin-bottom: 25px;
        text-align: center;
    }

    .edit-heading label {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0;
    } */

    /* Form Container */
    .delivery-fileds {
        background: #ffffff;
        border-radius: 8px;
        padding: 30px;
        border: 1px solid #e9ecef;
        margin-bottom: 30px;
    }

    /* Form Field Styling */
    .single-review {
        margin-bottom: 20px;
    }

    .single-review input,
    .single-review textarea,
    .single-review select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .single-review input:focus,
    .single-review textarea:focus,
    .single-review select:focus {
        outline: none;
        border-color: #f28738;
        box-shadow: 0 0 0 2px rgba(242, 135, 56, 0.1);
    }

    .single-review textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* Error Label Styling */
    .single-review label[style*="color:red"] {
        display: block;
        margin-top: 5px;
        font-size: 12px;
        font-weight: 500;
        color: #dc3545 !important;
    }

    /* Address Type Styling */
    .address-type {
        background: #f8f9fa;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .address-type .single-review label {
        display: block;
        margin-bottom: 15px;
        color: #2c3e50;
        font-weight: 600;
        font-size: 14px;
    }

    .address-type input[type="radio"],
    .address-type input[type="checkbox"] {
        width: auto;
        margin-right: 8px;
        margin-bottom: 0;
    }

    .address-type label {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        color: #2c3e50;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
    }

    /* Button Styling */
    .save-btn {
        display: flex;
        gap: 15px;
        /* justify-content: center; */
        align-items: center;
    }

    .save-btn button {
        background: #f28738;
        color: #ffffff;
        border: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .save-btn button:hover {
        background: #e67628;
        transform: translateY(-1px);
    }

    .edit-address {
        border-color: #f28738;
        border-radius: 6px;
    }

    /* .save-btn a {
        color: #ffffff;
        text-decoration: none;
        padding: 12px 30px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.2s ease;
    }

    .save-btn a:hover {
        transform: translateY(-1px);
        text-decoration: none;
        color: #ffffff;
    } */

    /* Responsive Design */
    @media (max-width: 991px) {
        .account-section {
            padding: 40px 0;
        }

        .account-section-inner {
            padding: 20px;
        }

        .edit-address,
        .delivery-fileds {
            padding: 20px;
        }

        .universal-dark-title span {
            font-size: 1.8rem;
        }

        .edit-heading label {
            font-size: 1.4rem;
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

        .edit-address,
        .delivery-fileds {
            padding: 15px;
        }

        .universal-dark-title span {
            font-size: 1.6rem;
        }

        .edit-heading label {
            font-size: 1.3rem;
        }

        .single-review input,
        .single-review textarea,
        .single-review select {
            padding: 10px 14px;
            font-size: 13px;
        }

        .save-btn {
            flex-direction: column;
        }

        .save-btn button,
        .save-btn a {
            width: 100%;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .universal-dark-title span {
            font-size: 1.4rem;
        }

        .edit-heading label {
            font-size: 1.2rem;
        }

        .single-review input,
        .single-review textarea,
        .single-review select {
            padding: 8px 12px;
            font-size: 12px;
        }

        .address-type {
            padding: 15px;
        }
    }
</style>

<div class="account-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="account-section-inner">
            @include('elements.my-account-menu')
            <div class="account-area">
                <div class="universal-dark-title">
                    <span>{{ $page_title }}</span>
                </div>
                <div class="account-address-area">
                    <div class="edit-address" style="display:blok">
                        <div class="edit-heading">
                            <label id="edit">{{ $page_title }}</label>
                        </div>
                         <form method="post" id="add-new-address">
                        <div class="delivery-fileds">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="single-review">
                                       <input class="form-control" name="id"  type="hidden"  value="{{ $postData['id'] ?? '' }}" maxlength="50">

                                       <input class="form-control" name="first_name" id="first_name" type="text" placeholder="First Name *" value="{{ $postData['first_name'] ?? '' }}" maxlength="50">
                                       @if($errors->has('first_name'))
                                           <label style="color:red">{{ $errors->first('first_name') }}</label>
                                       @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-review">
                                        <input class="form-control" name="last_name" id="last_name" type="text" placeholder="Last Name *" value="{{ $postData['last_name'] ?? '' }}" maxlength="50">
                                       @if($errors->has('last_name'))
                                           <label style="color:red">{{ $errors->first('last_name') }}</label>
                                       @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-review">
                                       <input class="form-control" name="mobile" id="mobile" type="text" placeholder="Mobile Number*" value="{{ $postData['mobile'] ?? '' }}" maxlength="10">
                                        @if($errors->has('mobile'))
                                           <label style="color:red">{{ $errors->first('mobile') }}</label>
                                       @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="single-review">
                                        <input type="text" class="form-control" name="company_name" id="company_name" type="text" placeholder="company Name *" value="{{ $postData['company_name'] ?? '' }}" maxlength="50">
                                       @if($errors->has('company_name'))
                                           <label style="color:red">{{ $errors->first('company_name') }}</label>
                                       @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="single-review">

                                        <textarea style="height:150px;" name="address" placeholder="Address (area &amp; street)*">{{ $postData['address'] ?? '' }}</textarea>
                                       @if($errors->has('address'))
                                           <label style="color:red">{{ $errors->first('address') }}</label>
                                       @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="single-review">
                                      <select name="country" onchange="getState($(this).val())">
                                          @if($language_name == 'French')
                                          <option value="">-- Choisissez le pays --</option>
                                          @else
                                         <option value="">-- Select Country --</option>
                                          @endif

                                      @foreach($countries as $country)
                                          @php $selected = ''; @endphp
                                          @php $post_country = $postData['country'] ?? ''; @endphp
                                          @if($country->id == $post_country)
                                              @php $selected='selected="selected"'; @endphp
                                          @endif
                                      <option value="{{ $country->id }}" {{ $selected }}>{{ $country->name }}</option>
                                      @endforeach
                                    </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="single-review">
                                      <select name="state" id="stateiD"  onchange="getCity($(this).val())">
                                          @if($language_name == 'French')
                                          <option value="">-- Sélectionnez l'état --</option>
                                          @else
                                         <option value="">-- Select State --</option>
                                          @endif

                                      @foreach($states as $state)
                                      @php $selected=''; @endphp
                                      @php $post_state = $postData['state'] ?? ''; @endphp

                                      @if($state->id == $post_state)
                                            @php $selected='selected="selected"'; @endphp
                                      @endif
                                       <option value="{{ $state->id }}" {{ $selected }}>{{ $state->name }}
                                      </option>
                                @endforeach
                                </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="single-review">
                                      <select name="city" id="cityId">
                                          @if($language_name == 'French')
                                          <option value="">-- Sélectionnez une ville --</option>
                                          @else
                                         <option value="">-- Select City --</option>
                                          @endif

                                      @foreach($citys as $city)
                                          @php $selected =''; @endphp
                                          @php $post_city = $postData['city'] ?? ''; @endphp

                                            @if($city->id == $post_city)
                                              @php $selected='selected="selected"'; @endphp
                                            @endif
                                      <option value="{{ $city->id }}" {{ $selected }}>{{ $city->name }}</option>
                                      @endforeach
                                    </select>
                                     @if($errors->has('city'))
                                           <label style="color:red">{{ $errors->first('city') }}</label>
                                       @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="single-review">
                                         <input class="form-control" name="pin_code" id="pin_code" type="text" placeholder="Pin Code*" value="{{ $postData['pin_code'] ?? '' }}" maxlength="10">
                                        @if($errors->has('pin_code'))
                                           <label style="color:red">{{ $errors->first('pin_code') }}</label>
                                       @endif

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="address-type">
                            <div class="single-review">
                                <label>
                                @if($language_name == 'French')
                                  Type d'adresse
                                @else
                                 Address Type
                                @endif</label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    @php
                                        $address_type = $postData['address_type'] ?? '';
                                    @endphp
                                    <label id="home"><input name="address_type" value="home" for="home" type="radio"

                                    @if($address_type=='') echo 'checked'; @elseif($address_type=='home') echo 'checked'; @endif>>
                                        @if($language_name == 'French')
                                          Accueil (livraison toute la journée
                                        @else
                                         Home (All day delivery
                                        @endif)</label>
                                </div>
                                <div class="col-md-6">
                                    <label id="work"><input name="address_type" value="work" for="work" type="radio" @if($address_type=='work') echo 'checked'; @endif>>
                                    @if($language_name == 'French')
                                          Travail (livraison entre 10h et 17h)
                                        @else
                                         Work (Delivery between 10AM - 5PM)
                                        @endif</label>
                                </div>
                                <div class="col-md-6">
                                    @php
                                                $default_delivery_address = $postData['default_delivery_address'] ?? '';
                                                $cehecked='';
                                                if ($default_delivery_address==1) {
                                                    $cehecked='checked';
                                                }
                                    @endphp
                                    <label id="default_delivery_address">
                                    <input name="default_delivery_address" value="1" for="default_delivery_address" type="checkbox"
                                    {{ $cehecked }} style="width: auto;">
                                        @if($language_name == 'French')
                                          Créer une adresse de livraison par défaut
                                        @else
                                         Make a Default Delivery Address
                                        @endif</label>
                                </div>
                            </div>
                        </div>
                        <div class="save-btn">
                            <button class="save">Save</button>
                            <a href="{{ url('MyAccounts/manageAddress') }}">
                            @if($language_name == 'French')
                              Annuler
                            @else
                             Cancel
                            @endif</a>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
