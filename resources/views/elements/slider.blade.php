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

    .carousel {
        height: 100%;
    }

    .carousel-inner {
        height: 100%;
    }

    .carousel-item {
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .carousel-item img {
        width: 100%;
        height: auto;
        max-height: 100vh;
        object-fit: contain;
        object-position: center;
        transform: scale(1.1);
        transition: transform 4s ease-out;
    }

    .carousel-item.active img {
        transform: scale(1);
    }

    .carousel-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom,
            rgba(0, 0, 0, 0.3) 0%,
            rgba(0, 0, 0, 0.2) 40%,
            rgba(0, 0, 0, 0.1) 60%,
            rgba(0, 0, 0, 0.4) 100%
        );
        z-index: 1;
    }

    /* Indicators */
    .carousel-indicators {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 15;
        display: flex;
        justify-content: center;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .carousel-indicators li {
        width: 50px;
        height: 4px;
        margin: 0 5px;
        border: none;
        border-radius: 2px;
        background-color: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .carousel-indicators li::after {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 0;
        background-color: #ffffff;
    }

    .carousel-indicators li.active {
        background-color: rgba(255, 255, 255, 0.8);
    }

    .carousel-indicators li.active::after {
        width: 100%;
    }

    /* Navigation Arrows */
    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s ease;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        margin: 0 30px;
    }

    .main-slider:hover .carousel-control-prev,
    .main-slider:hover .carousel-control-next {
        opacity: 1;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-control-prev i,
    .carousel-control-next i {
        font-size: 24px;
        line-height: 1;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Loading Animation */
    @keyframes slideLoading {
        0% { transform: scaleX(0); }
        100% { transform: scaleX(1); }
    }

    /* Mobile Optimization */
    @media (max-width: 768px) {
        .carousel-control-prev,
        .carousel-control-next {
            width: 40px;
            height: 40px;
            margin: 0 10px;
        }

        .carousel-indicators {
            bottom: 20px;
        }

        .carousel-indicators li {
            width: 30px;
            height: 3px;
            margin: 0 3px;
        }
    }

    /* Touch Swipe Hint */
    .swipe-hint {
        position: absolute;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        color: #ffffff;
        font-size: 14px;
        opacity: 0.8;
        z-index: 2;
        pointer-events: none;
        display: none;
    }

    @media (max-width: 768px) {
        .swipe-hint {
            display: block;
            animation: fadeOut 3s forwards 2s;
        }
    }

    @keyframes fadeOut {
        to { opacity: 0; }
    }
</style>

<section class="main-slider">
    <div id="mainCarousel" class="carousel slide" data-ride="carousel">
        @if(!empty($Branrers))
            <!-- Indicators -->
            @if(count($Branrers) > 1)
                <ol class="carousel-indicators">
                    @foreach($Branrers as $key => $banner)
                        <li data-target="#mainCarousel"
                            data-slide-to="{{ $key }}"
                            class="{{ $key === 0 ? 'active' : '' }}">
                        </li>
                    @endforeach
                </ol>
            @endif

            <!-- Slides -->
            <div class="carousel-inner">
                @foreach($Branrers as $key => $banner)
                    @php
                        $imageUrl = $language_name == 'French'
                            ? getBannerImage($banner->banner_image_french, 'large')
                            : getBannerImage($banner->banner_image, 'large');

                        $filename = $language_name == 'French'
                            ? $banner->banner_image_french
                            : $banner->banner_image;

                        $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
                    @endphp
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <img src="{{ $imageUrl }}"
                             alt="{{ $filenameWithoutExtension }}"
                             loading="{{ $key === 0 ? 'eager' : 'lazy' }}">
                    </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            @if(count($Branrers) > 1)
                <a class="carousel-control-prev" href="#mainCarousel" role="button" data-slide="prev">
                    <i class="las la-angle-left" aria-hidden="true"></i>
                    <span class="sr-only">{{ __('Previous') }}</span>
                </a>
                <a class="carousel-control-next" href="#mainCarousel" role="button" data-slide="next">
                    <i class="las la-angle-right" aria-hidden="true"></i>
                    <span class="sr-only">{{ __('Next') }}</span>
                </a>
                <!-- Mobile Swipe Hint -->
                <div class="swipe-hint">
                    {{ $language_name == 'French' ? 'Glissez pour naviguer' : 'Swipe to navigate' }}
                </div>
            @endif
        @else
            <!-- Default Slide -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ BANNER_DEFAULT_IMAGE_URL }}" alt="{{ __('Default Banner') }}">
                </div>
            </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carousel = $('#mainCarousel');

    // Initialize carousel with custom options
    carousel.carousel({
        interval: 5000,  // 5 seconds per slide
        pause: 'hover',  // Pause on hover
        keyboard: true,  // Allow keyboard navigation
        touch: true      // Enable touch swipe on mobile
    });

    // Progress bar animation for indicators
    function resetIndicators() {
        $('.carousel-indicators li').removeClass('animate');
        $('.carousel-indicators li.active').addClass('animate');
    }

    // Reset indicator animation on slide
    carousel.on('slid.bs.carousel', function() {
        resetIndicators();
    });

    // Initial indicator animation
    resetIndicators();

    // Preload next image
    function preloadNextImage() {
        const activeItem = carousel.find('.carousel-item.active');
        const nextItem = activeItem.next('.carousel-item').length ?
                        activeItem.next('.carousel-item') :
                        carousel.find('.carousel-item:first');

        if (nextItem.length) {
            const img = nextItem.find('img');
            if (img.attr('loading') === 'lazy') {
                img.attr('loading', 'eager');
            }
        }
    }

    // Preload next image on slide
    carousel.on('slide.bs.carousel', function() {
        preloadNextImage();
    });

    // Handle touch events for mobile
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.on('touchstart', function(e) {
        touchStartX = e.originalEvent.touches[0].clientX;
    });

    carousel.on('touchend', function(e) {
        touchEndX = e.originalEvent.changedTouches[0].clientX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        const swipeLength = touchEndX - touchStartX;

        if (Math.abs(swipeLength) > swipeThreshold) {
            if (swipeLength > 0) {
                carousel.carousel('prev');
            } else {
                carousel.carousel('next');
            }
        }
    }

    // Pause on video if present
    carousel.find('video').on('play', function() {
        carousel.carousel('pause');
    });

    // Resume on video end
    carousel.find('video').on('ended', function() {
        carousel.carousel('cycle');
    });
});
</script>
