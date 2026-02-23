{{-- CI: application/views/elements/header-menu-bar.php --}}
<div class="container-fluid header-menu-bar">
    <div class="header-menu-bar-inner">
        <ul class="all-menu">
            <li>
                <a href="{{ url('/') }}">
                    {{ $language_name == 'french' ? 'Accueil' : 'Home' }}
                </a>
            </li>
            <li>
                <a href="{{ url('Products') }}" id="products">
                    {{ $language_name == 'french' ? 'Des produits' : 'Products' }}
                </a>
                <div class="product-dropdown">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4 col-lg-3 col-xl-3" style="border-right: 1px solid #ccc;">
                                <div class="menus-section">
                                    <div class="all-menus">
                                        @if(isset($categories['categories']))
                                            @foreach($categories['categories'] as $key => $category)
                                                @php
                                                    $count = count($category['sub_categories'] ?? []);
                                                    $url = url('Products?category_id=' . base64_encode($category['id']));
                                                    $urlmain = $url;
                                                    $data_toggle = '';
                                                    if (!empty($count)) {
                                                        $url = 'Cat' . $category['id'];
                                                        $data_toggle = 'tab';
                                                    }
                                                @endphp
                                                
                                                @if(!empty($count))
                                                    <a href="{{ $urlmain }}">
                                                        <button class="drop-cat tablinks" onmouseover="openCity(event, '{{ $url }}')">
                                                            {{ $language_name == 'french' ? ucfirst($category['name_french']) : ucfirst($category['name']) }}
                                                            <i class="las la-angle-right"></i>
                                                        </button>
                                                    </a>
                                                @else
                                                    <a href="{{ $urlmain }}">
                                                        <button class="drop-cat tablinks" onmouseover="openCity(event, '{{ $url }}')">
                                                            {{ $language_name == 'french' ? ucfirst($category['name_french']) : ucfirst($category['name']) }}
                                                            <i class="las la-angle-right"></i>
                                                        </button>
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-9 col-xl-9">
                                @if(isset($categories['categories']))
                                    @foreach($categories['categories'] as $key => $category)
                                        <div id="Cat{{ $category['id'] }}" class="tabcontent" style="display: none;">
                                            <div class="row">
                                                @if(isset($category['sub_categories']))
                                                    @foreach($category['sub_categories'] as $key => $subCategory)
                                                        @php
                                                            $url = url('Products?category_id=' . base64_encode($category['id']) . '&sub_category_id=' . base64_encode($subCategory['id']));
                                                        @endphp
                                                        <div class="col-md-6 col-lg-4 col-xl-4">
                                                            <div class="menus-section">
                                                                <div class="menus-title">
                                                                    <span>
                                                                        <a href="{{ $url }}">
                                                                            {{ $language_name == 'french' ? ucfirst($subCategory['name_french']) : ucfirst($subCategory['name']) }}
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            
            {{-- Dynamic Pages (CI lines 83-104) --}}
            @if(isset($pages))
                @foreach($pages as $key => $page)
                    @php
                        $slug = $page['slug'];
                        $url = url('Page/' . $slug);
                        $pageSlugArray = [
                            'brands' => ['class' => 'Pages', 'action' => 'brands'],
                            'support' => ['class' => 'Pages', 'action' => 'support'],
                            'contact-us' => ['class' => 'Pages', 'action' => 'contactUs'],
                            'preffered-customer' => ['class' => 'Pages', 'action' => 'prefferedCustomer'],
                            'estimate' => ['class' => 'Pages', 'action' => 'estimate'],
                            'home' => ['class' => 'Homes', 'action' => ''],
                            'products' => ['class' => 'Products', 'action' => ''],
                            'faq' => ['class' => 'Pages', 'action' => 'faq'],
                            'blogs' => ['class' => 'Blogs', 'action' => ''],
                        ];
                        
                        if (array_key_exists($slug, $pageSlugArray)) {
                            $dataUrl = $pageSlugArray[$slug];
                            $url = url($dataUrl['class'] . '/' . $dataUrl['action']);
                        }
                    @endphp
                    <li>
                        <a href="{{ $url }}">
                            {{ $language_name == 'french' ? ucfirst($page['title_french']) : ucfirst($page['title']) }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</div>

{{-- Mobile Sidebar Menu (CI lines 109-191) --}}
<div id="mySidenav" class="sidenav">
    <div class="sidebar-menu-field text-left">
        <div class="head">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav01()">
                <i class="la la-times"></i>
            </a>
        </div>
        <div class="mobile-menu-area">
            <ul class="mobile-menu">
                <li>
                    <a href="{{ url('/') }}">
                        {{ $language_name == 'french' ? 'Accueil' : 'Home' }}
                    </a>
                </li>
                <li class="mobile-drop">
                    <a href="{{ url('Products') }}" id="products">
                        {{ $language_name == 'french' ? 'Des produits' : 'Products' }}
                    </a>
                    <span class="mob-drop-icon"><i class="las la-angle-down"></i></span>
                    <div class="mob-drop-cat" style="display: none;">
                        <div class="shop-filter-single">
                            @if(isset($categories['categories']) && count($categories['categories']) > 0)
                                <div class="shop-filter-info">
                                    @php
                                        $selected = isset($_GET['category_id']) ? base64_decode($_GET['category_id']) : 'selected';
                                        $sub_category_selected = isset($_GET['sub_category_id']) ? base64_decode($_GET['sub_category_id']) : 'selected';
                                    @endphp
                                    <a href="{{ url('Products') }}" class="{{ $selected }}">
                                        {{ $language_name == 'french' ? 'Toutes catégories' : 'All categories' }}
                                    </a>
                                    @foreach($categories['categories'] as $key => $category)
                                        <div class="single-filter-tab">
                                            <a href="{{ url('Products?category_id=' . base64_encode($category['id'])) }}" class="{{ $selected == $category['id'] ? 'selected' : '' }}">
                                                {{ $language_name == 'french' ? ucfirst($category['name_french']) : ucfirst($category['name']) }}
                                            </a>
                                            <div class="single-filter-hover">
                                                @if(isset($category['sub_categories']) && count($category['sub_categories']) > 0)
                                                    @foreach($category['sub_categories'] as $skey => $subcategory)
                                                        <div class="single-filter-hover-inner">
                                                            <a href="{{ url('Products?category_id=' . base64_encode($category['id']) . '&sub_category_id=' . base64_encode($subcategory['id'])) }}" class="{{ $sub_category_selected == $subcategory['id'] ? 'selected' : '' }}">
                                                                {{ $language_name == 'french' ? $subcategory['name_french'] : $subcategory['name'] }}
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="shop-filter-info">
                                    {{ $language_name == 'french' ? 'Aucune catégorie trouvée' : 'No Category Found' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
                
                {{-- Dynamic Pages for Mobile (CI lines 165-186) --}}
                @if(isset($pages))
                    @foreach($pages as $key => $page)
                        @php
                            $slug = $page['slug'];
                            $url = url('Page/' . $slug);
                            $pageSlugArray = [
                                'brands' => ['class' => 'Pages', 'action' => 'brands'],
                                'support' => ['class' => 'Pages', 'action' => 'support'],
                                'contact-us' => ['class' => 'Pages', 'action' => 'contactUs'],
                                'preffered-customer' => ['class' => 'Pages', 'action' => 'prefferedCustomer'],
                                'estimate' => ['class' => 'Pages', 'action' => 'estimate'],
                                'home' => ['class' => 'Homes', 'action' => ''],
                                'products' => ['class' => 'Products', 'action' => ''],
                                'faq' => ['class' => 'Pages', 'action' => 'faq'],
                                'blogs' => ['class' => 'Blogs', 'action' => ''],
                            ];
                            
                            if (array_key_exists($slug, $pageSlugArray)) {
                                $dataUrl = $pageSlugArray[$slug];
                                $url = url($dataUrl['class'] . '/' . $dataUrl['action']);
                            }
                        @endphp
                        <li>
                            <a href="{{ $url }}">
                                {{ $language_name == 'french' ? ucfirst($page['title_french']) : ucfirst($page['title']) }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>
