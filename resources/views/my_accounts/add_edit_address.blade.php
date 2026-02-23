@extends('elements.app')

@section('title', $page_title ?? 'Add/Edit Address')

@section('content')
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
