{{-- CI: application/views/elements/header-top-bar.php --}}
<style>
    :root {
        --primary-color: #f28738;
        --secondary-color: #ff6b00;
        --text-dark: #2d3436;
        --text-light: #636e72;
        --border-color: #e9ecef;
        --bg-light: #f8f9fa;
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.12);
    }

    /* Header Top Bar Container - matches section backgrounds (f8f9fa, light gradients) */
    .header-top-bar {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #f0f4f8 100%);
        border-bottom: 1px solid #e9ecef;
        position: relative;
        z-index: 1000;
    }

    .top-inner-bar {
        padding: 0.25rem 0;
    }

    /* Top Bar Menu */
    .top-bar-menu {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .top-bar-menu ul {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .top-bar-menu ul li {
        margin: 0;
        padding: 0;
    }

    /* Left Menu (Contact Info) */
    .left-menu ul {
        flex-wrap: wrap;
    }

    .left-menu ul li span {
        color: var(--text-dark);
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: color 0.2s ease;
    }

    .left-menu ul li span:hover {
        color: var(--primary-color);
    }

    .left-menu ul li span strong {
        color: #183e73;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .left-menu ul li:first-child span::before {
        content: '\f095';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        font-size: 0.9rem;
        color: var(--primary-color);
    }

    .left-menu ul li:last-child span::before {
        content: '\f017';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        font-size: 0.9rem;
        color: var(--primary-color);
    }

    /* Right Menu */
    .right-menu {
        justify-content: flex-end;
    }

    .right-menu ul {
        gap: 1.25rem;
        align-items: center;
    }

    .right-menu ul li a {
        color: var(--text-dark);
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        border-radius: 6px;
        position: relative;
    }

    .right-menu ul li a:hover {
        color: var(--primary-color);
        /* background: rgba(242, 135, 56, 0.12);
        transform: translateY(-1px); */
    }

    .right-menu ul li a strong {
        color: var(--primary-color);
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Language Selector */
    .language-selector {
        position: relative;
    }

    .language-selector-box {
        position: relative;
    }

    .language-selector-box > a {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-dark) !important;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.375rem 0.75rem !important;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .language-selector-box > a:hover {
        background: rgba(242, 135, 56, 0.12) !important;
        color: var(--primary-color) !important;
        border-color: rgba(242, 135, 56, 0.3);
    }

    .language-selector-box > a i {
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }

    .language-selector:hover .language-selector-box > a i {
        transform: rotate(180deg);
    }

    .language-selector-content {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 0.5rem;
        background: white;
        border-radius: 8px;
        box-shadow: var(--shadow-lg);
        min-width: 150px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1001;
    }

    .language-selector:hover .language-selector-content {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .upward-arrow {
        position: absolute;
        top: 0px;
        right: 20px;
        width: 16px;
        height: 16px;
    }

    .upward-arrow div {
        width: 100%;
        height: 100%;
        background: white;
        transform: rotate(45deg);
        box-shadow: -2px -2px 4px rgba(0, 0, 0, 0.1);
    }

    .language-selector-content a {
        display: block;
        padding: 0.75rem 1rem;
        color: var(--text-dark) !important;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        border-radius: 0;
        background: transparent !important;
    }

    .language-selector-content a:hover {
        background: var(--bg-light) !important;
        color: var(--primary-color) !important;
        transform: none;
    }

    .language-selector-content a:first-child {
        border-radius: 8px 8px 0 0;
    }

    .language-selector-content a:last-child {
        border-radius: 0 0 8px 8px;
    }

    /* Wishlist Link */
    /* .right-menu ul li:nth-child(3) a::before {
        content: '\f004';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        font-size: 0.9rem;
        color: var(--primary-color);
    } */

    /* Login/Logout Links */
    /* .right-menu ul li:last-child a::before {
        content: '\f2bd';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        font-size: 0.9rem;
        color: var(--primary-color);
    } */

    .header-top-bar .container {
        /* flex: 0 0 41.666667%;
        max-width: 41.666667%; */
        max-width: 1200px;
    }

    .header-top-bar .action-divider {
        width: 1px;
        height: 16px;
        background: #ddd;
        /* margin: 0 2px; */
        margin: 6px 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .top-inner-bar {
            padding: 0.625rem 0;
        }

        .top-bar-menu ul {
            gap: 1rem;
        }

        .left-menu ul {
            gap: 1rem;
        }

        .left-menu ul li span {
            font-size: 0.8rem;
        }

        .right-menu ul {
            gap: 0.875rem;
        }

        .right-menu ul li a {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .language-selector-content {
            min-width: 130px;
        }
    }

    @media (max-width: 576px) {
        .header-top-bar {
            display: none;
        }
    }

    /* Subdomain Links Styles */
    .subdomain-links .subdomain-links-group {
        display: flex;
        align-items: center;
        /* gap: 0.5rem; */
        padding: 0.25rem 0;
    }

    .subdomain-link {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 400;
        background: transparent;
        border: 1px solid transparent;
    }

    .subdomain-link:hover {
        background: var(--bg-light);
        color: var(--primary-color) !important;
        border-color: var(--border-color);
        transform: translateY(-1px);
    }

    .link-icon {
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .link-text {
        font-weight: 500;
        color: var(--text-dark) !important;
    }

    @media (max-width: 768px) {
        .subdomain-links .subdomain-links-group {
            flex-direction: column;
            gap: 0.25rem;
            align-items: flex-start;
        }
        
        .subdomain-link {
            padding: 0.25rem 0.5rem;
        }
    }
</style>

<div class="header-top-bar">
    <div class="top-inner-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-5">
                    <div class="top-bar-menu left-menu">
                        <ul>
                            <li>
                                <span>
                                    {{ $language_name == 'french' ? 'Appelez-nous' : 'Call Us' }}: 
                                    <strong>
                                        @if($language_name == 'french')
                                            {{ $configurations['contact_no_french'] ?? '1-877-384-8043' }}
                                        @else
                                            {{ $configurations['contact_no'] ?? '1-877-384-8043' }}
                                        @endif
                                    </strong>
                                </span>
                            </li>
                            <li>
                                <span>
                                    @if($language_name == 'french')
                                        {!! $configurations['office_timing_french'] ?? 'Du lundi au vendredi: <strong>9:00-18:00</strong>' !!}
                                    @else
                                        {!! $configurations['office_timing'] ?? 'Monday-Friday: <strong>9:00-18:00</strong>' !!}
                                    @endif
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-12 col-md-7">
                    <div class="top-bar-menu right-menu">
                        <ul>
                            {{-- Language Selector (CI lines 35-58) --}}
                            @if(($MainStoreData['show_language_translation'] ?? 1) == 1)
                                <li style="display: flex">
                                    @if(!empty($StoreListData) && is_array($StoreListData))
                                        @foreach($StoreListData as $key => $language)
                                            <a href="{{ $language['url'] ?? '#' }}">
                                                @if($language['language_name'] == 'English') 
                                                    <span style="margin-right: 4px;">🇬🇧</span>EN
                                                @else 
                                                    <span style="margin-right: 4px;">🇫🇷</span>FR
                                                @endif
                                            </a>
                                            @if(!$loop->last)
                                                <span class="action-divider"></span>
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- <div class="language-selector">
                                        <div class="language-selector-box">
                                            <a href="javascript:void(0)">
                                                {{ $MainStoreData['language_name'] ?? 'English' }}
                                                <i class="las la-angle-down"></i>
                                            </a>
                                            <div class="language-selector-content">
                                                <div class="upward-arrow">
                                                    <div></div>
                                                </div>
                                                @if(!empty($StoreListData) && is_array($StoreListData))
                                                    @foreach($StoreListData as $key => $language)
                                                        <a href="{{ $language['url'] ?? '#' }}">
                                                            {{ $language['language_name'] ?? '' }}
                                                        </a>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div> --}}
                                </li>
                            @endif
                            
                            {{-- Subdomain Links --}}
                            <li class="subdomain-links">
                                <div class="subdomain-links-group">
                                    <a href="https://pod.printing.coop" target="_blank" class="subdomain-link">
                                        {{-- <span class="link-icon">📦</span> --}}
                                        <span class="link-text">{{ $language_name == 'french' ? 'Impression à la demande' : 'Print on demand' }}</span>
                                    </a>
                                    <span class="action-divider"></span>
                                    <a href="https://store.printing.coop" target="_blank" class="subdomain-link">
                                        {{-- <span class="link-icon">🛍️</span> --}}
                                        <span class="link-text">{{ $language_name == 'french' ? 'Magasin' : 'Store' }}</span>
                                    </a>
                                </div>
                            </li>
                            
                            {{-- Wishlist (CI lines 83-89) --}}
                            {{-- @php
                                $totalWishListCount = 0;
                                if (!empty($loginId)) {
                                    $totalWishListCount = DB::table('wishlists')
                                        ->where('user_id', $loginId)
                                        ->count();
                                }
                            @endphp
                            <li>
                                <a href="{{ url('Wishlists') }}">
                                    {{ $language_name == 'french' ? "Ma liste d'envies" : 'My Wish List' }} 
                                    (<strong id="WishlistsCount">{{ $totalWishListCount }}</strong>)
                                </a>
                            </li> --}}
                            
                            {{-- Login/Logout (CI lines 91-98) --}}
                            @if(empty($loginId))
                                {{-- <li>
                                    <a href="{{ url('Logins') }}">
                                        {{ $language_name == 'french' ? "S'identifier" : 'Login' }}
                                    </a>
                                </li> --}}
                            @else
                                <li>
                                    <a href="{{ url('MyAccounts/logout') }}">
                                        {{ $language_name == 'french' ? 'Se déconnecter' : 'Logout' }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
