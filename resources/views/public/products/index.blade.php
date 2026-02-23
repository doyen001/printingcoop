{{-- 
    Product listing page (replicate CI Products/index.php)
    
    Available variables:
    - $lists: Array of products
    - $category_id, $category_name, $category_data
    - $sub_category_id, $sub_category_name, $sub_category_data
    - $printer_brand, $printer_series, $printer_models
    - $total: Total product count
    - $NextPage, $prevPage: Pagination
    - $url: Base URL for filters
    - $order: Current sort order
--}}

@extends('elements.app')

@section('content')
<div class="products-listing">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>{{ $page_title }}</h1>
        
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('Products') }}">Products</a></li>
                
                @if(!empty($category_name))
                    <li class="breadcrumb-item active">{{ $category_name }}</li>
                @endif
                
                @if(!empty($sub_category_name))
                    <li class="breadcrumb-item active">{{ $sub_category_name }}</li>
                @endif
            </ol>
        </nav>
    </div>
    
    {{-- Filters Section --}}
    <div class="filters-section">
        <div class="row">
            <div class="col-md-3">
                {{-- Category Filter --}}
                <div class="filter-group">
                    <h4>Categories</h4>
                    {{-- Category filter dropdown/list --}}
                </div>
                
                {{-- Subcategory Filter --}}
                @if(!empty($category_id))
                <div class="filter-group">
                    <h4>Subcategories</h4>
                    {{-- Subcategory filter dropdown/list --}}
                </div>
                @endif
                
                {{-- Printer Filters (for store 5) --}}
                @if(config('store.website_store_id') == 5)
                <div class="filter-group">
                    <h4>Printer Brand</h4>
                    {{-- Printer brand filter --}}
                </div>
                
                @if(!empty($printer_brand))
                <div class="filter-group">
                    <h4>Printer Series</h4>
                    {{-- Printer series filter --}}
                </div>
                @endif
                
                @if(!empty($printer_series))
                <div class="filter-group">
                    <h4>Printer Models</h4>
                    {{-- Printer models filter --}}
                </div>
                @endif
                @endif
            </div>
            
            <div class="col-md-9">
                {{-- Sort and Results Count --}}
                <div class="products-toolbar">
                    <div class="results-count">
                        Showing {{ count($lists) }} of {{ $total }} products
                    </div>
                    
                    <div class="sort-by">
                        <label>Sort By:</label>
                        <select name="sort_by" onchange="window.location.href='{{ $url }}&sort_by=' + this.value">
                            <option value="name" {{ $order == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="price_low" {{ $order == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ $order == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ $order == 'newest' ? 'selected' : '' }}>Newest</option>
                        </select>
                    </div>
                </div>
                
                {{-- Product Grid --}}
                <div class="products-grid row">
                    @forelse($lists as $product)
                    <div class="col-md-4 col-sm-6 product-item">
                        <div class="product-card">
                            {{-- Product Image --}}
                            <div class="product-image">
                                <a href="{{ url('Products/view/' . base64_encode($product['id'])) }}">
                                    <img src="{{ url('uploads/products/medium/' . $product['product_image']) }}" 
                                         alt="{{ $product['name'] }}" 
                                         class="img-fluid">
                                </a>
                            </div>
                            
                            {{-- Product Info --}}
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="{{ url('Products/view/' . base64_encode($product['id'])) }}">
                                        {{ config('store.language_name') == 'French' ? $product['name_french'] : $product['name'] }}
                                    </a>
                                </h3>
                                
                                {{-- Category --}}
                                @if(isset($product['category']['name']))
                                <div class="product-category">
                                    {{ config('store.language_name') == 'French' ? $product['category']['name_french'] : $product['category']['name'] }}
                                </div>
                                @endif
                                
                                {{-- Price --}}
                                <div class="product-price">
                                    {{ config('store.product_price_currency_symbol') }}{{ number_format($product['price'], 2) }}
                                </div>
                                
                                {{-- Rating --}}
                                @if($product['rating'] > 0)
                                <div class="product-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa{{ $i <= $product['rating'] ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                    <span>({{ $product['reviews'] }} reviews)</span>
                                </div>
                                @endif
                                
                                {{-- Add to Cart Button --}}
                                <a href="{{ url('Products/view/' . base64_encode($product['id'])) }}" 
                                   class="btn btn-primary btn-block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="no-products">No products found.</p>
                    </div>
                    @endforelse
                </div>
                
                {{-- Pagination (12 per page) --}}
                @if($total > 12)
                <div class="pagination-wrapper">
                    <nav aria-label="Product pagination">
                        <ul class="pagination">
                            @if(!empty($prevPage))
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}&pageno={{ $prevPage }}">Previous</a>
                            </li>
                            @endif
                            
                            {{-- Page numbers would go here --}}
                            
                            @if(!empty($NextPage))
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}&pageno={{ $NextPage }}">Next</a>
                            </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
