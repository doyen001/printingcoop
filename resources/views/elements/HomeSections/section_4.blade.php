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
    background-color: #f8f9fa;
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
/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 40px 20px;
    padding: 40px 0;
}

.book-printing-section .product-card {
    background: transparent;
    text-align: center;
    transform: translateY(0);
    transition: transform 0.1s ease;
}

.book-printing-section .product-card:hover {
    transform: translateY(-2px);
}

.book-printing-section .product-image {
    position: relative;
    width: 100%;
    background: #ffffff;
    border-radius: .5rem;
    overflow: hidden;
    padding-top: 100%; /* 1:1 square like section 2 */
}

.book-printing-section .product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* object-fit: contain; */
}

.book-printing-section .product-info {
    padding: 10px 4px 0;
    background: transparent;
    text-align: center;
}

.book-printing-section .category {
    margin-bottom: 2px;
    font-size: 12px;
    font-weight: 600;
    color: #333333;
}

.book-printing-section .category a {
    color: inherit;
    text-decoration: none;
}

.book-printing-section .product-title {
    margin: 0 0 2px 0;
}

.book-printing-section .product-title a {
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    color: #333333;
}

.book-printing-section .product-title a:hover {
    text-decoration: underline;
}

.book-printing-section .price {
    font-size: 12px;
    font-weight: 400;
    color: #666666;
    margin-bottom: 0;
}

.quick-view-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #2d3436;
    background: white;
    border-radius: 44px;
    padding: 10px 17px 10px 15px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgb(0 102 197 / 30%);
}

.quick-view-btn:hover {
    background: #0066c5ff;
    color: #ffffff;
}

.quick-view-btn i {
    font-size: 1.1rem;
}

/* No Products Message */
.no-products {
    text-align: center;
    padding: 40px 20px;
}

/* Vertical blocks for each tag (flattened tabs) */
.product-block {
    margin-top: 40px;
}

.product-block header {
    text-align: center;
    margin-bottom: 20px;
}

.product-block-title {
    font-size: 28px;
    font-weight: 600;
    color: #484848;
    margin-bottom: 0;
    position: relative;
    display: inline-block;
    letter-spacing: -0.5px;
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

.product-content {
    padding: 0 100px;
}

</style>

<section class="book-printing-section" id="section-4-main">
    <div class="container">
        {{-- <div class="section-header fade-in">
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
        </div> --}}

        <div class="product-content">
            @foreach($montreal_book_printing_tags as $key => $val)
                @php
                    $tag_id = $val->id;
                    $label = ucwords($language_name == 'french' ? $val->name_french : $val->name);
                    
                    // For Booklets tag, use the Booklets - Catalogs category to match header menu bar
                    if ($label == 'Booklets') {
                        $bookletsCatalogsCategoryId = DB::table('categories')
                            ->where('name', 'Booklets - Catalogs')
                            ->value('id');
                        $cartNameProducts = DB::table('products')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->where('products.category_id', $bookletsCatalogsCategoryId)
                            ->where('products.status', 1)
                            ->orderBy('products.updated', 'desc')
                            ->select('products.*', 'categories.name as category_name')
                            ->limit(15)
                            ->get();
                    } else {
                        // Get products by tag using FIND_IN_SET (CI line 303)
                        // Limit to 4 products per tag (CI model getProductByTagId default limit)
                        $cartNameProducts = DB::table('products')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                            ->where('products.status', 1)
                            ->orderBy('products.updated', 'desc')
                            ->select('products.*', 'categories.name as category_name')
                            ->limit(15)
                            ->get();
                    }
                @endphp
                
                @if($cartNameProducts && count($cartNameProducts) > 0)
                    <div class="product-block" id="section-4-tag-{{ $tag_id }}">
                        <header style="text-align: center;">
                            <h3 class="product-block-title">{{ $label }}</h3>
                        </header>
                        <div class="product-grid">
                            @foreach($cartNameProducts as $key => $cartNameProduct)
                                @php
                                    $imageurl = url('uploads/products/' . $cartNameProduct->product_image);
                                    $filename = $cartNameProduct->product_image;
                                    $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
                                    $productUrl = url('Products/view/' . base64_encode($cartNameProduct->id));
                                @endphp
                                <div class="product-card fade-in">
                                    <a href="{{ $productUrl }}" class="product-image">
                                        <img src="{{ $imageurl }}" alt="{{ $filenameWithoutExtension }}" loading="lazy">
                                    </a>
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
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="no-products fade-in">
                        <p class="section-description">
                            {{ $language_name == 'french' ? 'Aucun produit trouvé' : 'No Product Found' }}
                        </p>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
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
        // document.querySelectorAll('.product-card').forEach((card, i) => {
        //     card.style.transitionDelay = `${i * 0}s`;
        // });
    });
</script>
