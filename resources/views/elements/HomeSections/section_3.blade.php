{{-- CI: application/views/elements/HomeSections/section_3.php --}}
{{-- OUR SERVICES Section --}}

<style>
/* Section 3: Our Services Styles */
/* Extracted from CI section_3.php */

.services-section {
    padding: 100px 0;
    background: #fff;
    position: relative;
    overflow: hidden;
}

.services-section::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.services-header {
    max-width: 800px;
    margin: 0 0 80px 0;
    position: relative;
}

.services-title {
    font-size: 3rem;
    color: #183e73;
    margin-bottom: 25px;
    font-weight: 800;
    line-height: 1.2;
    position: relative;
}

.services-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -15px;
    width: 80px;
    height: 4px;
    background: #ff6b35;
    border-radius: 2px;
}

.services-description {
    font-size: 1.2rem;
    color: #666666;
    line-height: 1.8;
    margin-bottom: 20px;
}

.services-content {
    font-size: 1.1rem;
    color: #666666;
    line-height: 1.6;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 30px;
    margin-top: 40px;
}

.service-card {
    background: #ffffff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
    position: relative;
    cursor: pointer;
}

/* Masonry-style layout */
.service-card:nth-child(3n+1) {
    grid-column: span 4;
}

.service-card:nth-child(3n+2) {
    grid-column: span 5;
}

.service-card:nth-child(3n+3) {
    grid-column: span 3;
}

.service-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, #fff 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.service-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.service-image {
    position: relative;
    padding-top: 70%;
    overflow: hidden;
    border-radius: 13px;
}

.service-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.6) 100%);
    opacity: 0;
    transition: all 0.3s ease;
}

.service-card:hover .service-image::after {
    opacity: 1;
}

.service-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.3s ease;
}

.service-card:hover .service-image img {
    transform: scale(1.1);
}

.service-info {
    padding: 25px;
    position: relative;
}

.service-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 15px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.service-name::after {
    content: '→';
    font-size: 1.2rem;
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
}

.service-card:hover .service-name::after {
    opacity: 1;
    transform: translateX(0);
}

.service-card:hover .service-name {
    color: #ff6b35;
}

.service-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

/* Animation Classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-in {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .services-grid {
        grid-template-columns: repeat(6, 1fr);
    }

    .service-card:nth-child(3n+1),
    .service-card:nth-child(3n+2),
    .service-card:nth-child(3n+3) {
        grid-column: span 3;
    }
}

@media (max-width: 991px) {
    .services-section {
        padding: 80px 0;
    }

    .services-title {
        font-size: 2.5rem;
    }

    .services-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .service-card:nth-child(3n+1),
    .service-card:nth-child(3n+2),
    .service-card:nth-child(3n+3) {
        grid-column: span 1;
    }
}

@media (max-width: 767px) {
    .services-title {
        font-size: 2rem;
    }

    .services-description {
        font-size: 1.1rem;
    }

    .services-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .services-section {
        padding: 60px 0;
    }
    
    .services-header {
        margin-bottom: 40px;
    }
}
</style>

<section class="services-section">
    <div class="container">
        <div class="services-header animate-in">
            <h2 class="services-title">
                @if($language_name == 'french')
                    {{ $section_3->name_french ?? '' }}
                @else
                    {{ $section_3->name ?? '' }}
                @endif
            </h2>
            <div class="services-description">
                @if($language_name == 'french')
                    {{ $section_3->description_french ?? '' }}
                @else
                    {{ $section_3->description ?? '' }}
                @endif
            </div>
            <div class="services-content">
                @if($language_name == 'french')
                    {!! $section_3->content_french ?? '' !!}
                @else
                    {!! $section_3->content ?? '' !!}
                @endif
            </div>
        </div>

        @if(isset($allServices) && count($allServices) > 0)
            <div class="services-grid">
                @foreach($allServices as $key => $service)
                    @php
                        // Get service image based on language (CI lines 314-319)
                        $imageurl = url('uploads/banners/small/' . $service->service_image);
                        if ($language_name == 'french' && !empty($service->service_image_french)) {
                            $imageurl = url('uploads/banners/small/' . $service->service_image_french);
                        }
                        
                        $serviceName = $language_name == 'french' ? ($service->name_french ?? '') : ($service->name ?? '');
                    @endphp
                    
                    <div class="service-card">
                        <a href="javascript:void(0)" class="service-link">
                            <div class="service-image">
                                <img src="{{ $imageurl }}" alt="{{ $serviceName }}" loading="lazy">
                            </div>
                            <div class="service-info">
                                <h3 class="service-name">{{ $serviceName }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intersection Observer for service cards
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    entry.target.style.transitionDelay = `${index * 0.1}s`;
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px'
        });

        // Observe all service cards
        document.querySelectorAll('.service-card').forEach(card => {
            observer.observe(card);
        });

        // Add hover effect for service cards
        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });
</script>
