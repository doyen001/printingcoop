{{-- CI: application/views/elements/HomeSections/section_7.php --}}
{{-- REGISTER FOR FREE! Section --}}

<style>
:root {
        --join-dark-bg: #1a1a1a;
        --join-light-bg: #ffffff;
        --join-dark-text: #ffffff;
        --join-light-text: #1a1a1a;
        --join-muted-text: #888888;
        --join-accent: #ff6b35;
        --join-accent-hover: #ff5a1f;
    }

    .join-section {
        padding: 6rem 0;
        background: var(--join-light-bg);
        position: relative;
        overflow: hidden;
    }

    .join-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23FF6B35" fill-opacity="0.05" d="M45.3,-77.5C59.9,-70.3,73.5,-60.2,82.2,-46.7C90.9,-33.2,94.7,-16.6,93.3,-0.8C91.9,15,85.3,30,76.3,43C67.3,56,55.9,67,42.3,74.8C28.7,82.6,14.4,87.2,-0.4,87.9C-15.1,88.7,-30.2,85.6,-44,78.7C-57.8,71.8,-70.2,61.1,-78.9,47.4C-87.5,33.7,-92.3,16.9,-91.3,0.6C-90.3,-15.7,-83.5,-31.3,-74.3,-45.1C-65.2,-58.9,-53.7,-70.8,-40,-77.8C-26.2,-84.8,-13.1,-86.9,1.3,-89.1C15.7,-91.4,31.3,-93.8,45.3,-87.5Z" transform="translate(100 100)"/></svg>') no-repeat center center;
        background-size: 80% 80%;
        opacity: 0.1;
        z-index: 0;
    }

    .join-wrapper {
        position: relative;
        z-index: 1;
    }

    .join-content {
        padding-right: 3rem;
    }

    .join-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        background-color: rgba(255, 107, 53, 0.15);
        color: var(--join-accent);
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .join-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #183e73;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .join-description {
        color: var(--join-muted-text);
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .join-features {
        margin-bottom: 2.5rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        background: rgba(0, 0, 0, 0.03);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: translateX(5px);
    }

    .feature-item i {
        color: #f28738;
        font-size: 1.2rem;
        margin-right: 1rem;
    }

    .feature-item span {
        color: var(--join-light-text);
        font-size: 1.1rem;
    }

    .join-button {
        display: inline-flex;
        align-items: center;
        padding: 1rem 2rem;
        background-color: #f28738;
        color: var(--join-light-bg);
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .join-button span {
        margin-right: 0.75rem;
    }

    .join-button i {
        transition: transform 0.3s ease;
    }

    .join-button:hover {
        background-color: #183e73;
        transform: translateY(-2px);
        color: var(--join-light-bg);
        text-decoration: none;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    .join-button:hover i {
        transform: translateX(5px);
    }

    .join-image {
        position: relative;
        padding: 2rem;
    }

    .image-shape {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(255, 107, 53, 0.1);
        border-radius: 20px;
        transform: rotate(-3deg);
        z-index: 0;
    }

    .image-content {
        position: relative;
        z-index: 1;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .image-content img {
        width: 100%;
        height: auto;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    .image-content:hover img {
        transform: scale(1.05);
    }

    @media (max-width: 991px) {
        .join-section {
            padding: 4rem 0;
        }

        .join-content {
            padding-right: 0;
            margin-bottom: 3rem;
        }

        .join-title {
            font-size: 2rem;
        }
    }:root {
        --join-dark-bg: #1a1a1a;
        --join-light-bg: #ffffff;
        --join-dark-text: #ffffff;
        --join-light-text: #1a1a1a;
        --join-muted-text: #888888;
        --join-accent: #ff6b35;
        --join-accent-hover: #ff5a1f;
    }

    .join-section {
        padding: 6rem 0;
        background: var(--join-light-bg);
        position: relative;
        overflow: hidden;
    }

    .join-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23FF6B35" fill-opacity="0.05" d="M45.3,-77.5C59.9,-70.3,73.5,-60.2,82.2,-46.7C90.9,-33.2,94.7,-16.6,93.3,-0.8C91.9,15,85.3,30,76.3,43C67.3,56,55.9,67,42.3,74.8C28.7,82.6,14.4,87.2,-0.4,87.9C-15.1,88.7,-30.2,85.6,-44,78.7C-57.8,71.8,-70.2,61.1,-78.9,47.4C-87.5,33.7,-92.3,16.9,-91.3,0.6C-90.3,-15.7,-83.5,-31.3,-74.3,-45.1C-65.2,-58.9,-53.7,-70.8,-40,-77.8C-26.2,-84.8,-13.1,-86.9,1.3,-89.1C15.7,-91.4,31.3,-93.8,45.3,-87.5Z" transform="translate(100 100)"/></svg>') no-repeat center center;
        background-size: 80% 80%;
        opacity: 0.1;
        z-index: 0;
    }

    .join-wrapper {
        position: relative;
        z-index: 1;
    }

    .join-content {
        padding-right: 3rem;
    }

    .join-badge {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        background-color: rgba(255, 107, 53, 0.15);
        color: var(--join-accent);
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .join-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #183e73;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .join-description {
        color: var(--join-muted-text);
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }

    .join-features {
        margin-bottom: 2.5rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        background: rgba(0, 0, 0, 0.03);
        padding: 1rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .feature-item:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: translateX(5px);
    }

    .feature-item i {
        color: #f28738;
        font-size: 1.2rem;
        margin-right: 1rem;
    }

    .feature-item span {
        color: var(--join-light-text);
        font-size: 1.1rem;
    }

    .join-button {
        display: inline-flex;
        align-items: center;
        padding: 1rem 2rem;
        background-color: #f28738;
        color: var(--join-light-bg);
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .join-button span {
        margin-right: 0.75rem;
    }

    .join-button i {
        transition: transform 0.3s ease;
    }

    .join-button:hover {
        background-color: #183e73;
        transform: translateY(-2px);
        color: var(--join-light-bg);
        text-decoration: none;
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
    }

    .join-button:hover i {
        transform: translateX(5px);
    }

    .join-image {
        position: relative;
        padding: 2rem;
    }

    .image-shape {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-color: rgba(255, 107, 53, 0.1);
        border-radius: 20px;
        transform: rotate(-3deg);
        z-index: 0;
    }

    .image-content {
        position: relative;
        z-index: 1;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .image-content img {
        width: 100%;
        height: auto;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }

    .image-content:hover img {
        transform: scale(1.05);
    }

    @media (max-width: 991px) {
        .join-section {
            padding: 4rem 0;
        }

        .join-content {
            padding-right: 0;
            margin-bottom: 3rem;
        }

        .join-title {
            font-size: 2rem;
        }
    }
</style>

<section class="join-section light-theme">
    <div class="container">
        <div class="join-wrapper">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-12">
                    <div class="join-content">
                        <div class="join-badge">
                            {{ ($language_name == 'French') ? 'Rejoignez-nous' : 'Join Us' }}
                        </div>
                        <h2 class="join-title">
                            @if ($language_name == 'French')
                                {{ $section_7->name_french ?? '' }}
                            @else 
                                {{ $section_7->name ?? '' }}
                            
                            @endif
                        </h2>
                        <div class="join-description">
                            @if ($language_name == 'French')
                                {{ $section_7->description_french ?? '' }}
                            @else 
                                {{$section_7->description ?? ''}}
                            
                            @endif
                        </div>
                        <div class="join-features">
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ ($language_name == 'French') ? 'Prix compétitifs' : 'Competitive Pricing' }}</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ ($language_name == 'French') ? 'Service rapide' : 'Fast Service' }}</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ ($language_name == 'French') ? 'Support 24/7' : '24/7 Support' }}</span>
                            </div>
                        </div>
                        <a href="Logins" class="join-button">
                            <span>{{ ($language_name == 'French') ? 'Rejoignez nous maintenant' : 'Join Us Now' }}</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="join-image">
                        <div class="image-shape"></div>
                        <div class="image-content">
                            @if ($language_name == 'French')
                                {!! $section_7->content_french ?? '' !!}
                            @else
                                {!! $section_7->content ?? '' !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
