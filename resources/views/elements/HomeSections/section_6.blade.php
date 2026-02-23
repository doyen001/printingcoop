<!-- {{-- CI: application/views/elements/HomeSections/section_6.php --}}
{{-- Main Services Section --}}
@if(isset($section_6))
    @if($language_name == 'french')
        {!! $section_6->content_french ?? '' !!}
    @else
        {!! $section_6->content ?? '' !!}
    @endif
@endif -->

<style>
.main-services {
        padding: 80px 0;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        position: relative;
        overflow: hidden;
    }

    .main-services::before {
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

    .service-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 40px 30px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(255, 107, 53, 0) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .service-card:hover::before {
        opacity: 1;
    }

    .service-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 107, 53, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon {
        transform: scale(1.1);
    }

    .service-icon i {
        font-size: 2.5rem;
        color: var(--primary-color);
    }

    .service-icon img {
        width: 40px;
        height: 40px;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon img {
        filter: brightness(0) invert(1);
    }

    .service-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-title {
        color: var(--primary-color);
    }

    .service-description {
        color: #666;
        font-size: 1rem;
        line-height: 1.6;
        margin: 0;
    }

    /* Dark Theme Version */
    .main-services.dark-theme {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d3436 100%);
    }

    .main-services.dark-theme .service-card {
        background: #2d3436;
    }

    .main-services.dark-theme .service-title {
        color: #ffffff;
    }

    .main-services.dark-theme .service-description {
        color: #b2bec3;
    }

    /* Responsive Styles */
    @media (max-width: 991px) {
        .main-services {
            padding: 60px 0;
        }

        .service-card {
            padding: 30px 20px;
        }

        .service-icon {
            width: 60px;
            height: 60px;
        }

        .service-icon img {
            width: 30px;
            height: 30px;
        }
    }

    @media (max-width: 767px) {
        .service-card {
            margin-bottom: 20px;
        }
    }

    @media (max-width: 768px) {
        .service-icon i {
            font-size: 2rem;
        }

        .service-title {
            font-size: 1.1rem;
        }
    }
</style>

<section class="main-services">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-hand-pointer"></i>
                    </div>
                    <h3 class="service-title">
                        @if ($language_name == 'French')
                            VOTRE IMPRIMERIE EN LIGNE ET POUR DES MATÉRIAUX IMPRIMÉS PROFESSIONNELLEMENT
                        @else
                            YOUR ONLINE PRINT SHOP & FOR PROFESSIONALLY PRINTED MATERIALS
                        @endif
                    </h3>
                    <p class="service-description">
                        @if ($language_name == 'French')
                            Pour chaque commande, nous nous assurons que vos modèles sont adaptés à l'impression gratuitement. Si des modifications doivent être apportées, nous vous en informons par e-mail. & collectez dans l'un de nos 3 magasins GRATUITEMENT.
                        @else
                            For every order, we make sure your templates are suitable for printing free of charge. If any alterations need to be made, we inform you by email. & collect from any one of our 3 stores FREE.
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <h3 class="service-title">
                        @if ($language_name == 'French')
                            DÉLAIS RAPIDES
                        @else
                            QUICK TURNAROUNDS
                        @endif
                    </h3>
                    <p class="service-description">
                        @if ($language_name == 'French')
                            Nous savons que la rapidité de mise sur le marché est essentielle, c'est pourquoi nous proposons des délais d'exécution ultra-rapides sur la plupart des produits. Dans de nombreux endroits, la livraison le jour même peut également être disponible. Pas de soucis.
                        @else
                            We know speed-to-market is essential, which is why we offer ultra-fast turnaround times on most products. In many locations, Same Day Delivery may also be available. No worries.
                        @endif
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="service-title">
                        @if ($language_name == 'French')
                            PRIX COMPÉTITIFS
                        @else
                            COMPETITIVE PRICES
                        @endif
                    </h3>
                    <p class="service-description">
                        @if ($language_name == 'French')
                            Nous utilisons une technologie moderne pour rendre l'impression rentable et meilleure que jamais. Nous offrons de la valeur et grâce à notre calculateur de prix, vous pouvez modifier les options pour trouver ce qui convient le mieux à votre budget.
                        @else
                            We use modern technology to make print cost-effective and better than ever before. We deliver value, and thanks to our pricing calculator, you can change options to find something best for your budget.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>