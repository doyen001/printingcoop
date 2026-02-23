{{-- CI: application/views/Products/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? ($language_name == 'french' ? 'Produits' : 'Products'))

@section('content')

{{-- Breadcrumb --}}
<!-- <div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ $language_name == 'french' ? 'Accueil' : 'Home' }}</a></li>
                @if(!empty($category_data))
                    <li class="breadcrumb-item"><a href="{{ url('Products/' . $category_data->category_slug) }}">{{ $language_name == 'french' ? $category_data->name_french : $category_data->name }}</a></li>
                @endif
                @if(!empty($sub_category_data))
                    <li class="breadcrumb-item active" aria-current="page">{{ $language_name == 'french' ? $sub_category_data->name_french : $sub_category_data->name }}</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $language_name == 'french' ? 'Produits' : 'Products' }}</li>
                @endif
            </ol>
        </nav>
    </div>
</div> -->

<style>
:root {
    --primary-color: #f28738;
    --secondary-color: #ff6b00;
    --light-gray: #f5f5f5;
    --border-color: #e0e0e0;
    --text-color: #333;
    --card-shadow: 0 2px 10px rgba(0,0,0,0.1);
    --hover-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.products-section {
    padding: 4rem 0;
    background: var(--light-gray);
}

/* Category Sidebar Styles */
.categories-sidebar {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    position: sticky;
    top: 20px;
}

.sidebar-title {
    color: #f28738;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding: 0.75rem 1rem;
    background: rgba(24, 62, 115, 0.05);
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.sidebar-title i {
    color: var(--secondary-color);
    font-size: 1.1em;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-item {
    margin-bottom: 0.5rem;
}

.category-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #183e73;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    background: transparent;
    flex-grow: 1;
}

.category-link:hover,
.category-link.selected {
    background: linear-gradient(45deg, rgba(24, 62, 115, 0.08), rgba(255, 107, 0, 0.08));
    color: #f28738;
    transform: translateX(5px);
}

.category-link span:first-child {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.category-count {
    background: white;
    color: #183e73;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(255, 107, 0, 0.2);
}

.subcategory-list {
    margin-left: 1.25rem;
    padding-left: 1rem;
    margin-top: 0.5rem;
    border-left: 2px dashed rgba(24, 62, 115, 0.2);
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.category-item.expanded > .subcategory-list {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.subcategory-list .category-link {
    font-size: 0.95rem;
    padding: 0.6rem 0.75rem;
}

.subcategory-list .category-count {
    font-size: 0.8rem;
    padding: 0.2rem 0.6rem;
}

.category-item-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.toggle-subcategories {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    color: #4a5568;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    outline: none;
}

.toggle-subcategories:hover {
    color: var(--primary-color);
}

.toggle-subcategories i {
    transition: transform 0.3s ease;
}

.category-item.expanded .toggle-subcategories i {
    transform: rotate(180deg);
}

.no-categories {
    text-align: center;
    padding: 2rem 1rem;
    color: #666;
    font-style: italic;
    background: rgba(24, 62, 115, 0.05);
    border-radius: 8px;
}

/* Products Container */
.products-container {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: var(--card-shadow);
}

.category-header {
    margin-bottom: 2rem;
    text-align: center;
}

.category-title {
    color: #f28738;
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.category-description {
    color: var(--text-color);
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
}

/* Sort Controls */
.sort-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.sort-select {
    padding: 0.5rem 2rem 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    background: white;
    color: var(--text-color);
    font-size: 1rem;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    cursor: pointer;
}

/* Products Grid */
.products-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 1rem 0;
}

@media (max-width: 1024px) {
    .products-grid {
        grid-template-columns: repeat(2, minmax(250px, 1fr));
    }
}

@media (max-width: 640px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
}

/* Product Cards */
.product-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: var(--card-shadow);
    position: relative;
}

.product-card:hover {
    box-shadow: var(--hover-shadow);
    transform: translateY(-5px);
}

.product-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #f28738;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.875rem;
    z-index: 1;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.product-card:hover .product-badge {
    opacity: 1;
    transform: translateY(0);
}

.product-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-details {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.product-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #f28738;
    margin: 0;
    line-height: 1.4;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: auto;
}

.product-price {
    font-weight: 600;
    position: relative;
    color: var(--secondary-color);
}

.quick-view-btn {
    background: #f28738;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    text-decoration: none;
    transition: all 0.3s ease;
    text-align: center;
    flex-grow: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.quick-view-btn i {
    font-size: 0.875rem;
}

.quick-view-btn:hover {
    transform: translateY(-2px);
    color: #fff;
    box-shadow: 0 4px 12px rgba(242, 135, 56, 0.4);
}

/* Pagination Controls */
.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
    flex-wrap: wrap;
}

.pagination-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    min-width: 45px;
    justify-content: center;
}

.pagination-btn.page-num {
    padding: 0.75rem;
}

.pagination-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.pagination-btn.active {
    background: var(--primary-color);
    color: white;
    pointer-events: none;
}

.pagination-dots {
    color: var(--primary-color);
    font-weight: bold;
    padding: 0 0.5rem;
}

.prev-btn, .next-btn {
    background: #f28738;
    border-color: #f28738;
    color: white;
}

.prev-btn:hover, .next-btn:hover {
    background: #d67530;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #666;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #999;
}

/* Responsive */
@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
}

@media (max-width: 768px) {
    .products-section {
        padding: 2rem 0;
    }

    .categories-sidebar {
        margin-bottom: 2rem;
        position: relative;
        top: 0;
    }

    .category-title {
        font-size: 1.5rem;
    }

    .products-container {
        padding: 1rem;
    }

    .sort-controls {
        flex-direction: column;
        gap: 1rem;
    }
    
    .pagination-controls {
        margin-top: 2rem;
        gap: 0.25rem;
    }

    .pagination-btn {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        min-width: 40px;
    }
}

@media (max-width: 480px) {
    .pagination-btn span {
        display: none;
    }

    .prev-btn i, .next-btn i {
        margin: 0;
    }
}
</style>

@if (!in_array($page_title, ['Home', 'Product Details', 'Accueil']))
<div class="page-title-section universal-bg-white">
    <div class="container">
        <div class="page-title-section-inner universal-half-spacing kkk">
            <div class="inner-breadcrum bbb">
                <a href="{{ config('app.url') }}">
                    {{ app()->getLocale() == 'fr' ? 'Accueil' : 'Home' }}
                </a>
                
                @if(empty($sub_category_name) && empty($category_name))
                    /
                    <span class="current gg">
                        {{ __('all categories') }}
                    </span>
                @endif
                
                @if(!empty($sub_category_name))
                    /
                    <span class="current">
                        <a href="{{ url('Products?category_id=' . base64_encode($category_id ?? '')) }}">
                            {{ $category_name }}
                        </a>
                    </span>
                @endif
                
                @if(empty($sub_category_name) && !empty($category_name))
                    /
                    <span class="current">
                        {{ $category_name }}
                    </span>
                @endif
                
                @if(isset($sub_category_name))
                    /
                    <span>
                        {{ $sub_category_name }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endif


<div class="products-section">
    <div class="container">
        <div class="row">
            {{-- Categories Sidebar --}}
            <div class="col-lg-3">
                <div class="categories-sidebar">
                    <h2 class="sidebar-title">
                        <i class="fas fa-layer-group"></i>
                        {{ $language_name == 'french' ? 'Catégories' : 'Categories' }}
                    </h2>

                    @if(isset($categories['categories']) && count($categories['categories']) > 0)
                        <ul class="category-list">
                            @php
                                $selected = $selected_category ?? 'null';
                                $selected_sub_cat = $selected_subcategory ?? 'null';
                            @endphp
                            
                            @if($MainStoreData->show_all_categories ?? false)
                                <li class="category-item">
                                    <a href="{{ site_url('Products') }}" class="category-link {{ $selected == 'selected' ? 'selected' : '' }}">
                                        <span>
                                            <i class="fas fa-border-all"></i>
                                            {{ $language_name == 'french' ? 'Toutes catégories' : 'All categories' }}
                                        </span>
                                        <span class="category-count">{{ $categories['all_categories_products'] ?? 0 }}</span>
                                    </a>
                                </li>
                            @endif

                            @foreach($categories['categories'] as $category)
                                <li class="category-item {{ !empty($category['sub_categories']) ? 'has-children' : '' }}
                                    {{ ($selected == $category['id'] || (!empty($category['sub_categories']) && in_array($selected_sub_cat, array_column($category['sub_categories'], 'id')))) ? 'expanded' : '' }}">
                                    <div class="category-item-wrapper">
                                        @if(!empty($category['sub_categories']))
                                            <button class="toggle-subcategories" aria-label="Toggle subcategories">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        @endif
                                        @php
                                            $categoryId = base64_encode($category['id'] ?? '') ?? '';
                                        @endphp
                                        <a href="{{ site_url('Products?category_id=' . $categoryId) }}"
                                           class="category-link {{ $selected == $categoryId ? 'selected' : '' }}">
                                            <span>
                                                <i class="fas fa-folder"></i>
                                                {{ $language_name == 'french' ? ucfirst($category['name_french'] ?? $category['name']) : ucfirst($category['name']) }}
                                            </span>
                                            <span class="category-count">{{ $category['total_products'] ?? 0 }}</span>
                                        </a>
                                    </div>

                                    @if(!empty($category['sub_categories']))
                                        <ul class="category-list subcategory-list">
                                            @foreach($category['sub_categories'] as $subcategory)
                                            @php
                                                $categoryId = base64_encode($category['id'] ?? '') ?? '';
                                                $subCategoryId = base64_encode($subcategory['id'] ?? '') ?? '';
                                            @endphp
                                                <li class="category-item">
                                                    <a href="{{ site_url('Products?category_id=' . ($categoryId) . '&sub_category_id=' . ($subCategoryId)) }}"
                                                       class="category-link {{ $selected_sub_cat == $subCategoryId ? 'selected' : '' }}">
                                                        <span>
                                                            <i class="fas fa-angle-right"></i>
                                                            {{ $language_name == 'french' ? ($subcategory['name_french'] ?? $subcategory['name']) : $subcategory['name'] }}
                                                        </span>
                                                        <span class="category-count">{{ $subcategory['sub_category_total_products'] ?? 0 }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="no-categories">
                            <i class="fas fa-folder-open fa-2x mb-3"></i>
                            <p>{{ $language_name == 'french' ? 'Aucune catégorie trouvée' : 'No Category Found' }}</p>
                        </div>
                    @endif
                </div>
            </div>
                
            {{-- Products Listing --}}
            @if(isset($lists) && count($lists) > 0)
                <div class="col-lg-9">
                    <div class="products-container">
                        {{-- Category Header --}}
                        <div class="category-header">
                            <h1 class="category-title">
                            @php
                                $cat_title = '';
                                $cat_des = '';
                                
                                if (empty($sub_category_data) && !empty($category_data)) {
                                    $cat_title = ($language_name == 'french') ? $category_data->name_french : $category_data->name;
                                    $cat_des = ($language_name == 'french') ? ($category_data->category_dispersion_french ?? '') : ($category_data->category_dispersion ?? '');
                                } else if (!empty($sub_category_data) && !empty($category_data)) {
                                    $cat_title = ($language_name == 'french') ? $sub_category_data->name_french : $sub_category_data->name;
                                    $cat_des = ($language_name == 'french') ? ($sub_category_data->sub_category_dispersion_french ?? '') : ($sub_category_data->sub_category_dispersion ?? '');
                                } else {
                                    $cat_title = ($language_name == 'french') ? "Toutes catégories" : "All Categories Products";
                                }
                                echo $cat_title;
                            @endphp
                            </h1>
                            @if(!empty($cat_des))
                                <p class="category-description">{!! $cat_des !!}</p>
                            @endif
                        </div>

                        {{-- Sort Controls --}}
                        <div class="sort-controls">
                            <div class="sort-group">
                                <select class="sort-select" id="sortProducts">
                                    <option value="default">{{ $language_name == 'french' ? 'Trier par' : 'Sort by' }}</option>
                                    <option value="name_asc">{{ $language_name == 'french' ? 'Nom (A-Z)' : 'Name (A-Z)' }}</option>
                                    <option value="name_desc">{{ $language_name == 'french' ? 'Nom (Z-A)' : 'Name (Z-A)' }}</option>
                                    <option value="price_asc">{{ $language_name == 'french' ? 'Prix (Croissant)' : 'Price (Low to High)' }}</option>
                                    <option value="price_desc">{{ $language_name == 'french' ? 'Prix (Décroissant)' : 'Price (High to Low)' }}</option>
                                </select>
                            </div>
                        </div>
                        
                        {{-- Products Grid --}}
                        <div class="products-grid">
                            @foreach($lists as $list)
                                @php
                                    $imageurl = site_url('uploads/products/' . ($list['product_image'] ?? 'default.jpg'));
                                    $filename = $list['product_image'] ?? '';
                                    $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
                                    $product_id = $list['id'] ?? '';
                                    $id = base64_encode($product_id);
                                @endphp
                              
                                <div class="product-card">
                                    <div class="product-badge">
                                        {{ $language_name == 'french' ? 'Voir les détails' : 'View Details' }}
                                    </div>
                                    <div class="product-image">
                                        <a href="{{ site_url('Products/view/' . $id) }}">
                                            <img src="{{ $imageurl }}" alt="{{ $filenameWithoutExtension }}">
                                        </a>
                                    </div>

                                    <div class="product-details">
                                        <h3 class="product-title">
                                            <a href="{{ site_url('Products/view/' . $id) }}" style="color: inherit; text-decoration: none;">
                                                {{ $language_name == 'french' ? ($list['name_french'] ?? $list['name'] ?? '') : ($list['name'] ?? '') }}
                                            </a>
                                        </h3>

                                        @if(!empty($list['description']) || !empty($list['description_french']))
                                            <p class="product-description">
                                                {{ $language_name == 'french' ? ($list['description_french'] ?? $list['description'] ?? '') : ($list['description'] ?? '') }}
                                            </p>
                                        @endif

                                        @if(!empty($list['price']))
                                            <span class="product-price">{{ $product_price_currency_symbol ?? '$' }}{{ number_format($list[$product_price_currency] ?? $list['price'], 2) }}</span>
                                        @endif

                                        <div class="product-meta">
                                            <a href="{{ site_url('Products/view/' . $id) }}" class="quick-view-btn">
                                                <i class="fas fa-eye"></i>
                                                {{ $language_name == 'french' ? 'Aperçu rapide' : 'Quick View' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination Buttons --}}
                        @if(isset($total_pages) && $total_pages > 1)
                            <div class="pagination-controls">
                                @if(!empty($prevPage))
                                    <a href="{{ $url . (strpos($url, '?') !== false ? '&' : '?') }}pageno={{ $prevPage }}" class="pagination-btn prev-btn">
                                        <i class="fas fa-chevron-left"></i>
                                        {{ $language_name == 'french' ? 'Précédent' : 'Previous' }}
                                    </a>
                                @endif

                                @php
                                    $pageno = $pageno ?? 1;
                                    $range = 2;
                                @endphp

                                @if($pageno > 1)
                                    <a href="{{ $url . (strpos($url, '?') !== false ? '&' : '?') }}pageno=1" class="pagination-btn page-num {{ $pageno == 1 ? 'active' : '' }}">1</a>
                                @endif

                                @if($pageno > $range + 1)
                                    <span class="pagination-dots">...</span>
                                @endif

                                @for($i = max(2, $pageno - $range); $i <= min($pageno + $range, $total_pages - 1); $i++)
                                    <a href="{{ $url . (strpos($url, '?') !== false ? '&' : '?') }}pageno={{ $i }}" class="pagination-btn page-num {{ $pageno == $i ? 'active' : '' }}">{{ $i }}</a>
                                @endfor

                                @if($pageno < $total_pages - $range)
                                    <span class="pagination-dots">...</span>
                                @endif

                                @if($pageno < $total_pages)
                                    <a href="{{ $url . (strpos($url, '?') !== false ? '&' : '?') }}pageno={{ $total_pages }}" class="pagination-btn page-num {{ $pageno == $total_pages ? 'active' : '' }}">{{ $total_pages }}</a>
                                @endif

                                @if(!empty($NextPage))
                                    <a href="{{ $url . (strpos($url, '?') !== false ? '&' : '?') }}pageno={{ $NextPage }}" class="pagination-btn next-btn">
                                        {{ $language_name == 'french' ? 'Suivant' : 'Next' }}
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="col-lg-9">
                    <div class="products-container">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>{{ $language_name == 'french' ? 'Aucun produit trouvé' : 'No Products Found' }}</h3>
                            <p>{{ $language_name == 'french' ? 'Essayez de modifier vos filtres ou parcourez d\'autres catégories.' : 'Try adjusting your filters or browse other categories.' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sort functionality
    const sortSelect = document.getElementById('sortProducts');
    const productsGrid = document.querySelector('.products-grid');

    if (sortSelect && productsGrid) {
        sortSelect.addEventListener('change', function() {
            const products = Array.from(productsGrid.children);

            products.sort((a, b) => {
                const titleA = a.querySelector('.product-title')?.textContent || '';
                const titleB = b.querySelector('.product-title')?.textContent || '';
                const priceA = parseFloat(a.querySelector('.product-price')?.textContent.replace(/[^0-9.]/g, '') || 0);
                const priceB = parseFloat(b.querySelector('.product-price')?.textContent.replace(/[^0-9.]/g, '') || 0);

                switch(this.value) {
                    case 'name_asc':
                        return titleA.localeCompare(titleB);
                    case 'name_desc':
                        return titleB.localeCompare(titleA);
                    case 'price_asc':
                        return priceA - priceB;
                    case 'price_desc':
                        return priceB - priceA;
                    default:
                        return 0;
                }
            });

            productsGrid.innerHTML = '';
            products.forEach(product => productsGrid.appendChild(product));
        });
    }

    // Category accordion functionality
    const toggleButtons = document.querySelectorAll('.toggle-subcategories');

    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const categoryItem = this.closest('.category-item');
            const wasExpanded = categoryItem.classList.contains('expanded');

            // Only close other items if this isn't already selected
            if (!categoryItem.querySelector('.category-link.selected')) {
                document.querySelectorAll('.has-children.expanded').forEach(el => {
                    if (el !== categoryItem && !el.querySelector('.category-link.selected') &&
                        !el.querySelector('.subcategory-list .category-link.selected')) {
                        el.classList.remove('expanded');
                    }
                });
            }

            // Toggle current item
            categoryItem.classList.toggle('expanded');
        });
    });

    // Loading animation for product images
    function addLoadingAnimation() {
        const productImages = document.querySelectorAll('.product-image img');
        productImages.forEach(img => {
            if (!img.complete) {
                img.parentElement.classList.add('loading');
                img.addEventListener('load', () => {
                    img.parentElement.classList.remove('loading');
                });
            }
        });
    }

    addLoadingAnimation();
});
</script>
@endsection
