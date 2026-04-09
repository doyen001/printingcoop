{{-- CI: application/views/Pages/estimate.php --}}
@extends('elements.app')

@section('title', $language_name == 'french' ? 'Demande d\'estimation' : 'Request an Estimate')

@section('content')

    <style>
        /* CSS Variables */
        :root {
            /* --primary-color: #183e73; */
            --secondary-color: #ff6b00;
            --accent-color: #f58634;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #333;
            --border-color: #dcdcdc;
            --shadow-light: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
            --transition: all 0.3s ease-in-out;
        }

        /* Main Layout */
        .estimate-section {
            padding: 4rem 2rem;
            background: var(--light-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .estimate-section .container {
            max-width: 1200px !important;
            margin: 0 auto;
            padding: 0;
        }

        .estimate-form-container {
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: var(--shadow-light);
            overflow: hidden;
        }

        /* Info Section */
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
            background: linear-gradient(to left, rgba(255,255,255,0.1), transparent);
            z-index: 1;
            border-right: 1px solid #e0e0e0;
        }

        .info-content {
            /* padding-right: 2rem; */
        }

        .estimate-form-container .page-title {
            color: #183e73;
            font-size: 2rem;
            font-weight: 600;
            position: relative;
            text-align: left;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
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

        .page-description .highlight-text {
            font-weight: 600;
            color: #ff6b00;
            margin-bottom: 1rem;
            display: block;
        }

        .page-description .minimum-notice {
            background: rgba(255, 107, 0, 0.1);
            border-left: 4px solid #ff6b00;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }

        .page-description .feature-list {
            margin: 1.5rem 0;
        }

        .page-description .feature-item {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .page-description .feature-item::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #ff6b00;
            font-weight: bold;
        }

        .page-description .footer-note {
            font-size: 0.9rem;
            color: #666;
            font-style: italic;
            margin-top: 1.5rem;
        }

        /* Dynamic Quantity Fields */
        .quantity-container {
            margin-bottom: 1rem;
        }

        .quantity-field {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .quantity-field input {
            flex: 1;
            max-width: 120px;
        }

        .quantity-field .remove-qty-btn {
            background: #ff4444;
            color: white;
            border: none;
            border-radius: 6px;
            width: auto;
            height: 36px;
            padding: 0 12px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            flex-shrink: 0;
            gap: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quantity-field .remove-qty-btn::before {
            content: '🗑';
            font-size: 14px;
            margin-right: 2px;
        }

        .quantity-field .remove-qty-btn:hover {
            background: #cc0000;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(255, 68, 68, 0.3);
        }

        .quantity-field .remove-qty-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 4px rgba(255, 68, 68, 0.3);
        }

        .add-qty-btn {
            background: transparent;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition);
            margin-top: 0.5rem;
        }

        .add-qty-btn:hover {
            background: var(--secondary-color);
            color: white;
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
            flex: 1;
            padding: 3rem;
            background: #ffffff;
            min-width: 600px;
            border-right: 1px solid #e9ecef;
        }

        /* .form-section .page-title {
            color: #fff;
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 1rem;
            text-align: left;
        } */

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
            margin-bottom: 1.5rem;
        }

        .form-field {
            margin-bottom: 0.25rem;
        }

        .form-field:last-child {
            margin-bottom: 0;
        }

        .form-field.full-width {
            grid-column: 1 / -1;
        }

        /* Form section spacing */
        /* .form-section .page-title {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .form-section .page-title:first-child {
            margin-top: 0;
        } */

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .estimate-section input,
        select,
        textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            transition: border-color 0.2s ease;
            background: white;
        }

        .estimate-section input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #f58634;
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
            color: #333;
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

        .radio-option input[type="radio"]:checked+.radio-custom {
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

        .radio-option input[type="radio"]:checked+.radio-custom:after {
            display: block;
        }

        /* Buttons */
        .submit-btn {
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

        .submit-btn:hover {
            background: #db762eff;
        }

        /* Helper Text */
        .helper-text {
            color: #666;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Captcha */
        .captcha-section {
            background: rgba(255, 255, 255, 0.1);
            /* padding: 1.5rem; */
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

        /* Section Titles */
        .form-section-title {
            color: #333;
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
                            @if ($language_name == 'french')
                                <span class="highlight-text">Bienvenue à notre portail de devis personnalisés chez Printing
                                    Coop.</span>
                                <p>Ici, vous pouvez soumettre commodément des demandes de commandes d'impression qui
                                    dépassent nos options répertoriées, adaptées à vos besoins spécifiques. Nous nous
                                    engageons à vous fournir:</p>

                                <div class="minimum-notice">
                                    <strong>Veuillez noter que toutes les demandes de devis personnalisés nécessitent un
                                        minimum de 500 $.</strong> Cela aide à couvrir le temps, les ressources et les soins
                                    supplémentaires impliqués dans la tarification et la production de travaux
                                    personnalisés.
                                </div>

                                <p>Les devis personnalisés nécessitent le même travail de prépresse, de révision de
                                    production et de planification, quelle que soit la taille de la commande. En réponse à
                                    l'augmentation du volume de devis, nous avons mis à jour notre minimum à 500 $ pour
                                    garantir une tarification précise et un délai de livraison fiable, tout en élargissant
                                    nos options de site Web afin que de nombreux produits puissent désormais être commandés
                                    plus rapidement et plus rentablement sans devis personnalisé.</p>

                                <div class="feature-list">
                                    <div class="feature-item">
                                        <strong>Délai de traitement moyen des devis personnalisés: 1-2 jours
                                            ouvrables*</strong><br>
                                        Traitement rapide des devis, afin que vous puissiez procéder à vos projets sans
                                        retard.
                                    </div>
                                    <div class="feature-item">
                                        <strong>Prix compétitifs</strong><br>
                                        Nous nous assurons que vous obtenez la meilleure valeur pour vos commandes
                                        d'impression uniques.
                                    </div>
                                    <div class="feature-item">
                                        <strong>Attention individuelle aux commandes</strong><br>
                                        Vos commandes ne sont pas regroupées; nous traitons individuellement chaque demande
                                        pour maintenir les normes de qualité les plus élevées.
                                    </div>
                                </div>

                                <p>Laissez-nous vous aider à donner vie à vos visions d'impression uniques avec l'attention
                                    personnalisée qu'elles méritent. Commencez par demander votre devis personnalisé dès
                                    aujourd'hui!</p>

                                <p class="footer-note">*Veuillez noter que les délais de traitement des devis peuvent varier
                                    en fonction du volume et de la complexité de votre demande de devis.</p>
                            @else
                                <span class="highlight-text">Welcome to our Custom Quote Request portal at Printing
                                    Coop.</span>
                                <p>Here, you can conveniently submit requests for print orders that go beyond our listed
                                    options, tailored to your specific needs. We're committed to providing you with:</p>

                                <div class="minimum-notice">
                                    <strong>Please note that all custom quote requests require a minimum of $500.</strong>
                                    This helps cover the extra time, resources and care involved in quoting and producing
                                    custom work.
                                </div>

                                <p>Custom estimates require the same prepress, production review, and scheduling work
                                    regardless of order size, so in response to increased quote volumes we've updated our
                                    minimum to $500 to ensure accurate pricing and reliable turnaround, while also expanding
                                    our website options so many products can now be ordered faster and more cost-effectively
                                    without a custom quote.</p>

                                <div class="feature-list">
                                    <div class="feature-item">
                                        <strong>Average custom quote turnaround time: 1-2 business days*</strong><br>
                                        Fast turnaround on quotes, so you can proceed with your projects without delay.
                                    </div>
                                    <div class="feature-item">
                                        <strong>Competitive Pricing</strong><br>
                                        We ensure you get the best value for your unique print orders.
                                    </div>
                                    <div class="feature-item">
                                        <strong>Individual attention to Orders</strong><br>
                                        Your orders are not gang run; we individually process each request to maintain the
                                        highest quality standards.
                                    </div>
                                </div>

                                <p>Let us help you bring your unique print visions to life with the personalized attention
                                    they deserve. Start by requesting your custom quote today!</p>

                                <p class="footer-note">*Please note quoting turnaround times may vary depending on the
                                    volume and complexity of your quote request.</p>
                            @endif
                        </div>
                        {{-- <ul class="benefits-list">
                        <li>{{ $language_name == 'french' ? 
                            'Remplissez ce formulaire avec précision pour obtenir une estimation détaillée.' : 
                            'Fill out this form accurately to get a detailed estimate.' }}</li>
                        <li>{{ $language_name == 'french' ? 
                            'Tous les champs marqués d\'un astérisque (*) sont obligatoires.' : 
                            'All fields marked with an asterisk (*) are required.' }}</li>
                        <li>{{ $language_name == 'french' ? 
                            'Notre équipe examinera votre demande et vous contactera dans les plus brefs délais.' : 
                            'Our team will review your request and contact you as soon as possible.' }}</li>
                    </ul> --}}
                    </div>
                </div>

                <div class="form-section">
                    {{-- <h1 class="page-title">
                        {{ $language_name == 'french' ? 'Demande d\'estimation' : 'Request an Estimate' }}
                    </h1> --}}

                    <div id="estimate-msg"></div>
                    @if ($language_name == 'french')
                        <form action="{{ url('products/saveEstimate') }}" method="post" id="estimate-form">
                            @csrf
                            <h2 class="page-title">Informations de contact</h2>
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

                            <div class="form-field">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="same_quote_request" value="1">
                                    <p class="checkbox-text">Répétez-vous une commande personnalisée ou un numéro de devis précédent?</p>
                                </label>
                            </div>

                            <label>Type de produit (ex: cartes postales, livrets)</label>
                            <div class="form-field" style="margin-bottom: 1.5rem;">
                                <select name="product_name">
                                    <option value="">Sélectionnez un produit...</option>
                                    <option value="Top Sellers">Meilleures ventes</option>
                                    <option value="Large Format">Grand format</option>
                                    <option value="Print Products">Produits d'impression</option>
                                    <option value="Holiday Printing">Impression de vacances</option>
                                    <option value="Stationery">Papeterie</option>
                                    <option value="Labels / Stickers">Étiquettes / autocollants</option>
                                    <option value="Direct Mail">Courrier direct</option>
                                    <option value="Promotional">Promotionnel</option>
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
                                    <option value="Wall Calendars">Calendriers muraux</option>
                                    <option value="Variable Printing">Impression variable</option>
                                </select>
                            </div>

                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Taille à plat (pouces) <span class="">*</span></label>
                                    <input type="text" name="flat_size" required>
                                    <span class="helper-text">Format plat: la taille du travail lorsqu'il n'est pas plié.</span>
                                </div>
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
                                <div class="form-field">
                                    <label>Pliant</label>
                                    <select name="folding">
                                        <option value="No Fold">Sans pli</option>
                                        <option value="Half Fold">Pli simple</option>
                                        <option value="3 Pannel Z Fold">Pli Z 3 volets</option>
                                        <option value="3 Pannel Roll Fold">Pli roulé 3 volets</option>
                                        <option value="Double Parallel Fold">Pli parallèle double</option>
                                        <option value="Gate Fold">Pli portail</option>
                                        <option value="Double Gate Fold">Pli portail double</option>
                                        <option value="Four Pannel Accordian Fold">Pli accordéon 4 volets</option>
                                        <option value="8 Pg Fold">Pli 8 pages</option>
                                        <option value="12 Pg Fold">Pli 12 pages</option>
                                        <option value="Other">Autre</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-field">
                                <label>Nombre de côtés</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input name="no_of_sides" value="1" type="radio" checked="">
                                        <span class="radio-custom"></span>
                                        <p>1 face (pouces)</p>
                                    </label>
                                    <label class="radio-option">
                                        <input name="no_of_sides" value="2" type="radio">
                                        <span class="radio-custom"></span>
                                        <p>Format plat (2 faces)</p>
                                    </label>
                                </div>
                            </div>

                            <div class="form-field full-width">
                                <label>
                                    <font color="red">*</font>Entrez la demande / Notes supplémentaires
                                </label>
                                <font color="red">Veuillez vous assurer que vous demandez des devis pour un seul type de produit à la fois</font>
                                <textarea
                                    name="notes"
                                    placeholder="Veuillez vous assurer que votre devis contient toutes les informations requises. Ex. Taille finale, format à plat, matériau, côtés imprimés, exigences de finition, quantité requise, variations, etc."
                                    style="height: 150px;"
                                    required
                                ></textarea>
                            </div>

                            <label>Quantité requise:</label>
                            <div class="quantity-container" id="quantity-container">
                                <div class="quantity-field">
                                    <input type="text" name="qty_1" placeholder="Quantité">
                                </div>
                            <button type="button" class="add-qty-btn" id="add-qty-btn">+ Ajouter une autre quantité</button>
                            </div>

                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Combien de versions voulez-vous?<span class="required">*</span></label>
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
                                    <span class="helper-text">Combien de versions souhaitez-vous? Nombre de fichiers graphiques différents à soumettre. Par exemple, si vous avez 2 cartes postales différentes à commander, sélectionnez "2"</span>
                                </div>
                                <div class="form-field">
                                    <label>Méthode d'expédition</label>
                                    <select name="shipping_methods">
                                        <option value="Pick up at COOP">Ramasser</option>
                                        <option value="Ship to My Address">expédier à mon adresse</option>
                                    </select>
                                </div>
                            </div>

                            <label>Adresse de facturation:</label>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Rue <span class="">*</span></label>
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
                                        <option value="">-- Sélectionnez le pays --</option>
                                        @foreach ($countries as $country)
                                            @php
                                                $selected = '';
                                                $post_country = $postData['country'] ?? '';
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
                                    <label>État</label>
                                    <select name="province" id="stateiD">
                                        <option value="">-- Sélectionnez l'état --</option>
                                        @foreach ($states as $state)
                                            @php
                                                $selected = '';
                                                $post_state = $postData['state'] ?? '';
                                                if ($state->StateID == $post_state) {
                                                    $selected = 'selected="selected"';
                                                }
                                            @endphp
                                            <option value="{{ $state->StateID }}" {{ $selected }}>
                                                {{ $state->StateName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Téléphone</label>
                                    <input type="tel" name="telephone">
                                </div>
                                <div class="form-field">
                                    <label>Code postal <span class="">*</span></label>
                                    <input type="text" name="postal_code" required>
                                </div>
                            </div>

                            <div class="captcha-section">
                                <label>Entrez le mot que vous voyez ci-dessous:</label>
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
                                    <input type="text" name="captcha" value="" required />
                                </div>
                            </div>

                            <div class="form-field full-width">
                                <button type="submit" class="submit-btn">Envoyer la demande</button>
                            </div>
                        </form>
                    @else
                        <form action="{{ url('products/saveEstimate') }}" method="post" id="estimate-form">
                            @csrf
                            <h2 class="page-title">Contact Information</h2>
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

                            <div class="form-field">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="same_quote_request" value="1">
                                    <p class="checkbox-text">Are you repeating a previous custom order or quote number?</p>
                                </label>
                            </div>



                            {{-- <h2 class="form-section-title">Project Details</h2>
                        <div class="form-field full-width">
                            <label><strong>Please enter your products specifications below:</strong></label>
                        </div> --}}
                            {{-- <div class="form-field">
                                <label class="checkbox-label">
                                    <input type="checkbox" id="upload-option-btn" name="has_quote_form">
                                    <p class="checkbox-text">I have my own quote submission form to upload</p>
                                </label>
                            </div> --}}
                            <label>Product Type ( i.e. Postcards, Booklets )</label>
                            <div class="form-field" style="margin-bottom: 1.5rem;">
                                <select name="product_name">
                                    <option value="">Select a product...</option>
                                    {{-- <optgroup label="Categories"> --}}
                                        <option value="Top Sellers">Top Sellers</option>
                                        <option value="Large Format">Large Format</option>
                                        <option value="Print Products">Print Products</option>
                                        <option value="Holiday Printing">Holiday Printing</option>
                                        <option value="Stationery">Stationery</option>
                                        <option value="Labels / Stickers">Labels / Stickers</option>
                                        <option value="Direct Mail">Direct Mail</option>
                                        <option value="Promotional">Promotional</option>
                                    {{-- </optgroup>
                                    <optgroup label="Specific Products"> --}}
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
                                    {{-- </optgroup> --}}
                                </select>
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
                                    <span class="helper-text">Finished size: the size of the job once it is completely
                                        folded.</span>
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

                            <div class="form-field full-width">
                                <label>
                                    <font color="red">*</font>Enter Request / Additional Notes
                                </label>
                                <font color="red">Please ensure you request quotes for one product type at a time</font>
                                <textarea
                                    name="notes"
                                    placeholder="Please ensure your quote contains all required information. Eg. Final size, flat size, material, printed sides, finishing requirements, required quantity, variations, etc."
                                    style="height: 150px;"
                                    required
                                ></textarea>
                            </div>


                            <label>Qty Required:</label>
                            <div class="quantity-container" id="quantity-container">
                                <div class="quantity-field">
                                    {{-- <label>Qty</label> --}}
                                    <input type="text" name="qty_1" placeholder="Qty">
                                </div>
                            <button type="button" class="add-qty-btn" id="add-qty-btn">+ Add another Qty</button>
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
                                    <span class="helper-text">How many versions do you want? Number of different graphic
                                        files to submit. For example, if you have 2 different postcards to order, select
                                        "2"</span>
                                </div>
                                <div class="form-field">
                                    <label>Shipping Method</label>
                                    <select name="shipping_methods">
                                        <option value="Pick up at COOP">Pick up</option>
                                        <option value="Ship to My Address">Ship to My Address</option>
                                    </select>
                                </div>
                            </div>

                            <label>Billing Address:</label>
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
                                        @foreach ($countries as $country)
                                            @php
                                                $selected = '';
                                                $post_country = $postData['country'] ?? '';
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
                                    <label>State</label>
                                    <select name="province" id="stateiD">
                                        <option value="">-- Select State --</option>
                                        @foreach ($states as $state)
                                            @php
                                                $selected = '';
                                                $post_state = $postData['state'] ?? '';
                                                if ($state->StateID == $post_state) {
                                                    $selected = 'selected="selected"';
                                                }
                                            @endphp
                                            <option value="{{ $state->StateID }}" {{ $selected }}>
                                                {{ $state->StateName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Telephone</label>
                                    <input type="tel" name="telephone">
                                </div>
                                <div class="form-field">
                                    <label>Postal Code <span class="">*</span></label>
                                    <input type="text" name="postal_code" required>
                                </div>
                            </div>

                            

                            <div class="captcha-section">
                                <label>Enter the word you see below:</label>
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
                                    <input type="text" name="captcha" value="" required />
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
                    const allRequiredInputs = currentSection.querySelectorAll(
                        'input, select, textarea');
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
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            prevButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (currentStep > 1) {
                        showStep(currentStep - 1);
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                });
            });

        });

        // Dynamic Quantity Fields Management
        document.addEventListener('DOMContentLoaded', function() {
            const addQtyBtn = document.getElementById('add-qty-btn');
            const quantityContainer = document.getElementById('quantity-container');
            let qtyCount = 1;

            if (addQtyBtn && quantityContainer) {
                addQtyBtn.addEventListener('click', function() {
                    qtyCount++;
                    
                    // Create new quantity field (without label for additional fields)
                    const newQtyField = document.createElement('div');
                    newQtyField.className = 'quantity-field';
                    newQtyField.innerHTML = `
                        <input type="text" name="qty_${qtyCount}" placeholder="Qty">
                        <button type="button" class="remove-qty-btn" onclick="removeQtyField(this)">Remove</button>
                    `;
                    
                    quantityContainer.appendChild(newQtyField);
                    
                    // Limit to 10 quantity fields
                    if (qtyCount >= 10) {
                        addQtyBtn.style.display = 'none';
                    }
                });
            }
        });

        // Remove quantity field function
        window.removeQtyField = function(button) {
            const field = button.parentElement;
            const container = field.parentElement;
            const addBtn = document.getElementById('add-qty-btn');
            
            field.remove();
            
            // Re-index remaining fields (keep labels as "Qty" but update input names)
            const remainingFields = container.querySelectorAll('.quantity-field');
            remainingFields.forEach((field, index) => {
                const input = field.querySelector('input');
                if (input) input.name = `qty_${index + 1}`;
            });
            
            // Update count and show add button if needed
            const currentCount = remainingFields.length;
            if (currentCount < 10 && addBtn) {
                addBtn.style.display = 'block';
            }
        };

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
                $('#stateiD').html('<option value="">{!! $language_name == 'French' ? "-- Sélectionnez l\\'état --" : '-- Select State --' !!}</option>');
            }
        }
    </script>
@endpush
