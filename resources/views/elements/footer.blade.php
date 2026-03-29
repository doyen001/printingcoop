{{-- CI: application/views/templates/_parts/master_footer_view.php --}}

{{-- Hidden inputs for JavaScript --}}
<input type="hidden" id="lang_name" value="{{ $language_name }}">
<input type="hidden" id="site_url_foot" value="{{ url('/') }}/">
<input type="hidden" id="user_id_foot" value="{{ $loginId ?? '' }}">
<input type="hidden" id="user_id_covid_msg" value="{{ $showCOVID19MSG ?? 'null' }}">

@php
    // Only show newsletter on home page
    $currentRoute = request()->route()->getName();
    $showNewsletter = $currentRoute === 'home' || request()->is('/');
@endphp

@if($showNewsletter)
{{-- Newsletter Section --}}
<div class="newsletter-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h3 class="newsletter-title">
                    {{ $language_name == 'french' ? 'Bulletin' : 'Newsletter' }}
                </h3>
                <p class="newsletter-subtitle">
                    {{ $language_name == 'french' ? 'Restez informé de nos dernières offres et mises à jour' : 'Stay informed about our latest offers and updates' }}
                </p>
                <div class="newsletter-form">
                    <form id="email-subscribe" method="POST" class="subscription-form">
                        @csrf
                        <div id="subscribe-message"></div>
                        <div class="input-group">
                            <input type="email" 
                                   class="form-control" 
                                   name="email"
                                   id="subscribe-email"
                                   placeholder="{{ $language_name == 'french' ? 'Entrez votre adresse e-mail' : 'Enter your email address' }}" 
                                   required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    {{ $language_name == 'french' ? 'S\'abonner' : 'Subscribe' }}
                                    <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Main Footer --}}
<footer class="main-footer pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            {{-- Navigation Links --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading mb-4">{{ $language_name == 'french' ? 'Navigation' : 'Navigation' }}</h5>
                <ul class="footer-links list-unstyled">
                    <li><a href="{{ url('/') }}">{{ $language_name == 'french' ? 'Accueil' : 'Home' }}</a></li>
                    <li><a href="{{ url('Products') }}">{{ $language_name == 'french' ? 'Des produits' : 'Products' }}</a></li>
                    @foreach($pages as $page)
                        @php
                            $slug = $page['slug'];
                            $url = url('Page/' . $slug);
                        @endphp
                        <li>
                            <a href="{{ $url }}">
                                {{ $language_name == 'french' ? ucfirst($page['title_french']) : ucfirst($page['title']) }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Categories --}}
            <div class="col-lg-6 col-md-6">
                <h5 class="footer-heading mb-4">{{ $language_name == 'french' ? 'Catégories' : 'Categories' }}</h5>
                @if(in_array($website_store_id, [1, 3]))
                    <div class="row g-3">
                        @foreach($footerCategory as $category)
                            <div class="col-sm-6">
                                <a href="{{ url('Products?category_id=' . (base64_encode($category->id) ?? '')) }}" class="footer-category-link">
                                    {{ $language_name == 'french' ? ucfirst($category->name_french) : ucfirst($category->name) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6">
                <h5 class="footer-heading">{{ $language_name == 'french' ? 'Contact' : 'Get in Touch' }}</h5>
                <div class="footer-contact">
                    {{-- Main Office --}}
                    <div class="contact-location">
                        <div class="location-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="location-details">
                            <h6>Printing.coop</h6>
                            <p>9166 Rue Lajeunesse<br>
                            Montréal Québec H2M 1S2</p>
                            <a href="mailto:info@printing.coop">info@printing.coop</a><br>
                            <a href="tel:514-544-8043">514-544-8043</a><br>
                            <a href="tel:1-888-384-8043" class="toll-free">1-888-384-8043</a>
                        </div>
                    </div>

                    {{-- Papineau Office --}}
                    <div class="contact-location">
                        <div class="location-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="location-details">
                            <h6>Printing.coop Papineau</h6>
                            <p>4506 Avenue Papineau<br>
                            Montréal Québec H2H 1V1</p>
                            <a href="mailto:papineau@printing.coop">papineau@printing.coop</a><br>
                            <a href="tel:514-439-9255">514-439-9255</a>
                        </div>
                    </div>

                    {{-- Business Hours --}}
                    <div class="business-hours">
                        <div class="hours-icon">
                            <i class="far fa-clock"></i>
                        </div>
                        <div class="hours-details">
                            {{ $language_name == 'french' ? 'Lun-Ven: 9h00-17h00' : 'Mon-Fri: 9:00-17:00' }}
                        </div>
                    </div>
                </div>

                {{-- Social Media Icons --}}
                <div class="social-icons-wrapper">
                    <h6 class="social-title">{{ $language_name == 'french' ? 'Suivez-nous' : 'Follow Us' }}</h6>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/imprimeriecoop/" class="social-icon" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/printing.coop/" class="social-icon" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://twitter.com/PrintingCoop" class="social-icon" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/printingcoop" class="social-icon" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://pinterest.com/imprimeurcoop" class="social-icon" title="Pinterest">
                            <i class="fab fa-pinterest-p"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UC0UzU22tH8SRUaTuLeNGc_g" class="social-icon" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Footer --}}
        <div class="bottom-footer mt-5 pt-4 border-top">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="copyright mb-3 mb-md-0">
                        <span>&copy; {{ date('Y') }} 
                            @if($language_name == 'french')
                                {{ $configurations['copy_right_french'] ?? 'imprimerie co-op - Tous droits réservés' }}
                            @else
                                {{ $configurations['copy_right'] ?? 'printing co-op - All Rights Reserved' }}
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="payment-methods">
                        <img src="{{ url('assets/images/payment_method_logo.png') }}" alt="Payment Methods" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Legal Links Bar --}}
    <div class="footer-legal-bar">
        <div class="container">
            <div class="legal-links">
                <a href="{{ url('Pages/privacyPolicy') }}" class="legal-link">
                    {{ $language_name == 'french' ? 'Politique de confidentialité' : 'Privacy Policy' }}
                </a>
                <span class="legal-divider">|</span>
                <a href="{{ url('Pages/termsOfUse') }}" class="legal-link">
                    {{ $language_name == 'french' ? "Conditions d'utilisation" : 'Terms of Use' }}
                </a>
                <span class="legal-divider">|</span>
                <a href="{{ url('Pages/interestBasedAdvertising') }}" class="legal-link">
                    {{ $language_name == 'french' ? 'Publicité ciblée' : 'Interest-Based Advertising' }}
                </a>
                <span class="legal-divider">|</span>
                <a href="javascript:void(0)" class="legal-link" id="do-not-sell-link">
                    {{ $language_name == 'french' ? 'Ne vendez pas ou ne partagez pas mes informations personnelles' : 'Do Not Sell or Share My Personal Information' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Back to Top Button --}}
    <button id="back-top" class="back-to-top" style="display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

{{-- Cookie Management Styles --}}
<style>
/* Footer Legal Links Bar */
.footer-legal-bar {
    background: #3a3a3a;
    padding: 1rem 0;
    border-top: 1px solid #4a4a4a;
}

.legal-links {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 0;
}

.legal-link {
    color: #ccc;
    text-decoration: none;
    font-size: 0.85rem;
    padding: 0.25rem 0.75rem;
    transition: color 0.2s ease;
}

.legal-link:hover {
    color: #fff;
    text-decoration: underline;
}

.legal-divider {
    color: #888;
    font-size: 0.85rem;
}

/* Cookie Consent Banner */
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #2c2c2c;
    color: #e0e0e0;
    z-index: 10000;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.3);
    border-top: 3px solid #007bff;
}

.cookie-consent-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1.25rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.cookie-consent-text p {
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #ccc;
}

.cookie-consent-actions {
    display: flex;
    gap: 0.75rem;
    flex-shrink: 0;
}

.cookie-btn {
    padding: 0.5rem 1.25rem;
    border: none;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
}

.cookie-btn-accept {
    background: #007bff;
    color: #fff;
}

.cookie-btn-accept:hover {
    background: #0056b3;
}

.cookie-btn-reject {
    background: #555;
    color: #fff;
}

.cookie-btn-reject:hover {
    background: #444;
}

.cookie-btn-customize {
    background: transparent;
    color: #007bff;
    border: 1px solid #007bff;
}

.cookie-btn-customize:hover {
    background: #007bff;
    color: #fff;
}

/* Cookie Modal Overlay */
.cookie-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    z-index: 10001;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cookie-modal {
    background: #fff;
    border-radius: 8px;
    max-width: 550px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,0.3);
}

.cookie-modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.cookie-modal-header h3 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 600;
    color: #222;
}

.cookie-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: #666;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.cookie-modal-close:hover {
    color: #333;
}

.cookie-modal-body {
    padding: 1.5rem;
}

.cookie-modal-body > p {
    color: #555;
    font-size: 0.9rem;
    line-height: 1.6;
    margin-bottom: 1.25rem;
}

.cookie-category {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 0.75rem;
}

.cookie-category-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.cookie-category-header label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    margin: 0;
}

.cookie-category-header label strong {
    font-size: 0.9rem;
    color: #333;
}

.cookie-category p {
    margin: 0.5rem 0 0;
    font-size: 0.8rem;
    color: #666;
    line-height: 1.5;
}

.cookie-badge-required {
    background: #e9ecef;
    color: #666;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 3px;
    font-weight: 500;
}

/* Toggle Switch */
.cookie-toggle {
    -webkit-appearance: none;
    appearance: none;
    width: 40px;
    height: 22px;
    background: #ccc;
    border-radius: 11px;
    position: relative;
    cursor: pointer;
    outline: none;
    transition: background 0.2s ease;
}

.cookie-toggle::before {
    content: '';
    position: absolute;
    width: 18px;
    height: 18px;
    background: #fff;
    border-radius: 50%;
    top: 2px;
    left: 2px;
    transition: transform 0.2s ease;
}

.cookie-toggle:checked {
    background: #007bff;
}

.cookie-toggle:checked::before {
    transform: translateX(18px);
}

.cookie-toggle:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.cookie-modal-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
}

.dns-toggle-label {
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 768px) {
    .cookie-consent-inner {
        flex-direction: column;
        padding: 1rem;
        gap: 1rem;
    }
    .cookie-consent-actions {
        width: 100%;
        justify-content: center;
    }
    .legal-links {
        gap: 0.25rem;
    }
    .legal-link {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .legal-divider {
        font-size: 0.75rem;
    }
}
</style>

{{-- Cookie Consent Banner --}}
<div id="cookie-consent-banner" class="cookie-consent-banner" style="display: block;">
    <div class="cookie-consent-inner">
        <div class="cookie-consent-text">
            <p>
                @if($language_name == 'french')
                    Nous utilisons des cookies pour améliorer votre expérience sur notre site. En continuant à naviguer, vous acceptez notre utilisation des cookies.
                @else
                    We use cookies to enhance your experience on our site. By continuing to browse, you accept our use of cookies.
                @endif
            </p>
        </div>
        <div class="cookie-consent-actions">
            <button id="cookie-accept-all" class="cookie-btn cookie-btn-accept">
                {{ $language_name == 'french' ? 'Accepter tout' : 'Accept All' }}
            </button>
            <button id="cookie-reject-all" class="cookie-btn cookie-btn-reject">
                {{ $language_name == 'french' ? 'Refuser tout' : 'Reject All' }}
            </button>
            <button id="cookie-customize" class="cookie-btn cookie-btn-customize">
                {{ $language_name == 'french' ? 'Personnaliser' : 'Customize' }}
            </button>
        </div>
    </div>
</div>

{{-- Cookie Preferences Modal --}}
<div id="cookie-preferences-modal" class="cookie-modal-overlay" style="display: none;">
    <div class="cookie-modal">
        <div class="cookie-modal-header">
            <h3>{{ $language_name == 'french' ? 'Préférences de cookies' : 'Cookie Preferences' }}</h3>
            <button class="cookie-modal-close" id="cookie-modal-close">&times;</button>
        </div>
        <div class="cookie-modal-body">
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label>
                        <input type="checkbox" id="cookie-essential" checked disabled>
                        <strong>{{ $language_name == 'french' ? 'Cookies essentiels' : 'Essential Cookies' }}</strong>
                    </label>
                    <span class="cookie-badge cookie-badge-required">{{ $language_name == 'french' ? 'Requis' : 'Required' }}</span>
                </div>
                <p>{{ $language_name == 'french' ? 'Ces cookies sont nécessaires au fonctionnement du site.' : 'These cookies are necessary for the website to function.' }}</p>
            </div>
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label>
                        <input type="checkbox" id="cookie-analytics" class="cookie-toggle">
                        <strong>{{ $language_name == 'french' ? 'Cookies analytiques' : 'Analytics Cookies' }}</strong>
                    </label>
                </div>
                <p>{{ $language_name == 'french' ? "Nous aident à comprendre comment les visiteurs utilisent le site." : 'Help us understand how visitors use the site.' }}</p>
            </div>
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label>
                        <input type="checkbox" id="cookie-marketing" class="cookie-toggle">
                        <strong>{{ $language_name == 'french' ? 'Cookies marketing' : 'Marketing Cookies' }}</strong>
                    </label>
                </div>
                <p>{{ $language_name == 'french' ? 'Utilisés pour la publicité ciblée.' : 'Used for interest-based advertising.' }}</p>
            </div>
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label>
                        <input type="checkbox" id="cookie-personalization" class="cookie-toggle">
                        <strong>{{ $language_name == 'french' ? 'Cookies de personnalisation' : 'Personalization Cookies' }}</strong>
                    </label>
                </div>
                <p>{{ $language_name == 'french' ? 'Permettent de personnaliser votre expérience.' : 'Allow us to personalize your experience.' }}</p>
            </div>
        </div>
        <div class="cookie-modal-footer">
            <button id="cookie-save-preferences" class="cookie-btn cookie-btn-accept">
                {{ $language_name == 'french' ? 'Enregistrer les préférences' : 'Save Preferences' }}
            </button>
        </div>
    </div>
</div>

{{-- Manage Cookie Preferences Modal (Do Not Sell) --}}
<div id="do-not-sell-modal" class="cookie-modal-overlay" style="display: none;">
    <div class="cookie-modal">
        <div class="cookie-modal-header">
            <h3>{{ $language_name == 'french' ? 'Gérer les préférences de cookies' : 'Manage Cookie Preferences' }}</h3>
            <button class="cookie-modal-close" id="dns-modal-close">&times;</button>
        </div>
        <div class="cookie-modal-body">
            <h4 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 0.75rem; color: #333;">
                {{ $language_name == 'french' ? 'Utilisation des cookies:' : 'Cookie Usage:' }}
            </h4>
            <p style="margin-bottom: 1rem;">
                @if($language_name == 'french')
                    Les cookies et technologies similaires collectent certaines informations sur la façon dont vous utilisez notre site Web. Ces cookies permettent à notre site Web d'offrir des fonctions supplémentaires et des paramètres personnels pour créer une meilleure expérience utilisateur. Ils peuvent être définis par nous ou par des partenaires tiers.
                @else
                    Cookies and similar technologies collect certain information about how you use our website. These cookies enable our website to offer additional functions and personal settings to create a better user experience. They can be set by us or by third-party partners.
                @endif
            </p>
            <p style="margin-bottom: 1rem;">
                @if($language_name == 'french')
                    Les résidents de certains États ont le droit de refuser les "ventes", le "partage" ou le traitement d'informations personnelles pour la publicité ciblée. Vous pouvez vous désinscrire en déplaçant le bouton à bascule pour les cookies publicitaires ci-dessous vers la gauche.
                @else
                    Residents of certain states have the right to opt out of "sales," "sharing," or processing of personal information for targeted advertising. You can opt out by moving the toggle switch for Advertising cookies below to the left.
                @endif
            </p>
            <p style="margin-bottom: 1rem;">
                @if($language_name == 'french')
                    Vous pouvez également vous désinscrire des cookies d'analyse en utilisant le bouton ci-dessous.
                @else
                    You can also opt out of analytics cookies using the toggle below.
                @endif
            </p>
            <p style="margin-bottom: 1rem;">
                @if($language_name == 'french')
                    Vos choix de désinscription s'appliqueront au navigateur et à l'appareil que vous utilisez lorsque vous vous désinscrivez. Vous devrez vous désinscrire séparément sur d'autres navigateurs et appareils que vous utilisez.
                @else
                    Your opt-out choices will apply to the browser and device you are using when you opt out. You will need to opt out separately on other browsers and devices that you use.
                @endif
            </p>
            <p style="margin-bottom: 1.5rem; font-size: 0.9rem;">
                @if($language_name == 'french')
                    Certains cookies sont nécessaires et resteront pour fournir des fonctionnalités de base lorsque vous parcourez le site Web Printing Coop, même si vous vous désabonnez d'autres cookies. Ces cookies sont essentiels pour que le site Printing Coop fonctionne et soit sûr.
                @else
                    Certain cookies are needed and will remain to provide basic functionalities as you browse the Printing Coop website, even if you opt out of other cookies. These cookies are essential for making the Printing Coop site work and making it safe.
                @endif
            </p>

            {{-- Advertising Cookies --}}
            <div class="cookie-category" style="margin-bottom: 0.75rem;">
                <div class="cookie-category-header">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin: 0; width: 100%;">
                        <span style="flex: 1;">
                            <strong style="font-size: 0.95rem; color: #333;">{{ $language_name == 'french' ? 'Cookies publicitaires' : 'Advertising Cookies' }}</strong>
                        </span>
                        <input type="checkbox" id="dns-advertising-toggle" class="cookie-toggle" checked>
                    </label>
                </div>
            </div>

            {{-- Analytics Cookies --}}
            <div class="cookie-category">
                <div class="cookie-category-header">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; margin: 0; width: 100%;">
                        <span style="flex: 1;">
                            <strong style="font-size: 0.95rem; color: #333;">{{ $language_name == 'french' ? 'Cookies d\'analyse' : 'Analytics Cookies' }}</strong>
                        </span>
                        <input type="checkbox" id="dns-analytics-toggle" class="cookie-toggle" checked>
                    </label>
                </div>
            </div>
        </div>
        <div class="cookie-modal-footer" style="display: flex; gap: 1rem; justify-content: space-between;">
            <button id="dns-accept-all" class="cookie-btn cookie-btn-reject" style="background: #555; flex: 1;">
                {{ $language_name == 'french' ? 'Tout accepter' : 'Accept all' }}
            </button>
            <button id="dns-confirm-selection" class="cookie-btn cookie-btn-accept" style="flex: 1;">
                {{ $language_name == 'french' ? 'Confirmer la sélection actuelle' : 'Confirm Current Selection' }}
            </button>
        </div>
    </div>
</div>


{{-- Message Modal --}}
@include('elements.msg-modal')

{{-- Newsletter Subscription Script --}}
<script>
(function() {
    // Newsletter subscription AJAX
    const subscribeForm = document.getElementById('email-subscribe');
    
    if (subscribeForm) {
        subscribeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageDiv = document.getElementById('subscribe-message');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '{{ $language_name == "french" ? "Envoi..." : "Sending..." }}';
            
            fetch('{{ url("Products/emailSubscribe") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.msg + '</div>';
                    subscribeForm.reset();
                } else {
                    let errorMsg = data.msg || '{{ $language_name == "french" ? "Une erreur s\'est produite" : "An error occurred" }}';
                    if (data.errors) {
                        errorMsg = Object.values(data.errors).join('<br>');
                    }
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + errorMsg + '</div>';
                }
                
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                // Clear message after 5 seconds
                setTimeout(function() {
                    messageDiv.innerHTML = '';
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.innerHTML = '<div class="alert alert-danger">{{ $language_name == "french" ? "Une erreur s\'est produite" : "An error occurred" }}</div>';
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    }
    
    // Back to top functionality
    const backToTopBtn = document.getElementById('back-top');
    
    if (backToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 100) {
                backToTopBtn.classList.add('show');
                backToTopBtn.style.display = 'flex';
            } else {
                backToTopBtn.classList.remove('show');
                backToTopBtn.style.display = 'none';
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Header scroll effect
    window.addEventListener('scroll', function() {
        const header = document.querySelector('.main-header');
        if (header) {
            if (window.pageYOffset > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });
})();
</script>

{{-- Cookie Management Script --}}
<script>
(function() {
    // Cookie helper functions
    function setCookie(name, value, days) {
        var expires = '';
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/; SameSite=Lax';
    }

    function getCookie(name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length));
            }
        }
        return null;
    }

    function deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    // Elements
    var banner = document.getElementById('cookie-consent-banner');
    var acceptBtn = document.getElementById('cookie-accept-all');
    var rejectBtn = document.getElementById('cookie-reject-all');
    var customizeBtn = document.getElementById('cookie-customize');
    var preferencesModal = document.getElementById('cookie-preferences-modal');
    var preferencesCloseBtn = document.getElementById('cookie-modal-close');
    var savePreferencesBtn = document.getElementById('cookie-save-preferences');
    var doNotSellLink = document.getElementById('do-not-sell-link');
    var doNotSellModal = document.getElementById('do-not-sell-modal');
    var dnsCloseBtn = document.getElementById('dns-modal-close');
    var dnsAcceptAllBtn = document.getElementById('dns-accept-all');
    var dnsConfirmSelectionBtn = document.getElementById('dns-confirm-selection');
    var dnsAdvertisingToggle = document.getElementById('dns-advertising-toggle');
    var dnsAnalyticsToggle = document.getElementById('dns-analytics-toggle');
    var analyticsToggle = document.getElementById('cookie-analytics');
    var marketingToggle = document.getElementById('cookie-marketing');
    var personalizationToggle = document.getElementById('cookie-personalization');

    // Check if consent already given
    function checkConsent() {
        var consent = getCookie('cookie_consent');
        if (consent) {
            banner.style.display = 'none';
            loadPreferences();
        } else {
            banner.style.display = 'block';
        }
    }

    // Load saved preferences into toggles
    function loadPreferences() {
        var prefs = getCookie('cookie_preferences');
        if (prefs) {
            try {
                var parsed = JSON.parse(prefs);
                if (analyticsToggle) analyticsToggle.checked = parsed.analytics || false;
                if (marketingToggle) marketingToggle.checked = parsed.marketing || false;
                if (personalizationToggle) personalizationToggle.checked = parsed.personalization || false;
            } catch(e) {}
        }
        var dns = getCookie('do_not_sell');
        if (doNotSellToggle && dns === 'true') {
            doNotSellToggle.checked = true;
        }
    }

    // Save preferences to cookies
    function savePreferences(analytics, marketing, personalization) {
        var prefs = JSON.stringify({
            analytics: analytics,
            marketing: marketing,
            personalization: personalization
        });
        setCookie('cookie_consent', 'true', 365);
        setCookie('cookie_preferences', prefs, 365);
        banner.style.display = 'none';
    }

    // Accept All
    if (acceptBtn) {
        acceptBtn.addEventListener('click', function() {
            savePreferences(true, true, true);
        });
    }

    // Reject All
    if (rejectBtn) {
        rejectBtn.addEventListener('click', function() {
            savePreferences(false, false, false);
            setCookie('do_not_sell', 'true', 365);
        });
    }

    // Customize - open Manage Cookie Preferences modal (do-not-sell-modal)
    if (customizeBtn) {
        customizeBtn.addEventListener('click', function() {
            // Load current preferences into the DNS modal toggles
            var prefs = getCookie('cookie_preferences');
            if (prefs) {
                try {
                    var parsed = JSON.parse(prefs);
                    if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = parsed.marketing !== false;
                    if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = parsed.analytics !== false;
                } catch(e) {
                    if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = true;
                    if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = true;
                }
            } else {
                if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = true;
                if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = true;
            }
            doNotSellModal.style.display = 'flex';
        });
    }

    // Close preferences modal
    if (preferencesCloseBtn) {
        preferencesCloseBtn.addEventListener('click', function() {
            preferencesModal.style.display = 'none';
        });
    }

    // Save Preferences from modal
    if (savePreferencesBtn) {
        savePreferencesBtn.addEventListener('click', function() {
            var analytics = analyticsToggle ? analyticsToggle.checked : false;
            var marketing = marketingToggle ? marketingToggle.checked : false;
            var personalization = personalizationToggle ? personalizationToggle.checked : false;
            savePreferences(analytics, marketing, personalization);
            preferencesModal.style.display = 'none';
        });
    }

    // Do Not Sell link - open Manage Cookie Preferences modal
    if (doNotSellLink) {
        doNotSellLink.addEventListener('click', function(e) {
            e.preventDefault();
            // Load current preferences
            var prefs = getCookie('cookie_preferences');
            if (prefs) {
                try {
                    var parsed = JSON.parse(prefs);
                    if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = parsed.marketing !== false;
                    if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = parsed.analytics !== false;
                } catch(e) {
                    if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = true;
                    if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = true;
                }
            } else {
                if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = true;
                if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = true;
            }
            doNotSellModal.style.display = 'flex';
        });
    }

    // Close Manage Cookie Preferences modal
    if (dnsCloseBtn) {
        dnsCloseBtn.addEventListener('click', function() {
            doNotSellModal.style.display = 'none';
        });
    }

    // Accept All button in DNS modal
    if (dnsAcceptAllBtn) {
        dnsAcceptAllBtn.addEventListener('click', function() {
            if (dnsAdvertisingToggle) dnsAdvertisingToggle.checked = true;
            if (dnsAnalyticsToggle) dnsAnalyticsToggle.checked = true;
            
            // Save all cookies enabled
            savePreferences(true, true, true);
            setCookie('do_not_sell', 'false', 365);
            doNotSellModal.style.display = 'none';
        });
    }

    // Confirm Current Selection button in DNS modal
    if (dnsConfirmSelectionBtn) {
        dnsConfirmSelectionBtn.addEventListener('click', function() {
            var advertising = dnsAdvertisingToggle ? dnsAdvertisingToggle.checked : false;
            var analytics = dnsAnalyticsToggle ? dnsAnalyticsToggle.checked : false;
            
            // Save preferences based on toggles
            savePreferences(analytics, advertising, true);
            
            // Set do_not_sell based on advertising toggle
            setCookie('do_not_sell', advertising ? 'false' : 'true', 365);
            
            doNotSellModal.style.display = 'none';
        });
    }

    // Close modals when clicking overlay
    if (preferencesModal) {
        preferencesModal.addEventListener('click', function(e) {
            if (e.target === preferencesModal) {
                preferencesModal.style.display = 'none';
            }
        });
    }

    if (doNotSellModal) {
        doNotSellModal.addEventListener('click', function(e) {
            if (e.target === doNotSellModal) {
                doNotSellModal.style.display = 'none';
            }
        });
    }

    // Initialize
    checkConsent();
})();
</script>
