@extends('elements.app')

@section('title', $page_title ?? 'Manage Address')

@section('content')
<style>
    /* Simple and Clean Manage Address Styles */
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

    /* Add Address Button */
    .add-address-field {
        background: #f28738;
        color: #ffffff;
        border: none;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-bottom: 30px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .add-address-field:hover {
        background: #e67628;
        transform: translateY(-1px);
    }

    .add-address-field i {
        font-size: 16px;
    }

    /* Address Form Container */
    .delivery-fileds {
        background: #ffffff;
        border-radius: 8px;
        padding: 30px;
        border: 1px solid #e9ecef;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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

    .save-btn a {
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
    }

    /* Saved Address Boxes */
    .saved-address-box {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .saved-address-box:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .adrs-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .email-field-t {
        flex: 1;
    }

    .email-text-t {
        color: #2c3e50;
    }

    .address-type-name {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    /* .address-type-name.home {
        background: #e3f2fd;
        color: #1976d2;
    } */

    .address-type-name.work {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .email-text-t span {
        display: block;
        margin-bottom: 5px;
        line-height: 1.5;
    }

    .email-text-t .tt-t {
        font-weight: 500;
    }

    /* Dot Menu */
    .dot-menu {
        margin-left: 20px;
    }

    .dot-menu button {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .dot-menu button:hover {
        background: transparent;
        color: #2c3e50;
    }

    .dot-menu-section {
        position: absolute;
        right: 0;
        top: 100%;
        background: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
        min-width: 120px;
    }

    .dot-menu-section a {
        display: block;
        text-decoration: none;
        color: #2c3e50;
        padding: 8px 16px;
        transition: background-color 0.2s ease;
    }

    .dot-menu-section a:hover {
        background: #f8f9fa;
        text-decoration: none;
        color: #2c3e50;
    }

    .dot-menu-section button {
        background: none;
        border: none;
        color: #2c3e50;
        padding: 0;
        font-size: 14px;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    /* Empty State */
    .account-address-area p {
        text-align: center;
        padding: 40px;
        color: #6c757d;
        font-size: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .account-section {
            padding: 40px 0;
        }

        .account-section-inner {
            padding: 20px;
        }

        .delivery-fileds {
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

        .delivery-fileds {
            padding: 15px;
        }

        .universal-dark-title span {
            font-size: 1.6rem;
        }

        .add-address-field {
            width: 100%;
            justify-content: center;
            padding: 14px 20px;
        }

        .single-review input,
        .single-review textarea,
        .single-review select {
            padding: 10px 14px;
            font-size: 13px;
        }

        .adrs-section {
            flex-direction: column;
        }

        .dot-menu {
            margin-left: 0;
            margin-top: 15px;
            align-self: flex-end;
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

        .single-review input,
        .single-review textarea,
        .single-review select {
            padding: 8px 12px;
            font-size: 12px;
        }

        .address-type {
            padding: 15px;
        }

        .saved-address-box {
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
                    <span>
                    @if($language_name == 'french')
                      Vos entrées d'adresse
                    @else
                      Your Address Entries
                    @endif</span>
                </div>
                <div class="account-address-area">
                     <button class="add-address-field" id="new-address"><i class="las la-plus"></i>
                     @if($language_name == 'french')
                      Ajouter une nouvelle adresse de livraison
                    @else
                      Add a new shipping address
                    @endif</button>
                    <form method="post" id="add-new-address">
                    <div class="delivery-fileds" id="checkout-new-address" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="single-review">
                                    <input type="text" placeholder="First Name*" name="first_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <input type="text" placeholder="Last Name*" name="last_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <input type="text" placeholder="Phone Number*" name="mobile">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <input type="text" placeholder="Company Name" name="company_name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="single-review">
                                    <textarea style="height:150px;" type="text" placeholder="Address (area &amp; street)*"name="address"></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="single-review">
                                    <select name="country" onchange="getState($(this).val())">
                                        @if($language_name == 'french')
                                          <option value="">-- Choisissez le pays --</option>
                                        @else
                                          <option value="">-- Select Country --</option>
                                        @endif

                                      @foreach($countries as $country)
                                          @php $selected = ''; @endphp
                                          @php $post_country = isset($postData['country']) ? $postData['country'] : ''; @endphp
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
                                    <select name="state" id="stateiD" onchange="getCity($(this).val())">
                                        @if($language_name == 'french')
                                          <option value="">-- Sélectionnez l'état --</option>
                                        @else
                                          <option value="">-- Select State --</option>
                                        @endif

                                      @if(isset($states))
                                      @foreach($states as $state)
                                          @php $selected =''; @endphp
                                          @php $post_state = isset($postData['state']) ? $postData['state'] : ''; @endphp
                                          @if($state->id == $post_state)
                                              @php $selected='selected="selected"'; @endphp
                                          @endif
                                      <option value="{{ $state->id }}" {{ $selected }}>{{ $state->name }}</option>
                                      @endforeach
                                      @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="single-review">
                                   <select name="city" id="cityId">
                                       @if($language_name == 'french')
                                          <option value="">-- Sélectionnez une ville --</option>
                                        @else
                                          <option value="">-- Select City --</option>
                                        @endif

                                      @if(isset($citys))
                                      @foreach($citys as $city)
                                          @php $selected =''; @endphp
                                          @php $post_city = isset($postData['city']) ? $postData['city'] : ''; @endphp

                                            @if($city->id == $post_city)
                                              @php $selected='selected="selected"'; @endphp
                                            @endif
                                      <option value="{{ $city->id }}" {{ $selected }}>{{ $city->name }}</option>
                                      @endforeach
                                      @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="single-review">
                                    <input type="text" placeholder="Zip/Postal Code*" name="pin_code">
                                </div>
                            </div>
                            <div class="col-md-12">
                              <div class="address-type">
                                  <div class="single-review">
                                      <label>
                                      @if($language_name == 'french')
                                          Type d'adresse
                                        @else
                                          Address Type
                                        @endif</label>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <label id="home"><input name="address_type" value="home" for="home" type="radio" checked="">
                                          @if($language_name == 'french')
                                          Accueil (livraison toute la journée)
                                        @else
                                          Home (All day delivery)
                                        @endif</label>
                                      </div>
                                      <div class="col-md-6">
                                          <label id="work"><input name="address_type" value="work" for="work" type="radio">
                                          @if($language_name == 'french')
                                          Travail (livraison entre 10h et 17h)
                                        @else
                                          Work (Delivery between 10AM - 5PM)
                                        @endif</label>
                                      </div>
                                      <div class="col-md-6">

                                    <label id="default_delivery_address">
                                    <input name="default_delivery_address" value="1" for="default_delivery_address" type="checkbox"
                                     style="width: auto;">
                                     @if($language_name == 'french')
                                          Créer une adresse de livraison par défaut
                                        @else
                                          Make a Default Delivery Address
                                        @endif
                                    </label>
                                    </div>
                                  </div>
                              </div>
                            </div>
                            <div class="col-md-12">
                              <div class="save-btn login-btn">
                                  <button class="save" type="submit" id="save-address">
                                  @if($language_name == 'french')
                                          sauver
                                        @else
                                          Save
                                        @endif</button>
                                  <a id="cancel-address" href="javascript:void(0)">
                                  @if($language_name == 'french')
                                          Annuler
                                        @else
                                          Cancel
                                        @endif</a>
                              </div>
                            </div>
                        </div>
                    </div>
                </form>
                 <div id="address-list">
               @if(isset($address) && count($address) > 0)
               @foreach($address as $list)
                <div class="saved-address-box">
                    <div class="adrs-section">
                        <div class="email-field-t">
                            <div class="email-text-t">
                                <span class="address-type-name {{ $list->address_type ?? 'home' }}">{{ ucfirst($list->address_type ?? 'home') }}
                                @if($list->default_delivery_address==1)
                                   (Default Delivery Address)
                                @endif
                                </span>
                                <br>
                                <span>{{ ucfirst($list->name ?? '') }} {{ $list->mobile ?? '' }} {{ !empty($list->alternate_phone) ? ','.$list->alternate_phone : '' }}
                                 {{ !empty($list->company_name) ? '('.$list->company_name.")":'' }}
                                </span>

                                <br>
                                <span class="tt-t">{{ $list->address ?? '' }},
                                {{ $list->cityName ?? '' }}, {{ $list->StateName ?? '' }}, {{ $list->CountryName ?? '' }} - <strong>{{ $list->pin_code ?? '' }}</strong></span>

                            </div>
                            <div class="dot-menu">
                                <button type="submit"><i class="fa fas fa-ellipsis-v"></i></button>
                                <div class="dot-menu-section">
                                    <a href="{{ url('MyAccounts/addEditAddress/' . base64_encode($list->id)) }}">
                                    <button type="submit">
                                    @if($language_name == 'french')
                                          Éditer
                                        @else
                                          edit
                                        @endif</button>
                                    </a>
                                    <a href="{{ url('MyAccounts/deleteAddress/' . base64_encode($list->id)) }}" onclick="return confirm('are you sure you wish to delete this address.');">
                                    <button type="submit">
                                    @if($language_name == 'french')
                                          supprimer
                                        @else
                                          delete
                                        @endif</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               @endforeach
               @else
               <p style="text-align: center; padding: 20px;">{{ $language_name == 'french' ? 'Aucune adresse trouvée' : 'No addresses found' }}</p>
               @endif
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
