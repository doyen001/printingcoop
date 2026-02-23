@php
    $website_store_id = config('store.website_store_id', 1);
    $language_name = config('store.language_name', 'english');
@endphp
<!--Warning Modal -->
<div class="modal fade warning-modal" id="WarningModal" tabindex="-1" role="dialog" aria-labelledby="WarningModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="eWarningModalTitle">MESSAGE: COVID19</h5>
                <a href="javascript:void(0)" onclick="COVIDMSGClose()">
                    <button type="button" class="close" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </a>
              </div>
              <div class="modal-body">
                <div class="universal-dark-info">
                      @if($website_store_id == 1)
                          <span>
                            @if($language_name == 'french')
                                Suite à l'annonce du gouvernement concernant les commerces essentiels et non essentiels, nous prenons le temps de vous aviser que nos services se poursuivent puisque nos activités manufacturières apparaissent sur la liste des services essentiels.
                                <br>
                                Nous offrirons un service restreint d'impressions en ligne pour les entreprises qui sont considérées essentielles. Tel que les restaurateurs et les services alimentaires.
                                <br>
                                Nous continuerons donc à vous servir en toute sécurité tout en respectant les consignes sanitaires mises en place.<br> <br>Si ce service vous intéresse
                                <br>
                                1) SVP, faire parvenir vos demandes en ligne (imprimeur.coop) ou par courriel à info@imprimeur.coop avec les détails: fichier, quantités, formats, types de papier ou carton, types de finition.
                                <br>
                                2) Un paiement par carte de crédit, Interac e-Transfer ou PAYPAL (info@printing.coop) sera demandé
                                <br>
                                3) le travail sera produit et pourrait être récupéré à la porte du magasin (sans contact) ou livré.
                                <br>
                                Pour toutes questions ou urgences, contactez  au 514-550-8043.
                                <br>
                                Cordialement,
                                <br>
                                L'équipe de imprimeur.coop
                            @else
                                Following the government announcement about essential and non-essential businesses, we're taking this moment to inform you that we will continue our operations since our manufacturing activities are on the essential services list.
                                <br>
                                Our online printing service continues to serve businesses that are considered essential such as restaurateurs and food services safely.
                                <br>
                                If you are interested in this service
                                <br>
                                1) Please visit our website www,printing.coop or send the files to the following email: info@printing.coop
                                <br>
                                with the details: quantities, formats, type of paper or cardboard, type of finish.
                                <br>
                                2) Payment by credit card, Interac e-Transfer or Paypal (info@printing.coop)
                                <br>
                                3) The work will be produced and can be picked up at the door of the store (contactless).
                                <br>
                                If you have any questions or for any emergency, please contact us at 514-384-8043
                                <br>
                                Best regards,
                                <br>
                                Printing.coop team
                            @endif
                        </span>
                      @elseif($website_store_id == 3)
                          <span>
                            @if($language_name == 'french')
                                Suite à l'annonce du gouvernement concernant les commerces essentiels et non essentiels, nous prenons le temps de vous aviser que nos services se poursuivent puisque nos activités manufacturières apparaissent sur la liste des services essentiels.
                                <br>
                                Nous offrirons un service restreint d'impressions en ligne pour les entreprises qui sont considérées essentielles. Tel que les restaurateurs et les services alimentaires.
                                <br>
                                Nous continuerons donc à vous servir en toute sécurité tout en respectant les consignes sanitaires mises en place.<br> <br>Si ce service vous intéresse
                                <br>
                                1) SVP, faire parvenir vos demandes en ligne (www.fr.clickimprimerie.com) ou par courriel à info@frclickimprimerie.com avec les détails: fichier, quantités, formats, types de papier ou carton, types de finition.
                                <br>
                                2) Un paiement par carte de crédit, Interac e-Transfer ou PAYPAL (info@frclickimprimerie.com) sera demandé
                                <br>
                                3) le travail sera produit et pourrait être récupéré à la porte du magasin (sans contact) ou livré.
                                <br>
                                Pour toutes questions ou urgences, contactez  au 514-544-8043.
                                <br>
                                Cordialement,
                                <br>
                                L'équipe de imprimeur.coop
                            @else
                                Following the government announcement about essential and non-essential businesses, we're taking this moment to inform you that we will continue our operations since our manufacturing activities are on the essential services list.
                                <br>
                                Our online printing service continues to serve businesses that are considered essential such as restaurateurs and food services safely.
                                <br>
                                If you are interested in this service
                                <br>
                                1) Please visit our website www.clickimprimerie.com or send the files to the following email: info@clickimprimerie.com
                                <br>
                                with the details: quantities, formats, type of paper or cardboard, type of finish.
                                <br>
                                2) Payment by credit card, Interac e-Transfer or Paypal (info@clickimprimerie.com)
                                <br>
                                3) The work will be produced and can be picked up at the door of the store (contactless).
                                <br>
                                If you have any questions or for any emergency, please contact us at 514-544-8043
                                <br>
                                Best regards,
                                <br>
                                Printing.coop team
                            @endif
                        </span>
                      @elseif($website_store_id == 5)
                        <span>
                            @if($language_name == 'french')
                                Suite à l'annonce du gouvernement concernant les commerces essentiels et non essentiels, nous prenons le temps de vous aviser que nos services se poursuivent puisque nos activités manufacturières apparaissent sur la liste des services essentiels.
                                <br>
                                Nous offrirons un service restreint d'impressions en ligne pour les entreprises qui sont considérées essentielles. Tel que les restaurateurs et les services alimentaires.
                                <br>
                                Nous continuerons donc à vous servir en toute sécurité tout en respectant les consignes sanitaires mises en place.<br> <br>Si ce service vous intéresse
                                <br>
                                1) SVP, faire parvenir vos demandes en ligne (www.fr.ecoink.ca) ou par courriel à info@frecoink.ca avec les détails: fichier, quantités, formats, types de papier ou carton, types de finition.
                                <br>
                                2) Un paiement par carte de crédit, Interac e-Transfer ou PAYPAL (info@frecoink.ca) sera demandé
                                <br>
                                3) le travail sera produit et pourrait être récupéré à la porte du magasin (sans contact) ou livré.
                                <br>
                                Pour toutes questions ou urgences, contactez  au 514-550-8043.
                                <br>
                                Cordialement,
                                <br>
                                L'équipe de imprimeur.coop
                            @else
                                Following the government announcement about essential and non-essential businesses, we're taking this moment to inform you that we will continue our operations since our manufacturing activities are on the essential services list.
                                <br>
                                Our online printing service continues to serve businesses that are considered essential such as restaurateurs and food services safely.
                                <br>
                                If you are interested in this service
                                <br>
                                1) Please visit our website www.ecoink.ca or send the files to the following email: info@ecoink.ca
                                <br>
                                with the details: quantities, formats, type of paper or cardboard, type of finish.
                                <br>
                                2) Payment by credit card, Interac e-Transfer or Paypal (info@ecoink.ca)
                                <br>
                                3) The work will be produced and can be picked up at the door of the store (contactless).
                                <br>
                                If you have any questions or for any emergency, please contact us at 514-384-8043
                                <br>
                                Best regards,
                                <br>
                                Printing.coop team
                            @endif
                        </span>
                    @endif
                </div>
              </div>
        </div>
      </div>
</div>
