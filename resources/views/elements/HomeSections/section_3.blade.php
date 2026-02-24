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
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 40px;
}

.service-card {
    background: #fafafa;
    border: 1px solid #d4a574;
    border-radius: 12px;
    padding: 32px 24px;
    text-align: center;
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(20px);
    position: relative;
    cursor: pointer;
    box-shadow: none;
}

.service-card.visible {
    opacity: 1;
    transform: translateY(0);
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(212, 165, 116, 0.15);
    border-color: #f28738;
    background-size: cover;
    background-position: center;            
}

.service-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 24px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background-color: #ffefe8;
    border-radius: 18px;
}

.service-icon svg {
    width: 50%;
    height: 50%;
    object-fit: contain;
    color: #f28738;
    transition: all 0.3s ease;
}

.service-card:hover .service-icon {
    transform: scale(1.05);
    background-color: #f28738;
}

.service-card:hover .service-icon svg {
    color: rgb(255 240 234);
}

.service-card:hover .service-icon svg * {
    stroke: rgb(193 150 96) !important;
}

.service-name {
    font-size: 1.125rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    line-height: 1.4;
}

.service-description {
    font-size: 0.875rem;
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 0;
}

.service-card:hover .service-name {
    color: #f28738;
}

.service-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.service-image {
    position: relative;
    padding-top: 70%;
    overflow: hidden;
    border-radius: 13px;
    margin-bottom: 16px;
    transform: rotate(-3deg) translateY(0);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    background: linear-gradient(45deg, #f8f9fa, #e9ecef);
}

.service-card:hover .service-image {
    transform: rotate(0deg) scale(1.02) translateY(-2px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
    background: linear-gradient(45deg, #ffffff, #f8f9fa);
}

.service-image::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    transition: all 0.6s ease;
    opacity: 0;
}

.service-card:hover .service-image::before {
    opacity: 1;
    animation: shimmer 0.6s ease;
}

.service-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(0, 0, 0, 0.4) 50%, rgba(0, 0, 0, 0.7) 100%);
    opacity: 0;
    transition: all 0.4s ease;
    mix-blend-mode: multiply;
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
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    filter: brightness(1) contrast(1) saturate(1);
}

.service-card:hover .service-image img {
    transform: scale(1.15) rotate(1deg);
    filter: brightness(0.85) contrast(1.1) saturate(1.2);
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
    }
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
        grid-template-columns: repeat(3, 1fr);
    }
    
    .service-icon {
        width: 56px;
        height: 56px;
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
        gap: 20px;
    }
    
    .service-card {
        padding: 24px 20px;
    }
    
    .service-icon {
        width: 52px;
        height: 52px;
        margin-bottom: 20px;
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
    
    .service-card {
        padding: 20px 16px;
    }
    
    .service-icon {
        width: 48px;
        height: 48px;
        margin-bottom: 16px;
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
                            <div class="service-icon">
                                @php
                                    $iconName = 'default';
                                    $serviceNameLower = strtolower($serviceName);
                                    
                                    // Map service names to appropriate icons
                                    if (str_contains($serviceNameLower, 'print') || str_contains($serviceNameLower, 'impression')) {
                                        $iconName = 'printer';
                                    } elseif (str_contains($serviceNameLower, 'design') || str_contains($serviceNameLower, 'graphique')) {
                                        $iconName = 'design';
                                    } elseif (str_contains($serviceNameLower, 'flyer') || str_contains($serviceNameLower, 'event') || str_contains($serviceNameLower, 'prospectus')) {
                                        $iconName = 'flyer';
                                    } elseif (str_contains($serviceNameLower, 'card') || str_contains($serviceNameLower, 'carte')) {
                                        $iconName = 'card';
                                    } elseif (str_contains($serviceNameLower, 'banner') || str_contains($serviceNameLower, 'banniere') || str_contains($serviceNameLower, 'affiche')) {
                                        $iconName = 'banner';
                                    } elseif (str_contains($serviceNameLower, 'book') || str_contains($serviceNameLower, 'office') || str_contains($serviceNameLower, 'catalogue')) {
                                        $iconName = 'book';
                                    } elseif (str_contains($serviceNameLower, 'logo') || str_contains($serviceNameLower, 'branding')) {
                                        $iconName = 'logo';
                                    } elseif (str_contains($serviceNameLower, 'packaging') || str_contains($serviceNameLower, 'emballage')) {
                                        $iconName = 'package';
                                    } elseif (str_contains($serviceNameLower, 'photo') || str_contains($serviceNameLower, 'photographie')) {
                                        $iconName = 'photo';
                                    } elseif (str_contains($serviceNameLower, 'label') || str_contains($serviceNameLower, 'étiquette')) {
                                        $iconName = 'label';
                                    } elseif (str_contains($serviceNameLower, 'envelope') || str_contains($serviceNameLower, 'enveloppe')) {
                                        $iconName = 'envelope';
                                    } elseif (str_contains($serviceNameLower, 'sticker') || str_contains($serviceNameLower, 'autocollant')) {
                                        $iconName = 'sticker';
                                    } else {
                                        $iconName = 'default';
                                    }
                                @endphp
                                
                                @switch($iconName)
                                    @case('printer')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M6 9V2h12v7M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 9h12v11H6V9z"/>
                                            <rect x="8" y="12" width="8" height="3" rx="1"/>
                                            <line x1="3" y1="9" x2="21" y2="9" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('design')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                        </svg>
                                        @break
                                    @case('flyer')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="3" y1="9" x2="21" y2="9" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                            <line x1="9" y1="21" x2="9" y2="9" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('card')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                            <line x1="1" y1="10" x2="23" y2="10" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('banner')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v12a2 2 0 0 1-2 2v2z"/>
                                            <line x1="8" y1="6" x2="20" y2="6" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                            <line x1="8" y1="10" x2="20" y2="10" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                            <line x1="8" y1="14" x2="20" y2="14" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('book')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                        </svg>
                                        @break
                                    @case('logo')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="10"/>
                                            <path d="M8 14s1.5 2 4 2 4-2 4-2" stroke="rgb(255 240 234)" stroke-width="2" fill="none"/>
                                            <line x1="9" y1="9" x2="9.01" y2="9" stroke="rgb(255 240 234)" stroke-width="2"/>
                                            <line x1="15" y1="9" x2="15.01" y2="9" stroke="rgb(255 240 234)" stroke-width="2"/>
                                        </svg>
                                        @break
                                    @case('package')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96" fill="none" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                            <line x1="12" y1="22.08" x2="12" y2="12" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('photo')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21" fill="none" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('label')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                            <circle cx="12" cy="12" r="3" fill="rgb(255 240 234)"/>
                                        </svg>
                                        @break
                                    @case('envelope')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                            <polyline points="22,6 12,13 2,6" fill="none" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                    @case('sticker')
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                                            <circle cx="7" cy="7" r="1.5" fill="rgb(255 240 234)"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8" fill="none" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                            <line x1="16" y1="13" x2="8" y2="13" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                            <line x1="16" y1="17" x2="8" y2="17" stroke="rgb(255 240 234)" stroke-width="1.5"/>
                                        </svg>
                                        @break
                                @endswitch
                            </div>
                            <h3 class="service-name">{{ $serviceName }}</h3>
                            <div class="service-image">
                                 <img src="{{ $imageurl }}" alt="{{ $serviceName }}" loading="lazy">
                                {{-- @if($language_name == 'french')
                                    {{ Str::limit(strip_tags($service->description_french ?? ''), 100) }}
                                @else
                                    {{ Str::limit(strip_tags($service->description ?? ''), 100) }}
                                @endif --}}
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
        // document.querySelectorAll('.service-card').forEach(card => {
        //     card.addEventListener('mouseenter', function() {
        //         this.style.transform = 'translateY(-5px)';
        //     });

        //     card.addEventListener('mouseleave', function() {
        //         this.style.transform = 'translateY(0)';
        //     });
        // });
    });
</script>
