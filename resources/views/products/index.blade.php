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
    padding: 3rem 0 4rem;
    background: white;
}

/* Full-width products container */
.products-container-full {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Page header */
.page-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #e8e8e8;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111;
    margin: 0;
    text-transform: capitalize;
}

/* Alphabetical products layout */
.products-alphabetical {
    margin-bottom: 3rem;
}

.letter-section {
    margin-bottom: 3rem;
}

.letter-header {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #111;
    display: inline-block;
    min-width: 40px;
}

/* Products grid per letter */
.products-grid-alpha {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
}

/* Product card - vertical layout like screenshot */
.product-card-alpha {
    width: 220px;
    display: flex;
    flex-direction: row;
    align-items: center;
    text-decoration: none;
    padding: 4px 0;
    transition: all 0.3s ease;
}

.product-card-alpha:hover {
    background-color: transparent;
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

.product-card-image {
    width: 80px;
    height: 80px;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-card-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 0.2rem;
}

.product-card-info {
    min-width: 0;
    padding-left: 10px;
}

.product-card-name {
    font-size: 13px;
    font-weight: 500;
    color: #111;
    line-height: 1.3;
    display: block;
}

.product-card-price {
    font-size: 11px;
    color: #666;
    line-height: 1.3;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #95a5a6;
}

.empty-state i {
    font-size: 4rem;
    color: #dfe6e9;
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    color: #2d3436;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #636e72;
    max-width: 500px;
    margin: 0 auto;
}

/* Pagination */
.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 3rem;
    flex-wrap: wrap;
}

.pagination-btn {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 5px;
    background: white;
    color: var(--text-color);
    text-decoration: none;
    transition: all 0.2s ease;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination-btn:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.pagination-btn.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    font-weight: 600;
}

.pagination-btn.page-num {
    min-width: 40px;
    justify-content: center;
}

.pagination-dots {
    padding: 0 0.25rem;
    color: var(--text-color);
}

/* Responsive design */
@media (max-width: 1200px) {
    .products-grid-alpha {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .letter-header {
        font-size: 1.25rem;
    }
    
    .products-grid-alpha {
        grid-template-columns: 1fr;
    }
    
    .product-card-alpha {
        padding: 12px 16px;
    }
    
    .product-card-image {
        width: 60px;
        height: 60px;
        margin-right: 12px;
    }
    
    .product-card-name {
        font-size: 12px;
    }
    
    .product-card-price {
        font-size: 10px;
    }
}

@media (max-width: 480px) {
    .products-section {
        padding: 2rem 0 3rem;
    }
    
    .page-title {
        font-size: 1.25rem;
    }
    
    .letter-header {
        font-size: 1.1rem;
    }
    
    .product-card-image {
        width: 50px;
        height: 50px;
        margin-right: 10px;
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
        {{-- Products Listing --}}
        @if(isset($lists) && count($lists) > 0)
            <div class="products-container-full">
                {{-- Page Header --}}
                <!-- <div class="page-header">
                    <h1 class="page-title">
                    @php
                        $cat_title = '';
                        
                        if (empty($sub_category_data) && !empty($category_data)) {
                            $cat_title = ($language_name == 'french') ? $category_data->name_french : $category_data->name;
                        } else if (!empty($sub_category_data) && !empty($category_data)) {
                            $cat_title = ($language_name == 'french') ? $sub_category_data->name_french : $sub_category_data->name;
                        } else {
                            $cat_title = ($language_name == 'french') ? "Tous les produits" : "All Products";
                        }
                        echo $cat_title;
                    @endphp
                    </h1>
                </div> -->

                {{-- Products organized alphabetically --}}
                <div class="products-alphabetical">
                    @php
                        // Group products by initial letter (A-Z) and sort by name
                        $productsGroupedByLetter = [];
                        foreach ($lists as $list) {
                            $productName = $language_name == 'french'
                                ? ($list['name_french'] ?? $list['name'] ?? '')
                                : ($list['name'] ?? '');

                            $productNameTrimmed = trim($productName);
                            $firstChar = mb_substr($productNameTrimmed, 0, 1, 'UTF-8');

                            // Default non-letter initials (digits, symbols) into '#'
                            if (!preg_match('/[A-Za-z]/u', $firstChar)) {
                                $initial = '#';
                            } else {
                                $initial = strtoupper($firstChar);
                            }

                            if (!isset($productsGroupedByLetter[$initial])) {
                                $productsGroupedByLetter[$initial] = [];
                            }

                            $productsGroupedByLetter[$initial][] = [
                                'id' => $list['id'] ?? '',
                                'name' => $productNameTrimmed,
                                'image' => $list['product_image'] ?? 'default.jpg',
                                'price' => $list['price'] ?? 0,
                                'description' => $language_name == 'french'
                                    ? ($list['description_french'] ?? $list['description'] ?? '')
                                    : ($list['description'] ?? '')
                            ];
                        }

                        // Sort groups by key so A-Z, then '#'
                        uksort($productsGroupedByLetter, function ($a, $b) {
                            if ($a === $b) return 0;
                            if ($a === '#') return 1;
                            if ($b === '#') return -1;
                            return strcmp($a, $b);
                        });

                        // Sort products within each letter group by name
                        foreach ($productsGroupedByLetter as $letter => $products) {
                            usort($productsGroupedByLetter[$letter], function ($a, $b) {
                                return strcasecmp($a['name'], $b['name']);
                            });
                        }
                    @endphp
                    
                    @foreach($productsGroupedByLetter as $letter => $products)
                        <div class="letter-section">
                            <h2 class="letter-header">{{ $letter }}</h2>
                            <div class="products-grid-alpha">
                                @foreach($products as $product)
                                    @php
                                        $imageurl = site_url('uploads/products/' . $product['image']);
                                        $id = base64_encode($product['id']);
                                    @endphp
                                    
                                    <a href="{{ site_url('Products/view/' . $id) }}" class="product-card-alpha">
                                        <div class="product-card-image">
                                            <img src="{{ $imageurl }}" alt="{{ $product['name'] }}">
                                        </div>
                                        <div class="product-card-info">
                                            <span class="product-card-name">{{ $product['name'] }}</span>
                                            @if(!empty($product['price']))
                                                <span class="product-card-price">{{ $language_name == 'french' ? 'Début à' : 'Starting at' }} {{ $product_price_currency_symbol ?? '$' }}{{ number_format($product['price'], 2) }}</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
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
        @else
            {{-- Empty State --}}
            <div class="products-container-full">
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <h3>{{ $language_name == 'french' ? 'Aucun produit trouvé' : 'No Products Found' }}</h3>
                    <p>{{ $language_name == 'french' ? 'Essayez de modifier vos filtres ou parcourez d\'autres catégories.' : 'Try adjusting your filters or browse other categories.' }}</p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading animation for product images
    function addLoadingAnimation() {
        const productImages = document.querySelectorAll('.product-card-image img');
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
