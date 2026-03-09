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
    grid-template-columns: repeat(3, 1fr);
    gap: 50px;
    margin-top: 40px;
}

.service-image-card {
    background: transparent;
    text-align: center;
    transition: transform 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
    cursor: default;
}

.service-image-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.service-image-card:hover {
    transform: translateY(-3px);
}

/* (Removed original bento grid spans so cards flow in a simple grid) */

.service-icon {
    display: none;
}

.service-icon svg {
    width: 20px;
    height: 20px;
    object-fit: contain;
    color: #f28738;
    transition: all 0.3s ease;
}

/* .service-image-card:hover .service-icon {
    transform: scale(1.05);
    background-color: #f28738;
    border-color: #f28738;
}

.service-image-card:hover .service-icon svg {
    color: rgb(255 240 234);
}

.service-image-card:hover .service-icon svg * {
    stroke: rgb(193 150 96) !important;
} */

.service-name {
    font-size: 20px;
    font-weight: 600;
    color: #333333;
    margin-bottom: 0;
    line-height: 1.3;
}

.service-content {
    position: static;
    height: auto;
    display: block;
    padding: 10px 4px 0;
    background: transparent;
    text-align: center;
}

.service-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.service-image {
    position: relative;
    width: 100%;
    background: #ffffff;
    border-radius: .5rem;
    overflow: hidden;
    padding-top: 100%; /* 1:1 square */
}

.service-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
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
@media (max-width: 991px) {
    .services-section {
        padding: 80px 0;
    }

    .services-title {
        font-size: 2.5rem;
    }

    .services-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
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
        gap: 16px;
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
                    
                    <div class="service-image-card">
                        <a href="javascript:void(0)" class="service-link service-image">
                            <img src="{{ $imageurl }}" alt="{{ $serviceName }}" loading="lazy">
                        </a>
                        <div class="service-content">
                            <h3 class="service-name">{{ $serviceName }}</h3>
                        </div>
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
        document.querySelectorAll('.service-image-card').forEach(card => {
            observer.observe(card);
        });

        // Add hover effect for service cards
        // document.querySelectorAll('.service-image-card').forEach(card => {
        //     card.addEventListener('mouseenter', function() {
        //         this.style.transform = 'translateY(-5px)';
        //     });

        //     card.addEventListener('mouseleave', function() {
        //         this.style.transform = 'translateY(0)';
        //     });
        // });
    });
</script>
