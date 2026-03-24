{{-- CI: application/views/elements/header-menu-bar.php --}}
<style>
/* Clean and simple dropdown styling */
.product-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e0e0e0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.all-menu li:hover .product-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.product-dropdown .container {
    padding: 20px;
}

.product-dropdown .row {
    margin: 0;
}

/* Left sidebar categories */
.product-dropdown .col-md-4.col-lg-3.col-xl-3 {
    border-right: 1px solid #f0f0f0;
    padding-right: 20px;
}

.product-dropdown .all-menus .drop-cat {
    display: block;
    width: 100%;
    text-align: left;
    padding: 12px 16px;
    margin-bottom: 4px;
    font-weight: 400 !important;
    color: #555;
    font-size: 14px;
    line-height: 1.4;
    border-radius: 6px;
    transition: all 0.2s ease;
    background: transparent;
    border: none;
    cursor: pointer;
    position: relative;
}

.product-dropdown .all-menus .drop-cat:hover {
    color: #333;
    background: #f8f9fa;
    transform: translateX(4px);
}

.product-dropdown .all-menus .drop-cat.active {
    color: #333;
    background: #f0f4f8;
    font-weight: 500;
}

.product-dropdown .all-menus .drop-cat i {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.5;
    font-size: 12px;
    transition: all 0.2s ease;
}

.product-dropdown .all-menus .drop-cat:hover i,
.product-dropdown .all-menus .drop-cat.active i {
    opacity: 1;
    transform: translateY(-50%) translateX(2px);
}

/* Right content area */
.product-dropdown .col-md-8.col-lg-9.col-xl-9 {
    padding-left: 30px;
}

.product-dropdown .tabcontent {
    display: none;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Subcategory styling - Properly Visible */
.product-dropdown .menus-section {
    margin-bottom: 0;
}

.product-dropdown .menus-title {
    padding: 0;
    margin-bottom: 8px;
}

.product-dropdown .menus-title span {
    display: block;
}

.product-dropdown .menus-title span a {
    color: #666;
    font-size: 13px;
    font-weight: 400;
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
    display: inline-block;
    line-height: 1.3;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    margin: 2px;
}

.product-dropdown .menus-title span a:hover {
    color: #333;
    background: #e9ecef;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Remove all background color changes from main menu */
.all-menu li:hover a {
    background: transparent !important;
}

.product-dropdown .all-menus .drop-cat.tablinks:hover {
    color: #333 !important;
    background: #f8f9fa !important;
}

.product-dropdown .all-menus .drop-cat.tablinks.active:hover {
    color: #333 !important;
    background: #f0f4f8 !important;
}

.product-dropdown .all-menus .drop-cat.active {
    color: #333 !important;
    background: #f0f4f8 !important;
    padding-left: 16px !important;
}

.all-menu li:hover .menus-title span a {
    color: #333 !important;
    background: transparent !important;
}

/* Mobile menu styling */
.shop-filter-info .single-filter-tab:hover a,
.shop-filter-info a.selected {
    background: transparent !important;
    color: #333 !important;
    transition: 0.3s;
}

.mob-drop-cat .shop-filter-info a.selected {
    background: transparent !important;
    color: #333 !important;
}

/* Header menu bar styling */
.header-menu-bar {
    background-color: #f58634 !important;
    /* border-top: 1px #e67e22 solid;
    border-bottom: 1px #e67e22 solid; */
    height: 50px;
}

.header-menu-bar-inner {
    height: 100% !important;
}

.header-menu-bar .all-menu li a {
    color: #ffffff !important;
}

.header-menu-bar .all-menu li a:hover {
    color: #2c3e50 !important;
}

/* .header-menu-bar.sticky {
    box-shadow: 0 5px 15px rgba(168, 168, 168, 0.3);
} */

/* Subdomain Links Styles for Header Menu Bar */
.header-menu-bar .subdomain-links {
    display: flex;
    align-items: center;
}

.header-menu-bar .subdomain-links-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.header-menu-bar .subdomain-link {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.5rem 0.75rem;
    text-decoration: none;
    border-radius: 6px;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff !important;
}

.header-menu-bar .subdomain-link:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-1px);
}

.header-menu-bar .link-icon {
    font-size: 0.75rem;
    opacity: 0.9;
}

.header-menu-bar .link-text {
    font-weight: 500;
    color: #ffffff !important;
}

/* Responsive adjustments for subdomain links in menu bar */
@media (max-width: 991px) {
    .header-menu-bar .subdomain-links-group {
        flex-direction: column;
        gap: 0.25rem;
        align-items: flex-start;
    }
    
    .header-menu-bar .subdomain-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.8rem;
    }
}
</style>
<div class="container-fluid header-menu-bar">
    <div class="header-menu-bar-inner">
        <ul class="all-menu">
            <li>
                <a href="{{ site_url('/') }}">
                    {{ $language_name == 'french' ? 'Accueil' : 'Home' }}
                </a>
            </li>

            <li>
                <a href="{{ site_url('Products') }}" id="products">
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
                                                    $url = site_url('Products?category_id=' . base64_encode($category['id']));
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
                                                            $url = site_url('Products?category_id=' . base64_encode($category['id']) . '&sub_category_id=' . base64_encode($subCategory['id']));
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
                        
            {{-- Subdomain Links --}}
            <li>
                <a href="https://pod.printing.coop">
                    {{ $language_name == 'french' ? 'POD' : 'POD' }}
                </a>
            </li>
            <li>
                <a href="https://store.printing.coop">
                    {{ $language_name == 'french' ? 'Magasin' : 'Store' }}
                </a>
            </li>
            
            {{-- Dynamic Pages (CI lines 83-104) --}}
            @if(isset($pages))
                @foreach($pages as $key => $page)
                    @php
                        $slug = $page['slug'];
                        $url = site_url('Page/' . $slug);
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
                            $url = site_url($dataUrl['class'] . '/' . $dataUrl['action']);
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
                    <a href="{{ site_url('/') }}">
                        {{ $language_name == 'french' ? 'Accueil' : 'Home' }}
                    </a>
                </li>
                
                {{-- Subdomain Links for Mobile --}}
                <li>
                    <a href="https://pod.printing.coop" target="_blank">
                        {{ $language_name == 'french' ? 'POD' : 'POD' }}
                    </a>
                </li>
                <li>
                    <a href="https://store.printing.coop" target="_blank">
                        {{ $language_name == 'french' ? 'Magasin' : 'Store' }}
                    </a>
                </li>
                
                <li class="mobile-drop">
                    <a href="{{ site_url('Products') }}" id="products">
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
                                    <a href="{{ site_url('Products') }}" class="{{ $selected }}">
                                        {{ $language_name == 'french' ? 'Toutes catégories' : 'All categories' }}
                                    </a>
                                    @foreach($categories['categories'] as $key => $category)
                                        <div class="single-filter-tab">
                                            <a href="{{ site_url('Products?category_id=' . base64_encode($category['id'])) }}" class="{{ $selected == $category['id'] ? 'selected' : '' }}">
                                                {{ $language_name == 'french' ? ucfirst($category['name_french']) : ucfirst($category['name']) }}
                                            </a>
                                            <div class="single-filter-hover">
                                                @if(isset($category['sub_categories']) && count($category['sub_categories']) > 0)
                                                    @foreach($category['sub_categories'] as $skey => $subcategory)
                                                        <div class="single-filter-hover-inner">
                                                            <a href="{{ site_url('Products?category_id=' . base64_encode($category['id']) . '&sub_category_id=' . base64_encode($subcategory['id'])) }}" class="{{ $sub_category_selected == $subcategory['id'] ? 'selected' : '' }}">
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
                            $url = site_url('Page/' . $slug);
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
                                $url = site_url($dataUrl['class'] . '/' . $dataUrl['action']);
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
