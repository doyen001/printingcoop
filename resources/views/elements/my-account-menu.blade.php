@php
    $language_name = config('store.language_name', 'english');
    $BASE_URL = url('/');
@endphp
<style>
    /* Simple Button Styles with Proper Radius */
    .account-points {
        padding: 20px;
    }

    /* Mobile Navigation Button */
    .mobile-navigation {
        background: #f8f9fa;
        padding: 18px 24px;
        border-bottom: 1px solid #e9ecef;
        display: none;
        border-radius: 15px 15px 0 0;
    }

    .mobile-navigation .universal-light-info span {
        color: #2c3e50;
        font-weight: 600;
        font-size: 16px;
    }

    .account-icon {
        cursor: pointer;
        padding: 12px;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: #f28738;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(242, 135, 56, 0.3);
    }

    .account-icon:hover {
        background: #e67628;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(242, 135, 56, 0.4);
    }

    .account-icon i {
        color: #ffffff;
        font-size: 18px;
    }

    /* Menu Button Styles */
    .account-single-points {
        padding: 15px;
    }

    .account-single-points ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .account-single-points li {
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .account-single-points li:last-child {
        margin-bottom: 12px;
    }

    .account-single-points a {
        display: block;
        padding: 16px 20px;
        color: #2c3e50;
        text-decoration: none;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        background: #ffffff;
        border-radius: 10px;
        text-align: left;
        position: relative;
        overflow: hidden;
    }

    .account-single-points a:hover {
        color: #ffffff;
        text-decoration: none;
        background: #f28738;
        border-color: #f28738;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(242, 135, 56, 0.3);
    }

    .account-single-points a:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(242, 135, 56, 0.3);
    }

    /* Button gradient effect */
    .account-single-points a::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .account-single-points a:hover::before {
        left: 100%;
    }

    /* Active state */
    .account-single-points li.active a {
        background: #f28738;
        color: #ffffff;
        border-color: #f28738;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(242, 135, 56, 0.3);
    }

    /* Logout button special styling */
    .account-single-points li:last-child a {
        border-color: #dc3545;
        color: #dc3545;
        font-weight: 600;
        background-color: transparent;
    }

    .account-single-points li:last-child a:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 767px) {
        .mobile-navigation {
            display: block;
            border-radius: 15px 15px 0 0;
        }

        .account-single-points {
            display: none;
            padding: 15px;
        }

        .account-single-points.mobile-open {
            display: block;
        }

        .account-single-points a {
            padding: 14px 18px;
            font-size: 13px;
            border-radius: 8px;
        }
    }

    @media (max-width: 480px) {
        .mobile-navigation {
            padding: 15px 18px;
        }

        .account-single-points {
            padding: 12px;
        }

        .account-single-points a {
            padding: 12px 16px;
            font-size: 12px;
            border-radius: 6px;
        }

        .account-icon {
            padding: 10px;
            border-radius: 8px;
        }
    }
</style>

<div class="account-points">
    <div class="mobile-navigation">
        <div class="row align-items-center">
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

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const accountIcon = document.querySelector('.account-icon');
    const accountPoints = document.querySelector('.account-single-points');
    
    if (accountIcon && accountPoints) {
        accountIcon.addEventListener('click', function() {
            accountPoints.classList.toggle('mobile-open');
        });
    }
});
</script>
