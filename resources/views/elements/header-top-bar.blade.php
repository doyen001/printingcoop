{{-- CI: application/views/elements/header-top-bar.php --}}
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
                                <li>
                                    <div class="language-selector">
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
                                    </div>
                                </li>
                            @endif
                            
                            {{-- Wishlist (CI lines 83-89) --}}
                            @php
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
                            </li>
                            
                            {{-- Login/Logout (CI lines 91-98) --}}
                            @if(empty($loginId))
                                <li>
                                    <a href="{{ url('Logins') }}">
                                        {{ $language_name == 'french' ? "S'identifier S'enregistrer" : 'Login/Register' }}
                                    </a>
                                </li>
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
