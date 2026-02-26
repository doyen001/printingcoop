{{-- CI: application/views/elements/slider.php --}}
<style>
/* Modern 3D Stacked Carousel */
.main-slider-section {
    position: relative;
    width: 100%;
    padding: 80px 20px 120px;
    background: linear-gradient(135deg, #f0f4f8 0%, #e8f0f7 100%);
    overflow: hidden;
}

/* Decorative Blob Shapes */
.blob-decoration {
    position: absolute;
    border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
    opacity: 0.6;
    z-index: 1;
}

.blob-1 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #a8d5ba 0%, #7bc393 100%);
    top: 10%;
    left: -100px;
    animation: blobFloat 20s infinite ease-in-out;
}

.blob-2 {
    width: 250px;
    height: 250px;
    background: linear-gradient(135deg, #ffd89b 0%, #f9c74f 100%);
    bottom: 15%;
    right: -80px;
    animation: blobFloat 15s infinite ease-in-out reverse;
}

.blob-3 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #a8dadc 0%, #457b9d 100%);
    top: 50%;
    right: 10%;
    animation: blobFloat 18s infinite ease-in-out;
}

@keyframes blobFloat {
    0%, 100% {
        transform: translate(0, 0) rotate(0deg);
        border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
    }
    25% {
        transform: translate(20px, -20px) rotate(5deg);
        border-radius: 50% 50% 60% 40% / 50% 60% 40% 50%;
    }
    50% {
        transform: translate(-15px, 15px) rotate(-5deg);
        border-radius: 60% 40% 50% 50% / 60% 50% 50% 40%;
    }
    75% {
        transform: translate(15px, 20px) rotate(3deg);
        border-radius: 45% 55% 65% 35% / 45% 55% 45% 55%;
    }
}

.carousel-container {
    position: relative;
    max-width: 1200px;
    margin: 0 auto;
    z-index: 2;
    perspective: 1500px;
}

.carousel-wrapper {
    position: relative;
    width: 100%;
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.carousel-slide {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    transform: translate(-50%, -50%) scale(0.7) translateZ(-200px);
    opacity: 0;
    visibility: hidden;
    transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
}

/* Previous slide - left side, smaller, behind */
.carousel-slide.prev {
    transform: translate(-120%, -50%) scale(0.75) translateZ(-100px);
    opacity: 0.5;
    visibility: visible;
    z-index: 2;
}

/* Next slide - right side, smaller, behind */
.carousel-slide.next {
    transform: translate(20%, -50%) scale(0.75) translateZ(-100px);
    opacity: 0.5;
    visibility: visible;
    z-index: 2;
}

/* Active slide - center, full size, front */
.carousel-slide.active {
    transform: translate(-50%, -50%) scale(1) translateZ(0);
    opacity: 1;
    visibility: visible;
    z-index: 5;
}

.slide-image-container {
    position: relative;
    width: 100%;
    height: 100%;
    border-radius: 30px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    background: white;
    transition: box-shadow 0.3s ease;
}

.carousel-slide.active .slide-image-container {
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
}

.slide-image-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.2) 100%);
    z-index: 2;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.carousel-slide.active .slide-image-container::before {
    opacity: 1;
}

.carousel-slide.prev .slide-image-container::before,
.carousel-slide.next .slide-image-container::before {
    opacity: 0.3;
}

.slide-image-container img {
    width: 100%;
    height: 100%;
    object-fit: fill;
    object-position: center;
    transition: transform 0.8s ease;
}

.carousel-slide.active .slide-image-container img {
    transform: scale(1.05);
}

/* Content Overlay - only visible on active slide */
.slide-content {
    position: absolute;
    bottom: 40px;
    left: 40px;
    right: 40px;
    z-index: 3;
    color: white;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.carousel-slide.active .slide-content {
    opacity: 1;
}

.slide-content h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    transform: translateY(30px);
    transition: all 0.6s ease 0.2s;
}

.carousel-slide.active .slide-content h2 {
    transform: translateY(0);
}

.slide-content p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    text-shadow: 0 1px 5px rgba(0, 0, 0, 0.5);
    transform: translateY(30px);
    transition: all 0.6s ease 0.4s;
}

.carousel-slide.active .slide-content p {
    transform: translateY(0);
}

.slide-button {
    display: inline-block;
    padding: 12px 35px;
    background: #f28738;
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(242, 135, 56, 0.4);
    transform: translateY(30px);
    transition: all 0.6s ease 0.6s;
}

.carousel-slide.active .slide-button {
    transform: translateY(0);
}

.slide-button:hover {
    background: #e07628;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(242, 135, 56, 0.5);
}

/* Navigation Controls */
.carousel-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 55px;
    height: 55px;
    background: white;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.carousel-nav:hover {
    background: #f28738;
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 6px 25px rgba(242, 135, 56, 0.4);
}

.carousel-nav.prev-btn {
    left: -70px;
}

.carousel-nav.next-btn {
    right: -70px;
}

.carousel-nav i {
    font-size: 22px;
    color: #333;
    transition: color 0.3s ease;
}

.carousel-nav:hover i {
    color: white;
}

/* Indicators */
.carousel-indicators {
    position: absolute;
    bottom: -60px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 10;
}

.carousel-indicators li {
    width: 40px;
    height: 4px;
    border-radius: 2px;
    background: rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.carousel-indicators li::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 0;
    height: 100%;
    background: #f28738;
    transition: width 5s linear;
}

.carousel-indicators li.active::before {
    width: 100%;
}

.carousel-indicators li:hover {
    background: rgba(242, 135, 56, 0.3);
}

.carousel-indicators li.active {
    background: #f28738;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .carousel-nav.prev-btn {
        left: 20px;
    }
    
    .carousel-nav.next-btn {
        right: 20px;
    }
    
    .carousel-slide.prev {
        transform: translate(-110%, -50%) scale(0.7) translateZ(-100px);
    }
    
    .carousel-slide.next {
        transform: translate(10%, -50%) scale(0.7) translateZ(-100px);
    }
}

@media (max-width: 768px) {
    .main-slider-section {
        padding: 40px 15px 80px;
    }
    
    .carousel-wrapper {
        height: 350px;
    }
    
    .carousel-slide {
        width: 90%;
    }
    
    /* Hide prev/next slides on mobile for cleaner view */
    .carousel-slide.prev,
    .carousel-slide.next {
        opacity: 0;
        visibility: hidden;
    }
    
    .slide-image-container {
        border-radius: 20px;
    }
    
    .slide-content {
        bottom: 20px;
        left: 20px;
        right: 20px;
    }
    
    .slide-content h2 {
        font-size: 1.8rem;
    }
    
    .slide-content p {
        font-size: 0.95rem;
    }
    
    .carousel-nav {
        width: 45px;
        height: 45px;
    }
    
    .carousel-nav.prev-btn {
        left: 10px;
    }
    
    .carousel-nav.next-btn {
        right: 10px;
    }
    
    .carousel-nav i {
        font-size: 18px;
    }
    
    .blob-1, .blob-2, .blob-3 {
        display: none;
    }
}

@media (max-width: 480px) {
    .carousel-wrapper {
        height: 280px;
    }
    
    .slide-content h2 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    
    .slide-content p {
        font-size: 0.85rem;
        margin-bottom: 15px;
    }
    
    .slide-button {
        padding: 10px 25px;
        font-size: 0.9rem;
    }
    
    .carousel-indicators {
        bottom: -50px;
    }
    
    .carousel-indicators li {
        width: 30px;
    }
}
</style>

<div class="main-slider-section">
    <!-- Decorative Blobs -->
    <div class="blob-decoration blob-1"></div>
    <div class="blob-decoration blob-2"></div>
    <div class="blob-decoration blob-3"></div>
    
    <div class="carousel-container">
        @if($Branrers && count($Branrers) > 0)
            <div class="carousel-wrapper">
                @foreach($Branrers as $key => $list)
                    @php
                        $class = $key == 0 ? 'active' : '';
                        $imageurl = getBannerImage($list->banner_image, 'large');
                        if ($language_name == 'french' && !empty($list->banner_image_french)) {
                            $imageurl = getBannerImage($list->banner_image_french, 'large');
                        }
                        $title = $language_name == 'french' ? ($list->name_french ?? $list->name ?? '') : ($list->name ?? '');
                        $description = $language_name == 'french' ? ($list->description_french ?? $list->description ?? '') : ($list->description ?? '');
                    @endphp
                    
                    <div class="carousel-slide {{ $class }}" data-slide="{{ $key }}">
                        <div class="slide-image-container">
                            <img src="{{ $imageurl }}" alt="{{ $title }}">
                            <div class="slide-content">
                                <h2>{{ $title }}</h2>
                                <p>{{ Str::limit(strip_tags($description), 120) }}</p>
                                {{-- <a href="javascript:void(0)" class="slide-button">
                                    {{ $language_name == 'french' ? 'Découvrir' : 'Discover' }}
                                </a> --}}
                            </div>
                        </div>
                    </div>
                @endforeach
                
                <!-- Navigation -->
                @if(count($Branrers) > 1)
                    <button class="carousel-nav prev-btn" onclick="changeSlide(-1)">
                        <i class="las la-angle-left"></i>
                    </button>
                    <button class="carousel-nav next-btn" onclick="changeSlide(1)">
                        <i class="las la-angle-right"></i>
                    </button>
                    
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        @foreach($Branrers as $key => $list)
                            <li class="{{ $key == 0 ? 'active' : '' }}" onclick="goToSlide({{ $key }})"></li>
                        @endforeach
                    </ol>
                @endif
            </div>
        @else
            <!-- Default Slide -->
            <div class="carousel-wrapper">
                <div class="carousel-slide active">
                    <div class="slide-image-container">
                        <img src="{{ asset('defaults/banner-no-image.png') }}" alt="Default Banner">
                        <div class="slide-content">
                            <h2>{{ $language_name == 'french' ? 'Bienvenue' : 'Welcome' }}</h2>
                            <p>{{ $language_name == 'french' ? 'Découvrez nos produits et services de qualité' : 'Discover our quality products and services' }}</p>
                            <a href="javascript:void(0)" class="slide-button">
                                {{ $language_name == 'french' ? 'Explorer' : 'Explore' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
let currentSlide = 0;
const slides = document.querySelectorAll('.carousel-slide');
const totalSlides = slides.length;
let autoPlayInterval;

function showSlide(index) {
    // Remove all classes first
    slides.forEach((slide) => {
        slide.classList.remove('active', 'prev', 'next');
    });
    
    // Set active slide
    slides[index].classList.add('active');
    
    // Set previous slide
    const prevIndex = index === 0 ? totalSlides - 1 : index - 1;
    if (totalSlides > 1) {
        slides[prevIndex].classList.add('prev');
    }
    
    // Set next slide
    const nextIndex = index === totalSlides - 1 ? 0 : index + 1;
    if (totalSlides > 1) {
        slides[nextIndex].classList.add('next');
    }
    
    // Update indicators
    const indicators = document.querySelectorAll('.carousel-indicators li');
    indicators.forEach((indicator, i) => {
        indicator.classList.toggle('active', i === index);
    });
}

function changeSlide(direction) {
    if (totalSlides <= 1) return;
    
    currentSlide += direction;
    
    if (currentSlide >= totalSlides) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    }
    
    showSlide(currentSlide);
    restartAutoPlay();
}

function goToSlide(index) {
    if (totalSlides <= 1) return;
    
    currentSlide = index;
    showSlide(currentSlide);
    restartAutoPlay();
}

function startAutoPlay() {
    if (totalSlides <= 1) return;
    
    stopAutoPlay();
    autoPlayInterval = setInterval(() => {
        changeSlide(1);
    }, 5000);
}

function stopAutoPlay() {
    if (autoPlayInterval) {
        clearInterval(autoPlayInterval);
    }
}

function restartAutoPlay() {
    stopAutoPlay();
    startAutoPlay();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    if (totalSlides > 0) {
        showSlide(0);
        startAutoPlay();
        
        const wrapper = document.querySelector('.carousel-wrapper');
        if (wrapper) {
            wrapper.addEventListener('mouseenter', stopAutoPlay);
            wrapper.addEventListener('mouseleave', startAutoPlay);
        }
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        });
        
        // Touch/swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        
        wrapper.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        wrapper.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            const threshold = 50;
            
            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    changeSlide(1);
                } else {
                    changeSlide(-1);
                }
            }
        });
    }
});
</script>
