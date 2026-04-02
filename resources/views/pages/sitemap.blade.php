@extends('elements.app')

@section('content')
<div class="container py-5">

    <div class="row">
        <!-- Main Navigation -->
        <div class="col-12">
            <div class="sitemap-section">
                <h2 class="sitemap-heading">
                    <i class="fas fa-home me-2"></i>
                    {{ $language_name == 'french' ? 'Navigation Principale' : 'Main Navigation' }}
                </h2>
                <div class="sitemap-links">
                    <div class="link-group">
                        <h3>{{ $language_name == 'french' ? 'Menu Principal' : 'Main Menu' }}</h3>
                        <ul class="list-unstyled">
                            <li><a href="{{ url('Products') }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Tous les produits' : 'All products' }}
                            </a></li>
                            <li><a href="{{ url('Products?category_id=' . base64_encode('1')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Cartes de visite' : 'Business Cards' }}
                            </a></li>
                            <li><a href="{{ url('Products?tag_id=' . base64_encode('6')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Marketing&Papeterie' : 'Marketing&Stationery' }}
                            </a></li>
                            <li><a href="{{ url('Products?category_id=' . base64_encode('2')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Enseignes&Bannières' : 'Signs&Banners' }}
                            </a></li>
                            <li><a href="{{ url('Products?category_id=' . base64_encode('3')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Annonces et Cartes de Vœux' : 'Announces and Greeting Cards' }}
                            </a></li>
                            <li><a href="{{ url('Products?category_id=' . base64_encode('4')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Autocollants&Étiquettes' : 'Stickers&Labels' }}
                            </a></li>
                            <li><a href="{{ url('Products?category_id=' . base64_encode('5')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Cadeaux&Décoration' : 'Gifts&Décor' }}
                            </a></li>
                            <li><a href="{{ url('Products?category_id=' . base64_encode('7')) }}" class="sitemap-link">
                                <i class="fas fa-chevron-right me-2"></i>
                                {{ $language_name == 'french' ? 'Vêtements' : 'Apparel' }}
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* .sitemap-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 25px;
    height: 100%;
    border: 1px solid #e9ecef;
} */

.sitemap-heading {
    color: #2c3e50;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
    border-bottom: 2px solid #ff6b35;
    padding-bottom: 10px;
}

.sitemap-links {
    max-height: 500px;
    overflow: hidden;
}

.link-group {
    margin-bottom: 25px;
}

.link-group h3 {
    color: #495057;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 15px;
    padding-left: 10px;
    border-left: 3px solid #ff6b35;
}

.sitemap-link {
    color: #6c757d;
    text-decoration: none;
    display: block;
    padding: 8px 12px;
    border-radius: 5px;
    transition: all 0.3s ease;
    margin-bottom: 5px;
}

.sitemap-link:hover {
    color: #ff6b35;
    background-color: rgba(255, 107, 53, 0.1);
    transform: translateX(5px);
}

.sitemap-link i {
    color: #ff6b35;
    font-size: 0.8rem;
}

.sub-link {
    font-size: 0.9rem;
    opacity: 0.8;
}

.sub-link:hover {
    opacity: 1;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px;
    border-radius: 15px;
    margin-bottom: 40px;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.lead {
    font-size: 1.2rem;
    opacity: 0.9;
}

.sitemap-footer {
    border-top: 1px solid #e9ecef;
    padding-top: 30px;
    margin-top: 40px;
}

@media (max-width: 768px) {
    .sitemap-section {
        margin-bottom: 20px;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .sitemap-heading {
        font-size: 1.3rem;
    }
}
</style>
@endsection
