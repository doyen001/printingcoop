{{-- CI: application/views/Pages/estimate.php --}}
@extends('elements.app')

@section('title', $language_name == 'french' ? 'Demande d\'estimation' : 'Request an Estimate')

@section('content')

<style>
/* CSS Variables */
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

/* Main Layout */
.estimate-section {
    padding: 4rem 2rem;
    background: var(--light-gray);
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.estimate-form-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

/* Info Section */
.info-section {
    flex: 1;
    background: #fafafa;
    color: var(--primary-color);
    padding: 3rem;
    position: relative;
    min-width: 400px;
}

.info-section::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50px;
    height: 100%;
    background: linear-gradient(to left, rgba(255,255,255,0.1), transparent);
}

.info-content {
    position: sticky;
    top: 3rem;
}

.info-section .page-title {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

.page-description {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 2rem;
    color: #1b1a19;
    padding-top: 0 !important;
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
    color: rgba(27, 26, 25, 0.9);
}

.benefits-list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--secondary-color);
}

/* Form Section */
.form-section {
    flex: 1.5;
    padding: 3rem;
    background: var(--primary-color);
    min-width: 600px;
}

.form-section .page-title {
    color: #fff;
    font-size: 2.2rem;
    font-weight: 600;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
    text-align: left;
}

.form-section .page-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: var(--secondary-color);
    border-radius: 2px;
}

/* Messages */
#estimate-msg {
    margin-bottom: 1.5rem;
    padding: 1rem;
    border-radius: 8px;
    text-align: center;
    display: none;
}

#estimate-msg.success {
    background: #d4edda;
    color: var(--success-color);
    border: 1px solid #c3e6cb;
}

#estimate-msg.error {
    background: #f8d7da;
    color: var(--error-color);
    border: 1px solid #f5c6cb;
}

/* Form Elements */
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

label span {
    color: var(--secondary-color);
    margin-left: 4px;
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

/* Radio Buttons */
.radio-group {
    display: flex;
    gap: 2rem;
    margin-top: 0.5rem;
}

.radio-option {
    position: relative;
    padding-left: 35px;
    cursor: pointer;
    user-select: none;
    color: #fff;
}

.radio-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-custom {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    height: 24px;
    width: 24px;
    background-color: white;
    border: 2px solid var(--border-color);
    border-radius: 50%;
    transition: var(--transition);
}

.radio-option:hover .radio-custom {
    border-color: var(--secondary-color);
}

.radio-option input[type="radio"]:checked + .radio-custom {
    border-color: var(--secondary-color);
}

.radio-custom:after {
    content: '';
    position: absolute;
    display: none;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--secondary-color);
}

.radio-option input[type="radio"]:checked + .radio-custom:after {
    display: block;
}

/* Buttons */
.submit-btn {
    background: linear-gradient(135deg, var(--secondary-color), #e65c00);
    color: white;
    padding: 1rem 2.5rem;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    margin-top: 1rem;
    width: auto;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

/* Helper Text */
.helper-text {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

/* Captcha */
.captcha-section {
    background: rgba(255, 255, 255, 0.1);
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1rem;
}

.captcha-section label {
    color: #fff;
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

/* Section Titles */
.form-section-title {
    color: white;
    font-size: 24px;
}

/* Checkboxes */
.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: normal;
}

.checkbox-label input[type="checkbox"] {
    margin: 0;
    width: auto;
}

.checkbox-text {
    display: inline-block;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .estimate-form-container {
        flex-direction: column;
    }

    .info-section,
    .form-section {
        min-width: 100%;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .estimate-section {
        padding: 2rem 1rem;
    }

    .info-section,
    .form-section {
        padding: 2rem;
    }
}
</style>

<div class="estimate-section">
    <div class="container">
        <div class="estimate-form-container">
            <div class="info-section">
                <div class="info-content">
                    <h2 class="page-title">
                        {{ $language_name == 'french' ? 'Informations importantes' : 'Important Information' }}
                    </h2>
                    <div class="page-description">
                        @if($language_name == 'french')
                            {!! $pageData->description_french ?? '' !!}
                        @else
                            {!! $pageData->description ?? '' !!}
                        @endif
                    </div>
                    <ul class="benefits-list">
                        <li>{{ $language_name == 'french' ? 
                            'Remplissez ce formulaire avec précision pour obtenir une estimation détaillée.' : 
                            'Fill out this form accurately to get a detailed estimate.' }}</li>
                        <li>{{ $language_name == 'french' ? 
                            'Tous les champs marqués d\'un astérisque (*) sont obligatoires.' : 
                            'All fields marked with an asterisk (*) are required.' }}</li>
                        <li>{{ $language_name == 'french' ? 
                            'Notre équipe examinera votre demande et vous contactera dans les plus brefs délais.' : 
                            'Our team will review your request and contact you as soon as possible.' }}</li>
                    </ul>
                </div>
            </div>

            <div class="form-section">
                <h1 class="page-title">
                    {{ $language_name == 'french' ? 'Demande d\'estimation' : 'Request an Estimate' }}
                </h1>
                
                <div id="estimate-msg"></div>
                @if($language_name == 'french')
                    <form action="{{ url('products/saveEstimate') }}" method="post" id="estimate-form">
                        @csrf
                        <h2 class="form-section-title">Informations de contact</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Nom du contact <span class="">*</span></label>
                                <input type="text" name="contact_name" required>
                            </div>
                            <div class="form-field">
                                <label>Nom de la compagnie <span class="">*</span></label>
                                <input type="text" name="company_name" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Email <span class="">*</span></label>
                                <input type="email" name="email" required>
                            </div>
                            <div class="form-field">
                                <label>Numéro de téléphone <span class="">*</span></label>
                                <input type="text" name="phone_number" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>rue <span class="">*</span></label>
                                <input type="text" name="street" required>
                            </div>
                            <div class="form-field">
                                <label>Ville <span class="">*</span></label>
                                <input type="text" name="city" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Pays <span class="">*</span></label>
                                <select name="country" onchange="getState($(this).val())" required>
                                    <option value="">-- Choisissez le pays --</option>
                                    @foreach($countries as $country)
                                        @php
                                            $selected = '';
                                            $post_country = $postData['country'] ?? '';
                                            if ($country->id == $post_country) {
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{ $country->id }}" {{ $selected }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Etat</label>
                                <select name="province" id="stateiD">
                                    <option value="">-- Sélectionnez l'état --</option>
                                    @foreach($states as $state)
                                        @php
                                            $selected = '';
                                            $post_state = $postData['state'] ?? '';
                                            if ($state->StateID == $post_state) {
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{ $state->StateID }}" {{ $selected }}>{{ $state->StateName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>code postal <span class="">*</span></label>
                                <input type="text" name="postal_code" required>
                            </div>
                        </div>
                        
                        <h2 class="form-section-title">Détails du projet</h2>
                        <div class="form-field full-width">
                            <label><strong>Veuillez saisir les spécifications de vos produits ci-dessous:</strong></label>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="checkbox-label">
    <input type="checkbox" id="upload-option-btn" name="has_quote_form">
    <span class="checkbox-text">J'ai mon propre formulaire de soumission de devis à télécharger</span>
</label>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Type de produit (cartes postales, livrets)</label>
                                <select name="product_type">
                                    <option value="Top Sellers">Meilleures ventes</option>
                                    <option value="Large Format">Grand format</option>
                                    <option value="Print Products">Produits d'impression</option>
                                    <option value="Holiday Printing">Impression de vacances</option>
                                    <option value="Stationery">Papeterie</option>
                                    <option value="Labels / Stickers">Étiquettes / autocollants</option>
                                    <option value="Direct Mail">Courrier direct</option>
                                    <option value="Promotional">Promotionnel</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label></label>
                                <select name="product_name">
                                    <option value="Business Cards">Cartes de visite</option>
                                    <option value="Postcards">Cartes postales</option>
                                    <option value="Flyers">Flyers</option>
                                    <option value="Brochures">Brochures</option>
                                    <option value="Bookmarks">Favoris</option>
                                    <option value="Presentation Folders">Dossiers de présentation</option>
                                    <option value="Booklets">Livrets</option>
                                    <option value="Magnets">Aimants</option>
                                    <option value="Greeting Cards">Cartes de voeux</option>
                                    <option value="Numbered Tickets">Billets numérotés</option>
                                    <option value="Wall Calendars">Billets numérotés</option>
                                    <option value="Variable Printing">Impression variable</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Avez-vous déjà demandé le même devis?</label>
                                <select name="same_quote_request">
                                    <option value="0">Non</option>
                                    <option value="1">Oui</option>
                                </select>
                            </div>
                        </div>
                        
                        <h2 class="form-section-title">Finition</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Qté1</label>
                                <input type="text" name="qty_1">
                            </div>
                            <div class="form-field">
                                <label>Qté2</label>
                                <input type="text" name="qty_2">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Qté3</label>
                                <input type="text" name="qty_3">
                            </div>
                            <div class="form-field">
                                <label>Plus de quantité:</label>
                                <input type="text" name="more_qty">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Taille à plat (pouces) <span class="">*</span></label>
                                <input type="text" name="flat_size" required>
                                <span class="helper-text">Format plat: la taille du travail lorsqu'il n'est pas plié.</span>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Taille finie (pouces) <span class="">*</span></label>
                                <input type="text" name="finish_size" required>
                                <span class="helper-text">Taille finie: la taille du travail une fois qu'il est complètement plié.</span>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Papier / Stock</label>
                                <select name="paper_stock">
                                    <option value="8pt C2S COVER">8pt C2S COVER</option>
                                    <option value="8pt C2S GLOSS">8pt C2S GLOSS</option>
                                    <option value="8pt COVER C2S">8pt COVER C2S</option>
                                    <option value="12pt C2S GLOSS">12pt C2S GLOSS</option>
                                    <option value="13pt ENVIRO COVER">13pt ENVIRO COVER</option>
                                    <option value="14pt MATT">14pt MATT (coated)</option>
                                    <option value="14pt C2S GLOSS">14pt C2S GLOSS</option>
                                    <option value="14pt C2S GLOSS UV">14pt C2S GLOSS UV</option>
                                    <option value="16pt C2S GLOSS">16pt C2S GLOSS</option>
                                    <option value="60lb OFFSET TEXT">60lb OFFSET TEXT</option>
                                    <option value="70lb GLOSS TEXT">70lb GLOSS TEXT</option>
                                    <option value="70lb OFFSET TEXT">70lb OFFSET TEXT</option>
                                    <option value="80lb ENVIRO TEXT">80lb ENVIRO TEXT</option>
                                    <option value="80lb GLOSS TEXT">80lb GLOSS TEXT</option>
                                    <option value="80lb OFFSET TEXT">80lb OFFSET TEXT</option>
                                    <option value="80lb SILK TEXT">80lb SILK TEXT</option>
                                    <option value="100lb GLOSS TEXT">100lb GLOSS TEXT</option>
                                    <option value="100lb GLOSS COVER">100lb GLOSS COVER</option>
                                    <option value="100lb MATTE TEXT (COATED)">100lb MATTE TEXT (COATED)</option>
                                    <option value="4mm FOAM BOARD">4mm FOAM BOARD</option>
                                    <option value="4mm Coroplast">4mm Coroplast</option>
                                    <option value="6mm Coroplast">6mm Coroplast</option>
                                    <option value="8mm Coroplast">8mm Coroplast</option>
                                    <option value="CANVAS ROLL">CANVAS ROLL</option>
                                    <option value="ENVELOPE">ENVELOPE</option>
                                    <option value="LABEL">LABEL</option>
                                    <option value="LARGE POSTER">LARGE POSTER</option>
                                    <option value="13pt LINEN UNCOATED CARD">13pt LINEN UNCOATED CARD</option>
                                    <option value="70lb LINEN UNCOATED TEXT">70lb LINEN UNCOATED TEXT</option>
                                    <option value="MAGNET (14pt)">MAGNET (14pt)</option>
                                    <option value="OPAQUE CLING">OPAQUE CLING</option>
                                    <option value="Plastic">Plastic</option>
                                    <option value="POP STAND">POP STAND</option>
                                    <option value="STYRENE">STYRENE</option>
                                    <option value="TRANSPARENT CLING">TRANSPARENT CLING</option>
                                    <option value="Vinyl Gloss">Vinyl Gloss</option>
                                    <option value="Vinyl Matte">Vinyl Matte</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Nombre de côtés</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input name="no_of_sides" value="1" type="radio" checked="">
                                        <span class="radio-custom"></span>
                                        <span>1 face (pouces)</span>
                                    </label>
                                    <label class="radio-option">
                                        <input name="no_of_sides" value="2" type="radio">
                                        <span class="radio-custom"></span>
                                        <span>Format plat (2 faces)</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Pliant</label>
                                <select name="folding">
                                    <option value="No Fold">No Fold</option>
                                    <option value="Half Fold">Half Fold</option>
                                    <option value="3 Pannel Z Fold">3 Pannel Z Fold</option>
                                    <option value="3 Pannel Roll Fold">3 Pannel Roll Fold</option>
                                    <option value="Double Parallel Fold">Double Parallel Fold</option>
                                    <option value="Gate Fold">Gate Fold</option>
                                    <option value="Double Gate Fold">Double Gate Fold</option>
                                    <option value="Four Pannel Accordian Fold">Four Pannel Accordian Fold</option>
                                    <option value="8 Pg Fold">8 Pg Fold</option>
                                    <option value="12 Pg Fold">12 Pg Fold</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Combien de versions souhaitez-vous? <span class="required">*</span></label>
                                <select name="total_versions" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                                <span class="helper-text">Combien de versions souhaitez-vous? Nombre de fichiers graphiques différents à soumettre. Par exemple, si vous avez 2 cartes postales différentes à commander, sélectionnez «2»</span>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Méthode d'expédition</label>
                                <select name="shipping_methods">
                                    <option value="Pick up at COOP">Ramasser</option>
                                    <option value="Ship to My Address">expédier à mon adresse</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-field full-width">
                            <label><font color="red">*</font>Entrez la demande / Notes supplémentaires</label>
                            <font color="red">Veuillez vous assurer que vous demandez des devis pour un seul type de produit à la fois</font>
                            <textarea name="notes" placeholder="Veuillez vous assurer que votre devis contient toutes les informations requises. Ex. Taille finale, format à plat, matériau, côtés imprimés, exigences de finition, quantité requise, variations, etc." required></textarea>
                        </div>

                        <div class="captcha-section">
                            <label>Entrez le mot que vous voyez ci-dessous :</label>
                            <div class="captcha-row">
                                <div class="captcha-image">
                                    {!! $cap->image ?? '' !!}
                                </div>
                                <div class="captcha-refresh">
                                    <a href="javascript:void(0)" onClick="locdCapcha()">
                                        <img src="{{ url('assets/images/refresh.png') }}" style="width:15px;height:15px;">
                                    </a>   
                                </div>
                            </div>
                            <div class="captcha-input">
                                <input type="text" name="captcha" value="" required/>
                            </div>                                    
                        </div>

                        <div class="form-field full-width">
                            <button type="submit" class="submit-btn">Envoyer la demande</button>
                        </div>
                    </form>
                    @else
                    <form action="{{ url('products/saveEstimate') }}" method="post" id="estimate-form">
                        @csrf
                        <h2 class="form-section-title">Contact Information</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Contact Name <span class="">*</span></label>
                                <input type="text" name="contact_name" required>
                            </div>
                            <div class="form-field">
                                <label>Company Name <span class="">*</span></label>
                                <input type="text" name="company_name" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Email <span class="">*</span></label>
                                <input type="email" name="email" required>
                            </div>
                            <div class="form-field">
                                <label>Phone Number <span class="">*</span></label>
                                <input type="text" name="phone_number" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Street <span class="">*</span></label>
                                <input type="text" name="street" required>
                            </div>
                            <div class="form-field">
                                <label>City <span class="">*</span></label>
                                <input type="text" name="city" required>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Country <span class="">*</span></label>
                                <select name="country" onchange="getState($(this).val())" required>
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $country)
                                        @php
                                            $selected = '';
                                            $post_country = $postData['country'] ?? '';
                                            if ($country->id == $post_country) {
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{ $country->id }}" {{ $selected }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-field">
                                <label>State</label>
                                <select name="province" id="stateiD">
                                    <option value="">-- Select State --</option>
                                    @foreach($states as $state)
                                        @php
                                            $selected = '';
                                            $post_state = $postData['state'] ?? '';
                                            if ($state->StateID == $post_state) {
                                                $selected = 'selected="selected"';
                                            }
                                        @endphp
                                        <option value="{{ $state->StateID }}" {{ $selected }}>{{ $state->StateName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Postal Code <span class="">*</span></label>
                                <input type="text" name="postal_code" required>
                            </div>
                        </div>
                        
                        <h2 class="form-section-title">Project Details</h2>
                        <div class="form-field full-width">
                            <label><strong>Please enter your products specifications below:</strong></label>
                        </div>
                        <div class="form-field">
                            <label class="checkbox-label">
                                <input type="checkbox" id="upload-option-btn" name="has_quote_form">
                                <p class="checkbox-text">I have my own quote submission form to upload</p>
                            </label>
                        </div>
                        <div class="form-field">
                            <label>Product Type ( i.e. Postcards, Booklets )</label>
                            <select name="product_type">
                                <option value="Top Sellers">Top Sellers</option>
                                <option value="Large Format">Large Format</option>
                                <option value="Print Products">Print Products</option>
                                <option value="Holiday Printing">Holiday Printing</option>
                                <option value="Stationery">Stationery</option>
                                <option value="Labels / Stickers">Labels / Stickers</option>
                                <option value="Direct Mail">Direct Mail</option>
                                <option value="Promotional">Promotional</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label></label>
                            <select name="product_name">
                                <option value="Business Cards">Business Cards</option>
                                <option value="Postcards">Postcards</option>
                                <option value="Flyers">Flyers</option>
                                <option value="Brochures">Brochures</option>
                                <option value="Bookmarks">Bookmarks</option>
                                <option value="Presentation Folders">Presentation Folders</option>
                                <option value="Booklets">Booklets</option>
                                <option value="Magnets">Magnets</option>
                                <option value="Greeting Cards">Greeting Cards</option>
                                <option value="Numbered Tickets">Numbered Tickets</option>
                                <option value="Wall Calendars">Wall Calendars</option>
                                <option value="Variable Printing">Variable Printing</option>
                            </select>
                        </div>
                        <div class="form-field">
                            <label>Have you requested the same quote before?</label>
                            <select name="same_quote_request">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        
                        <h2 class="form-section-title">Finishing</h2>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Qty1</label>
                                <input type="text" name="qty_1">
                            </div>
                            <div class="form-field">
                                <label>Qty2</label>
                                <input type="text" name="qty_2">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Qty3</label>
                                <input type="text" name="qty_3">
                            </div>
                            <div class="form-field">
                                <label>More quantity:</label>
                                <input type="text" name="more_qty">
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Flat Size (inches) <span class="">*</span></label>
                                <input type="text" name="flat_size" required>
                                <span class="helper-text">Flat size: the size of the job when it is not folded.</span>
                            </div>
                            <div class="form-field">
                                <label>Finished Size (inches) <span class="">*</span></label>
                                <input type="text" name="finish_size" required>
                                <span class="helper-text">Finished size: the size of the job once it is completely folded.</span>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-field">
                                <label>Paper / Stock</label>
                                <select name="paper_stock">
                                    <option value="8pt C2S COVER">8pt C2S COVER</option>
                                    <option value="8pt C2S GLOSS">8pt C2S GLOSS</option>
                                    <option value="8pt COVER C2S">8pt COVER C2S</option>
                                    <option value="12pt C2S GLOSS">12pt C2S GLOSS</option>
                                    <option value="13pt ENVIRO COVER">13pt ENVIRO COVER</option>
                                    <option value="14pt MATT">14pt MATT (coated)</option>
                                    <option value="14pt C2S GLOSS">14pt C2S GLOSS</option>
                                    <option value="14pt C2S GLOSS UV">14pt C2S GLOSS UV</option>
                                    <option value="16pt C2S GLOSS">16pt C2S GLOSS</option>
                                    <option value="60lb OFFSET TEXT">60lb OFFSET TEXT</option>
                                    <option value="70lb GLOSS TEXT">70lb GLOSS TEXT</option>
                                    <option value="70lb OFFSET TEXT">70lb OFFSET TEXT</option>
                                    <option value="80lb ENVIRO TEXT">80lb ENVIRO TEXT</option>
                                    <option value="80lb GLOSS TEXT">80lb GLOSS TEXT</option>
                                    <option value="80lb OFFSET TEXT">80lb OFFSET TEXT</option>
                                    <option value="80lb SILK TEXT">80lb SILK TEXT</option>
                                    <option value="100lb GLOSS TEXT">100lb GLOSS TEXT</option>
                                    <option value="100lb GLOSS COVER">100lb GLOSS COVER</option>
                                    <option value="100lb MATTE TEXT (COATED)">100lb MATTE TEXT (COATED)</option>
                                    <option value="4mm FOAM BOARD">4mm FOAM BOARD</option>
                                    <option value="4mm Coroplast">4mm Coroplast</option>
                                    <option value="6mm Coroplast">6mm Coroplast</option>
                                    <option value="8mm Coroplast">8mm Coroplast</option>
                                    <option value="CANVAS ROLL">CANVAS ROLL</option>
                                    <option value="ENVELOPE">ENVELOPE</option>
                                    <option value="LABEL">LABEL</option>
                                    <option value="LARGE POSTER">LARGE POSTER</option>
                                    <option value="13pt LINEN UNCOATED CARD">13pt LINEN UNCOATED CARD</option>
                                    <option value="70lb LINEN UNCOATED TEXT">70lb LINEN UNCOATED TEXT</option>
                                    <option value="MAGNET (14pt)">MAGNET (14pt)</option>
                                    <option value="OPAQUE CLING">OPAQUE CLING</option>
                                    <option value="Plastic">Plastic</option>
                                    <option value="POP STAND">POP STAND</option>
                                    <option value="STYRENE">STYRENE</option>
                                    <option value="TRANSPARENT CLING">TRANSPARENT CLING</option>
                                    <option value="Vinyl Gloss">Vinyl Gloss</option>
                                    <option value="Vinyl Matte">Vinyl Matte</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Folding</label>
                                <select name="folding">
                                    <option value="No Fold">No Fold</option>
                                    <option value="Half Fold">Half Fold</option>
                                    <option value="3 Pannel Z Fold">3 Pannel Z Fold</option>
                                    <option value="3 Pannel Roll Fold">3 Pannel Roll Fold</option>
                                    <option value="Double Parallel Fold">Double Parallel Fold</option>
                                    <option value="Gate Fold">Gate Fold</option>
                                    <option value="Double Gate Fold">Double Gate Fold</option>
                                    <option value="Four Pannel Accordian Fold">Four Pannel Accordian Fold</option>
                                    <option value="8 Pg Fold">8 Pg Fold</option>
                                    <option value="12 Pg Fold">12 Pg Fold</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="form-grid"> --}}
                            <div class="form-field">
                                <label>Number of sides</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input name="no_of_sides" value="1" type="radio" checked="">
                                        <span class="radio-custom"></span>
                                        <p>1 sided (inches)</p>
                                    </label>
                                    <label class="radio-option">
                                        <input name="no_of_sides" value="2" type="radio">
                                        <span class="radio-custom"></span>
                                        <p>Flat size (2 sided)</p>
                                    </label>
                                </div>
                            </div>
                        {{-- </div> --}}
                        
                        <div class="form-grid">
                            <div class="form-field">
                                <label>How many versions do you want? <span class="required">*</span></label>
                                <select name="total_versions" required>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                </select>
                                <span class="helper-text">How many versions do you want? Number of different graphic files to submit. For example, if you have 2 different postcards to order, select "2"</span>
                            </div>
                            <div class="form-field">
                                <label>Shipping Method</label>
                                <select name="shipping_methods">
                                    <option value="Pick up at COOP">Pick up</option>
                                    <option value="Ship to My Address">Ship to My Address</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-field full-width">
                            <label><font color="red">*</font>Enter Request / Additional Notes</label>
                            <font color="red">Please ensure you request quotes for one product type at a time</font>
                            <textarea name="notes" placeholder="Please ensure your quote contains all required information. Eg. Final size, flat size, material, printed sides, finishing requirements, required quantity, variations, etc." required></textarea>
                        </div>

                        <div class="captcha-section">
                            <label>Enter the word you see below:</label>
                            <div class="captcha-row">
                                <div class="captcha-image">
                                    {!! $cap->image ?? '' !!}
                                </div>
                                <div class="captcha-refresh">
                                    <a href="javascript:void(0)" onClick="locdCapcha()">
                                        <img src="{{ url('assets/images/refresh.png') }}" style="width:15px;height:15px;">
                                    </a>   
                                </div>
                            </div>
                            <div class="captcha-input">
                                <input type="text" name="captcha" value="" required/>
                            </div>                                    
                        </div>

                        <div class="form-field full-width">
                            <button type="submit" class="submit-btn">Submit Request</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Multi-step form navigation (from CI estimate.php lines 1084-1184)
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('estimate-form');
    if (!form) return;

    const formSections = form.querySelectorAll('.form-section');
    const stepItems = form.querySelectorAll('.step-item');
    const prevButtons = form.querySelectorAll('.nav-button.prev');
    const nextButtons = form.querySelectorAll('.nav-button.next');
    
    let currentStep = 1;
    const totalSteps = formSections.length;

    function showStep(step) {
        formSections.forEach((section, index) => {
            section.classList.remove('active');
            if (index === step - 1) {
                section.classList.add('active');
            }
        });

        stepItems.forEach((item, index) => {
            item.classList.remove('active', 'completed');
            if (index < step - 1) {
                item.classList.add('completed');
            } else if (index === step - 1) {
                item.classList.add('active');
            }
        });

        currentStep = step;
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Validate current step before proceeding
            const currentSection = formSections[currentStep - 1];
            
            // Check for required fields with labels containing *
            const allRequiredInputs = currentSection.querySelectorAll('input, select, textarea');
            let hasEmptyRequired = false;
            let firstEmptyField = null;
            
            allRequiredInputs.forEach(input => {
                const label = input.closest('.form-group')?.querySelector('label');
                if (label && label.innerHTML.includes('*')) {
                    if (!input.value.trim()) {
                        hasEmptyRequired = true;
                        if (!firstEmptyField) {
                            firstEmptyField = input;
                        }
                    }
                    
                    // Additional validation for email fields
                    if (input.type === 'email' && input.value.trim()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(input.value.trim())) {
                            hasEmptyRequired = true;
                            if (!firstEmptyField) {
                                firstEmptyField = input;
                            }
                            // Add email validation error
                            input.setAttribute('data-email-error', 'true');
                        }
                    }
                }
            });
            
            if (hasEmptyRequired) {
                // Focus on first empty required field
                if (firstEmptyField) {
                    firstEmptyField.focus();
                    firstEmptyField.style.borderColor = 'red';
                    // Remove red border after 3 seconds
                    setTimeout(() => {
                        firstEmptyField.style.borderColor = '';
                    }, 3000);
                }
                
                // Show error message
                const existingError = currentSection.querySelector('.required-field-error');
                if (existingError) {
                    existingError.remove();
                }
                
                const errorMsg = document.createElement('div');
                errorMsg.className = 'required-field-error';
                errorMsg.style.cssText = 'color: red; margin-top: 10px; font-size: 14px;';
                
                // Check if it's an email validation error
                // if (firstEmptyField && firstEmptyField.getAttribute('data-email-error') === 'true') {
                //     errorMsg.textContent = 'Please enter a valid email address.';
                // } else {
                //     errorMsg.textContent = 'Please fill in all required fields marked with * before proceeding.';
                // }
                
                currentSection.querySelector('.form-grid').appendChild(errorMsg);
                
                // Remove error message after 5 seconds
                setTimeout(() => {
                    errorMsg.remove();
                }, 5000);
                
                return; // Don't proceed to next step
            }
            
            // Remove any existing error messages and email error attributes
            const existingError = currentSection.querySelector('.required-field-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Clean up email error attributes
            allRequiredInputs.forEach(input => {
                if (input.getAttribute('data-email-error') === 'true') {
                    input.removeAttribute('data-email-error');
                }
            });
            
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (currentStep > 1) {
                showStep(currentStep - 1);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

});

// Captcha refresh function (global scope)
window.locdCapcha = function() {
    console.log('Refreshing captcha...');
    $.ajax({
        url: "{{ url('Products/refreshCaptcha') }}",
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(data) {
            console.log('Captcha refreshed successfully:', data);
            if (data && data.captcha) {
                $('.captcha-image').html(data.captcha);
            } else {
                console.error('Invalid captcha response:', data);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error refreshing captcha:', error);
            console.error('Response:', xhr.responseText);
        }
    });
};

// State dropdown AJAX function
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
        $('#stateiD').html('<option value="">{!! ($language_name == "French") ? "-- Sélectionnez l\\'état --" : "-- Select State --" !!}</option>');
    }
}
</script>
@endpush
