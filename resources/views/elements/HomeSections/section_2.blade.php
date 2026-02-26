{{-- CI: application/views/elements/HomeSections/section_2.php --}}
{{-- Proudly Display Your Brand Section --}}


<style>
.showcase-section {
    padding: 80px 0;
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
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    margin-top: 40px;
}

.product-card {
    background: #ffffff;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    height: 500px;
    display: flex;
    flex-direction: column;
    padding: 5px;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.18);
}

.product-image {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #f8f9fa;
    border-radius: 24px;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.08);
}

/* Favorite Icon */
.product-favorite {
    position: absolute;
    top: 24px;
    right: 24px;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 3;
    border: 2px solid rgba(255, 255, 255, 0.5);
}

.product-favorite:hover {
    background: #f28738;
    border-color: #f28738;
    transform: scale(1.1);
}

.product-favorite i {
    font-size: 18px;
    color: #666;
    transition: color 0.3s ease;
}

.product-favorite:hover i {
    color: white;
}

/* Content Overlay */
.product-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px 24px 24px;
    backdrop-filter: blur(3px);
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.6) 60%, transparent 100%);
    display: flex;
    flex-direction: column;
    gap: 12px;
    z-index: 2;
    border-radius: 0 0 24px 24px;
}

.product-category {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
    text-transform: capitalize;
    font-weight: 500;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
}

.product-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 8px;
    line-height: 1.3;
    transition: color 0.3s ease;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    color: #ffa260ff;
}

/* Price and Icon Info */
.product-meta {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 12px;
}

.product-price-info {
    display: flex;
    align-items: center;
    gap: 8px;
    color: white;
    font-size: 0.95rem;
}

.product-price-info i {
    font-size: 16px;
    opacity: 0.9;
}

.product-price-info .amount {
    font-weight: 600;
}

/* Search/Action Button */
.view-details {
    display: block;
    width: 100%;
    text-align: center;
    padding: 14px 24px;
    background: white;
    color: #2d3436;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 18px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.view-details:hover {
    background: #f28738;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(242, 135, 56, 0.4);
}

.view-details i {
    display: none;
}

/* Tab Navigation Styles */
.showcase-tabs {
    margin-bottom: 40px;
    text-align: center;
}

.nav-pills {
    display: inline-flex;
    gap: 15px;
    padding: 5px;
    background: rgba(248, 249, 250, 0.8);
    border-radius: 50px;
    margin: 0;
    list-style: none;
    flex-wrap: wrap;
    justify-content: center;
}

.nav-pills li {
    margin: 0;
}

.nav-pills a {
    display: inline-block;
    padding: 12px 25px;
    color: #ff6b35;
    background: rgba(255, 107, 53, 0.1);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    border-radius: 25px;
    transition: all 0.3s ease;
    position: relative;
    white-space: nowrap;
}

.nav-pills a:hover {
    color: #ffffff;
    background: #f28738;
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}

.nav-pills a.active {
    color: #ffffff;
    background: #f28738;
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}

.tab-content {
    margin-top: 30px;
}

.tab-pane {
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.tab-pane.active {
    display: block;
    opacity: 1;
}

.tab-pane.show {
    opacity: 1;
}

/* Fade-in Animation */
.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.fade-in.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .showcase-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }
}

@media (max-width: 992px) {
    .showcase-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    
    .product-card {
        height: 450px;
    }
}

@media (max-width: 767px) {
    .showcase-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .product-card {
        height: 400px;
        padding: 4px;
    }
    
    .product-image {
        border-radius: 20px;
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
        height: 380px;
    }
    
    .product-title {
        font-size: 1.2rem;
    }
    
    .product-price-info {
        font-size: 0.9rem;
    }
}

@media (max-width: 768px) {
    .nav-pills {
        gap: 10px;
        padding: 5px;
        background: transparent;
    }

    .nav-pills a {
        padding: 8px 16px;
        font-size: 0.9rem;
    }
    
    .showcase-section {
        padding: 60px 0;
    }
}
</style>

<section class="showcase-section">
    <div class="container">
        <div class="showcase-header fade-in">
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
        </div>
        
        {{-- Tabs navigation (CI lines 313-329) --}}
        <div class="showcase-tabs">
            <ul class="nav nav-pills">
                @foreach($proudly_display_your_brand_tags as $key => $val)
                    @php
                        $active = $key == 0 ? 'active' : '';
                        $href = '#Process' . $val->id;
                        $label = $language_name == 'french' ? ucwords($val->name_french) : ucwords($val->name);
                    @endphp
                    <li>
                        <a class="{{ $active }}" data-toggle="pill" href="{{ $href }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        
        {{-- Tabs content (CI lines 331-378) --}}
        <div class="tab-content">
            @foreach($proudly_display_your_brand_tags as $key => $val)
                @php
                    $active = $key == 0 ? 'active show' : '';
                    $div_id = 'Process' . $val->id;
                    $tag_id = $val->id;
                    
                    // Get products by tag using FIND_IN_SET (CI line 340)
                    // Limit to 4 products per tab (CI model line 1614)
                    $posterAndPlansProducts = DB::table('products')
                        ->join('categories', 'products.category_id', '=', 'categories.id')
                        ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                        ->where('products.status', 1)
                        ->orderBy('products.updated', 'desc')
                        ->select('products.*', 'categories.name as category_name')
                        ->limit(4)
                        ->get();
                @endphp
                
                <div id="{{ $div_id }}" class="tab-pane fade {{ $active }}">
                    <div class="showcase-grid">
                        @if($posterAndPlansProducts && count($posterAndPlansProducts) > 0)
                            @foreach($posterAndPlansProducts as $index => $posterAndPlansProduct)
                                @php
                                    $imageurl = url('uploads/products/' . $posterAndPlansProduct->product_image);
                                    $productUrl = url('Products/view/' . base64_encode($posterAndPlansProduct->id));
                                @endphp
                                <div class="product-card fade-in">
                                    <div class="product-image">
                                        <a href="{{ $productUrl }}">
                                            <img src="{{ $imageurl }}" alt="{{ $posterAndPlansProduct->name }}" loading="lazy">
                                        </a>
                                        
                                        <!-- Favorite Icon -->
                                        {{-- <div class="product-favorite">
                                            <i class="lar la-heart"></i>
                                        </div> --}}
                                        
                                        <!-- Content Overlay -->
                                        <div class="product-info">
                                            <h3 class="product-title">
                                                <a href="{{ $productUrl }}">{{ $posterAndPlansProduct->name }}</a>
                                            </h3>
                                            <div class="product-category">{{ $posterAndPlansProduct->category_name }}</div>
                                            
                                            <div class="product-meta">
                                                <div class="product-price-info">
                                                    <i class="las la-tag"></i>
                                                    <span class="amount">{{ $product_price_currency_symbol ?? '$' }}{{ number_format($posterAndPlansProduct->{$product_price_currency ?? 'price_cad'}, 2) }}</span>
                                                </div>
                                            </div>
                                            
                                            <a href="{{ $productUrl }}" class="view-details">
                                                {{ $language_name == 'french' ? 'Voir les détails' : 'View Details' }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Bootstrap tab functionality
        const tabLinks = document.querySelectorAll('.showcase-tabs .nav-pills a[data-toggle="pill"]');
        
        tabLinks.forEach(function(tabLink) {
            tabLink.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                tabLinks.forEach(function(link) {
                    link.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab panes
                const tabPanes = document.querySelectorAll('.tab-content .tab-pane');
                tabPanes.forEach(function(pane) {
                    pane.classList.remove('active', 'show');
                });
                
                // Show the target tab pane
                const targetId = this.getAttribute('href');
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('active', 'show');
                }
            });
        });

        // Intersection Observer for fade-in animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    entry.target.style.transitionDelay = `${index * 0.03}s`;
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
