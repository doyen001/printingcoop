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

body {
    font-family: 'Poppins', sans-serif;
    background: var(--light-gray);
}

.contact-section {
    padding: 4rem 2rem;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
}

.contact-section-inner {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-light);
    transition: var(--transition);
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    padding: 0;
    overflow: hidden;
}

.contact-form-wrapper {
    flex: 1;
    min-width: 400px;
    padding: 3rem;
}

.map-wrapper {
    flex: 1;
    min-width: 400px;
    position: relative;
}

.contact-row {
    margin-bottom: 2rem;
    font-size: 1.1rem;
    color: var(--text-color);
    line-height: 1.7;
}

.contact-form h1 {
    color: #183e73;
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
    text-align: left;
}

.contact-form h1::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--secondary-color);
    border-radius: 2px;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-col {
    flex: 1 1 calc(50% - 0.75rem);
    min-width: 250px;
}

.form-col-full {
    flex: 1 1 100%;
}

label {
    display: block;
    margin-bottom: 0.75rem;
    font-weight: 500;
    color: #183e73;
    font-size: 0.95rem;
}

label .required {
    color: var(--secondary-color);
    margin-left: 4px;
}

input,
textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #183e73;
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
    background: #fff;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03);
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #183e73;
    box-shadow: 0 0 0 4px rgba(255,107,0,0.1);
}

input:hover,
textarea:hover {
    border-color: #183e73;
}

textarea {
    resize: vertical;
    min-height: 150px;
}

.captcha-container {
    margin: 1.5rem 0;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
}

.g-recaptcha {
    margin-bottom: 0.5rem;
}

#recaptcha-error {
    color: var(--error-color);
    font-size: 0.9rem;
    display: none;
}

#recaptcha-error.show {
    display: block;
}

.submit-container {
    margin-top: 1rem;
    text-align: left;
}

.button {
    background: var(--secondary-color);
    color: white;
    padding: 1rem 2.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    min-width: 180px;
}

.button:not([disabled]):hover {
    background: #e65c00;
    transform: translateY(-2px);
}

.button[disabled] {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

#contact-us-message {
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-radius: 5px;
    text-align: center;
    display: none;
}

#contact-us-message.success {
    background: #d4edda;
    color: var(--success-color);
    border: 1px solid #c3e6cb;
}

#contact-us-message.error {
    background: #f8d7da;
    color: var(--error-color);
    border: 1px solid #f5c6cb;
}

.contact_us_map {
    width: 100%;
    height: 100% !important;
    min-height: 400px;
    border: none;
    display: block;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .contact-section {
        padding: 2rem 1rem;
    }

    .contact-form-wrapper,
    .map-wrapper {
        min-width: 100%;
        padding: 1.5rem;
    }

    .form-col {
        flex: 1 1 100%;
    }

    .captcha-container {
        align-items: flex-start;
    }

    .g-recaptcha {
        transform: scale(0.9);
        transform-origin: left;
    }

    .submit-container {
        text-align: left;
    }

    .button {
        width: auto;
        max-width: none;
    }
}
</style>

@section('content')

<div class="contact-section">
    <div class="container">
        <div class="contact-section-inner">
            <div class="contact-form-wrapper">
                <div class="contact-row">
                    @if ($language_name == 'French') 
                        {!! $pageData->description_french !!}
                    @else
                        {!! $pageData->description !!}
                    @endif
                </div>
                <div class="contact-form">
                    <h1>@if ($language_name == 'French') 
                        Contactez-nous
                    @else
                        Contact Us
                    @endif</h1>
                    <form method="post" id="contact-us">
                        <div id="contact-us-message"></div>
                        <div class="form-row">
                            <div class="form-col">
                                <label for="contact-us-name">{!! ($language_name == 'French') ? 'Votre nom' : 'Your Name' !!}<span class="required">*</span></label>
                                <input type="text" name="name" id="contact-us-name" required>
                            </div>
                            <div class="form-col">
                                <label for="contact-us-phone">{!! ($language_name == 'French') ? 'Votre téléphone' : 'Your Phone' !!}<span class="required">*</span></label>
                                <input type="tel" name="phone" id="contact-us-phone" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col-full">
                                <label for="contact-us-email">{!! ($language_name == 'French') ? 'Votre email' : 'Your Email'!!}<span class="required">*</span></label>
                                <input type="email" name="email" id="contact-us-email" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col-full">
                                <label for="contact-us-comment">{!! ($language_name == 'French') ? 'Votre message' : 'Your Message'!!}<span class="required">*</span></label>
                                <textarea name="comment" id="contact-us-comment" rows="5" required></textarea>
                            </div>
                        </div>

                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                        <div class="form-row">
                            <div class="form-col-full">
                                <div class="captcha-container">
                                    <div class="g-recaptcha" id="rcaptcha" data-callback="contactus_recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                    <span id="recaptcha-error" style="display:none; color:red">{!! ($language_name == 'French') ? 'Le captcha est requis.' : 'Captcha is required.'!!}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col-full submit-container">
                                <button type="submit" class="button"
                                disabled="disabled" 
                                id="submit-contact-us-btn" style="border-radius: 8px !important">
                                    {!! ($language_name == 'French') ? 'Envoyer' : 'Submit'!!}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="map-wrapper">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2794.0783157450146!2d-73.64850274942343!3d45.54875007899941!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4cc918c2d6330ef7%3A0xb5938ad134624b6c!2s9166+Rue+Lajeunesse%2C+Montr%C3%A9al%2C+QC+H2M+1S2%2C+Canada!5e0!3m2!1sen!2sin!4v1530706367854" class="contact_us_map" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

@endsection
