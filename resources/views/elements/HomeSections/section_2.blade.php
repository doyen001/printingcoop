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
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.product-image {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.1);
}

.product-info {
    padding: 25px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: linear-gradient(to bottom, #ffffff 0%, #f8f9fa 100%);
}

.product-category {
    font-size: 0.85rem;
    color: #ff6b35;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
    display: inline-block;
    background: rgba(255, 107, 53, 0.1);
    padding: 4px 12px;
    border-radius: 15px;
}

.product-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 15px;
    line-height: 1.4;
    transition: color 0.3s ease;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    color: #ff6b35;
}

/* .product-price {
    margin-top: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 20px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.product-price .amount {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ff6b35;
} */

.view-details {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #2d3436;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    padding: 8px 16px;
    border-radius: 20px;
    background: rgba(45, 52, 54, 0.05);
}

.view-details:hover {
    color: #ff6b35;
    background: rgba(255, 107, 53, 0.1);
}

.view-details i {
    transition: transform 0.3s ease;
}

.view-details:hover i {
    transform: translateX(5px);
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
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 767px) {
    .showcase-grid {
        grid-template-columns: 1fr;
    }

    .product-info {
        padding: 20px;
    }

    .product-title {
        font-size: 1.1rem;
    }
    
    .showcase-title {
        font-size: 2rem;
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
                                    </div>
                                    <div class="product-info">
                                        <div>
                                            <div class="product-category">{{ $posterAndPlansProduct->category_name }}</div>
                                            <h3 class="product-title">
                                                <a href="{{ $productUrl }}">{{ $posterAndPlansProduct->name }}</a>
                                            </h3>
                                        </div>
                                        <div style="display: flex; flex-direction: column; gap: 10px">
                                            <span class="amount">{{ $product_price_currency_symbol ?? '$' }}{{ number_format($posterAndPlansProduct->{$product_price_currency ?? 'price_cad'}, 2) }}</span>
                                            <div>
                                                <a href="{{ $productUrl }}" class="view-details">
                                                    {{ $language_name == 'french' ? 'Voir les détails' : 'View Details' }}
                                                    <i class="las la-arrow-right"></i>
                                                </a>
                                            </div>
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
