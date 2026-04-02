<style>
    .main-slider {
        position: relative;
        width: 100%;
        height: auto;
        overflow: hidden;
        background: var(--secondary-color);
    }

    @media (max-width: 768px) {
        .main-slider {
            min-height: 400px;
        }
    }

    .banner-image {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .banner-image img {
        width: 100%;
        height: 340px;
        max-height: 100vh;
        /* object-fit: contain; */
        object-position: center;
        display: block;
    }

    /* Banner Button Overlays */
    .banner-buttons {
        position: absolute;
        bottom: 4%;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        z-index: 10;
        background: linear-gradient(135deg, rgb(233 230 223) 0%, rgb(246 244 231) 100%);
        border: 1px solid rgba(0, 0, 0, 0.12);
        border-radius: 30px;
        padding: 4px 28px;
        backdrop-filter: blur(4px);
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.15);
    }

    .banner-btn {
        font-size: 15px;
        font-weight: 400;
        text-decoration: none;
        color: #2c2c2c;
        cursor: pointer;
        border: none;
        background: transparent;
        font-family: roboto;
        white-space: nowrap;
        transition: color 0.2s ease;
    }

    .banner-btn:hover {
        color: #000000;
    }

    .banner-divider {
        width: 1px;
        height: 18px;
        background: #555;
        margin: 0 16px;
    }

    /* Mobile Optimization */
    @media (max-width: 768px) {
        .banner-image img {
            min-height: 400px;
            object-fit: cover;
        }

        .banner-buttons {
            bottom: 5%;
            padding: 8px 20px;
        }

        .banner-btn {
            font-size: 13px;
        }

        .banner-divider {
            margin: 0 10px;
            height: 14px;
        }
    }

    /* Tab Navigation Section - Using Section 2 Product Card Styles */
    .tab-navigation-section {
        padding: 30px 0;
        background: #f8f9fa;
        height: 100%;
    }

    .tab-nav-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 20px;
        margin: 0 auto;
        padding: 0 40px;
    }

    .tab-nav-item {
        cursor: pointer;
    }

    .tab-nav-item .product-card {
        /* background: transparent; */
        text-align: center;
        transform: translateY(0) !important;
        transition: transform 0.1s ease;
        height: 100%;
        opacity: 1;
    }

    .tab-nav-item .product-card:hover {
        transform: translateY(-3px) !important;
    }

    .tab-nav-item .product-image {
        width: 100%;
        background: #ffffff;
        border-radius: .2rem !important;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: .5rem;
        overflow: hidden;
    }

    .tab-nav-item .product-image img {
        position: static;
        width: 100%;
        height: 100%;
        aspect-ratio: 1 / 1;
    }

    .tab-nav-item .product-info {
        position: static;
        padding: 10px 4px 0;
        background: transparent;
        text-align: center;
    }

    .tab-nav-item .product-title {
        font-size: 14px;
        font-weight: 600;
        color: #333333;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .tab-nav-item .product-title a {
        color: inherit;
        text-decoration: none;
    }

    .tab-nav-item .product-title a:hover {
        text-decoration: underline;
    }

    .product-badge {
        background-color: #ff715b;
        color: white;
        position: absolute;
        border-radius: 5px;
        padding: 4px 12px;
        top: 8px;
        left: 8px;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-family: Sans-serif;
        font-weight: bold;
        width: 70%;
    }
</style>

<section class="main-slider">
    @if(!empty($Branrers))
        @php
            $firstBanner = $Branrers[0];
            
            // Get the image property with proper fallback
            $bannerImage = null;
            if (isset($firstBanner->image) && !empty($firstBanner->image)) {
                $bannerImage = $firstBanner->image;
            } elseif (isset($firstBanner->banner_image) && !empty($firstBanner->banner_image)) {
                $bannerImage = $firstBanner->banner_image;
            }
            
            // Get the French image property with proper fallback
            $bannerImageFrench = null;
            if (isset($firstBanner->image_french) && !empty($firstBanner->image_french)) {
                $bannerImageFrench = $firstBanner->image_french;
            } elseif (isset($firstBanner->banner_image_french) && !empty($firstBanner->banner_image_french)) {
                $bannerImageFrench = $firstBanner->banner_image_french;
            }
            
            // Determine which image to use
            if ($language_name == 'French') {
                $imageUrl = !empty($bannerImageFrench) ? url('uploads/banners/large/' . $bannerImageFrench) : 
                           (!empty($bannerImage) ? url('uploads/banners/large/' . $bannerImage) : BANNER_DEFAULT_IMAGE_URL);
                $filename = !empty($bannerImageFrench) ? $bannerImageFrench : 
                           (!empty($bannerImage) ? $bannerImage : 'default-banner');
            } else {
                $imageUrl = !empty($bannerImage) ? url('uploads/banners/large/' . $bannerImage) : BANNER_DEFAULT_IMAGE_URL;
                $filename = !empty($bannerImage) ? $bannerImage : 'default-banner';
            }

            $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
        @endphp
        <div class="banner-image">
            <img src="{{ $imageUrl }}"
                 alt="{{ $filenameWithoutExtension }}"
                 loading="eager">
            <div class="banner-buttons">
                <a href="{{ site_url('Pages/estimate') }}" class="banner-btn">{{ $language_name == 'French' ? 'Demander un devis' : 'Request a Quote' }}</a>
                <span class="banner-divider"></span>
                <a href="{{ site_url('Products') }}" class="banner-btn">{{ $language_name == 'French' ? 'Explorer les services' : 'Explore Services' }}</a>
            </div>
        </div>
    @else
        <!-- Default Banner -->
        <div class="banner-image">
            <img src="{{ BANNER_DEFAULT_IMAGE_URL }}" alt="{{ __('Default Banner') }}">
            <div class="banner-buttons">
                <a href="{{ site_url('Pages/estimate') }}" class="banner-btn">{{ $language_name == 'French' ? 'Demander un devis' : 'Request a Quote' }}</a>
                <span class="banner-divider"></span>
                <a href="{{ site_url('Products') }}" class="banner-btn">{{ $language_name == 'French' ? 'Explorer les services' : 'Explore Services' }}</a>
            </div>
        </div>
    @endif
</section>

{{-- Tab Navigation Section --}}
@if(isset($proudly_display_your_brand_tags) && isset($montreal_book_printing_tags))
<style>
/* Tab Navigation Section - Using Section 2 Product Card Styles */
.tab-navigation-section {
    padding: 30px 0;
    background: #fff;
    height: 100%;
}

.tab-nav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 20px;
    margin: 0 auto;
    padding: 0 40px;
}

.tab-nav-item {
    cursor: pointer;
}

.tab-nav-item .product-card {
    /* background: transparent; */
    text-align: center;
    transform: translateY(0) !important;
    transition: transform 0.1s ease;
    height: 100%;
    opacity: 1;
}

.tab-nav-item .product-card:hover {
    transform: translateY(-3px) !important;
}

.tab-nav-item .product-image {
    width: 100%;
    background: #ffffff;
    border-radius: .2rem !important;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: .5rem;
    overflow: hidden;
}

.tab-nav-item .product-image img {
    position: static;
    width: 100%;
    height: 100%;
    aspect-ratio: 1 / 1;
}

.tab-nav-item .product-info {
    position: static;
    padding: 10px 4px 0;
    background: transparent;
    text-align: center;
}

.tab-nav-item .product-title {
    font-size: 14px;
    font-weight: 600;
    color: #333333;
    margin-bottom: 2px;
    line-height: 1.3;
}

.tab-nav-item .product-title a {
    color: inherit;
    text-decoration: none;
}

.tab-nav-item .product-title a:hover {
    text-decoration: underline;
}

.product-badge {
    background-color: #ff715b;
    color: white;
    position: absolute;
    border-radius: 5px;
    padding: 4px 12px;
    top: 12px;
    left: 12px;
    font-size: 11px;
    text-transform: uppercase;
    font-family: Sans-serif;
    font-weight: bold;
}
</style>

<section class="tab-navigation-section">
    <div class="tab-nav-grid">
        {{-- Section 2 Tags --}}
        @if(isset($proudly_display_your_brand_tags) && count($proudly_display_your_brand_tags) > 0)
            @foreach($proudly_display_your_brand_tags as $key => $val)
                @php
                    $tag_id = $val->id;
                    $label = $language_name == 'french' ? ucwords($val->name_french) : ucwords($val->name);
                    
                    // Replace Ink-Toner Cartridges And Drums with Professional or Personal Apparel
                    if ($label == 'Ink-Toner Cartridges And Drums') {
                        $label = $language_name == 'french' ? 'Vêtements Professionnels Ou Personnels' : 'Professional or Personal Apparel';
                    }
                    
                    // For Professional or Personal Apparel, use manual image
                    if ($label == 'Professional or Personal Apparel' || $label == 'Vêtements Professionnels Ou Personnels') {
                        $firstProduct = (object) [
                            'product_image' => 'pod_images/11273188-copy.jpg'
                        ];
                    } else {
                        // Get second product for this tag (skip first, take second)
                        $firstProduct = DB::table('products')
                            ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                            ->where('products.status', 1)
                            ->orderBy('products.updated', 'desc')
                            ->skip(4)
                            ->take(4)
                            ->first();
                    }
                @endphp
                
                @if($firstProduct)
                    <div class="tab-nav-item" onclick="scrollToSection('section-2-tag-{{ $tag_id }}')">
                        <div class="product-card">
                            <div class="product-image">
                                <div class="product-badge">browse category</div>
                                <img src="{{ url('uploads/products/' . $firstProduct->product_image) }}" 
                                     alt="{{ $label }}" 
                                     loading="lazy">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="javascript:void(0)" onclick="scrollToSection('section-2-tag-{{ $tag_id }}')">{{ $label }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
        
        {{-- Section 4 Tags --}}
        @if(isset($montreal_book_printing_tags) && count($montreal_book_printing_tags) > 0)
            @foreach($montreal_book_printing_tags as $key => $val)
                @php
                    $tag_id = $val->id;
                    $label = $language_name == 'french' ? ucwords($val->name_french) : ucwords($val->name);
                    
                    // Replace Overnight with Personalized Office & Home Décor
                    if ($label == 'Overnight') {
                        $label = $language_name == 'french' ? 'Décoration De Bureau Et Maison Personnalisée' : 'Personalized Office & Home Décor';
                    }
                    
                    // For Personalized Office & Home Décor, use manual image
                    if ($label == 'Personalized Office & Home Décor' || $label == 'Décoration De Bureau Et Maison Personnalisée') {
                        $firstProduct = (object) [
                            'product_image' => 'store_images/decor_top-247x296.jpg'
                        ];
                    } else {
                        // Get first product for this tag
                        $firstProduct = DB::table('products')
                            ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                            ->where('products.status', 1)
                            ->orderBy('products.updated', 'desc')
                            ->first();
                    }
                @endphp
                
                @if($firstProduct)
                    <div class="tab-nav-item" onclick="scrollToSection('section-4-tag-{{ $tag_id }}')">
                        <div class="product-card">
                            <div class="product-image">
                                <div class="product-badge">browse category</div>
                                <img src="{{ url('uploads/products/' . $firstProduct->product_image) }}" 
                                     alt="{{ $label }}" 
                                     loading="lazy">
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">
                                    <a href="javascript:void(0)" onclick="scrollToSection('section-4-tag-{{ $tag_id }}')">{{ $label }}</a>
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</section>

<script>
// Function to scroll to specific section
window.scrollToSection = function(sectionId) {
    // Find the target section
    const targetSection = document.getElementById(sectionId);
    
    if (targetSection) {
        // Smooth scroll to the section
        targetSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        
        // Add highlight effect
        targetSection.style.transition = 'background-color 0.3s ease';
        // targetSection.style.backgroundColor = '#fff3cd';
        
        // Remove highlight after 2 seconds
        setTimeout(() => {
            targetSection.style.backgroundColor = '';
        }, 2000);
    } else {
        // If section not found, try to scroll to the main section areas
        if (sectionId.startsWith('section-2-')) {
            const section2 = document.querySelector('.showcase-section');
            if (section2) {
                section2.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } else if (sectionId.startsWith('section-4-')) {
            const section4 = document.querySelector('.book-printing-section');
            if (section4) {
                section4.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }
    }
};
</script>
@endif

