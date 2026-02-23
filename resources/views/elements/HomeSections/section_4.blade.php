{{-- CI: application/views/elements/HomeSections/section_4.php --}}
{{-- Montreal book printing Section --}}

<style>
    /* Section 4: Montreal Book Printing Styles */
/* Extracted from CI section_4.php */

.book-printing-section {
    position: relative;
    padding: 80px 0;
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 100vh;
}

.book-printing-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.book-printing-section .container {
    position: relative;
    z-index: 2;
}

.section-header {
    text-align: center;
    margin-bottom: 50px;
}

.section-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(255, 107, 53, 0.2);
    color: #ff6b35;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 25px;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #183e73;
    margin-bottom: 20px;
}

.section-description {
    font-size: 1.1rem;
    line-height: 1.8;
    max-width: 800px;
    margin: 0 auto 30px;
}

/* Product Navigation */
.product-nav {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 40px;
}

.product-nav-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px 25px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #f28738;
    font-size: 1rem;
}

.product-nav-item:hover,
.product-nav-item.active {
    background: #f28738;
    color: #ffffff;
    transform: translateY(-3px);
}

/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 30px;
    padding: 40px 0;
}

.book-printing-section .product-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.book-printing-section .product-card:hover {
    background: rgba(77, 57, 57, 0.1);
}

.book-printing-section .product-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
}

.book-printing-section .product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.book-printing-section .product-card:hover .product-image img {
    transform: scale(1.1);
}

.book-printing-section .product-info {
    padding: 20px;
}

.book-printing-section .category {
    margin-bottom: 10px;
}

.book-printing-section .category a {
    color: #ff6b35;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
}

.book-printing-section .product-title {
    margin: 0 0 15px 0;
}

.book-printing-section .product-title a {
    text-decoration: none;
    font-size: 1.1rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.book-printing-section .product-title a:hover {
    color: #ff6b35;
}

.book-printing-section .price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ff6b35;
    margin-bottom: 15px;
}

.quick-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: white;
    background: #003f7a;
    border-radius: 44px;
    padding: 10px 17px 10px 15px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.quick-view-btn:hover {
    background: #ff6b35;
    color: #ffffff;
}

.quick-view-btn i {
    font-size: 1.1rem;
}

/* Tab Content */
[data-tab-content] {
    display: none;
}

[data-tab-content].active {
    display: block;
}

/* No Products Message */
.no-products {
    text-align: center;
    padding: 40px 20px;
}

/* Responsive */
@media (max-width: 991px) {
    .book-printing-section {
        padding: 60px 0;
        background-attachment: scroll;
    }

    .section-title {
        font-size: 2rem;
    }

    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 767px) {
    .section-title {
        font-size: 1.75rem;
    }

    .section-description {
        font-size: 1rem;
    }

    .product-nav-item {
        padding: 10px 20px;
        font-size: 0.9rem;
    }
    
    .product-grid {
        grid-template-columns: 1fr;
    }
    
    .book-printing-section {
        min-height: auto;
    }
}

/* Animation Classes */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

</style>

<section class="book-printing-section">
    <div class="container">
        <div class="section-header fade-in">
            <div class="section-badge">
                {{ $language_name == 'french' ? 'Impression de Livres' : 'Book Printing' }}
            </div>
            <h2 class="section-title">
                @if($language_name == 'french')
                    {{ $section_4->name_french ?? '' }}
                @else
                    {{ $section_4->name ?? '' }}
                @endif
            </h2>
            <p class="section-description">
                @if($language_name == 'french')
                    {{ $section_4->description_french ?? '' }}
                @else
                    {{ $section_4->description ?? '' }}
                @endif
            </p>
            <p class="section-description">
                @if($language_name == 'french')
                    {!! $section_4->content_french ?? '' !!}
                @else
                    {!! $section_4->content ?? '' !!}
                @endif
            </p>
        </div>

        <div class="product-nav fade-in">
            @foreach($montreal_book_printing_tags as $key => $val)
                @php
                    $active = $key == 0 ? 'active' : '';
                    $div_id = 'Product1' . $val->id;
                    $label = ucwords($language_name == 'french' ? $val->name_french : $val->name);
                @endphp
                <button class="product-nav-item {{ $active }}" data-tab-target="#{{ $div_id }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="product-content">
            @foreach($montreal_book_printing_tags as $key => $val)
                @php
                    $active = $key == 0 ? 'active' : '';
                    $div_id = 'Product1' . $val->id;
                    $tag_id = $val->id;
                    
                    // Get products by tag using FIND_IN_SET (CI line 303)
                    // Limit to 4 products per tab (CI model getProductByTagId default limit)
                    $cartNameProducts = DB::table('products')
                        ->join('categories', 'products.category_id', '=', 'categories.id')
                        ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                        ->where('products.status', 1)
                        ->orderBy('products.updated', 'desc')
                        ->select('products.*', 'categories.name as category_name')
                        ->limit(4)
                        ->get();
                @endphp
                
                <div id="{{ $div_id }}" data-tab-content class="{{ $active }}">
                    <div class="product-grid">
                        @if($cartNameProducts && count($cartNameProducts) > 0)
                            @foreach($cartNameProducts as $key => $cartNameProduct)
                                @php
                                    $imageurl = url('uploads/products/' . $cartNameProduct->product_image);
                                    $filename = $cartNameProduct->product_image;
                                    $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
                                    $productUrl = url('Products/view/' . base64_encode($cartNameProduct->id));
                                @endphp
                                <div class="product-card fade-in" style="box-shadow: -3px 0px 16px rgba(0, 0, 0, 0.08), -3px 0px 8px rgba(0, 0, 0, 0.04);">
                                    <div class="product-image">
                                        <a href="{{ $productUrl }}">
                                            <img src="{{ $imageurl }}" alt="{{ $filenameWithoutExtension }}" loading="lazy">
                                        </a>
                                    </div>
                                    <div class="product-info">
                                        <div class="category">
                                            <a href="{{ $productUrl }}">
                                                {{ $cartNameProduct->category_name }}
                                            </a>
                                        </div>
                                        <h3 class="product-title">
                                            <a href="{{ $productUrl }}">
                                                {{ $cartNameProduct->name }}
                                            </a>
                                        </h3>
                                        <div class="price">
                                            <span class="amount">{{ $product_price_currency_symbol ?? '$' }}{{ number_format($cartNameProduct->{$product_price_currency ?? 'price_cad'}, 2) }}</span>
                                        </div>
                                        <div>
                                            <a href="{{ $productUrl }}" class="quick-view-btn">
                                                <i class="las la-search"></i>
                                                <span>{{ $language_name == 'french' ? 'Aperçu rapide' : 'Quick View' }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-products fade-in">
                                <p class="section-description">
                                    {{ $language_name == 'french' ? 'Aucun produit trouvé' : 'No Product Found' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab functionality
        const tabs = document.querySelectorAll('[data-tab-target]');
        const tabContents = document.querySelectorAll('[data-tab-content]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const target = document.querySelector(tab.dataset.tabTarget);

                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked tab and its content
                tab.classList.add('active');
                target.classList.add('active');
            });
        });

        // Intersection Observer for fade-in animation
        const fadeElements = document.querySelectorAll('.fade-in');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.03
        });

        fadeElements.forEach(element => {
            observer.observe(element);
        });

        // Add quicker delay to product cards
        document.querySelectorAll('.product-card').forEach((card, i) => {
            card.style.transitionDelay = `${i * 0.03}s`;
        });
    });
</script>
