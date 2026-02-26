<!DOCTYPE html>
<html lang="{{ config('store.language_name', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Printing Coop - Online Printing Services')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    
    <!-- CSS Files -->
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    @php
        $website_store_id = config('store.main_store_id', 1);
    @endphp
    @if($website_store_id == 1)
        <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    @elseif($website_store_id == 3)
        <link rel="stylesheet" href="{{ asset('assets/css/clickimprimerie.style.min.css') }}">
    @elseif($website_store_id == 5)
        <link rel="stylesheet" href="{{ asset('assets/css/ecoink.style.min.css') }}">
    @endif
    <link rel="stylesheet" href="{{ asset('assets/css/customslider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/provider.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link rel="stylesheet" href="{{ asset('assets/css/footer-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/slider-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/search-widget-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sticky-header.css') }}">
    
    <!-- Page-specific CSS -->
    @stack('styles')
    
    <!-- jQuery - Load in head for inline event handlers -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
    <!-- Announcement Bar -->
    @php
        $configurations = DB::table('configurations')->where('id', 1)->first();
        $language_name = config('store.language_name', 'english');
    @endphp
    <div class="announcements-bar">
        <div class="container">
            <span>
                @if($language_name == 'french')
                    {!! $configurations->announcement_french ?? 'Proudly involved in the community! 10% discount for Community organizations, co-operatives, not-for-profit organizations and print reselling companies will benefit.' !!}
                @else
                    {!! $configurations->announcement ?? 'Proudly involved in the community! 10% discount for Community organizations, co-operatives, not-for-profit organizations and print reselling companies will benefit.' !!}
                @endif
            </span>
            <i class="la la-times"></i>
        </div>
    </div>

    <!-- Header -->
    @include('elements.header-top-bar')
    @include('elements.header-mid-bar')
    @include('elements.header-menu-bar')
    
    <!-- Add to Cart Message -->
    <div class="addtocart-message"></div>
    
    <!-- Add to Wishlist Message -->
    <div class="addwishlist-message">
        <span>
            <i class="la la-heart-o"></i> 
            {{ $language_name == 'french' ? '"Produit" a été ajouté à votre liste de souhaits.' : '"Product" has been added to your wishlist.' }}
        </span>
    </div>
    
    <!-- Loader -->
    <div id="loader-img">
        <div id="loader-img-inner">
            @if($website_store_id == 1)
                <img src="{{ asset('assets/images/loder.gif') }}" width="100" alt="loader">
            @elseif($website_store_id == 3)
                <img src="{{ asset('assets/images/loader-pink.gif') }}" width="100" alt="loader">
            @elseif($website_store_id == 5)
                <img src="{{ asset('assets/images/loader-green.gif') }}" width="100" alt="loader">
            @endif
        </div>
    </div>
    
    <!-- Breadcrumb -->
    @include('elements.breadcrumb')
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('elements.footer')
    
    <!-- Hidden inputs for JavaScript -->
    <input type="hidden" id="lang_name" value="{{ $language_name ?? 'english' }}">
    <input type="hidden" id="site_url_foot" value="{{ url('/') }}">
    <input type="hidden" id="user_id_foot" value="{{ $loginId ?? '' }}">
    <input type="hidden" id="user_id_covid_msg" value="{{ $showCOVID19MSG ?? 'null' }}">
    
    <!-- JavaScript Files -->
    <script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/customslider.min.js') }}"></script>
    <script src="{{ asset('assets/js/validation.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.min.js') }}"></script>
    
    <!-- CSRF Token Setup for AJAX -->
    <script>
        // Set up CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Announcement bar close functionality
        $(document).ready(function() {
            $('.announcements-bar .la-times').click(function() {
                $('.announcements-bar').slideUp(300);
            });
        });
        
        // Product search functionality
        function searchProduct(searchtext) {
            if (searchtext != '') {
                $('#loader-img').show();
                var url = '{{ url("Products/searchProduct") }}';
                $('#searchDiv').show();
                $('#ProductListUl').html('');
                $('#coming-res-data').show();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        'searchtext': searchtext,
                        '_token': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#loader-img').hide();
                        $('#coming-res-data').hide();
                        $('#ProductListUl').html(data);
                    },
                    error: function(error) {
                        $('#loader-img').hide();
                        $('#coming-res-data').hide();
                        console.error('Search error:', error);
                    }
                });
            } else {
                $('#searchDiv').hide();
                $('#ProductListUl').html('');
                $('#coming-res-data').show();
            }
        }
        
        // Hide search dropdown when clicking outside
        $(document).click(function(event) {
            if (!$(event.target).closest('.mid-search-bar').length) {
                $('#searchDiv').hide();
            }
        });
    </script>
    
    <!-- Sticky Header JavaScript -->
    <script src="{{ asset('assets/js/sticky-header.js') }}"></script>
    
    <!-- Page-specific JavaScript -->
    @stack('scripts')
</body>
</html>
