{{-- CI: application/views/elements/header-mid-bar.php --}}
<style>
/* ===== Combined Header Bar (Logo + Search + Actions) ===== */
.header-mid-bar {
    background: #ffffff;
    /* border-bottom: 1px solid #e9ecef; */
    position: relative;
    z-index: 1000;
}

.header-mid-bar .container {
    max-width: 1400px;
}

.header-mid-bar-inner {
    padding: 10px 0;
}

/* Logo */
.header-mid-bar .site-logo img {
    max-height: 45px;
    width: auto;
}

/* Search Bar */
.mid-search-bar {
    position: relative;
    width: 100%;
}

.mid-search-bar span {
    display: flex;
    align-items: center;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 25px;
    padding: 6px 15px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.mid-search-bar span:focus-within {
    border-color: #387dde;
    outline: 3px solid #d8e6ff;
}

.header-mid-bar .mid-search-bar input {
    height: 32px;
}

.mid-search-bar input[type="text"] {
    border: none;
    outline: none;
    background: transparent;
    flex: 1;
    font-size: 14px;
    color: #333;
    padding: 0 8px;
    font-weight: 500;
}

.mid-search-bar input[type="text"]::placeholder {
    color: #999;
    font-weight: 400;
}

.mid-search-bar .la-search {
    color: #999;
    font-size: 18px;
    min-width: 20px;
    border: none;
    background: transparent;
}

/* Search Dropdown */
.open-search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    margin-top: 8px;
    z-index: 1000;
    max-height: 400px;
    overflow-y: auto;
    animation: dropdownSlide 0.3s ease;
}

@keyframes dropdownSlide {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.search-product-section { padding: 16px; }
.search-product-section-title {
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 12px;
}
.search-product-section-title span {
    font-size: 14px; font-weight: 600; color: #333;
    text-transform: uppercase; letter-spacing: 0.5px;
}
.search-product-result { min-height: 60px; }
.search-product-result #coming-res-data {
    display: block; text-align: center; color: #999;
    font-size: 14px; padding: 20px; font-style: italic;
}
.search-product-result #ProductListUl { list-style: none; margin: 0; padding: 0; }
.search-product-result #ProductListUl li {
    padding: 12px 16px; border-bottom: 1px solid #f5f5f5;
    cursor: pointer; transition: all 0.3s ease;
    display: flex; align-items: center; gap: 12px;
}
.search-product-result #ProductListUl li:hover { background-color: #f8f9fa; padding-left: 20px; }
.search-product-result #ProductListUl li:last-child { border-bottom: none; }
.search-product-result #ProductListUl li .product-image {
    width: 40px; height: 40px; border-radius: 8px; object-fit: cover; background: #f0f0f0;
}
.search-product-result #ProductListUl li .product-info { flex: 1; }
.search-product-result #ProductListUl li .product-name {
    font-size: 14px; font-weight: 500; color: #333; margin-bottom: 4px;
}
.search-product-result #ProductListUl li .product-price { font-size: 12px; color: #666; }
.search-product-result #ProductListUl li .product-category {
    font-size: 11px; color: #999; background: #f0f0f0;
    padding: 2px 8px; border-radius: 12px;
}

/* ===== Right Actions Area ===== */
.header-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 4px;
    flex-wrap: nowrap;
}

.header-actions .action-link {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #2d3436;
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.header-actions .action-link:hover {
    color: #f28738;
    background: rgba(242, 135, 56, 0.08);
}

.header-actions .action-link i {
    font-size: 1rem;
    color: #f28738;
}

.header-actions .action-divider {
    width: 1px;
    height: 16px;
    background: #ddd;
    margin: 0 2px;
}

/* Language Dropdown */
.header-actions .lang-dropdown {
    position: relative;
}

.header-actions .lang-dropdown-content {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 4px;
    background: white;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    min-width: 120px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-5px);
    transition: all 0.2s ease;
    z-index: 1001;
}

.header-actions .lang-dropdown:hover .lang-dropdown-content {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.header-actions .lang-dropdown-content a {
    display: block;
    padding: 8px 12px;
    color: #333;
    text-decoration: none;
    font-size: 0.8rem;
    transition: background 0.2s;
}

.header-actions .lang-dropdown-content a:hover {
    background: #f8f9fa;
    color: #f28738;
}

/* Cart Icon */
.header-actions .cart-action {
    position: relative;
}

.header-actions .cart-action .cart-contents-count {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #f28738;
    color: white;
    font-size: 10px;
    font-weight: 700;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

/* Responsive */
@media (max-width: 991px) {
    .header-actions {
        gap: 2px;
    }
    .header-actions .action-link {
        font-size: 0.75rem;
        padding: 3px 5px;
    }
    .header-actions .action-divider {
        display: none;
    }
}

@media (max-width: 768px) {
    .header-actions .hide-mobile {
        display: none;
    }
}

@media (min-width: 1200px) {
    .col-xl-3 {
        flex: 0 0 22%;
        max-width: 22%;
    }
    .col-xl-4 {
        flex: 0 0 25%;
        max-width: 25%;
    }
    .col-xl-5 {
        flex: 0 0 51%;
        max-width: 51%;
    }
}

.mid-action-icon {
    transform: none;
}
</style>

<div class="header-mid-bar">
    <div class="container" style="z-index: 6">
        <div class="header-mid-bar-inner">
            <div class="row align-items-center">
                {{-- Logo --}}
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

                {{-- Search --}}
                <div class="col-md-6 col-lg-4 col-xl-4">
                    <div class="mid-search-bar">
                        <span style="padding: 5px 15px">
                            <input type="text" placeholder="Search..." onkeyup="searchProduct($(this).val())" id="ToSeachBoxes">
                            <i class="las la-search"></i>
                        </span>
                        <div class="open-search-dropdown" style="display:none" id="searchDiv">
                            <div class="search-product-section">
                                <div class="search-product-section-title">
                                    <span>{{ $language_name == 'french' ? 'Résultats de recherche' : 'Search Results' }}</span>
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

                {{-- All Actions (merged from top-bar + mid-bar) --}}
                <div class="col-md-6 col-lg-5 col-xl-5">
                    <div class="header-actions">

                        {{-- Language Selector --}}
                        @if(($MainStoreData['show_language_translation'] ?? 1) == 1)
                            <div class="lang-dropdown">
                                <a href="javascript:void(0)" class="action-link">
                                    {{ $MainStoreData['language_name'] ?? 'English' }}
                                    <i class="las la-angle-down" style="font-size: 0.7rem;"></i>
                                </a>
                                <div class="lang-dropdown-content">
                                    @if(!empty($StoreListData) && is_array($StoreListData))
                                        @foreach($StoreListData as $key => $language)
                                            <a href="{{ $language['url'] ?? '#' }}">{{ $language['language_name'] ?? '' }}</a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <span class="action-divider"></span>
                        @endif

                        {{-- POD --}}
                        <a href="https://pod.printing.coop" target="_blank" class="action-link hide-mobile">
                            <i class="las la-box"></i> POD
                        </a>
                        <span class="action-divider hide-mobile"></span>

                        {{-- Store --}}
                        <a href="https://store.printing.coop" target="_blank" class="action-link hide-mobile">
                            <i class="las la-store"></i> {{ $language_name == 'french' ? 'Magasin' : 'Store' }}
                        </a>
                        <span class="action-divider"></span>

                        {{-- Wishlist --}}
                        @php
                            $totalWishListCount = 0;
                            if (!empty($loginId)) {
                                $totalWishListCount = DB::table('wishlists')
                                    ->where('user_id', $loginId)
                                    ->count();
                            }
                        @endphp
                        <a href="{{ url('Wishlists') }}" class="action-link hide-mobile">
                            <i class="las la-heart"></i>
                            {{ $language_name == 'french' ? "Souhaits" : 'Wishlist' }}
                            (<strong id="WishlistsCount">{{ $totalWishListCount }}</strong>)
                        </a>
                        <span class="action-divider"></span>

                        {{-- Login/Logout --}}
                        @if(empty($loginId))
                            <a href="{{ url('Logins') }}" class="action-link">
                                <i class="las la-user"></i>
                                {{ $language_name == 'french' ? "S'identifier" : 'Login' }}
                            </a>
                        @else
                            <a href="{{ url('MyAccounts') }}" class="action-link">
                                <i class="las la-user"></i>
                                {{ $loginName ?? 'User' }}
                            </a>
                        @endif
                        <span class="action-divider"></span>

                        {{-- Help --}}
                        <a href="tel:{{ $configurations['contact_no'] ?? '18773848043' }}" class="action-link hide-mobile" title="{{ $configurations['contact_no'] ?? '1-877-384-8043' }}">
                            <i class="las la-headset"></i>
                            <span>{{ $language_name == 'french' ? 'Aide' : 'Help' }}</span>
                        </a>

                        {{-- Cart --}}
                        @php
                            $cartService = new \App\Services\CartService();
                            $cartCount = $cartService->totalItems();
                        @endphp
                        <div class="mid-action-single cart-action">
                            <div class="mid-action-single-inner" style="display: flex; align-items: center; cursor: pointer;">
                                <div class="mid-action-icon" style="position: relative;">
                                    <i class="las la-shopping-cart" style="font-size: 1.5rem; color: #f28738;"></i>
                                    <span class="cart-contents-count">{{ $cartCount }}</span>
                                </div>
                                <span style="font-size: 0.8rem; font-weight: 500; margin-left: 4px; color: #2d3436;">
                                    {{ $language_name == 'French' ? 'Chariot' : 'Cart' }}
                                </span>
                            </div>
                            <div class="cart-items-table">
                                @include('elements.cart-items')
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
