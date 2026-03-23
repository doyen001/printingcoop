{{-- CI: application/views/elements/header-mid-bar.php --}}
<style>
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
    padding: 8px 15px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.mid-search-bar span:hover {
    /* border-color: #d0d0d0; */
}

.mid-search-bar span:focus-within {
    /* border-color: #c0c0c0; */
    border-color: #387dde;
    outline: 4px solid #d8e6ff;
}

.mid-search-bar input[type="text"] {
    border: none;
    outline: none;
    background: transparent;
    flex: 1;
    font-size: 16px;
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
    font-size: 20px;
    min-width: 24px;
    border: none;
    background: transparent;
    position: relative;
}

.mid-search-bar .shortcut-button {
    background-color: #f0f0f0;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    padding: 3px 6px;
    margin-left: 10px;
    font-size: 12px;
    color: #666;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
}

.mid-search-bar .shortcut-button:hover {
    background-color: #e5e5e5;
    border-color: #d5d5d5;
    color: #555;
}

/* Search Dropdown Styles */
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
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.search-product-section {
    padding: 16px;
}

.search-product-section-title {
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 12px;
}

.search-product-section-title span {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.search-product-result {
    min-height: 60px;
}

.search-product-result #coming-res-data {
    display: block;
    text-align: center;
    color: #999;
    font-size: 14px;
    padding: 20px;
    font-style: italic;
}

.search-product-result #ProductListUl {
    list-style: none;
    margin: 0;
    padding: 0;
}

.search-product-result #ProductListUl li {
    padding: 12px 16px;
    border-bottom: 1px solid #f5f5f5;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-product-result #ProductListUl li:hover {
    background-color: #f8f9fa;
    padding-left: 20px;
}

.search-product-result #ProductListUl li:last-child {
    border-bottom: none;
}

.search-product-result #ProductListUl li .product-image {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    object-fit: cover;
    background: #f0f0f0;
}

.search-product-result #ProductListUl li .product-info {
    flex: 1;
}

.search-product-result #ProductListUl li .product-name {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 4px;
}

.search-product-result #ProductListUl li .product-price {
    font-size: 12px;
    color: #666;
}

.search-product-result #ProductListUl li .product-category {
    font-size: 11px;
    color: #999;
    background: #f0f0f0;
    padding: 2px 8px;
    border-radius: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.header-mid-bar .container {
    max-width: 1300px;
}

.header-mid-bar .mid-action-single-inner {
    padding-left: 40px;
}

.header-mid-bar .mid-action-area ul li {
    padding-left: 10px;
}

/* Mobile Responsive Styles */
@media (max-width: 1199px) {
    .header-mid-bar .row > div[style*="flex"] {
        flex: 0 0 auto !important;
        max-width: none !important;
    }
    
    .mid-action-area ul {
        gap: 10px;
    }
    
    .mid-action-content span {
        font-size: 13px;
    }
}

@media (max-width: 991px) {
    .header-mid-bar .container {
        max-width: 100%;
        padding: 0 15px;
    }
    
    .header-mid-bar .row {
        margin: 0;
    }
    
    .header-mid-bar .row > div {
        padding: 5px;
    }
    
    .site-logo img {
        max-width: 120px;
        height: auto;
    }
    
    .mid-search-bar {
        margin: 10px 0;
    }
    
    .mid-action-area ul {
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .mid-action-single-inner {
        padding: 8px;
    }
    
    .mid-action-icon svg {
        width: 28px;
        height: 28px;
    }
    
    .mid-action-content span {
        font-size: 12px;
    }
    
    .mid-action-content span strong {
        font-size: 13px;
    }
}

@media (max-width: 767px) {
    .header-mid-bar .row {
        flex-direction: column;
    }
    
    .header-mid-bar .row > div {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
    
    .site-logo {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
    }
    
    .site-logo img {
        max-width: 100px;
    }
    
    .mid-search-bar {
        width: 100%;
        margin: 10px 0;
    }
    
    .mid-action-area {
        width: 100%;
        overflow-x: auto;
    }
    
    .mid-action-area ul {
        display: flex;
        justify-content: space-around;
        flex-wrap: nowrap;
        gap: 5px;
        padding: 10px 0;
    }
    
    .mid-action-single {
        flex-shrink: 0;
    }
    
    .mid-action-single-inner {
        flex-direction: column;
        text-align: center;
        padding: 5px;
        min-width: 70px;
    }
    
    .mid-action-icon {
        margin-bottom: 5px;
    }
    
    .mid-action-icon svg {
        width: 24px;
        height: 24px;
    }
    
    .mid-action-content span {
        font-size: 10px;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .mid-action-content span strong {
        font-size: 11px;
    }
    
    .search-product-result {
        max-height: 300px;
    }
}

@media (max-width: 575px) {
    .header-mid-bar .container {
        padding: 0 10px;
    }
    
    .site-logo img {
        max-width: 80px;
    }
    
    .menu-bar i {
        font-size: 24px;
    }
    
    .mid-search-bar input[type="text"] {
        font-size: 14px;
    }
    
    .mid-action-area ul {
        gap: 3px;
    }
    
    .mid-action-single-inner {
        padding: 3px;
        min-width: 60px;
    }
    
    .mid-action-icon svg {
        width: 20px;
        height: 20px;
    }
    
    .mid-action-content span {
        font-size: 9px;
    }
    
    .mid-action-content span strong {
        font-size: 10px;
    }
    
    .cart-contents-count {
        font-size: 10px;
        min-width: 16px;
        height: 16px;
        line-height: 16px;
    }
}

@media (max-width: 400px) {
    .site-logo img {
        max-width: 70px;
    }
    
    .mid-action-single-inner {
        min-width: 50px;
    }
    
    .mid-action-content span {
        font-size: 8px;
    }
    
    .mid-action-content span strong {
        font-size: 9px;
    }
}
</style>
<div class="header-mid-bar">
    <div class="container" style="z-index: 6">
        <div class="header-mid-bar-inner">
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-3 col-xl-3" style="flex: 0 0 25%; max-width: 25%;">
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

                
                
                <div class="col-md-6 col-lg-4 col-xl-5" style="flex: 0 0 32%; max-width: 32%;">
                    <div class="mid-search-bar">
                        <span style="padding: 5px 15px">
                            <input type="text" placeholder="Search..." onkeyup="searchProduct($(this).val())" id="ToSeachBoxes">
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
                
                <div class="col-md-6 col-lg-5 col-xl-4" style="flex: 0 0 43%; max-width: 43%;">
                    <div class="mid-action-area">
                        <ul>
                            {{-- Reseller Button --}}
                            <li>
                                <div class="mid-action-single">
                                    <a href="{{ url('/Pages/prefferedCustomer') }}">
                                        <div class="mid-action-single-inner">
                                            @if($website_store_id == 1)
                                                <div class="mid-action-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                        <g fill="none" stroke="#f58634" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <!-- Person icon -->
                                                            <circle cx="17.5" cy="10.5" r="4"/>
                                                            <path d="M11.5,23c0-3.3,2.7-6,6-6s6,2.7,6,6"/>
                                                            
                                                            <!-- Distribution lines and circles -->
                                                            <line x1="17.5" y1="23" x2="17.5" y2="27"/>
                                                            <circle cx="17.5" cy="29" r="1.5"/>
                                                            
                                                            <line x1="17.5" y1="23" x2="10" y2="27"/>
                                                            <circle cx="8" cy="29" r="1.5"/>
                                                            
                                                            <line x1="17.5" y1="23" x2="25" y2="27"/>
                                                            <circle cx="27" cy="29" r="1.5"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            @elseif($website_store_id == 3)
                                                <div class="mid-action-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                        <g fill="none" stroke="#e72582" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <!-- Person icon -->
                                                            <circle cx="17.5" cy="10.5" r="4"/>
                                                            <path d="M11.5,23c0-3.3,2.7-6,6-6s6,2.7,6,6"/>
                                                            
                                                            <!-- Distribution lines and circles -->
                                                            <line x1="17.5" y1="23" x2="17.5" y2="27"/>
                                                            <circle cx="17.5" cy="29" r="1.5"/>
                                                            
                                                            <line x1="17.5" y1="23" x2="10" y2="27"/>
                                                            <circle cx="8" cy="29" r="1.5"/>
                                                            
                                                            <line x1="17.5" y1="23" x2="25" y2="27"/>
                                                            <circle cx="27" cy="29" r="1.5"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            @elseif($website_store_id == 5)
                                                <div class="mid-action-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 35 35" height="35" width="35" class="header-link-icon">
                                                        <g fill="none" stroke="#7aa93c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <!-- Person icon -->
                                                            <circle cx="17.5" cy="10.5" r="4"/>
                                                            <path d="M11.5,23c0-3.3,2.7-6,6-6s6,2.7,6,6"/>
                                                            
                                                            <!-- Distribution lines and circles -->
                                                            <line x1="17.5" y1="23" x2="17.5" y2="27"/>
                                                            <circle cx="17.5" cy="29" r="1.5"/>
                                                            
                                                            <line x1="17.5" y1="23" x2="10" y2="27"/>
                                                            <circle cx="8" cy="29" r="1.5"/>
                                                            
                                                            <line x1="17.5" y1="23" x2="25" y2="27"/>
                                                            <circle cx="27" cy="29" r="1.5"/>
                                                        </g>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="mid-action-content">
                                                <span>
                                                    <strong>{{ $language_name == 'french' ? 'Revendeur' : 'Reseller' }}</strong>
                                                    {{ $language_name == 'french' ? 'Espace revendeur' : 'Reseller Portal' }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            
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
