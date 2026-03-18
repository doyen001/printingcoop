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

    .banner-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.1);
        z-index: 1;
    }

    /* Mobile Optimization */
    @media (max-width: 768px) {
        .banner-image img {
            min-height: 400px;
            object-fit: cover;
        }
    }

    /* Tab Navigation Section - Using Section 2 Product Card Styles */
    .tab-navigation-section {
        padding: 40px 0;
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
        font-size: 11px;
        text-transform: uppercase;
        font-family: Sans-serif;
        font-weight: bold;
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
        </div>
    @else
        <!-- Default Banner -->
        <div class="banner-image">
            <img src="{{ BANNER_DEFAULT_IMAGE_URL }}" alt="{{ __('Default Banner') }}">
        </div>
    @endif
</section>

{{-- Tab Navigation Section --}}
@if(isset($proudly_display_your_brand_tags) && isset($montreal_book_printing_tags))
<style>
/* Tab Navigation Section - Using Section 2 Product Card Styles */
.tab-navigation-section {
    padding: 40px 0;
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
                    
                    // Get first product for this tag
                    $firstProduct = DB::table('products')
                        ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                        ->where('products.status', 1)
                        ->orderBy('products.updated', 'desc')
                        ->first();
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
                    
                    // Get first product for this tag
                    $firstProduct = DB::table('products')
                        ->whereRaw("FIND_IN_SET(?, product_tag)", [$tag_id])
                        ->where('products.status', 1)
                        ->orderBy('products.updated', 'desc')
                        ->first();
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

