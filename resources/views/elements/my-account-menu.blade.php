@php
    $language_name = config('store.language_name', 'english');
    $BASE_URL = url('/');
@endphp
<div class="account-points">
    <div class="mobile-navigation">
        <div class="row align-items-row">
            <div class="col-8 col-md-8">
                <div class="universal-light-info">
                    <span>
                    {{ ($language_name == 'french') ? 'La navigation' : 'Navigation' }}</span>
                </div>
            </div>
            <div class="col-4 col-md-4 text-right">
                <div class="account-icon">
                    <i class="las la-bars"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="account-single-points">
        <ul>
            <li><a href="{{ $BASE_URL }}/MyOrders">
            {{ ($language_name == 'french') ? 'Historique des commandes' : 'Order History' }}</a></li>
            <li><a href="{{ $BASE_URL }}/MyAccounts">
            {{ ($language_name == 'french') ? 'Modifier le compte' : 'Edit Account' }}</a></li>
            <li><a href="{{ $BASE_URL }}/MyAccounts/changePassword">
            {{ ($language_name == 'french') ? 'Changer le mot de passe' : 'Change Password' }}</a></li>
            <li><a href="{{ $BASE_URL }}/MyAccounts/manageAddress">
            {{ ($language_name == 'french') ? 'Gérer les adresses' : 'Manage Addresses' }}</a></li>
            <!-- <li><a href="{{ $BASE_URL }}/Wishlists">Wishlist</a></li>  -->

            <!-- <li><a href="{{ $BASE_URL }}/Tickets/index/">Support</a></li> -->
            <!-- <li><a href="{{ $BASE_URL }}/MyAccounts/notification">Notifications</a></li> -->
            <li><a href="{{ $BASE_URL }}/MyAccounts/logout">
            {{ ($language_name == 'french') ? 'Se déconnecter' : 'Logout' }}</a></li>
        </ul>
    </div>
</div>
