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
    text-transform: none !important;
}

.header-menu-bar .all-menu li a:hover {
    color: #2c3e50 !important;
}

/* .header-menu-bar.sticky {
    box-shadow: 0 5px 15px rgba(168, 168, 168, 0.3);
} */
</style>
<div class="container-fluid header-menu-bar">
    <div class="header-menu-bar-inner">
        <ul class="all-menu">
            <li>
                <a href="{{ site_url('Products') }}">
                    {{ $language_name == 'french' ? 'Tous les produits' : 'All products' }}
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
            <li>
                <a href="{{ site_url('Products?category_id=' . base64_encode('1')) }}">
                    {{ $language_name == 'french' ? 'Cartes de visite' : 'Business Cards' }}
                </a>
            </li>
            <li>
                <a href="{{ site_url('Products?tag_id=' . base64_encode('6')) }}">
                    {{ $language_name == 'french' ? 'Marketing&Papeterie' : 'Marketing&Stationery' }}
                </a>
            </li>
            <li>
                <a href="{{ site_url('Products?category_id=Mzc=') }}">
                    {{ $language_name == 'french' ? 'Panneaux&Bannières' : 'Signs&Banners' }}
                </a>
            </li>
            <li>
                <a href="{{ site_url('Products?category_id=MTI=Products?category_id=Mg==') }}">
                    {{ $language_name == 'french' ? 'Annonces et Cartes de vœux' : 'Announces and Greeting Cards' }}
                </a>
            </li>
            <li>
                <a href="{{ site_url('Products?category_id=Mg==') }}">
                    {{ $language_name == 'french' ? 'Stickers&Étiquettes' : 'Stickers&Labels' }}
                </a>
            </li>
            <li>
                <a href="{{ site_url('Pages/estimate') }}">
                    {{ $language_name == 'french' ? 'Demande de Devis' : 'Quote Request' }}
                </a>
            </li>
            <li>
                <a href="#" onclick="confirmRedirectToPOD(event)">
                    {{ $language_name == 'french' ? 'Cadeaux&Décoration' : 'Gifts&Décor' }}
                </a>
            </li>
            <li>
                <a href="#" onclick="confirmRedirectToStore(event)">
                    {{ $language_name == 'french' ? 'Vêtements' : 'Apparel' }}
                </a>
            </li>
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
                    <a href="{{ site_url('Products') }}">
                        {{ $language_name == 'french' ? 'Tous les produits' : 'All products' }}
                    </a>
                </li>
                <li>
                    <a href="{{ site_url('Products?category_id=' . base64_encode('1')) }}">
                        {{ $language_name == 'french' ? 'Cartes de visite' : 'Business Cards' }}
                    </a>
                </li>
                <li>
                    <a href="{{ site_url('Products?tag_id=' . base64_encode('6')) }}">
                        {{ $language_name == 'french' ? 'Marketing&Papeterie' : 'Marketing&Stationery' }}
                    </a>
                </li>
                <li>
                    <a href="{{ site_url('Products') }}">
                        {{ $language_name == 'french' ? 'Panneaux&Bannières' : 'Signs&Banners' }}
                    </a>
                </li>
                <li>
                    <a href="{{ site_url('Products') }}">
                        {{ $language_name == 'french' ? 'Annonces et Cartes de vœux' : 'Announces and Greeting Cards' }}
                    </a>
                </li>
                <li>
                    <a href="{{ site_url('Products') }}">
                        {{ $language_name == 'french' ? 'Stickers&Étiquettes' : 'Stickers&Labels' }}
                    </a>
                </li>
                <li>
                    <a href="{{ site_url('Pages/estimate') }}">
                        {{ $language_name == 'french' ? 'Demande de Devis' : 'Quote Request' }}
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmRedirectToPOD(event)">
                        {{ $language_name == 'french' ? 'Cadeaux&Décoration' : 'Gifts&Décor' }}
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmRedirectToStore(event)">
                        {{ $language_name == 'french' ? 'Vêtements' : 'Apparel' }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
// Function to show confirmation modal for POD redirect
function confirmRedirectToPOD(event) {
    event.preventDefault();
    
    const message = '{{ $language_name == "french" ? "Êtes-vous sûr de vouloir visiter notre boutique POD pour des produits personnalisés?" : "Are you sure you want to visit our POD store for custom products?" }}';
    
    // Create modal HTML
    const modalHtml = `
        <div id="pod-confirm-modal" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        ">
            <div style="
                background: white;
                padding: 30px;
                border-radius: 8px;
                max-width: 400px;
                text-align: center;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            ">
                <h3 style="margin: 0 0 15px 0; color: #333;">{{ $language_name == "french" ? "Confirmation" : "Confirmation" }}</h3>
                <p style="margin: 0 0 25px 0; color: #666; line-height: 1.5;">${message}</p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button id="pod-yes-btn" style="
                        background: #f28738;
                        color: white;
                        border: none;
                        padding: 10px 25px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 500;
                    ">{{ $language_name == "french" ? "Oui" : "Yes" }}</button>
                    <button id="pod-no-btn" style="
                        background: #6c757d;
                        color: white;
                        border: none;
                        padding: 10px 25px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 500;
                    ">{{ $language_name == "french" ? "Non" : "No" }}</button>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Add event listeners
    document.getElementById('pod-yes-btn').addEventListener('click', function() {
        window.open('https://pod.printing.coop/', '_blank');
        document.getElementById('pod-confirm-modal').remove();
    });
    
    document.getElementById('pod-no-btn').addEventListener('click', function() {
        document.getElementById('pod-confirm-modal').remove();
    });
    
    // Close modal when clicking outside
    document.getElementById('pod-confirm-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}

// Function to show confirmation modal for Store redirect
function confirmRedirectToStore(event) {
    event.preventDefault();
    
    const message = '{{ $language_name == "french" ? "Êtes-vous sûr de vouloir visiter notre boutique pour des articles personnalisés?" : "Are you sure you want to visit our store for custom items?" }}';
    
    // Create modal HTML
    const modalHtml = `
        <div id="store-confirm-modal" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        ">
            <div style="
                background: white;
                padding: 30px;
                border-radius: 8px;
                max-width: 400px;
                text-align: center;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            ">
                <h3 style="margin: 0 0 15px 0; color: #333;">{{ $language_name == "french" ? "Confirmation" : "Confirmation" }}</h3>
                <p style="margin: 0 0 25px 0; color: #666; line-height: 1.5;">${message}</p>
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button id="store-yes-btn" style="
                        background: #f28738;
                        color: white;
                        border: none;
                        padding: 10px 25px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 500;
                    ">{{ $language_name == "french" ? "Oui" : "Yes" }}</button>
                    <button id="store-no-btn" style="
                        background: #6c757d;
                        color: white;
                        border: none;
                        padding: 10px 25px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-size: 14px;
                        font-weight: 500;
                    ">{{ $language_name == "french" ? "Non" : "No" }}</button>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Add event listeners
    document.getElementById('store-yes-btn').addEventListener('click', function() {
        window.open('https://store.printing.coop/', '_blank');
        document.getElementById('store-confirm-modal').remove();
    });
    
    document.getElementById('store-no-btn').addEventListener('click', function() {
        document.getElementById('store-confirm-modal').remove();
    });
    
    // Close modal when clicking outside
    document.getElementById('store-confirm-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.remove();
        }
    });
}
</script>
