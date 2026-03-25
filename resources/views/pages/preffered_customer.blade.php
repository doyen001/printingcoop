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
    background: #fafafa;
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
    background: linear-gradient(to left, rgba(255,255,255,0.1), transparent);
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

.benefits-list li {
    padding-left: 2rem;
    position: relative;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.9);
}

.benefits-list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--secondary-color);
}

.form-section {
    flex: 1;
    padding: 3rem;
    background: #183e73;
    min-width: 600px;
}

.page-title {
    color: #fff;
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
    text-align: left;
}

.page-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--secondary-color);
    border-radius: 2px;
}

.side-bar-title {
    color: #183e73;
    font-size: 2rem;
    font-weight: 600;
    position: relative;
    text-align: left;
    padding-bottom: 1rem;
}

.side-bar-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--secondary-color);
    border-radius: 2px;
}

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
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-field {
    margin-bottom: 1rem;
}

.form-field.full-width {
    grid-column: 1 / -1;
}

label {
    display: block;
    margin-bottom: 0.75rem;
    color: #fff;
    font-weight: 500;
    font-size: 0.95rem;
}

input,
select,
textarea {
    width: 100%;
    padding: 0.85rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
    background: white;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 4px rgba(255,107,0,0.1);
}

.button-submit {
    background: #f28738;
    color: white;
    padding: 1rem 2.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    width: auto;
    margin-top: 1rem;
}

.button-submit:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
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
</style>

@section('content')

<div class="preferred-customer-section">
    <div class="container">
        <div class="customer-form-container">
            <div class="info-section">
                <div class="info-content">
                    <h2 class="side-bar-title">
                        {!! ($language_name == 'French') ? 'Avantages des Membres Privilèges' : 'Preferred Customer Benefits' !!}
                    </h2>
                    <div class="page-description">
                        @if ($language_name == 'French')
                            {!! $pageData->description_french ?? '' !!}
                        @else
                            {!! $pageData->description ?? '' !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h1 class="page-title">
                    {!! ($language_name == 'French') ? 'Devenez un membre Privilège' : 'Become a Preferred Customer' !!}
                </h1>
                
                <div id="signup-msg"></div>
                
                <form action="" method="post" id="Preferred-Customer">
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="label-style" for="fname">{!! ($language_name == 'French') ? 'Prénom' : 'First Name'!!}</label>
                            <input class="input-style" type="text" id="fname" name="fname" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="lname">{!! ($language_name == 'French') ? 'Nom de famille' : 'Last Name'!!}</label>
                            <input class="input-style" type="text" id="lname" name="lname" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="company_name">{!! ($language_name == 'French') ? 'Nom de la compagnie' : 'Company Name'!!}</label>
                            <input class="input-style" type="text" id="company_name" name="company_name" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="email">Email</label>
                            <input class="input-style" type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="signup-password">{!! ($language_name == 'French') ? 'Mot de passe' : 'Password'!!}</label>
                            <input class="input-style" type="password" id="signup-password" name="password" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="responsible_name">{!! ($language_name == 'French') ? 'Nom du responsable' : 'Responsible Name'!!}</label>
                            <input class="input-style" type="text" id="responsible_name" name="responsible_name" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="cp">{!! ($language_name == 'French') ? 'CP' : 'CP'!!}</label>
                            <input class="input-style" type="text" id="cp" name="cp" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="active_area">{!! ($language_name == 'French') ? 'Zone active' : 'Active Area'!!}</label>
                            <input class="input-style" type="text" id="active_area" name="active_area" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="address">{!! ($language_name == 'French') ? 'Adresse' : 'Address'!!}</label>
                            <input class="input-style" type="text" id="address" name="address" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="mobile">{!! ($language_name == 'French') ? 'Numéro de téléphone' : 'Phone Number'!!}</label>
                            <input class="input-style" type="tel" id="mobile" name="mobile" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="country">{!! ($language_name == 'French') ? 'Pays' : 'Country'!!}</label>
                            <select id="country" name="country" onchange="getState($(this).val())" class="crs-country" required>
                                <option value="">{!! ($language_name == 'French') ? '-- Choisissez le pays --' : '-- Select Country --'!!}</option>
                                @foreach ($countries as $country) 
                                    @php
                                    $selected = '';
                                    $post_country = isset($postData->country) ? $postData->country : '';
                                    if ($country->id == $post_country) {
                                        $selected = 'selected="selected"';
                                    }
                                    @endphp
                                <option value="{{ $country->id }}" {{ $selected }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-field">
                            <label class="label-style" for="stateiD">{!! ($language_name == 'French') ? 'État/Province' : 'State/Province' !!}</label>
                            <select id="stateiD" name="region" required>
                                <option value="">{!! ($language_name == 'French') ? '-- Sélectionnez l\'état --' : '-- Select State --' !!}</option>
                            </select>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="city">{!! ($language_name == 'French') ? 'Ville' : 'City'!!}</label>
                            <input class="input-style" type="text" id="city" name="city" required>
                        </div>
                        
                        <div class="form-field">
                            <label class="label-style" for="zip_code">{!! ($language_name == 'French') ? 'Code postal' : 'Postal Code'!!}</label>
                            <input class="input-style" type="text" id="zip_code" name="zip_code" required>
                        </div>
                        
                        <div class="form-field full-width">
                            <label class="label-style" for="request">{!! ($language_name == 'French') ? 'Demande' : 'Request'!!}</label>
                            <textarea id="request" name="request" required></textarea>
                        </div>
                        
                        <div class="submit-container">
                            <button class="button-submit" type="submit">
                                {!! ($language_name == 'French') ? 'Devenir membre' : 'Become a Member'!!}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function getState(country_id) {
    $('#stateiD').val('');
    $('#stateiD').html('<option value="">Loading...</option>');
    if (country_id != '') {
        var url = '{{ url("/getStateDropDownListByAjax") }}/' + country_id;
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
        $('#stateiD').html('<option value="">{!! ($language_name == "French") ? "-- Sélectionnez l\'état --" : "-- Select State --" !!}</option>');
    }
}
</script>
@endpush
