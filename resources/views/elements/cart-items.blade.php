{{-- CI: application/views/elements/cart-items.php --}}
@php
    // Use Darryldecode Cart to get cart contents
    $cartService = new \App\Services\CartService();
    $cartCount = $cartService->totalItems();
    $cartContents = $cartService->contents();
@endphp

@if($cartCount > 0)
<div class="cart-selector-content">
    <div class="cart-product-display">
        <table>
            <tbody>
                @foreach($cartContents as $item)
                    @php
                        // Get product data from database
                        $productData = DB::table('products')
                            ->where('id', $item['id'])
                            ->first();
                        
                        // Get product image URL
                        $productImage = $item['options']['product_image'] ?? '';
                        if (!empty($productImage)) {
                            if (strpos($productImage, 'http') === 0) {
                                $imageurl = $productImage;
                            } else {
                                $imageurl = url('uploads/products/' . $productImage);
                            }
                        } else {
                            $imageurl = url('assets/images/default-product.jpg');
                        }
                        
                        // Get product slug for URL
                        $productId = $productData->id ?? '';
                    @endphp
                    <tr>
                        <td style="width: 80px;">
                            <div class="cart-product-img">
                                <a href="{{ url('Products/view/' . base64_encode($productId)) }}">
                                    <img src="{{ $imageurl }}" alt="{{ $productData->name ?? '' }}">
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="cart-product-desc">
                                <div class="cart-product-title">
                                    <a href="{{ url('Products/view/' . base64_encode($productId)) }}">
                                        <span>{{ ucfirst($productData->name ?? '') }}</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="cart-product-quantity">
                                <span>{{ $item['qty'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cart-product-price">
                                <span>{{ $product_price_currency_symbol ?? '$' }}{{ number_format($item['price'], 2) }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="cart-product-delete">
                                <a href="javascript:void(0)" 
                                   onclick="removeCartItem('{{ $item['rowid'] }}','{{ $item['id'] }}')" 
                                   class="remove">×</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="cart-product-total-section">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-8">
                <div class="cart-product-info">
                    <span>{{ $language_name == 'french' ? 'Sous-total:' : 'Subtotal:' }}</span>
                    <strong>{{ $product_price_currency_symbol ?? '$' }}{{ number_format($cartService->total(), 2) }}</strong>
                    <br>
                    <span>{{ $language_name == 'french' ? 'Total:' : 'Total:' }}</span>
                    <strong>{{ $product_price_currency_symbol ?? '$' }}{{ number_format($cartService->total(), 2) }}</strong>
                </div>
            </div>
        </div>
        <div class="cart-product-button">
            <a href="{{ url('ShoppingCarts') }}">
                <button type="button" class="cart-view">
                    {{ $language_name == 'french' ? 'Voir le panier' : 'View cart' }}
                </button>
            </a>
            <a href="{{ url('Checkouts') }}">
                <button type="button" class="cart-checkout">
                    {{ $language_name == 'french' ? 'Check-out' : 'Checkout' }}
                </button>
            </a>
        </div>
    </div>
</div>
@else
<div class="cart-selector-content for-empty">
    <div class="container m-2">
        <div class="universal-small-dark-title text-center">
            <span>
                {{ $language_name == 'french' ? "Vous n'avez aucun article dans votre panier." : 'You have no items in your shopping cart.' }}
            </span>
        </div>
        <div class="cart-product-button text-center">
            <a href="{{ url('Products') }}">
                <button type="button" class="cart-checkout">
                    {{ $language_name == 'french' ? 'Continuer vos achats' : 'Continue Shopping' }}
                </button>
            </a>
        </div>
    </div>
</div>
@endif
