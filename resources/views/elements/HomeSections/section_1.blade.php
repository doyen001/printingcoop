{{-- CI: application/views/elements/HomeSections/section_2.php --}}
{{-- Proudly Display Your Brand Section --}}


<style>
.showcase-section {
    /* padding: 80px 0; */
    background-color: #f8f9fa;
    position: relative;
    overflow: hidden;
}

.showcase-header {
    text-align: center;
    margin-bottom: 50px;
}

.showcase-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #183e73;
    margin-bottom: 20px;
    position: relative;
    display: inline-block;
}

.showcase-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: #ff6b35;
    border-radius: 2px;
}

.showcase-description {
    font-size: 1.1rem;
    color: #666666;
    max-width: 800px;
    margin: 0 auto 30px;
    line-height: 1.6;
}

.showcase-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px 20px;
    margin-top: 40px;
}

.product-card {
    background: transparent;
    /* border-radius: 8px; */
    /* padding: 16px 16px 18px; */
    text-align: center;
    /* box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.04); */
    /* transition: box-shadow 0.2s ease, transform 0.15s ease; */
    transform: translateY(0) !important;
    transition: transform 0.1s ease;
    height: 340px;
}

.product-card:hover {
    /* box-shadow: 0 4px 12px rgba(0, 0, 0, 0.16); */
    transform: translateY(-3px) !important;
}

.product-image {
    width: 100%;
    background: #ffffff;
    border-radius: 4px;
    /* padding: 10px; */
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: .5rem;
    overflow: hidden;
}

.product-image img {
    position: static;
    width: 100%;
    height: 100%;
    aspect-ratio: 1 / 1;
    /* object-fit: contain; */
}

.product-info {
    position: static;
    padding: 10px 4px 0;
    background: transparent;
    text-align: center;
}

.product-category {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #333333;
    margin-bottom: 2px;
    line-height: 1.3;
}

.product-title {
    font-size: 14px;
    font-weight: 600;
    color: #333333;
    margin-bottom: 2px;
    line-height: 1.3;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    text-decoration: underline;
}

.product-meta,
.product-price-info {
    display: none;
}

.product-starting-price {
    font-size: 12px;
    color: #666666;
}

/* Tag blocks (formerly tabs) */
.showcase-tag-block {
    margin: 40px 0 100px 0;
    padding: 0 100px;
}

.showcase-tag-title {
    font-size: 28px;
    font-weight: 600;
    color: #484848;
    margin-bottom: 0;
    position: relative;
    display: inline-block;
    letter-spacing: -0.5px;
}

.showcase-tag-subtitle {
    color: #484848;
    font-weight: 100;
    font-size: 18px;
    margin-bottom: 20px;
}

/* Fade-in Animation */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 992px) {
    .showcase-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .product-card {
        height: 320px;
    }
}

@media (max-width: 767px) {
    .showcase-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .product-card {
        height: 300px;
        /* padding: 4px; */
    }
    
    .product-image {
        border-radius: .5 rem;
    }

    .product-info {
        padding: 24px 20px 20px;
        border-radius: 0 0 20px 20px;
    }

    .product-title {
        font-size: 1.3rem;
    }
    
    .product-category {
        font-size: 0.8rem;
    }
    
    .product-favorite {
        top: 20px;
        right: 20px;
        width: 36px;
        height: 36px;
    }
    
    .product-favorite i {
        font-size: 16px;
    }
    
    .view-details {
        padding: 12px 20px;
        font-size: 0.9rem;
    }
    
    .showcase-title {
        font-size: 2rem;
    }
}

@media (max-width: 480px) {
    .product-card {
        height: 280px;
    }
    
    .product-title {
        font-size: 1.2rem;
    }
    
    .product-price-info {
        font-size: 0.9rem;
    }
}

@media (max-width: 768px) {
    .showcase-section {
        padding: 60px 0;
    }
}
</style>

<section class="showcase-section" id="section-2-main">
    <div class="container">
        {{-- <div class="showcase-header fade-in">
            <h2 class="showcase-title">
                @if($language_name == 'french')
                    {{ $section_2->name_french ?? '' }}
                @else
                    {{ $section_2->name ?? '' }}
                @endif
            </h2>
            <p class="showcase-description">
                @if($language_name == 'french')
                    {{ $section_2->description_french ?? '' }}
                @else
                    {{ $section_2->description ?? '' }}
                @endif
            </p>
            <p class="showcase-description">
                @if($language_name == 'french')
                    {!! $section_2->content_french ?? '' !!}
                @else
                    {!! $section_2->content ?? '' !!}
                @endif
            </p>
        </div> --}}
        
        {{-- Vertical tag blocks (flattened tabs) --}}
        @foreach($proudly_display_your_brand_tags as $key => $val)
            @php
                $tag_id = $val->id;
                $label = $language_name == 'french' ? ucwords($val->name_french) : ucwords($val->name);

                // Get products by tag using FIND_IN_SET (CI line 340), limit 30 per tag
                $posterAndPlansProducts = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                    ->where('products.status', 1)
                    ->orderBy('products.updated', 'desc')
                    ->select('products.*', 'categories.name as category_name')
                    ->limit(9)
                    ->get();
            @endphp

            @if($posterAndPlansProducts && count($posterAndPlansProducts) > 0)
                <div class="showcase-tag-block" id="section-2-tag-{{ $tag_id }}">
                    <header style="text-align: center;">
                        <h3 class="showcase-tag-title">{{ $label }}</h3>
                        {{-- <p class="showcase-tag-subtitle">
                            {{ $language_name == 'french' ? "Des produits qui attirent l'attention pour toute promotion ou événement." : 'Classic marketing materials with consistent results.' }}
                        </p> --}}
                    </header>
                    <div class="showcase-grid">
                        @foreach($posterAndPlansProducts as $index => $posterAndPlansProduct)
                            @php
                                $imageurl = url('uploads/products/' . $posterAndPlansProduct->product_image);
                                $productUrl = url('Products/view/' . base64_encode($posterAndPlansProduct->id));
                            @endphp
                            <div class="product-card">
                                <a href="{{ $productUrl }}" class="product-image">
                                    <img src="{{ $imageurl }}" alt="{{ $posterAndPlansProduct->name }}" loading="lazy">
                                </a>
                                <div class="product-info">
                                    <div class="product-category">{{ $posterAndPlansProduct->category_name }}</div>
                                    <h3 class="product-title">
                                        <a href="{{ $productUrl }}">{{ $posterAndPlansProduct->name }}</a>
                                    </h3>
                                    <div class="product-starting-price">
                                        {{ $product_price_currency_symbol ?? '$' }}{{ number_format($posterAndPlansProduct->{$product_price_currency ?? 'price_cad'}, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intersection Observer for fade-in animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // entry.target.style.transitionDelay = `${index * 0}s`;
                }
            });
        }, {
            threshold: 0.03
        });

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(element => {
            observer.observe(element);
        });
    });
</script>
