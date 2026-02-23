{{-- CI: application/views/elements/header-mid-bar.php --}}
@section('content')
<div class="header-mid-bar">
    <div class="container">
        <div class="header-mid-bar-inner">
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-3 col-xl-3">
                    <div class="site-logo">
                        <div class="menu-bar">
                            <i class="las la-bars" data-toggle="dropdown" aria-expanded="false" onclick="openNav01()"></i>
                        </div>
                        <a href="{{ url('/') }}">
                            @php
                                $imageurl = '';
                                $alt = '';
                                if (!empty($configurations['logo_image'])) {
                                    if ($language_name == 'french') {
                                        $imageurl = url('uploads/logo/' . ($configurations['logo_image_french'] ?? $configurations['logo_image']));
                                        $alt = $configurations['log_alt_teg_french'] ?? '';
                                    } else {
                                        $imageurl = url('uploads/logo/' . $configurations['logo_image']);
                                        $alt = $configurations['log_alt_teg'] ?? '';
                                    }
                                }
                            @endphp
                            
                            @if($imageurl)
                                <img src="{{ $imageurl }}" width="100" alt="{{ $alt }}">
                            @else
                                <img src="{{ url('assets/images/printing.coopLogo.png') }}" alt="Digital and Offset Printing">
                            @endif
                        </a>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4 col-xl-5">
                    <div class="mid-search-bar">
                        <span>
                            <input type="text" placeholder="Search..." onkeyup="searchProduct($(this).val())" id="ToSeachBox">
                            <i class="las la-search"></i>
                        </span>
                        <div class="open-search-dropdown" style="display:none" id="searchDiv">
                            <div class="search-product-section">
                                <div class="search-product-section-title">
                                    <span>
                                        {{ $language_name == 'french' ? 'Résultats de recherche' : 'Search Results' }}
                                    </span>
                                </div>
                                <div class="search-product-result">
                                    <span style="color:black; border:0px;" id="coming-res-data">
                                        {{ $language_name == 'french' ? 'Le résultat de la recherche arrive ...' : 'Search result is coming...' }}
                                    </span>
                                    <ul id="ProductListUl"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-5 col-xl-4">
                    <div class="mid-action-area">
                        <ul>
                            {{-- Help/Contact (CI lines 61-124) --}}
                            <li>
                                <div class="mid-action-single">
                                    <a href="tel:18773848043">
                                        <div class="mid-action-single-inner">
                                            @if($website_store_id == 1)
                                                <div class="mid-action-icon">
                                                    <svg version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                        <g>
                                                            <path fill="#f58634" d="M26.2,19.9L26,20.7c-0.9,3-2.7,5.4-5.2,7c-0.2-1.2-1.3-2.1-2.5-2.1h-2c-1.4,0-2.6,1.2-2.6,2.6v0.4c0,1.4,1.2,2.6,2.6,2.6h2c0.8,0,1.6-0.4,2-1l0.3-0.2c3.4-1.8,5.9-4.8,7-8.6l0.3-0.9L26.2,19.9z M18.3,29.3h-2c-0.4,0-0.8-0.3-0.8-0.8v-0.4c0-0.4,0.3-0.8,0.8-0.8h2c0.4,0,0.8,0.3,0.8,0.8v0.4c0,0.1,0,0.1,0,0.2l0,0l0,0C18.9,29.1,18.6,29.3,18.3,29.3z"></path>
                                                            <path fill="#38454F" d="M28.4,13.5c0-6.9-5.6-12.5-12.5-12.5S3.4,6.7,3.4,13.5v1.1c0,0,0,0.1,0,0.1v3.8c0,2.2,1.8,4,4,4s4-1.8,4-4v-3.8c0-2.2-1.8-4-4-4c-0.7,0-1.3,0.2-1.9,0.5c1-4.8,5.3-8.4,10.4-8.4c5.1,0,9.4,3.6,10.4,8.4c-0.6-0.3-1.2-0.5-1.9-0.5c-2.2,0-4,1.8-4,4v3.8c0,2.2,1.8,4,4,4c2,0,3.7-1.5,3.9-3.5h0V13.5z M7.4,12.6c1.2,0,2.2,1,2.2,2.2v3.8c0,1.2-1,2.2-2.2,2.2s-2.2-1-2.2-2.2v-0.8h0v-3.1C5.3,13.5,6.2,12.6,7.4,12.6z M26.6,18.5c0,1.2-1,2.2-2.2,2.2s-2.2-1-2.2-2.2v-3.8c0-1.2,1-2.2,2.2-2.2s2.2,1,2.2,2.2V18.5z"></path>
                                                        </g>
                                                    </svg>
                                                </div>
                                            @elseif($website_store_id == 3)
                                                <div class="mid-action-icon">
                                                    <svg version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                        <g>
                                                            <path fill="#e72582" d="M26.2,19.9L26,20.7c-0.9,3-2.7,5.4-5.2,7c-0.2-1.2-1.3-2.1-2.5-2.1h-2c-1.4,0-2.6,1.2-2.6,2.6v0.4c0,1.4,1.2,2.6,2.6,2.6h2c0.8,0,1.6-0.4,2-1l0.3-0.2c3.4-1.8,5.9-4.8,7-8.6l0.3-0.9L26.2,19.9z M18.3,29.3h-2c-0.4,0-0.8-0.3-0.8-0.8v-0.4c0-0.4,0.3-0.8,0.8-0.8h2c0.4,0,0.8,0.3,0.8,0.8v0.4c0,0.1,0,0.1,0,0.2l0,0l0,0C18.9,29.1,18.6,29.3,18.3,29.3z"></path>
                                                            <path fill="#38454F" d="M28.4,13.5c0-6.9-5.6-12.5-12.5-12.5S3.4,6.7,3.4,13.5v1.1c0,0,0,0.1,0,0.1v3.8c0,2.2,1.8,4,4,4s4-1.8,4-4v-3.8c0-2.2-1.8-4-4-4c-0.7,0-1.3,0.2-1.9,0.5c1-4.8,5.3-8.4,10.4-8.4c5.1,0,9.4,3.6,10.4,8.4c-0.6-0.3-1.2-0.5-1.9-0.5c-2.2,0-4,1.8-4,4v3.8c0,2.2,1.8,4,4,4c2,0,3.7-1.5,3.9-3.5h0V13.5z M7.4,12.6c1.2,0,2.2,1,2.2,2.2v3.8c0,1.2-1,2.2-2.2,2.2s-2.2-1-2.2-2.2v-0.8h0v-3.1C5.3,13.5,6.2,12.6,7.4,12.6z M26.6,18.5c0,1.2-1,2.2-2.2,2.2s-2.2-1-2.2-2.2v-3.8c0-1.2,1-2.2,2.2-2.2s2.2,1,2.2,2.2V18.5z"></path>
                                                        </g>
                                                    </svg>
                                                </div>
                                            @elseif($website_store_id == 5)
                                                <div class="mid-action-icon">
                                                    <svg version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                        <g>
                                                            <path fill="#7aa93c" d="M26.2,19.9L26,20.7c-0.9,3-2.7,5.4-5.2,7c-0.2-1.2-1.3-2.1-2.5-2.1h-2c-1.4,0-2.6,1.2-2.6,2.6v0.4c0,1.4,1.2,2.6,2.6,2.6h2c0.8,0,1.6-0.4,2-1l0.3-0.2c3.4-1.8,5.9-4.8,7-8.6l0.3-0.9L26.2,19.9z M18.3,29.3h-2c-0.4,0-0.8-0.3-0.8-0.8v-0.4c0-0.4,0.3-0.8,0.8-0.8h2c0.4,0,0.8,0.3,0.8,0.8v0.4c0,0.1,0,0.1,0,0.2l0,0l0,0C18.9,29.1,18.6,29.3,18.3,29.3z"></path>
                                                            <path fill="#38454F" d="M28.4,13.5c0-6.9-5.6-12.5-12.5-12.5S3.4,6.7,3.4,13.5v1.1c0,0,0,0.1,0,0.1v3.8c0,2.2,1.8,4,4,4s4-1.8,4-4v-3.8c0-2.2-1.8-4-4-4c-0.7,0-1.3,0.2-1.9,0.5c1-4.8,5.3-8.4,10.4-8.4c5.1,0,9.4,3.6,10.4,8.4c-0.6-0.3-1.2-0.5-1.9-0.5c-2.2,0-4,1.8-4,4v3.8c0,2.2,1.8,4,4,4c2,0,3.7-1.5,3.9-3.5h0V13.5z M7.4,12.6c1.2,0,2.2,1,2.2,2.2v3.8c0,1.2-1,2.2-2.2,2.2s-2.2-1-2.2-2.2v-0.8h0v-3.1C5.3,13.5,6.2,12.6,7.4,12.6z M26.6,18.5c0,1.2-1,2.2-2.2,2.2s-2.2-1-2.2-2.2v-3.8c0-1.2,1-2.2,2.2-2.2s2.2,1,2.2,2.2V18.5z"></path>
                                                        </g>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="mid-action-content">
                                                <span>
                                                    <strong>{{ $language_name == 'french' ? "L'aide est là." : 'Help is here.' }}</strong>
                                                    {{ $configurations['contact_no'] ?? '1-877-384-8043' }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            
                            {{-- My Account (CI lines 125-188) --}}
                            @if(!empty($loginId))
                                <li>
                                    <div class="mid-action-single">
                                        <a href="{{ url('MyAccounts') }}">
                                            <div class="mid-action-single-inner">
                                                @if($website_store_id == 1)
                                                    <div class="mid-action-icon">
                                                        <svg version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                            <g>
                                                                <path fill="#f58634" d="M27.6,29.5L27,28.8c-2.8-3.3-6.8-5.1-11.2-5.1s-8.4,1.8-11.2,5.1L4,29.5l-1.4-1.2l0.6-0.7c3.1-3.7,7.6-5.7,12.6-5.7s9.4,2,12.6,5.7l0.6,0.7L27.6,29.5z"></path>
                                                                <path fill="#38454F" d="M15.8,19.3c-4.8,0-8.7-3.9-8.7-8.7C7.2,5.9,11,2,15.8,2s8.7,3.9,8.7,8.7C24.5,15.4,20.6,19.3,15.8,19.3z M15.8,3.8C12,3.8,9,6.8,9,10.6s3.1,6.9,6.9,6.9s6.9-3.1,6.9-6.9S19.6,3.8,15.8,3.8z"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                @elseif($website_store_id == 3)
                                                    <div class="mid-action-icon">
                                                        <svg version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                            <g>
                                                                <path fill="#e72582" d="M27.6,29.5L27,28.8c-2.8-3.3-6.8-5.1-11.2-5.1s-8.4,1.8-11.2,5.1L4,29.5l-1.4-1.2l0.6-0.7c3.1-3.7,7.6-5.7,12.6-5.7s9.4,2,12.6,5.7l0.6,0.7L27.6,29.5z"></path>
                                                                <path fill="#38454F" d="M15.8,19.3c-4.8,0-8.7-3.9-8.7-8.7C7.2,5.9,11,2,15.8,2s8.7,3.9,8.7,8.7C24.5,15.4,20.6,19.3,15.8,19.3z M15.8,3.8C12,3.8,9,6.8,9,10.6s3.1,6.9,6.9,6.9s6.9-3.1,6.9-6.9S19.6,3.8,15.8,3.8z"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                @elseif($website_store_id == 5)
                                                    <div class="mid-action-icon">
                                                        <svg version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                            <g>
                                                                <path fill="#7aa93c" d="M27.6,29.5L27,28.8c-2.8-3.3-6.8-5.1-11.2-5.1s-8.4,1.8-11.2,5.1L4,29.5l-1.4-1.2l0.6-0.7c3.1-3.7,7.6-5.7,12.6-5.7s9.4,2,12.6,5.7l0.6,0.7L27.6,29.5z"></path>
                                                                <path fill="#38454F" d="M15.8,19.3c-4.8,0-8.7-3.9-8.7-8.7C7.2,5.9,11,2,15.8,2s8.7,3.9,8.7,8.7C24.5,15.4,20.6,19.3,15.8,19.3z M15.8,3.8C12,3.8,9,6.8,9,10.6s3.1,6.9,6.9,6.9s6.9-3.1,6.9-6.9S19.6,3.8,15.8,3.8z"></path>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="mid-action-content">
                                                    <span>
                                                        <strong>{{ $loginName ?? 'User' }}</strong>
                                                        {{ $language_name == 'french' ? 'Mon compte' : 'My account' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @endif
                            
                            {{-- Cart (CI lines 189-267) --}}
                            <li>
                                <div class="mid-action-single">
                                    <div class="mid-action-single-inner">

                                        @php
                                            // Use CartService to get cart count
                                            $cartService = new \App\Services\CartService();
                                            $cartCount = $cartService->totalItems();
                                        @endphp

                                        @if($website_store_id == 1)
                                            <div class="mid-action-icon">
                                                <svg version="1.2" baseProfile="tiny" id="Layer_1" xmlns="https://www.w3.org/2000/svg"
                                                    xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 35 35"
                                                    height="35" width="35" class="header-link-icon" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <path fill="#38454F" d="M27.5,22.3H11.1c-0.4,0-0.7-0.2-0.9-0.6L4.5,4.4H0V2.6h5.1c0.4,0,0.7,0.2,0.9,0.6l2.2,6.4h23
                                                                c0.3,0,0.6,0.1,0.7,0.4c0.2,0.2,0.2,0.5,0.1,0.8l-3.6,10.9C28.2,22.1,27.9,22.3,27.5,22.3z M11.8,20.5h15.1l3-9.1H8.7L11.8,20.5z">
                                                            </path>
                                                        </g>
                                                        <g>
                                                            <circle fill="#f58634" cx="13.5" cy="26.7" r="2.2"></circle>
                                                        </g>
                                                        <g>
                                                            <circle fill="#f58634" cx="25" cy="26.7" r="2.2"></circle>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span class="cart-contents-count">{{ $cartCount }}</span>
                                            </div>

                                        @elseif($website_store_id == 3)
                                            <div class="mid-action-icon">
                                                <svg version="1.2" baseProfile="tiny" id="Layer_1" xmlns="https://www.w3.org/2000/svg"
                                                    xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 35 35"
                                                    height="35" width="35" class="header-link-icon" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <path fill="#38454F" d="M27.5,22.3H11.1c-0.4,0-0.7-0.2-0.9-0.6L4.5,4.4H0V2.6h5.1c0.4,0,0.7,0.2,0.9,0.6l2.2,6.4h23
                                                                c0.3,0,0.6,0.1,0.7,0.4c0.2,0.2,0.2,0.5,0.1,0.8l-3.6,10.9C28.2,22.1,27.9,22.3,27.5,22.3z M11.8,20.5h15.1l3-9.1H8.7L11.8,20.5z">
                                                            </path>
                                                        </g>
                                                        <g>
                                                            <circle fill="#e72582" cx="13.5" cy="26.7" r="2.2"></circle>
                                                        </g>
                                                        <g>
                                                            <circle fill="#e72582" cx="25" cy="26.7" r="2.2"></circle>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span class="cart-contents-count">{{ $cartCount }}</span>
                                            </div>

                                        @elseif($website_store_id == 5)
                                            <div class="mid-action-icon">
                                                <svg version="1.2" baseProfile="tiny" id="Layer_1" xmlns="https://www.w3.org/2000/svg"
                                                    xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 35 35"
                                                    height="35" width="35" class="header-link-icon" xml:space="preserve">
                                                    <g>
                                                        <g>
                                                            <path fill="#38454F" d="M27.5,22.3H11.1c-0.4,0-0.7-0.2-0.9-0.6L4.5,4.4H0V2.6h5.1c0.4,0,0.7,0.2,0.9,0.6l2.2,6.4h23
                                                                c0.3,0,0.6,0.1,0.7,0.4c0.2,0.2,0.2,0.5,0.1,0.8l-3.6,10.9C28.2,22.1,27.9,22.3,27.5,22.3z M11.8,20.5h15.1l3-9.1H8.7L11.8,20.5z">
                                                            </path>
                                                        </g>
                                                        <g>
                                                            <circle fill="#7aa93c" cx="13.5" cy="26.7" r="2.2"></circle>
                                                        </g>
                                                        <g>
                                                            <circle fill="#7aa93c" cx="25" cy="26.7" r="2.2"></circle>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span class="cart-contents-count">{{ $cartCount }}</span>
                                            </div>
                                        @endif

                                        <div class="mid-action-content">
                                            <span>
                                                <strong>{{ $language_name == 'French' ? 'Chariot' : 'Cart' }}</strong>
                                            </span>
                                        </div>

                                    </div>

                                    <div class="cart-items-table">
                                        @include('elements.cart-items')
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
