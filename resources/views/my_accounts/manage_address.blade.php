{{-- CI: application/views/MyAccounts/manage_address.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'Manage Address')

@section('content')
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
