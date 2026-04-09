@extends('elements.app')

<style>
    :root {
        --primary-color: #183e73;
        --secondary-color: #ff6b00;
        --light-gray: #f8f9fa;
        --border-color: #dcdcdc;
        --text-color: #333;
        --error-color: #dc3545;
        --success-color: #28a745;
        --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
        --shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
        --transition: all 0.3s ease-in-out;
    }

    .preferred-customer-section {
        padding: 4rem 2rem;
        background: var(--light-gray);
        min-height: 100vh;
        display: flex;
        align-items: center;
    }

    .preferred-customer-section .container {
        max-width: 1200px !important;
        margin: 0 auto;
        padding: 0;
    }

    .customer-form-container {
        display: flex;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-light);
        overflow: hidden;
    }

    .info-section {
        flex: 1;
        background: #fff;
        color: #183e73;
        padding: 3rem;
        position: relative;
        min-width: 400px;
        display: flex;
        flex-direction: column;
    }

    .info-section::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50px;
        height: 100%;
        background: linear-gradient(to left, rgba(255, 255, 255, 0.1), transparent);
        z-index: 1;
    }

    .info-content {
        padding-right: 2rem;
    }

    .page-description {
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 2rem;
        margin-top: 1rem;
        color: #1b1a19;
        max-height: 1100px;
        overflow-y: auto;
        overflow-x: hidden;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .page-description::-webkit-scrollbar {
        display: none;
    }

    .page-description::-webkit-scrollbar-track {
        display: none;
    }

    .page-description::-webkit-scrollbar-thumb {
        display: none;
    }

    .page-description .contact-section-detail {
        padding-top: 0;
    }

    .page-description .customer-detail-single {
        font-family: 'Calibri', 'Arial', sans-serif;
    }

    .page-description h3 {
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 1.2rem;
        margin-top: 1.5rem;
        color: #183e73;
        font-weight: 700;
    }

    .page-description h3:first-child {
        margin-top: 0;
    }

    .page-description h3 strong {
        font-weight: 700;
        color: inherit;
        display: block;
    }

    .page-description p {
        font-size: 0.875rem;
        line-height: 1.65;
        margin-bottom: 1rem;
        color: #1b1a19;
        text-align: justify;
    }

    .page-description ul {
        margin: 1rem 0 1.5rem 0;
        padding-left: 2rem;
        list-style-type: disc;
    }

    .page-description li {
        font-size: 0.875rem;
        line-height: 1.65;
        margin-bottom: 0.6rem;
        color: #1b1a19;
    }

    .page-description b,
    .page-description strong {
        font-weight: 600;
    }

    .page-description b span[style*="font-size:22.5pt"] {
        font-size: 1.3rem !important;
        font-weight: 700 !important;
        color: #59595c !important;
        display: block;
        margin: 1.5rem 0 1rem 0;
        line-height: 1.4;
    }

    .page-description span[style*="font-size:14.0pt"] {
        font-size: 0.875rem !important;
        line-height: 1.65 !important;
        font-family: 'Calibri Light', 'Calibri', sans-serif !important;
    }

    .page-description span[style*="font-size:10.0pt"] {
        font-size: 0.8rem !important;
        line-height: 1.6 !important;
        font-family: 'Arial', sans-serif !important;
    }

    .benefits-list {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
    }

    .benefits-list {
        display: flex;
        flex-direction: column;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.25rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    /* .benefit-item:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: translateX(5px);
} */

    .benefit-icon {
        width: 50px;
        height: 50px;
        object-fit: contain;
        flex-shrink: 0;
    }

    .benefit-item span {
        color: #333;
        font-size: 0.95rem;
        font-weight: 500;
        line-height: 1.4;
    }

    .contact-info {
        margin-top: 2rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        border-left: 4px solid #f28738;
    }

    .contact-info p {
        margin: 0.5rem 0;
        color: #333;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .contact-info p:first-child {
        font-weight: 500;
    }

    .benefit-item p {
        font-size: 0.75rem;
        margin-bottom: 0;
    }

    .form-section {
        flex: 1;
        padding: 2rem;
        background: #ffffff;
        min-width: 600px;
        border-right: 1px solid #e9ecef;
    }

    .page-title {
        color: #333;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        text-align: left;
    }

    /* .page-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--secondary-color);
    border-radius: 2px;
} */

    .side-bar-title {
        color: #183e73;
        font-size: 2rem;
        font-weight: 600;
        position: relative;
        text-align: left;
        padding-bottom: 1rem;
    }

    /* .side-bar-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--secondary-color);
    border-radius: 2px;
} */

    #signup-msg {
        margin-bottom: 1.5rem;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        display: none;
    }

    #signup-msg.success {
        background: #d4edda;
        color: var(--success-color);
        border: 1px solid #c3e6cb;
    }

    #signup-msg.error {
        background: #f8d7da;
        color: var(--error-color);
        border: 1px solid #f5c6cb;
    }

    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .form-field {
        margin-bottom: 0.5rem;
    }

    .form-field.full-width {
        width: 100%;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .preferred-customer-section input,
    select,
    textarea {
        width: 100%;
        padding: 0.5rem .75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.95rem;
        transition: border-color 0.2s ease;
        background: white;
    }

    .preferred-customer-section input:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #f58634;
    }

    .button-submit {
        background: #f58634;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        width: 100%;
        margin-top: 0.5rem;
    }

    .button-submit:hover {
        background: #db762eff;
    }

    @media (max-width: 1200px) {
        .customer-form-container {
            flex-direction: column;
        }

        .info-section,
        .form-section {
            min-width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .preferred-customer-section {
            padding: 2rem 1rem;
        }

        .info-section,
        .form-section {
            padding: 2rem;
        }
    }

    .universal-spacing {
        padding-top: 20px !important;
    }

    /* Captcha */
    .captcha-section {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        margin-top: 1rem;
    }

    .captcha-section label {
        color: #333;
        margin-bottom: 1rem;
    }

    .captcha-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .captcha-image {
        flex: 1;
    }

    .captcha-refresh {
        margin-left: 0.5rem;
    }

    .captcha-refresh a {
        color: var(--secondary-color);
        text-decoration: none;
        font-size: 0.9rem;
    }

    .captcha-refresh a:hover {
        text-decoration: underline;
    }

    .captcha-input input {
        margin-top: 0.5rem;
    }
</style>

@section('content')

    <div class="preferred-customer-section">
        <div class="container">
            <div class="customer-form-container">
                <div class="form-section">
                    <h1 class="page-title">
                        {!! $language_name == 'french' ? 'Devenez un membre Privilège' : 'Become a Preferred Customer' !!}
                    </h1>

                    <div id="signup-msg"></div>

                    <form action="" method="post" id="Preferred-Customer">
                        <div class="form-grid">
                            <div class="form-field">
                                <input class="input-style" type="text" id="fname" name="fname"
                                    placeholder="{!! $language_name == 'french' ? 'Prénom' : 'First Name' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="text" id="lname" name="lname"
                                    placeholder="{!! $language_name == 'french' ? 'Nom de famille' : 'Last Name' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="text" id="company_name" name="company_name"
                                    placeholder="{!! $language_name == 'french' ? 'Nom de la compagnie' : 'Company Name' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="email" id="email" name="email" placeholder="Email"
                                    required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="password" id="signup-password" name="password"
                                    placeholder="{!! $language_name == 'french' ? 'Mot de passe' : 'Password' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="password" id="confirm-password" name="confirm_password"
                                    placeholder="{!! $language_name == 'french' ? 'Confirmer le mot de passe' : 'Confirm Password' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="text" id="responsible_name" name="responsible_name"
                                    placeholder="{!! $language_name == 'french' ? 'Nom du responsable' : 'Responsible Name' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="text" id="cp" name="cp" placeholder="CP"
                                    required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="text" id="active_area" name="active_area"
                                    placeholder="{!! $language_name == 'french' ? 'Zone active' : 'Active Area' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="text" id="address" name="address"
                                    placeholder="{!! $language_name == 'french' ? 'Adresse' : 'Address' !!}" required>
                            </div>

                            <div class="form-field">
                                <input class="input-style" type="tel" id="mobile" name="mobile"
                                    placeholder="{!! $language_name == 'french' ? 'Numéro de téléphone' : 'Phone Number' !!}" required>
                            </div>

                            <div class="form-field">
                                <select id="country" name="country" onchange="getState($(this).val())" class="crs-country"
                                    required>
                                    <option value="">{!! $language_name == 'french' ? '-- Choisissez le pays --' : '-- Select Country --' !!}</option>
                                    @foreach ($countries as $country)
                                        @php
                                            $selected = '';
                                            $post_country = isset($postData->country) ? $postData->country : '';
                                            if ($country->id == $post_country) {
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{ $country->id }}" {{ $selected }}>{{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-field">
                                <select id="stateiD" name="region" required>
                                    <option value="">{!! $language_name == 'french' ? '-- Sélectionnez l\'état --' : '-- Select State --' !!}</option>
                                </select>
                            </div>

                            <div style="display: grid; grid-template-columns: 6fr 4fr; gap: 10px;">
                                <div class="form-field">
                                    <input class="input-style" type="text" id="city" name="city"
                                        placeholder="{!! $language_name == 'french' ? 'Ville' : 'City' !!}" required>
                                </div>

                                <div class="form-field">
                                    <input class="input-style" type="text" id="zip_code" name="zip_code"
                                        placeholder="{!! $language_name == 'french' ? 'Code postal' : 'Postal Code' !!}" required>
                                </div>
                            </div>

                            <div class="form-field full-width">
                                <textarea id="request" name="request" placeholder="{!! $language_name == 'french' ? 'Demande' : 'Request' !!}" required></textarea>
                            </div>

                            <div class="captcha-section">
                                <label>{!! $language_name == 'french' ? 'Entrez le mot que vous voyez ci-dessous:' : 'Enter the word you see below:' !!}</label>
                                <div class="captcha-row">
                                    <div class="captcha-image">
                                        {!! $cap->image ?? '' !!}
                                    </div>
                                    <div class="captcha-refresh">
                                        <a href="javascript:void(0)" onClick="locdCapcha()">
                                            <img src="{{ url('assets/images/refresh.png') }}"
                                                style="width:15px;height:15px;">
                                        </a>
                                    </div>
                                </div>
                                <div class="captcha-input">
                                    <input type="text" name="captcha" id="captcha-input" value="" required />
                                </div>
                            </div>

                            <div class="submit-container">
                                <button class="button-submit" type="submit">
                                    {!! $language_name == 'french' ? 'Devenir membre' : 'Become a Member' !!}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="info-section">
                    <div class="info-content">
                        <h2 class="page-title">
                            {!! $language_name == 'french' ? 'Avantages des Membres Privilèges' : 'Preferred Customer Benefits' !!}
                        </h2>
                        <div class="page-description">
                            @if ($language_name == 'french')
                                <div class="benefits-list">
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/wholesale_icon.svg" alt="Prix de Gros"
                                            class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Prix de Gros</span>
                                            <p>Accès aux économies avec des prix exclusifs pour les courtiers.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/offers_pog_icon.svg" alt="Offres Exclusives"
                                            class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Offres Exclusives</span>
                                            <p>Remises spéciales, points de récompense, & accès anticipé aux produits.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/order_pickup_icon.svg"
                                            alt="Retrait de Commande" class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Retrait de Commande</span>
                                            <p>Économisez de l'argent en retirant les commandes de nos bureaux en CA, TX, et
                                                KY.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/quotes_icon.svg" alt="Devis Personnalisés"
                                            class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Devis Personnalisés</span>
                                            <p>Créez un produit personnalisé non offert sur le site.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/blind_drop_shipping_icon.svg"
                                            alt="Expédition Discrète" class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Expédition Discrète</span>
                                            <p>Nous expédions directement à vos clients dans un emballage non marqué</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="contact-info">
                                    <p>Pour plus d'informations ou de questions, appelez-nous directement au
                                        1-888-384-8043.</p>
                                    <p>Veuillez nous permettre jusqu'à 1-2 jours ouvrables pour répondre à votre e-mail.
                                    </p>
                                </div>
                            @else
                                <div class="benefits-list">
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/wholesale_icon.svg" alt="Discounted Prices"
                                            class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Wholesale Pricing</span>
                                            <p>Access to savings with pricing exclusive to brokers.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/offers_pog_icon.svg" alt="Free Shipping"
                                            class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Exclusive Offers</span>
                                            <p>Special discounts, rewards points, & early access to products.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/order_pickup_icon.svg"
                                            alt="Priority Support" class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Order Pickup</span>
                                            <p>Save money by picking up orders from our offices in CA, TX, and KY.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/quotes_icon.svg" alt="Exclusive Access"
                                            class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Custom Quotes</span>
                                            <p>Create a custom product not offered on the site.</p>
                                        </div>
                                    </div>
                                    <div class="benefit-item">
                                        <img src="/uploads/preferred_customer/blind_drop_shipping_icon.svg"
                                            alt="Special Discounts" class="benefit-icon">
                                        <div style="display: flex; justify-content: center; flex-direction: column">
                                            <span>Blind Drop Shipping</span>
                                            <p>We ship non-branded packaging directly to your customers</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="contact-info">
                                    <p>For more info or questions, call us directly at
                                        1-888-384-8043.</p>
                                    <p>Please allow us up to 1-2 business days to respond to
                                        your email.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Captcha refresh function
        function locdCapcha() {
            $.ajax({
                url: "{{ url('Products/refreshCaptcha') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data && data.captcha) {
                        $('.captcha-image').html(data.captcha);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error refreshing captcha:', error);
                }
            });
        }

        function getState(country_id) {
            $('#stateiD').val('');
            $('#stateiD').html('<option value="">Loading...</option>');
            if (country_id != '') {
                var url = '{{ url('/getStateDropDownListByAjax') }}/' + country_id;
                $.ajax({
                    type: "GET",
                    url: url,
                    contentType: "html",
                    success: function(data) {
                        $('#stateiD').html(data);
                    },
                    error: function() {
                        $('#stateiD').html('<option value="">Error loading states</option>');
                    }
                });
            } else {
                $('#stateiD').html('<option value="">{!! $language_name == 'french' ? "-- Sélectionnez l\'état --" : '-- Select State --' !!}</option>');
            }
        }

        // Password confirmation validation
        $(document).ready(function() {
            $('#Preferred-Customer').on('submit', function(e) {
                var password = $('#signup-password').val();
                var confirmPassword = $('#confirm-password').val();

                if (password !== confirmPassword) {
                    e.preventDefault();

                    // Show error message
                    var errorMsg = '{!! $language_name == 'french' ? 'Les mots de passe ne correspondent pas.' : 'Passwords do not match.' !!}';
                    $('#signup-msg').html('<div class="alert alert-danger">' + errorMsg + '</div>').show();

                    // Clear confirm password field
                    $('#confirm-password').val('');
                    $('#confirm-password').focus();

                    return false;
                }

                // Captcha validation
                var captchaVal = $('#captcha-input').val().trim();
                if (captchaVal === '') {
                    e.preventDefault();
                    var captchaMsg = '{!! $language_name == 'french' ? 'Veuillez entrer le code anti-spam.' : 'Please enter the anti-spam code.' !!}';
                    $('#signup-msg').html('<div class="alert alert-danger">' + captchaMsg + '</div>')
                .show();
                    $('#captcha-input').focus();
                    return false;
                }
            });

            // Real-time validation
            $('#confirm-password').on('input', function() {
                var password = $('#signup-password').val();
                var confirmPassword = $(this).val();

                if (confirmPassword.length > 0 && password !== confirmPassword) {
                    $(this).css('border-color', '#dc3545');
                    var errorMsg = '{!! $language_name == 'french' ? 'Les mots de passe ne correspondent pas.' : 'Passwords do not match.' !!}';
                    $('#signup-msg').html('<div class="alert alert-danger">' + errorMsg + '</div>').show();
                } else {
                    $(this).css('border-color', '#28a745');
                    $('#signup-msg').html('').hide();
                }
            });
        });
    </script>
@endpush
