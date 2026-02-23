@php
    $currentRoute = request()->path();
    $currentRouteName = request()->route() ? request()->route()->getName() : '';
@endphp

<div class="account-points">
    <div class="mobile-navigation">
        <div class="row align-items-row">
            <div class="col-8 col-md-8">
                <div class="universal-light-info">
                    <span>
                        {{ $language_name == 'french' ? 'La navigation' : 'Navigation' }}
                    </span>
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
            <li class="{{ str_contains($currentRoute, 'MyOrders') ? 'active' : '' }}">
                <a href="{{ url('MyOrders') }}">
                    {{ $language_name == 'french' ? 'Historique des commandes' : 'Order History' }}
                </a>
            </li>
            <li class="{{ str_contains($currentRoute, 'MyAccounts') && !str_contains($currentRoute, 'changePassword') && !str_contains($currentRoute, 'manageAddress') ? 'active' : '' }}">
                <a href="{{ url('MyAccounts') }}">
                    {{ $language_name == 'french' ? 'Modifier le compte' : 'Edit Account' }}
                </a>
            </li>
            <li class="{{ str_contains($currentRoute, 'MyAccounts/changePassword') ? 'active' : '' }}">
                <a href="{{ url('MyAccounts/changePassword') }}">
                    {{ $language_name == 'french' ? 'Changer le mot de passe' : 'Change Password' }}
                </a>
            </li>
            <li class="{{ str_contains($currentRoute, 'MyAccounts/manageAddress') ? 'active' : '' }}">
                <a href="{{ url('MyAccounts/manageAddress') }}">
                    {{ $language_name == 'french' ? 'Gérer les adresses' : 'Manage Addresses' }}
                </a>
            </li>
            <li class="{{ str_contains($currentRoute, 'Wishlists') ? 'active' : '' }}">
                <a href="{{ url('Wishlists') }}">
                    {{ $language_name == 'french' ? 'Liste de souhaits' : 'Wishlist' }}
                </a>
            </li>
            <li class="{{ str_contains($currentRoute, 'Tickets') ? 'active' : '' }}">
                <a href="{{ url('Tickets/index') }}">
                    {{ $language_name == 'french' ? 'Soutien' : 'Support Tickets' }}
                </a>
            </li>
            <li>
                <a href="{{ url('MyAccounts/logout') }}">
                    {{ $language_name == 'french' ? 'Se déconnecter' : 'Logout' }}
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    .account-points {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .mobile-navigation {
        display: none;
        padding: 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }
    
    .account-icon {
        font-size: 24px;
        color: #333;
    }
    
    .account-single-points ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .account-single-points ul li {
        border-bottom: 1px solid #eee;
    }
    
    .account-single-points ul li:last-child {
        border-bottom: none;
    }
    
    .account-single-points ul li a {
        display: block;
        padding: 15px 20px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .account-single-points ul li a:hover {
        background: #f8f9fa;
        color: #007bff;
        padding-left: 25px;
    }
    
    .account-single-points ul li.active a {
        background: #007bff;
        color: #fff;
        font-weight: 600;
    }
    
    .account-single-points ul li.active a:hover {
        background: #0056b3;
        color: #fff;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .mobile-navigation {
            display: block;
        }
        
        .account-single-points {
            display: none;
        }
        
        .account-single-points.show {
            display: block;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileNav = document.querySelector('.mobile-navigation');
        const accountPoints = document.querySelector('.account-single-points');
        
        if (mobileNav && accountPoints) {
            mobileNav.addEventListener('click', function() {
                accountPoints.classList.toggle('show');
            });
        }
    });
</script>
