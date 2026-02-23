{{-- CI: application/views/Pages/estimate.php --}}
@extends('elements.app')

@section('title', $language_name == 'french' ? 'Demande d\'estimation' : 'Request an Estimate')

@section('content')

<style>
:root {
    --primary-color: #183e73;
    --secondary-color: #ff6b00;
    --light-gray: #f8f9fa;
    --border-color: #dcdcdc;
    --text-color: #333;
    --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s ease-in-out;
}

.estimate-section {
    background: var(--light-gray);
    padding: 4rem 2rem;
    min-height: 100vh;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
}

.estimate-header {
    text-align: center;
    margin-bottom: 3rem;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.estimate-header h1 {
    color: #183e73;
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.estimate-header p {
    color: var(--text-color);
    font-size: 1.1rem;
    line-height: 1.6;
}

.estimate-container {
    background: white;
    border-radius: 20px;
    box-shadow: var(--shadow-light);
    overflow: hidden;
}

.estimate-grid {
    display: grid;
    grid-template-columns: 350px 1fr;
}

.estimate-sidebar {
    background: #183e73;
    padding: 2rem;
    color: white;
}

.estimate-sidebar h3 {
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f28738;
}

.sidebar-info {
    margin-bottom: 2rem;
}

.sidebar-info p {
    margin-bottom: 1rem;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
}

.estimate-main {
    padding: 3rem;
}

.step-progress {
    display: flex;
    justify-content: space-between;
    margin-bottom: 3rem;
    position: relative;
}

.step-progress::before {
    content: '';
    position: absolute;
    top: 15px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--border-color);
    z-index: 1;
}

.step-item {
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 1rem;
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--border-color);
    color: var(--text-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: 600;
    transition: var(--transition);
}

.step-title {
    color: var(--text-color);
    font-size: 0.9rem;
    text-align: center;
    transition: var(--transition);
}

.step-item.active .step-number {
    background: #f28738;
    color: white;
}

.step-item.active .step-title {
    color: #183e73;
    font-weight: 600;
}

.step-item.completed .step-number {
    background: var(--primary-color);
    color: white;
}

.form-section {
    display: none;
    animation: fadeIn 0.5s ease-in-out;
}

.form-section.active {
    display: block;
}

/* .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
} */

.form-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.nav-button {
    background: white;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.nav-button:hover {
    background: var(--primary-color);
    color: white;
}

.nav-button.next {
    background: var(--primary-color);
    color: white;
}

.nav-button.next:hover {
    background: #12305a;
}

.nav-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    color: #183e73;
    margin-bottom: 0.75rem;
    font-weight: 500;
    font-size: 0.95rem;
}

.form-group label span {
    color: var(--secondary-color);
    margin-left: 4px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.85rem;
    border: 2px solid #183e73;
    border-radius: 8px;
    font-size: 1rem;
    transition: var(--transition);
    background: white;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 4px rgba(255,107,0,0.1);
}

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
    margin-top: 2rem;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.form-section-title {
    color: #183e73;
}

.helper-text {
    color: #666;
    font-size: 0.9rem;
    margin-top: 0.5rem;
}

.sides-group {
    display: flex;
    gap: 2rem;
}

@media (max-width: 1200px) {
    .estimate-grid {
        grid-template-columns: 1fr;
    }

    .estimate-sidebar {
        padding: 2rem;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .estimate-section {
        padding: 2rem 1rem;
    }
}

@media (max-width: 768px) {
    .estimate-container {
        border-radius: 12px;
    }

    .estimate-main {
        padding: 1.5rem;
    }

    .estimate-header h1 {
        font-size: 2rem;
    }

    .submit-btn {
        width: 100%;
    }
}
</style>

<div class="estimate-section">
    <div class="container">
        <div class="estimate-header">
            <h1>{{ $language_name == 'french' ? 'Demande d\'estimation' : 'Request an Estimate' }}</h1>
            <div class="description">
                @if($language_name == 'french')
                    {!! $pageData->description_french ?? '' !!}
                @else
                    {!! $pageData->description ?? '' !!}
                @endif
            </div>
        </div>

        @if(session('msg'))
            <div class="alert alert-success" style="text-align: center; margin-bottom: 20px;">
                <strong>{{ session('msg') }}</strong>
            </div>
        @endif

        <div class="estimate-container">
            <div class="estimate-grid">
                <div class="estimate-sidebar">
                    <div class="sidebar-info">
                        <h3>{{ $language_name == 'french' ? 'Informations importantes' : 'Important Information' }}</h3>
                        <p>{{ $language_name == 'french' ? 
                            'Remplissez ce formulaire avec précision pour obtenir une estimation détaillée. Tous les champs marqués d\'un astérisque (*) sont obligatoires.' : 
                            'Fill out this form accurately to get a detailed estimate. All fields marked with an asterisk (*) are required.' }}</p>
                        <p>{{ $language_name == 'french' ? 
                            'Notre équipe examinera votre demande et vous contactera dans les plus brefs délais.' : 
                            'Our team will review your request and contact you as soon as possible.' }}</p>
                    </div>
                </div>

                <div class="estimate-main">
                    @if($language_name == 'french')
                        <form action="{{ url('products/saveEstimate') }}" method="post" id="estimate-form">
                            @csrf
                            <div class="step-progress">
                                <div class="step-item active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Informations de contact</div>
                                </div>
                                <div class="step-item" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Détails du projet</div>
                                </div>
                                <div class="step-item" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Finition</div>
                                </div>
                            </div>

                            <div class="form-section active" data-step="1">
                                <h2 class="form-section-title">Informations de contact</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Nom du contact <span class="">*</span></label>
                                        <input type="text" name="contact_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Nom de la compagnie <span class="">*</span></label>
                                        <input type="text" name="company_name">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Email <span class="">*</span></label>
                                        <input type="email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label>Numéro de téléphone <span class="">*</span></label>
                                        <input type="text" name="phone_number">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>rue <span class="">*</span></label>
                                        <input type="text" name="street">
                                    </div>
                                    <div class="form-group">
                                        <label>Ville <span class="">*</span></label>
                                        <input type="text" name="city">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Pays <span class="">*</span></label>
                                        <select name="country" onchange="getState($(this).val())">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
                                        <label>code postal <span class="">*</span></label>
                                        <input type="text" name="postal_code">
                                    </div>
                                </div>
                                <div class="form-navigation">
                                    <button type="button" class="nav-button prev" style="visibility: hidden">Précédent</button>
                                    <button type="button" class="nav-button next">Suivant</button>
                                </div>
                            </div>

                            <div class="form-section" data-step="2">
                                <h2 class="form-section-title">Détails du projet</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Veuillez saisir les spécifications de vos produits ci-dessous:</label>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label><input type="checkbox" id="upload-option-btn" name="has_quote_form">J'ai mon propre formulaire de soumission de devis à télécharger</label>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Avez-vous déjà demandé le même devis?</label>
                                        <select name="same_quote_request">
                                            <option value="0">Non</option>
                                            <option value="1">Oui</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-navigation">
                                    <button type="button" class="nav-button prev">Précédent</button>
                                    <button type="button" class="nav-button next">Suivant</button>
                                </div>
                            </div>

                            <div class="form-section" data-step="3">
                                <h2 class="form-section-title">Finition</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Qté1</label>
                                        <input type="text" name="qty_1">
                                    </div>
                                    <div class="form-group">
                                        <label>Qté2</label>
                                        <input type="text" name="qty_2">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Qté3</label>
                                        <input type="text" name="qty_3">
                                    </div>
                                    <div class="form-group">
                                        <label>Plus de quantité:</label>
                                        <input type="text" name="more_qty">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Taille à plat (pouces) <span class="">*</span></label>
                                        <input type="text" name="flat_size">
                                        <span>Format plat: la taille du travail lorsqu'il n'est pas plié.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Taille finie (pouces) <span class="">*</span></label>
                                        <input type="text" name="finish_size">
                                        <span>Taille finie: la taille du travail une fois qu'il est complètement plié.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
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
                                    <div class="form-group">
                                        <label>Nombre de côtés</label>
                                        <div class="sides-group">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
                                        <label>Méthode d'expédition</label>
                                        <select name="shipping_methods">
                                            <option value="Pick up at COOP">Ramasser</option>
                                            <option value="Ship to My Address">expédier à mon adresse</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label><font color="red">*</font>Entrez la demande / Notes supplémentaires</label>
                                        <font color="red">Veuillez vous assurer que vous demandez des devis pour un seul type de produit à la fois</font>
                                        <textarea name="notes" placeholder="Veuillez vous assurer que votre devis contient toutes les informations requises. Ex. Taille finale, format à plat, matériau, côtés imprimés, exigences de finition, quantité requise, variations, etc." required></textarea>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-left:16px;">
                                    <div class="row">
                                    Entrez le mot que vous voyez ci-dessous :
                                    </div>
                                    <div class="row">	
                                        <div class="col" id="cap">
                                        {!! $cap->image ?? '' !!}
                                        </div>
                                        <div class="col">
                                                <a href="javascript:void()" onClick="locdCapcha()">
                                                <img src="{{ url('assets/images/refresh.png') }}" style="width:15px;height:15px;">
                                                </a>   
                                        </div>
                                    </div>
                                    <div class="row">
                                    <input type="text" name="captcha" value="" required/>
                                    </div>                                    
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="nav-button prev">Précédent</button>
                                    <button type="submit" class="submit-btn">Envoyer la demande</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <form action="{{ url('products/saveEstimate') }}" method="post" id="estimate-form">
                            @csrf
                            <div class="step-progress">
                                <div class="step-item active" data-step="1">
                                    <div class="step-number">1</div>
                                    <div class="step-title">Contact Information</div>
                                </div>
                                <div class="step-item" data-step="2">
                                    <div class="step-number">2</div>
                                    <div class="step-title">Project Details</div>
                                </div>
                                <div class="step-item" data-step="3">
                                    <div class="step-number">3</div>
                                    <div class="step-title">Finishing</div>
                                </div>
                            </div>

                            <div class="form-section active" data-step="1">
                                <h2 class="form-section-title">Contact Information</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Contact Name <span class="">*</span></label>
                                        <input type="text" name="contact_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Company Name <span class="">*</span></label>
                                        <input type="text" name="company_name">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Email <span class="">*</span></label>
                                        <input type="email" name="email">
                                    </div>
                                    <div class="form-group">
                                        <label>Phone Number <span class="">*</span></label>
                                        <input type="text" name="phone_number">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Street <span class="">*</span></label>
                                        <input type="text" name="street">
                                    </div>
                                    <div class="form-group">
                                        <label>City <span class="">*</span></label>
                                        <input type="text" name="city">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Country <span class="">*</span></label>
                                        <select name="country" onchange="getState($(this).val())">
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
                                    <div class="form-group">
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
                                    <div class="form-group">
                                        <label>Postal Code <span class="">*</span></label>
                                        <input type="text" name="postal_code">
                                    </div>
                                </div>
                                <div class="form-navigation">
                                    <button type="button" class="nav-button prev" style="visibility: hidden">Previous</button>
                                    <button type="button" class="nav-button next">Next</button>
                                </div>
                            </div>

                            <div class="form-section" data-step="2">
                                <h2 class="form-section-title">Project Details</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Please enter your products specifications below:</label>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label><input type="checkbox" id="upload-option-btn" name="has_quote_form">I have my own quote submission form to upload</label>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
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
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
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
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Have you requested the same quote before?</label>
                                        <select name="same_quote_request">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-navigation">
                                    <button type="button" class="nav-button prev">Previous</button>
                                    <button type="button" class="nav-button next">Next</button>
                                </div>
                            </div>

                            <div class="form-section" data-step="3">
                                <h2 class="form-section-title">Finishing</h2>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Qty1</label>
                                        <input type="text" name="qty_1">
                                    </div>
                                    <div class="form-group">
                                        <label>Qty2</label>
                                        <input type="text" name="qty_2">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Qty3</label>
                                        <input type="text" name="qty_3">
                                    </div>
                                    <div class="form-group">
                                        <label>More Qty:</label>
                                        <input type="text" name="more_qty">
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Flat Size ( inches ) <span class="text-danger">*</span></label>
                                        <input type="text" name="flat_size">
                                        <span>Flat Size: the size of the job when it is not folded.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Finished Size ( inches ) <span class="text-danger">*</span></label>
                                        <input type="text" name="finish_size">
                                        <span>Finished Size: the size of the job after it is completely folded.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Paper/Stock</label>
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
                                    <div class="form-group">
                                        <label>Number of Sides</label>
                                        <div class="sides-group">
                                            <label class="radio-option">
                                                <input name="no_of_sides" value="1" type="radio" checked="">
                                                <span class="radio-custom"></span>
                                                <span>1 Sided (inches)</span>
                                            </label>
                                            <label class="radio-option">
                                                <input name="no_of_sides" value="2" type="radio">
                                                <span class="radio-custom"></span>
                                                <span>Flat Size (2 Sided)</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
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
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>How many versions would you like? <span class="required">*</span></label>
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
                                        <span class="helper-text">How many versions would you like?Number of different artwork files to be submitted. For example, if you have 2 different postcards to be ordered, then select "2"</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Shipping Method</label>
                                       <select name="shipping_methods">
                                            <option value="Pick up at COOP">Pick Up</option>
                                            <option value="Ship to My Address">Ship to My Address</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label><font color="red">*</font>Enter Request / Additional Notes</label>
                                        <font color="red">Please ensure that you are requesting quotes for one product type at a time</font>
                                        <textarea name="notes" placeholder="Please ensure that your quote has all the information required. Ex. Final Size, Flat size, Material, printed sides, coating requirements, Qty required, Variations etc." required></textarea>
                                    </div>
                                </div>

                                <div class="form-group" style="margin-left:16px;">
                                    <div class="row">
                                    Submit the word you see below:
                                    </div>
                                    <div class="row">	
                                        <div class="col" id="cap">
                                            {!! $cap->image ?? '' !!}
                                        </div>
                                        <div class="col">
                                                <a href="javascript:void()" onClick="locdCapcha()">
                                                <img src="{{ url('assets/images/refresh.png') }}" style="width:15px;height:15px;">
                                                </a>   
                                        </div>
                                    </div>
                                    <div class="row">
                                    <input type="text" name="captcha" value="" required/>
                                    </div>                                    
                                </div>

                                <div class="form-navigation">
                                    <button type="button" class="nav-button prev">Previous</button>
                                    <button type="submit" class="submit-btn">Submit Request</button>
                                </div>
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

    // Captcha refresh function
    window.locdCapcha = function() {
        $.ajax({
            url: '{{ url("Products/refreshCaptcha") }}',
            type: 'POST',
            success: function(data) {
                $('#cap').html(data.captcha);
            }
        });
    };
});

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
        $('#stateiD').html('<option value="">{!! ($language_name == "French") ? "-- Sélectionnez l\'état --" : "-- Select State --" !!}</option>');
    }
}
</script>
@endpush
