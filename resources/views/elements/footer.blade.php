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
                                <a href="{{ url('Products/' . ($category->category_slug ?? '')) }}" class="footer-category-link">
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

    {{-- Back to Top Button --}}
    <button id="back-top" class="back-to-top" style="display: none;">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>


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
