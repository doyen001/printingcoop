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
                <a href="https://pod.printing.coop">
                    {{ $language_name == 'french' ? 'Cadeaux&Décoration' : 'Gifts&Décor' }}
                </a>
            </li>
            <li>
                <a href="https://store.printing.coop">
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
                    <a href="https://printondemand.printing.coop">
                        {{ $language_name == 'french' ? 'Cadeaux&Décoration' : 'Gifts&Décor' }}
                    </a>
                </li>
                <li>
                    <a href="https://store.printing.coop">
                        {{ $language_name == 'french' ? 'Vêtements' : 'Apparel' }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
