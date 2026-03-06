{{-- CI: application/views/Wishlists/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? ($language_name == 'french' ? 'Liste de souhaits' : 'Wishlist'))

@section('content')
@php
    $language_name = config('store.language_name', 'english');
    $product_price_currency_symbol = config('app.currency_symbol', '$');
@endphp

<style>
.wishlist-section {
    padding: 60px 0;
}

.wishlist-header {
    margin-bottom: 40px;
    text-align: center;
}

.wishlist-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 10px;
}

.wishlist-header p {
    font-size: 16px;
    color: #666;
}

.shop-cart-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
}

.shop-cart-table thead {
    background: #f8f9fa;
}

.shop-cart-table thead th {
    padding: 20px 15px;
    text-align: center;
    font-weight: 600;
    color: #333;
    font-size: 14px;
    text-transform: uppercase;
    border-bottom: 2px solid #e0e0e0;
}

.shop-cart-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.3s ease;
}

.shop-cart-table tbody tr:hover {
    background-color: #f9f9f9;
}

.shop-cart-table tbody td {
    padding: 20px 15px;
    vertical-align: middle;
    text-align: center;
}

.product-remove {
    width: 50px;
    text-align: center;
}

.product-remove .remove {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: #ff4444;
    color: #fff;
    border-radius: 50%;
    font-size: 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.product-remove .remove:hover {
    background: #cc0000;
    transform: scale(1.1);
}

.product-thumbnail {
    width: 120px;
}

.product-thumbnail img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.product-name {
    min-width: 250px;
}

.product-name a {
    color: #333;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-name a:hover {
    color: #007bff;
}

.product-availability {
    font-size: 14px;
    margin-top: 5px;
}

.in-stock {
    color: #28a745;
    font-weight: 600;
}

.out-of-stock {
    color: #dc3545;
    font-weight: 600;
}

.product-price1 {
    width: 150px;
}

.product-price1 .new-price {
    font-size: 18px;
    font-weight: 700;
    color: #333;
}

.product-actions {
    width: 200px;
}

.btn-add-to-cart {
    padding: 10px 20px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-add-to-cart:hover {
    background: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}

.btn-add-to-cart:disabled {
    background: #ccc;
    cursor: not-allowed;
    transform: none;
}

.wishlist-actions {
    margin-top: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
}

.wishlist-actions .btn {
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-continue {
    background: #6c757d;
    color: #fff;
}

.btn-continue:hover {
    background: #5a6268;
}

.btn-share {
    background: #17a2b8;
    color: #fff;
}

.btn-share:hover {
    background: #138496;
}

.btn-clear {
    background: #dc3545;
    color: #fff;
}

.btn-clear:hover {
    background: #c82333;
}

.empty-wishlist {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.empty-wishlist i {
    font-size: 80px;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-wishlist h4 {
    font-size: 24px;
    color: #666;
    margin-bottom: 15px;
}

.empty-wishlist p {
    font-size: 16px;
    color: #999;
    margin-bottom: 30px;
}

.empty-wishlist .btn-shop {
    padding: 12px 40px;
    background: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.empty-wishlist .btn-shop:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .shop-cart-table {
        display: block;
        overflow-x: auto;
    }
    
    .wishlist-actions {
        flex-direction: column;
        gap: 10px;
    }
    
    .wishlist-actions .btn {
        width: 100%;
        text-align: center;
    }
}
</style>
<!-- 
<div class="wishlist-section universal-spacing universal-bg-white">
    <div class="container">
        {{-- Wishlist Header --}}
        <div class="wishlist-header">
            <h1>
                @if($language_name == 'french')
                    Ma Liste de Souhaits
                @else
                    My Wishlist
                @endif
            </h1>
            <p>
                @if($language_name == 'french')
                    Gérez vos produits préférés et ajoutez-les facilement à votre panier
                @else
                    Manage your favorite products and easily add them to your cart
                @endif
            </p>
        </div>

        <div class="cart-section-inner">
            @if(!empty($wishlists) && count($wishlists) > 0)
                <table class="shop-cart-table" id="tableWishList">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{ $language_name == 'french' ? 'Produit' : 'Product' }}</th>
                            <th>{{ $language_name == 'french' ? 'Prix' : 'Price' }}</th>
                            <th>{{ $language_name == 'french' ? 'Disponibilité' : 'Availability' }}</th>
                            <th>{{ $language_name == 'french' ? 'Actions' : 'Actions' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wishlists as $list)
                            @php
                                $imageurl = !empty($list->product_image) 
                                    ? url('uploads/products/' . $list->product_image) 
                                    : url('assets/images/default-product.png');
                                $productId = base64_encode($list->product_id);
                                $productName = $language_name == 'french' 
                                    ? ucfirst($list->name_french ?? $list->name) 
                                    : ucfirst($list->name);
                                $isInStock = $list->is_stock == 1;
                                $isActive = $list->product_status == 1;
                            @endphp
                            <tr id="wishlist-row-{{ $list->id }}">
                                <td class="product-remove">
                                    <a href="javascript:void(0)" 
                                       class="remove" 
                                       onclick="deleteWishlist({{ $list->id }}, 1)"
                                       title="{{ $language_name == 'french' ? 'Supprimer' : 'Remove' }}">
                                        ×
                                    </a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="{{ url('Products/view/' . $productId) }}">
                                        <img src="{{ $imageurl }}" alt="{{ $productName }}">
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ url('Products/view/' . $productId) }}">
                                        <span>{{ $productName }}</span>
                                    </a>
                                    <div class="product-availability">
                                        @if($isActive && $isInStock)
                                            <span class="in-stock">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $language_name == 'french' ? 'En stock' : 'In Stock' }}
                                            </span>
                                        @else
                                            <span class="out-of-stock">
                                                <i class="fas fa-times-circle"></i>
                                                {{ $language_name == 'french' ? 'Rupture de stock' : 'Out of Stock' }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="product-price1">
                                    <span class="new-price">{{ $product_price_currency_symbol }}{{ number_format($list->price, 2) }}</span>
                                </td>
                                <td class="product-availability">
                                    @if($isActive && $isInStock)
                                        <span class="in-stock">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $language_name == 'french' ? 'Disponible' : 'Available' }}
                                        </span>
                                    @else
                                        <span class="out-of-stock">
                                            <i class="fas fa-times-circle"></i>
                                            {{ $language_name == 'french' ? 'Indisponible' : 'Unavailable' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="product-actions">
                                    @if($isActive && $isInStock)
                                        <button type="button" 
                                                class="btn-add-to-cart" 
                                                onclick="moveToCart({{ $list->product_id }}, {{ $list->id }})">
                                            <i class="fas fa-shopping-cart"></i>
                                            {{ $language_name == 'french' ? 'Ajouter au panier' : 'Add to Cart' }}
                                        </button>
                                    @else
                                        <button type="button" class="btn-add-to-cart" disabled>
                                            {{ $language_name == 'french' ? 'Non disponible' : 'Unavailable' }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Wishlist Actions --}}
                <div class="wishlist-actions">
                    <a href="{{ url('Products') }}" class="btn btn-continue">
                        <i class="fas fa-arrow-left"></i>
                        {{ $language_name == 'french' ? 'Continuer vos achats' : 'Continue Shopping' }}
                    </a>
                    <div>
                        <button type="button" class="btn btn-share" onclick="shareWishlist()">
                            <i class="fas fa-share-alt"></i>
                            {{ $language_name == 'french' ? 'Partager la liste' : 'Share Wishlist' }}
                        </button>
                        <button type="button" class="btn btn-clear" onclick="clearWishlist()">
                            <i class="fas fa-trash-alt"></i>
                            {{ $language_name == 'french' ? 'Vider la liste' : 'Clear Wishlist' }}
                        </button>
                    </div>
                </div>
            @else
                {{-- Empty Wishlist State --}}
                <div class="empty-wishlist">
                    <i class="fas fa-heart"></i>
                    <h4>
                        @if($language_name == 'french')
                            Votre liste de souhaits est vide
                        @else
                            Your Wishlist is Empty
                        @endif
                    </h4>
                    <p>
                        @if($language_name == 'french')
                            Explorez nos produits et ajoutez vos favoris à votre liste de souhaits
                        @else
                            Explore our products and add your favorites to your wishlist
                        @endif
                    </p>
                    <a href="{{ url('Products') }}" class="btn-shop">
                        <i class="fas fa-shopping-bag"></i>
                        {{ $language_name == 'french' ? 'Commencer vos achats' : 'Start Shopping' }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var language_name = '{{ $language_name }}';
    
    /**
     * Delete item from wishlist
     * CI: Wishlists->deleteWishlist()
     */
    window.deleteWishlist = function(wishlistId, type) {
        if (!confirm(language_name == 'french' 
            ? 'Êtes-vous sûr de vouloir supprimer cet article de votre liste de souhaits?' 
            : 'Are you sure you want to remove this item from your wishlist?')) {
            return;
        }
        
        $.ajax({
            type: 'POST',
            url: '{{ url("Wishlists/deleteWishlist") }}',
            data: {
                wishlist_id: wishlistId,
                type: type,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#loader-img').show();
            },
            success: function(response) {
                $('#loader-img').hide();
                let data = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (data.status == 1) {
                    $('#wishlist-row-' + wishlistId).fadeOut(300, function() {
                        $(this).remove();
                        
                        // Check if wishlist is empty
                        if ($('#tableWishList tbody tr').length === 0) {
                            location.reload();
                        }
                    });
                    
                    // Update wishlist count in header
                    if (data.wishlist_count !== undefined) {
                        $('.wishlist-count').text(data.wishlist_count);
                    }
                    
                    // Show success message
                    showMessage(data.msg, 'success');
                } else {
                    showMessage(data.msg, 'error');
                }
            },
            error: function() {
                $('#loader-img').hide();
                showMessage(
                    language_name == 'french' 
                        ? 'Une erreur s\'est produite' 
                        : 'An error occurred',
                    'error'
                );
            }
        });
    };
    
    /**
     * Move item to cart
     * CI: Wishlists->moveToCart()
     */
    window.moveToCart = function(productId, wishlistId) {
        $.ajax({
            type: 'POST',
            url: '{{ url("Wishlists/moveToCart") }}',
            data: {
                product_id: productId,
                wishlist_id: wishlistId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#loader-img').show();
            },
            success: function(response) {
                $('#loader-img').hide();
                let data = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (data.status == 1) {
                    // Remove from wishlist display
                    $('#wishlist-row-' + wishlistId).fadeOut(300, function() {
                        $(this).remove();
                        
                        if ($('#tableWishList tbody tr').length === 0) {
                            location.reload();
                        }
                    });
                    
                    // Update cart count
                    if (data.cart_count !== undefined) {
                        $('.cart-count').text(data.cart_count);
                    }
                    
                    // Update wishlist count
                    if (data.wishlist_count !== undefined) {
                        $('.wishlist-count').text(data.wishlist_count);
                    }
                    
                    showMessage(data.msg, 'success');
                } else {
                    showMessage(data.msg, 'error');
                }
            },
            error: function() {
                $('#loader-img').hide();
                showMessage(
                    language_name == 'french' 
                        ? 'Une erreur s\'est produite' 
                        : 'An error occurred',
                    'error'
                );
            }
        });
    };
    
    /**
     * Share wishlist
     * CI: Wishlists->shareWishlist()
     */
    window.shareWishlist = function() {
        $.ajax({
            type: 'GET',
            url: '{{ url("Wishlists/shareWishlist") }}',
            beforeSend: function() {
                $('#loader-img').show();
            },
            success: function(response) {
                $('#loader-img').hide();
                let data = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (data.status == 1 && data.share_url) {
                    // Copy to clipboard
                    navigator.clipboard.writeText(data.share_url).then(function() {
                        showMessage(
                            language_name == 'french' 
                                ? 'Lien de partage copié dans le presse-papiers!' 
                                : 'Share link copied to clipboard!',
                            'success'
                        );
                    }).catch(function() {
                        // Fallback: show URL in alert
                        prompt(
                            language_name == 'french' 
                                ? 'Copiez ce lien pour partager votre liste de souhaits:' 
                                : 'Copy this link to share your wishlist:',
                            data.share_url
                        );
                    });
                } else {
                    showMessage(data.msg || 'Error generating share link', 'error');
                }
            },
            error: function() {
                $('#loader-img').hide();
                showMessage(
                    language_name == 'french' 
                        ? 'Une erreur s\'est produite' 
                        : 'An error occurred',
                    'error'
                );
            }
        });
    };
    
    /**
     * Clear entire wishlist
     * CI: Wishlists->clearWishlist()
     */
    window.clearWishlist = function() {
        if (!confirm(language_name == 'french' 
            ? 'Êtes-vous sûr de vouloir vider toute votre liste de souhaits?' 
            : 'Are you sure you want to clear your entire wishlist?')) {
            return;
        }
        
        $.ajax({
            type: 'GET',
            url: '{{ url("Wishlists/clearWishlist") }}',
            beforeSend: function() {
                $('#loader-img').show();
            },
            success: function(response) {
                $('#loader-img').hide();
                let data = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (data.status == 1) {
                    location.reload();
                } else {
                    showMessage(data.msg, 'error');
                }
            },
            error: function() {
                $('#loader-img').hide();
                showMessage(
                    language_name == 'french' 
                        ? 'Une erreur s\'est produite' 
                        : 'An error occurred',
                    'error'
                );
            }
        });
    };
    
    /**
     * Show message helper
     */
    function showMessage(message, type) {
        var messageClass = type === 'success' ? 'addtocart-message' : 'addwishlist-message';
        var $messageDiv = $('.' + messageClass);
        
        $messageDiv.html('<span>' + message + '</span>');
        $messageDiv.fadeIn(300).delay(3000).fadeOut(300);
    }
});
</script> -->
<div class="cart-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="cart-section-inner">
            @if(!empty($wishlists) && $wishlists->count() > 0)
                <table class="shop-cart-table" id="tableWishList">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>Product</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wishlists as $list)
                            @php
                                $imageurl = getProductImage($list->product_image, 'medium');
                            @endphp
                            <tr>
                                <td class="product-remove">
                                    <a href="javascript:void(0)" 
                                       class="remove"
                                       onclick="deleteWishlist({{ $list->id }}, 1)">
                                        ×
                                    </a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="{{ url('Products/view/' . base64_encode($list->product_id)) }}">
                                        <img src="{{ $imageurl }}" alt="{{ $list->name }}">
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ url('Products/view/' . base64_encode($list->product_id)) }}">
                                        <span>{{ ucfirst($list->name) }}</span>
                                    </a>
                                </td>
                                <td class="product-price1">
                                    <span class="new-price">
                                        {{ config('app.currency_symbol') . number_format($list->price, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="actions">
                                <div class="coupon">
                                    <a href="{{ url('Products') }}">
                                        <button type="button">Update Wishlist</button>
                                    </a>
                                </div>
                            </td>
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="text-center">
                    <h4 class="lead">{{ __('Wishlist Empty') }}</h4>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


