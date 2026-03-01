@extends('elements.app')

@section('title', $language_name == 'french' ? $Product['name_french'] ?? $Product['name'] : $Product['name'])

@section('content')

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

        /* Breadcrumb Section */
        .page-title-section {
            background: white;
            /* padding: 1.5rem 0; */
            border-bottom: 1px solid var(--border-color);
        }

        .inner-breadcrum {
            font-size: 0.95rem;
            color: var(--text-light);
        }

        .inner-breadcrum a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .inner-breadcrum a:hover {
            color: var(--primary-color);
        }

        .inner-breadcrum .current {
            color: var(--text-dark);
            font-weight: 600;
        }

        /* Main Product Section */
        .shop-single-section {
            background: var(--bg-light);
            padding: 3rem 0;
        }

        .shop-single-section-inner {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }

        /* Image Gallery */
        .swiper-container-gallery-top {
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
            background: var(--bg-light);
        }

        .shop-product-img {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }

        .shop-product-img img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-button-next,
        .swiper-button-prev {
            background: white;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 18px;
            color: var(--primary-color);
            font-weight: bold;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            /* background: var(--primary-color); */
        }

        .swiper-button-next:hover:after,
        .swiper-button-prev:hover:after {
            color: white;
        }

        /* Thumbnail Gallery */
        .product-sample-image {
            margin-top: 1rem;
        }

        .swiper-container-gallery-thumbs {
            border-radius: 12px;
            overflow: hidden;
        }

        .swiper-container-gallery-thumbs .swiper-slide {
            opacity: 0.6;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
        }

        .swiper-container-gallery-thumbs .swiper-slide-thumb-active {
            opacity: 1;
            border: 2px solid var(--primary-color);
        }

        .latest-product-img {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }

        .latest-product-img img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Product Details - modern card */
        .shop-product-detail-section {
            position: relative;
            background: #ffffff;
            border-radius: 24px;
            padding: 22px 22px 20px 30px;
            box-shadow: 0 26px 70px rgba(15, 23, 42, 0.14);
            border: 1px solid rgba(242, 135, 56, 0.16);
        }
/* 
        .shop-product-detail-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 16px;
            bottom: 16px;
            width: 3px;
            border-radius: 999px;
            background: linear-gradient(180deg, #ffb166, #f28738);
        } */

        .shop-product-detail {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
            gap: 1rem;
        }

        .shop-product-detail h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            line-height: 1.3;
            flex: 1;
        }

        .wishlist-area a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: var(--bg-light);
            border-radius: 999px;
            color: var(--text-light);
            font-size: 22px;
            transition: all 0.25s ease;
            text-decoration: none;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
        }

        .wishlist-area a:hover {
            background: #ffffff;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(242, 135, 56, 0.35);
        }

        /* Category & Availability */
        .shop-category {
            font-size: 0.95rem;
            color: var(--text-light);
            margin-bottom: 0.75rem;
        }

        .shop-category-label {
            font-weight: 600;
            color: #6b7280;
        }

        .shop-category font {
            display: inline-block;
            padding: 0.15rem 0.7rem;
            border-radius: 999px;
            background: #fff3e7;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .availability-pill {
            display: inline-block;
            padding: 0.15rem 0.7rem;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            background: #ecfdf3;
            color: #15803d;
        }

        .availability-pill.out-of-stock {
            background: #fef2f2;
            color: #b91c1c;
        }

        /* Description */
        .universal-dark-info {
            color: var(--text-light);
            line-height: 1.7;
            margin-bottom: 1.25rem;
            padding: 1rem 1.1rem;
            background: #f9fafb;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
        }

        /* Product Fields */
        .product-fields {
            margin-bottom: 1.4rem;
            padding: 1.1rem 1.1rem 0.4rem;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
        }

        /* Price Area */
        .set-price-area {
            margin-bottom: 1.4rem;
        }

        .shop-product-price {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .shop-product-price .new-price {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Quantity Input */
        .quant-cart {
            margin-bottom: 1rem;
        }

        .quant-cart label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .quant-cart input[type="text"] {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            transition: all 0.3s ease;
            text-align: center;
        }

        .quant-cart input[type="text"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(242, 135, 56, 0.1);
        }

        /* File Upload Section */
        .file-upload-section {
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            background: var(--bg-light);
            border-radius: 12px;
        }

        .info-span {
            display: block;
            font-size: 0.85rem;
            color: var(--text-light);
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(242, 135, 56, 0.05);
        }

        .file-btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }

        .upload-area:hover .file-btn {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(242, 135, 56, 0.3);
        }

        #file-drop {
            display: block;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* Add to Cart Button */
        .cart-adder {
            width: 100%;
            padding: 1rem 2rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(242, 135, 56, 0.3);
        }

        .cart-adder:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(242, 135, 56, 0.4);
        }

        .cart-adder:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Tabs Section */
        .shop-single-elements {
            margin-top: 3rem;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow-md);
        }

        .featured-tabs {
            display: flex;
            gap: 0.5rem;
            border-bottom: 2px solid var(--border-color);
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .tablinks {
            padding: 1rem 1.5rem;
            background: transparent;
            border: none;
            color: var(--text-light);
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }

        .tablinks:hover {
            color: var(--primary-color);
        }

        .tablinks.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }

        /* Tab Content */
        .tabcontent {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .universal-dark-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 1rem;
        }

        /* Template Table */
        .table {
            margin-top: 1rem;
        }

        .table thead th {
            background: var(--bg-light);
            color: var(--text-dark);
            font-weight: 600;
            border: none;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        button.downloadButton {
            border: none;
            background: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        button.downloadButton:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(242, 135, 56, 0.3);
        }

        /* Uploaded File Styles */
        .uploaded-file-single {
            margin-bottom: 1rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            background: white;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .uploaded-file-single:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .uploaded-file-single-inner {
            display: flex;
            align-items: center;
            padding: 1rem;
            gap: 1rem;
        }

        .uploaded-file-img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            background-color: var(--bg-light);
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .uploaded-file-single:hover .uploaded-file-img {
            transform: scale(1.05);
        }

        .uploaded-file-info {
            flex: 1;
            min-width: 0;
        }

        .uploaded-file-name {
            margin-bottom: 0.5rem;
        }

        .uploaded-file-name a {
            width: 150px;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .uploaded-file-name a:hover {
            color: var(--primary-color);
        }

        .upload-action-btn {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .upload-action-btn button {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-action-btn button:first-child {
            background: var(--bg-light);
            color: var(--text-dark);
        }

        .upload-action-btn button:first-child:hover {
            background: var(--primary-color);
            color: white;
        }

        .upload-action-btn button:last-child {
            background: #ff4757;
            color: white;
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-action-btn button:last-child:hover {
            background: #ff3838;
            transform: scale(1.1);
        }

        .upload-field {
            margin-top: 0.75rem;
        }

        .upload-field textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
            font-family: inherit;
            resize: vertical;
            min-height: 60px;
            transition: all 0.3s ease;
        }

        .upload-field textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(242, 135, 56, 0.1);
        }

        .align-items-center .col-md-7 {
            padding: 0 0 0 12%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .shop-product-detail-section {
                padding-left: 0;
                margin-top: 2rem;
            }

            .shop-product-detail h1 {
                font-size: 1.5rem;
            }

            .shop-single-section-inner {
                padding: 1.5rem;
            }

            .shop-single-elements {
                padding: 1.5rem;
            }

            .tablinks {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }

            .uploaded-file-single-inner {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .uploaded-file-img {
                width: 100%;
                height: 120px;
            }

            .upload-action-btn {
                width: 100%;
                justify-content: space-between;
            }

            .upload-action-btn button:first-child {
                flex: 1;
            }
        }
    </style>

    <div class="page-title-section universal-bg-white">
        <div class="container">
            <div class="page-title-section-inner universal-half-spacing">
                <div class="inner-breadcrum">
                    @if ($language_name == 'french')
                        <a href="{{ url('/') }}">Accueil</a>
                        @if (!empty($Product['category_name']))
                            @php
                                $category = app('App\Models\Category')->find($Product['category_id']);
                            @endphp
                            @if ($category && $category->status == 1)
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '')) }}">
                                    {{ $Product['category_name_french'] }}
                                </a>
                            @endif
                        @endif
                        @if (!empty($Product['sub_category_name']))
                            @php
                                $subcategory = app('App\Models\SubCategory')->find($Product['sub_category_id']);
                            @endphp
                            @if ($subcategory && $subcategory->status == 1 && !empty($subcategory->subcategory_slug))
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '') . '&sub_category_id=' . base64_encode($Product['sub_category_id'] ?? '')) }}">
                                    {{ $Product['sub_category_name_french'] }}
                                </a>
                            @endif
                        @endif
                        /<span class="current">{{ $Product['name_french'] }}</span>
                    @else
                        <a href="{{ url('/') }}">Home</a>
                        @if (!empty($Product['category_name']))
                            @php
                                $category = app('App\Models\Category')->find($Product['category_id']);
                            @endphp
                            @if ($category && $category->status == 1)
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '')) }}">
                                    {{ $Product['category_name'] }}
                                </a>
                            @endif
                        @endif
                        @if (!empty($Product['sub_category_name']))
                            @php
                                $subcategory = app('App\Models\SubCategory')->find($Product['sub_category_id']);
                            @endphp
                            @if ($subcategory && $subcategory->status == 1 && !empty($subcategory->subcategory_slug))
                                /
                                <a href="{{ url('Products?category_id=' . base64_encode($Product['category_id'] ?? '') . '&sub_category_id=' . base64_encode($Product['sub_category_id'] ?? '')) }}">
                                    {{ $Product['sub_category_name'] }}
                                </a>
                            @endif
                        @endif
                        /<span class="current">{{ $Product['name'] }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="shop-single-section universal-spacing universal-bg-white">
        <div class="container">
            <div class="shop-single-section-inner">
                <div class="row">
                    <div class="col-md-5 col-lg-6 col-xl-6">
                        <div class="swiper-container-gallery-top">
                            <div class="swiper-wrapper">
                                @foreach ($ProductImages as $key => $ProductImage)
                                    <div class="swiper-slide">
                                        <div class="shop-product-img">
                                            <img src="{{ getProductImage($ProductImage['image'], 'large') }}"
                                                alt="{{ $language_name == 'french' ? $Product['name_french'] : $Product['name'] }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @php
                                $prod_name = $language_name == 'french' ? $Product['name_french'] : $Product['name'];
                            @endphp
                            @if (count($ProductImages) > 1)
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            @endif
                        </div>
                        <div class="product-sample-image">
                            <div class="swiper-container-gallery-thumbs">
                                <div class="swiper-wrapper">
                                    @php
                                        $count = 0;
                                    @endphp
                                    @foreach ($ProductImages as $key => $ProductImage)
                                        @php
                                            $count++;
                                        @endphp
                                        <div class="swiper-slide">
                                            <div class="latest-single-product">
                                                <div class="latest-product-img">
                                                    <img src="{{ getProductImage($ProductImage['image']) }}"
                                                        alt="{{ $prod_name . '_' . $count }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-6 col-xl-6">
                        <div class="shop-product-detail-section">
                            <div class="shop-product-detail">
                                <h1>{{ $language_name == 'french' ? $Product['name_french'] : $Product['name'] }}</h1>
                                <div class="wishlist-area">
                                    <a data-toggle="tooltip" title="Add to wishlist" href="javascript:void(0)"
                                        onclick="addProductWishList('{{ $Product['id'] }}')">
                                        <i class="la la-heart-o"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    @php
                                        $multipalCategoryData = $Product['multipalCategoryData'];
                                    @endphp
                                    <div class="shop-category">
                                        <span class="shop-category-label">
                                            {{ $language_name == 'french' ? 'Catégorie' : 'Category' }} :
                                        </span>
                                        @foreach ($multipalCategoryData as $key => $val)
                                            <font>{{ $language_name == 'french' ? $val['name_french'] : $val['name'] }}</font>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="shop-category">
                                        <span class="shop-category-label">
                                            {{ $language_name == 'french' ? 'Disponibilité' : 'Availability' }} :
                                        </span>
                                        @php
                                            $inStock = empty($Product['is_stock']);
                                        @endphp
                                        <span class="availability-pill {{ $inStock ? '' : 'out-of-stock' }}">
                                            @if ($language_name == 'french')
                                                {{ $inStock ? 'En Stock' : 'En rupture de stock' }}
                                            @else
                                                {{ $inStock ? 'In Stock' : 'Out of Stock' }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($Product['short_description_french']) || !empty($Product['short_description']))
                                <div class="universal-dark-info">
                                    <span>{{ $language_name == 'french' ? $Product['short_description_french'] : $Product['short_description'] }}</span>
                                </div>
                            @endif

                            @php
                                $product_id = $Product['id'];
                                $buyNow = checkBuyNowProduct($Product['is_stock'], $Product['total_stock']);
                            @endphp

                            @if ($buyNow)
                                <form method="post" id="cartForm">
                                    @csrf
                                    <input type="hidden" id="product_id" value="{{ $Product['id'] }}" name="product_id">
                                    <input type="hidden" id="product_price" value="{{ $Product[$product_price_currency] }}"
                                        name="price">
                                    <div class="product-fields">
                                        <div class="row">
                                            @if ($provider)
                                                @include('products.product_detail_provider')
                                            @else
                                                @include('products.product_detail')
                                            @endif
                                        </div>
                                    </div>

                                    <div class="set-price-area">
                                        <div class="row align-items-center">
                                            <div class="col-6 col-md-6">
                                                <div class="shop-product-price">
                                                    <span>
                                                        <img src="{{ url('assets/images/loder.gif') }}" width="30"
                                                            style="display:none" id="loader-img" alt="loader-image">
                                                        <font class="new-price">
                                                            {{ $product_price_currency_symbol }}<span
                                                                id="total-price">{{ $Product[$product_price_currency] }}</span>
                                                        </font>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-6">
                                                <div class="quant-cart">
                                                    <label>{{ $language_name == 'french' ? "Combien d'ensembles" : 'How many sets' }}</label>
                                                    <input type="text" value="1" id="quantity" name="quantity"
                                                        onkeypress="javascript:return isNumber(event)"
                                                        onchange="setQuantity()">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($website_store_id != 5)
                                        <div class="file-upload-section">
                                            <div class="file-upload-area">
                                                <input type="file" name="file" id="file" style="display:none"
                                                    accept="image/jpeg, image/jpg, application/pdf">
                                                <span
                                                    class="info-span">{{ $language_name == 'french' ? 'Soumettre et télécharger le fichier (Taille autorisée par fichier: 250 Mo. Type de fichier autorisé: pdf, jpg, jpeg)' : 'Submit and Upload File (Allow size per file: 250 Mb. Allow file type:pdf, jpg, jpeg)' }}</span>
                                                <div class="upload-file upload-area" id="uploadfile">
                                                    <span
                                                        class="file-btn">{{ $language_name == 'french' ? 'Soumettre le téléchargement' : 'Submit Upload' }}</span>
                                                    <span
                                                        id="file-drop">{{ $language_name == 'french' ? 'Glisser-déposer des fichiers' : 'Drag & Drop Files' }}</span>
                                                </div>
                                            </div>

                                            <div class="uploaded-file-detail" id="upload-file-data">
                                                {{-- @if (session()->has("product_id.{$Product['id']}"))
                                                    @php
                                                        $file_data = session("product_id.{$Product['id']}");
                                                    @endphp
                                                    @foreach ($file_data as $key => $return_arr)
                                                        <div class="uploaded-file-single"
                                                            id="teb-{{ $return_arr['skey'] }}">
                                                            <div class="uploaded-file-single-inner">
                                                                <a href="{{ $return_arr['file_base_url'] }}"
                                                                    target="_blank">
                                                                    <div class="uploaded-file-img"
                                                                        style="background-image: url({{ $return_arr['src'] }})">
                                                                    </div>
                                                                </a>
                                                                <div class="uploaded-file-info">
                                                                    <div class="row align-items-center">
                                                                        <div class="col-md-7">
                                                                            <div class="uploaded-file-name"><span><a
                                                                                        href="{{ $return_arr['file_base_url'] }}"
                                                                                        target="_blank">{{ $return_arr['name'] }}</a></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="upload-action-btn">
                                                                                <button type="button"
                                                                                    onclick="update_cumment('{{ $return_arr['skey'] }}')"
                                                                                    id="smc-{{ $return_arr['skey'] }}">
                                                                                    {{ $language_name == 'french' ? 'Note de mise à jour' : 'Update Note' }}
                                                                                </button>
                                                                                <button type="button" title="Delete"
                                                                                    onclick="delete_image('{{ $return_arr['skey'] }}')"
                                                                                    id="smd-{{ $return_arr['skey'] }}">
                                                                                    <i class="las la-trash"></i>
                                                                                </button>
                                                                                <input type="hidden"
                                                                                    value="{{ $return_arr['location'] }}"
                                                                                    id="location-{{ $return_arr['skey'] }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="upload-field">
                                                                        <textarea id="cumment-{{ $return_arr['skey'] }}">{{ $return_arr['cumment'] }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif --}}
                                            </div>
                                        </div>
                                    @endif

                                    <div class="quant-cart">
                                        <input type="hidden" id="{{ $Product['id'] }}-rowid"
                                            value="{{ $productRowid }}">
                                        <input type="hidden" id="{{ $Product['id'] }}-productId">
                                        <button class="cart-adder" type="submit" id="btnSubmit">
                                            <span>{{ $language_name == 'french' ? 'Ajouter au chariot' : 'Add to Cart' }}</span>
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="shop-single-elements">
                <div class="featured-tabs tab">
                    <button class="tablinks active" id="defaultOpen" onclick="openCity(event, 'Description')">
                        {{ $language_name == 'french' ? 'La description' : 'Description' }}
                    </button>
                    @if (!empty($ProductDescriptions))
                        @foreach ($ProductDescriptions as $key => $val)
                            <button class="tablinks" id="defaultOpen{{ $val['id'] }}"
                                onclick="openCity(event, 'Description{{ $val['id'] }}')">
                                {{ $language_name == 'french' ? $val['title_french'] : $val['title'] }}
                            </button>
                        @endforeach
                    @endif
                    @if (!empty($ProductTemplates))
                        <button class="tablinks" id="defaultOpen-Template" onclick="openCity(event, 'template')">
                            {{ $language_name == 'french' ? 'Modèles' : 'Templates' }}
                        </button>
                    @endif
                </div>

                <div class="featured-tab-output">
                    <div id="Description" class="tabcontent">
                        <div class="tabcontent-inner">
                            <div class="universal-dark-title">
                                <span>{{ $language_name == 'french' ? 'La description' : 'Description' }}</span>
                            </div>
                            <div class="universal-dark-info">
                                <span>{!! $language_name == 'french' ? $Product['full_description_french'] : $Product['full_description'] !!}</span>
                            </div>
                        </div>
                    </div>

                    @if (!empty($ProductDescriptions))
                        @foreach ($ProductDescriptions as $key => $val)
                            <div id="Description{{ $val['id'] }}" class="tabcontent" style="display:none">
                                <div class="tabcontent-inner">
                                    <div class="universal-dark-title">
                                        <span>{{ $language_name == 'french' ? $val['title_french'] : $val['title'] }}</span>
                                    </div>
                                    <div class="universal-dark-info">
                                        <span>{!! $language_name == 'french' ? $val['description_french'] : $val['description'] !!}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (!empty($ProductTemplates))
                        <div id="template" class="tabcontent" style="display:none">
                            <div class="tabcontent-inner">
                                <div class="universal-dark-title">
                                    <span>{{ $language_name == 'french' ? 'Modèles' : 'Template' }}</span>
                                </div>
                                <div class="universal-dark-info">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    {{ $language_name == 'french' ? 'Dimensions finales' : 'Final Dimensions' }}
                                                </th>
                                                <th scope="col">
                                                    {{ $language_name == 'french' ? 'La description' : 'Description' }}
                                                </th>
                                                <th scope="col">
                                                    {{ $language_name == 'french' ? 'Télécharger' : 'Download' }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ProductTemplates as $key => $val)
                                                <tr>
                                                    <td>{{ $language_name == 'french' ? $val['final_dimensions_french'] : $val['final_dimensions'] }}
                                                    </td>
                                                    <td>{{ $language_name == 'french' ? $val['template_description_french'] : $val['template_description'] }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $path_ajax = '';
                                                            if ($val['template_file']) {
                                                                $path_ajax = url('uploads/templates/' . $val['template_file']);
                                                            }
                                                        @endphp
                                                        <button class="downloadButton"
                                                            data-image="{{ $val['template_file'] }}"
                                                            data-template-file="{{ $path_ajax }}">
                                                            <i class="fa fa-download" aria-hidden="true"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($language_name == 'french')
        <script src="{{ url('assets/file-upload/script-french.js') }}" type="text/javascript"></script>
    @else
        <script src="{{ url('assets/file-upload/script.js') }}" type="text/javascript"></script>
    @endif

    <script>
        function setQuantity() {
            var quantity = $('#quantity').val();
            if ($provider){
                if (quantity == '' || quantity == 0) {
                    $('#quantity').val('1');
                }
                $('#total-price').html($('[name="price"]').val() * $('#quantity').val());
            }
            else{
                if (quantity == '' || quantity == 0) {
                    $('#quantity').val('1');
                }
                var formData = new FormData($('#cartForm')[0]);
                $('#loader-img').show();
                $('.new-price-img').hide();

                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: "{{ url('products/calculate-price') }}",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#loader-img').hide();
                        $('.new-price-img').show();

                        var json = JSON.parse(data);
                        if (json.success == 1) {
                            $('#total-price').html(json.price);
                        }
                    }
                });
            }
        }

        function update_cumment(skey) {
            var cumment = $('#cumment-' + skey).val();
            var product_id = '{{ $product_id }}';
            if (cumment == '') {
                alert('Enter cumment');
                return false
            }

            $('#smc-' + skey).prop('disabled', true);
            $('#smc-' + skey).html('<img src="assets/images/loder.gif" width=20>');
            $('#loader-img').show();
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: "{{ url('Products/updateCumment') }}",
                data: ({
                    'cumment': cumment,
                    'product_id': product_id,
                    'skey': skey
                }),
                success: function(data) {
                    $('#loader-img').hide();
                    $('#smc-' + skey).prop('disabled', false);
                    $('#smc-' + skey).html('Update Note');
                }
            });
        }

        function delete_image(skey) {
            var location = $('#location-' + skey).val();
            var product_id = '{{ $product_id }}';
            if (location == '') {
                return false
            }

            $('#smd-' + skey).prop('disabled', true);
            $('#smd-' + skey).html('<img src="assets/images/loder.gif" width=20>');
            $('#loader-img').show();
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: "{{ url('Products/deleteImage') }}",
                data: ({
                    'location': location,
                    'product_id': product_id,
                    'skey': skey
                }),
                success: function(data) {
                    $('#loader-img').hide();
                    $('#upload-file-data #teb-' + skey).remove();
                }
            });
        }

        function isNumber(evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;
            return true;
        }

        $('form#cartForm').on('submit', function(e) {
            $('#loader-img').show();
            $('#btnSubmit').prop('disabled', true);
            e.preventDefault();
            var url = "{{ url('Products/addToCart') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    params: $(this).serialize()
                },
                cache: false,
                headers: {
                    accept: 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#loader-img').hide();
                    var json = JSON.parse(data);
                    console.log('Cart AJAX Response:', json);
                    var status = json.status;
                    var msg = json.msg;
                    $('#btnSubmit').prop('disabled', false);
                    if (status == 1) {
                        console.log('Updating cart count to:', json.total_item);
                        $('.cart-contents-count').html(json.total_item);
                        getCartItem();
                        $('.addtocart-message').html('<span><i class="la la-cart-plus"></i>' + msg +
                            '.</span>').addClass('active');

                        setTimeout(function() {
                            $('.addtocart-message').removeClass('active');
                            location.assign("{{ url('ShoppingCarts') }}");
                        }, 1000);
                    } else {
                        $('.addtocart-message').html('<span><i class="la la-cart-plus"></i>' + msg +
                            '.</span>').addClass('active');
                        setTimeout(function() {
                            $('.addtocart-message').removeClass('active');
                        }, 2000);
                    }
                },
                error: function(error) {}
            });
        });

        $(document).ready(function() {
            $('.downloadButton').on('click', function() {
                var templateFileName = $(this).data('template-file');
                var Name = $(this).data('image');

                var downloadUrl = templateFileName;

                var $links = $('<a>').attr({
                    href: downloadUrl,
                    download: Name
                });

                $links.appendTo('body')[0].click();
                $links.remove();
            });
        });
    </script>
@endsection
