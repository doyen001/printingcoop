<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
    .main-slider {
        position: relative;
        width: 100%;
        overflow: hidden;
        /* background: linear-gradient(135deg, #dde3ff 0%, #ffefe3 100%); */
        background: #fff;
        padding: 15px 0 10px;
    }

    .main-slider .swiper {
        width: 100%;
        padding: 10px 0 5px;
        overflow: hidden;
    }

    .main-slider .swiper-wrapper {
        align-items: center;
    }

    .main-slider .swiper-slide {
        width: 320px;
        height: 300px;
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        cursor: grab;
        transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .main-slider .swiper-slide:active {
        cursor: grabbing;
    }

    .main-slider .swiper-slide-active {
        border: 2px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        animation: slideIn 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* @keyframes slideIn {
        0% {
            opacity: 0.5;
            transform: scale(0.9) translateY(20px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    } */

    .main-slider .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        border-radius: 14px;
        transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .main-slider .swiper-slide-active img {
        animation: zoomIn 0.8s ease-out;
    }

    /* @keyframes zoomIn {
        0% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    } */

    .main-slider .swiper-slide .banner-card-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px 15px 15px;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        text-align: center;
        z-index: 2;
        border-radius: 0 0 14px 14px;
    }

    /* Swiper Pagination */
    .main-slider .swiper-pagination {
        position: relative;
        margin-top: 15px;
    }

    .main-slider .swiper-pagination-bullet {
        width: 30px;
        height: 4px;
        border-radius: 2px;
        background: rgba(255, 255, 255, 0.3);
        opacity: 1;
        transition: all 0.3s ease;
    }

    .main-slider .swiper-pagination-bullet-active {
        width: 50px;
        background: rgba(255, 255, 255, 0.9);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-slider {
            padding: 10px 0 8px;
        }

        .main-slider .swiper-slide {
            width: 220px;
            height: 180px;
        }
    }

    @media (min-width: 1200px) {
        .main-slider .swiper-slide {
            width: 240px;
            height: 300px;
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
        <div class="swiper bannerSwiper">
            <div class="swiper-wrapper">
                @foreach($Branrers as $key => $banner)
                    @php
                        // Get the image property with proper fallback
                        $bannerImage = null;
                        if (isset($banner->banner_image) && !empty($banner->banner_image)) {
                            $bannerImage = $banner->banner_image;
                        } elseif (isset($banner->image) && !empty($banner->image)) {
                            $bannerImage = $banner->image;
                        }

                        // Get the French image property with proper fallback
                        $bannerImageFrench = null;
                        if (isset($banner->banner_image_french) && !empty($banner->banner_image_french)) {
                            $bannerImageFrench = $banner->banner_image_french;
                        } elseif (isset($banner->image_french) && !empty($banner->image_french)) {
                            $bannerImageFrench = $banner->image_french;
                        }

                        // Determine which image to use
                        if ($language_name == 'french') {
                            $imageUrl = !empty($bannerImageFrench) ? url('uploads/banners/large/' . $bannerImageFrench) :
                                       (!empty($bannerImage) ? url('uploads/banners/large/' . $bannerImage) : BANNER_DEFAULT_IMAGE_URL);
                            $filename = !empty($bannerImageFrench) ? $bannerImageFrench :
                                       (!empty($bannerImage) ? $bannerImage : 'default-banner');
                            $bannerName = $banner->name_french ?? $banner->name ?? '';
                        } else {
                            $imageUrl = !empty($bannerImage) ? url('uploads/banners/large/' . $bannerImage) : BANNER_DEFAULT_IMAGE_URL;
                            $filename = !empty($bannerImage) ? $bannerImage : 'default-banner';
                            $bannerName = $banner->name ?? '';
                        }

                        $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
                    @endphp
                    <div class="swiper-slide">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $filenameWithoutExtension }}"
                             loading="{{ $key === 0 ? 'eager' : 'lazy' }}"
                             {{-- style="transform: none" --}}
                             >
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    @endif
</section>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const bannerSwiper = new Swiper('.bannerSwiper', {
        effect: 'coverflow',
        centeredSlides: true,
        slidesPerView: 'auto',
        loop: true,
        speed: 800,
        grabCursor: true,

        coverflowEffect: {
            rotate: 0,
            stretch: 50,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },

        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
        },

        mousewheel: {
            forceToAxis: true,
            sensitivity: 1,
        },

        keyboard: {
            enabled: true,
        },

        pagination: {
            el: '.bannerSwiper .swiper-pagination',
            clickable: true,
        },
    });
});
</script>

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
                    if ($label == 'Ink-Toner Cartridges And Drums' || $label == 'Cartouches D&#39;encre-toner Et Tambours') {
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
                    if ($label == 'Overnight' || $label == 'Pendant La Nuit') {
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

