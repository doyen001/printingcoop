<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\CartService;
use App\Models\Product;

/**
 * ShoppingCartsController
 * CI: application/controllers/ShoppingCarts.php
 */
class ShoppingCartsController extends Controller
{
    protected $cart;
    protected $language_name;
    
    public function __construct()
    {
        $this->cart = new CartService();
        $this->language_name = strtolower(config('store.language_name', 'english'));
    }
    
    /**
     * Display shopping cart page
     * CI: ShoppingCarts->index() lines 19-29
     */
    public function index()
    {
        $data = [
            'page_title' => $this->language_name == 'french' ? 'Panier' : 'Shopping Cart',
            'language_name' => $this->language_name,
            'cart' => $this->cart,
        ];
        
        return view('shopping_carts.index', $data);
    }
    
    /**
     * Add product to cart
     * Delegates to ProductsController::addToCart to keep a single
     * CI-aligned implementation of the add-to-cart logic.
     */
    public function addToCart(Request $request)
    {
        $productsController = app(\App\Http\Controllers\ProductsController::class);
        return $productsController->addToCart($request);
    }
    
    /**
     * Get product attributes from IDs
     */
    protected function getProductAttributes($product_id, $params)
    {
        $attributes = $params['attributes'] ?? [];
        $productOptions = [];
        
        // This would call Product model method - simplified for now
        // In full implementation, this should call attributeDataFromIds
        
        $custom = [];
        $custom_fields = [
            'width' => ['attribute_name_real' => 'Width', 'attribute_name' => 'Width', 'attribute_name_french' => 'Largeur'],
            'length' => ['attribute_name_real' => 'Length', 'attribute_name' => 'Length', 'attribute_name_french' => 'Longueur'],
        ];
        
        if (isset($params['custom'])) {
            foreach ($params['custom'] as $custom_item) {
                if (($custom_item['use'] ?? 0) == 1) {
                    foreach ($custom_item as $key => $value) {
                        if (!array_key_exists($key, $custom_fields)) continue;
                        $custom[] = [
                            'attribute_name_real' => $custom_fields[$key]['attribute_name_real'],
                            'attribute_name' => $custom_fields[$key]['attribute_name'],
                            'attribute_name_french' => $custom_fields[$key]['attribute_name_french'],
                            'item_name' => $value,
                            'item_name_french' => $value,
                        ];
                    }
                }
            }
        }
        
        $productOptions['custom'] = $custom;
        return $productOptions;
    }
    
    /**
     * Get product quantity/size dropdown data
     */
    protected function getProductQuantitySizeDropdown($product_id)
    {
        // Simplified - in full implementation this should call Product model
        return [];
    }
    
    /**
     * Calculate width/length pricing
     */
    protected function calculateWidthLength($params, $product_id, $price)
    {
        $product_length = $params['product_length'] ?? null;
        $product_width = $params['product_width'] ?? null;
        $product_total_page = $params['product_total_page'] ?? null;
        $length_width_quantity_show = $params['length_width_quantity_show'] ?? 0;
        $length_width_color = $params['length_width_color'] ?? '';
        
        $Product = DB::table('products')->where('id', $product_id)->first();
        if (!$Product) {
            return ['error' => 'Product not found'];
        }
        
        $Product = (array) $Product;
        
        // Validation
        if (empty($product_length)) {
            return ['error' => $this->language_name == 'french' ? 'Veuillez saisir la longueur' : 'Please enter length'];
        }
        
        if ($product_length < $Product['min_length'] || $product_length > $Product['max_length']) {
            $msg = $this->language_name == 'french'
                ? "Veuillez saisir la longueur entre {$Product['min_length']} et {$Product['max_length']}"
                : "Please length enter between {$Product['min_length']} and {$Product['max_length']}";
            return ['error' => $msg];
        }
        
        if (empty($product_width)) {
            return ['error' => $this->language_name == 'french' ? 'Veuillez saisir la largeur' : 'Please enter width'];
        }
        
        if ($product_width < $Product['min_width'] || $product_width > $Product['max_width']) {
            $msg = $this->language_name == 'french'
                ? "Veuillez saisir la largeur entre {$Product['min_width']} et {$Product['max_width']}"
                : "Please width enter between {$Product['min_width']} and {$Product['max_width']}";
            return ['error' => $msg];
        }
        
        $rq_area = $product_length * $product_width;
        $extra_price = 0;
        
        if ($Product['length_width_color_show'] == 1) {
            if ($length_width_color == 'black') {
                $extra_price = $Product['length_width_unit_price_black'] * $rq_area;
            } else if ($length_width_color == 'color') {
                $extra_price = $Product['length_width_price_color'] * $rq_area;
            } else {
                $extra_price = $Product['min_length_min_width_price'] * $rq_area;
            }
        } else {
            $extra_price = $Product['min_length_min_width_price'] * $rq_area;
        }
        
        $product_total_page_label = '';
        if ($length_width_quantity_show == 1 && !empty($product_total_page)) {
            $extra_price = $product_total_page * $extra_price;
            $product_total_page_label = $product_total_page;
        }
        
        $price += $extra_price;
        
        return [
            'price' => $price,
            'data' => [
                'product_width' => $product_width,
                'product_length' => $product_length,
                'product_total_page' => $product_total_page_label,
                'length_width_color_show' => $Product['length_width_color_show'],
                'length_width_color' => $length_width_color,
                'length_width_color_french' => $length_width_color == 'black' ? 'Noire' : 'Couleur',
            ]
        ];
    }
    
    /**
     * Calculate depth/width/length pricing
     */
    protected function calculateDepthWidthLength($params, $product_id, $price)
    {
        // Simplified implementation - similar to calculateWidthLength
        return ['price' => $price, 'data' => []];
    }
    
    /**
     * Calculate page width/length pricing
     */
    protected function calculatePageWidthLength($params, $product_id, $price)
    {
        // Simplified implementation - similar to calculateWidthLength
        return ['price' => $price, 'data' => []];
    }
    
    /**
     * Update cart item quantity
     * CI: ShoppingCarts->updateCartItem() lines 819-851
     */
    public function updateCartItem(Request $request)
    {
        $json = ['status' => 0, 'msg' => ''];
        
        $rowId = $request->input('rowId');
        $quantity = $request->input('quantity');
        $product_id = $request->input('product_id');
        
        $productData = DB::table('products')->where('id', $product_id)->first();
        
        $data = [
            'rowid' => $rowId,
            'qty' => $quantity,
        ];
        
        if ($this->cart->update($data)) {
            $row = $this->cart->getItem($rowId);
            $json['status'] = 1;
            $json['total_item'] = $this->cart->totalItems();
            $json['sub_total'] = config('app.currency_symbol', '$') . number_format($this->cart->total(), 2);
            $json['row_sub_total'] = config('app.currency_symbol', '$') . number_format($row['subtotal'], 2);
            $json['row_id'] = $rowId;
            $json['product_id'] = $row['id'];
            $json['msg'] = $this->language_name == 'french'
                ? ucfirst(strtolower(($productData->name_french ?? '') . ' a été mis à jour dans votre panier.'))
                : ucfirst(strtolower(($productData->name ?? '') . ' has been updated to your shopping cart.'));
        } else {
            $json['msg'] = $this->language_name == 'french'
                ? "La mise à jour de l'article du panier a été effectuée"
                : 'Shopping cart item update has been failed';
        }
        
        // return response()->json($json);
        echo json_encode($json);
    }
    
    /**
     * Remove item from cart
     * CI: ShoppingCarts->removeCartItem() lines 743-764
     */
    public function removeCartItem(Request $request)
    {
        $json = ['status' => 0, 'msg' => ''];
        
        $rowId = $request->input('rowId');
        
        // Get cart item before removing to clean up session
        $cartItem = $this->cart->getItem($rowId);
        
        if ($this->cart->remove($rowId)) {
            // Clean up uploaded images from session for this product
            if ($cartItem && isset($cartItem['options']['product_id'])) {
                $product_id = $cartItem['options']['product_id'];
                if (session()->has('product_id.' . $product_id)) {
                    session()->forget('product_id.' . $product_id);
                }
            }
            
            $json['status'] = 1;
            $json['total_item'] = $this->cart->totalItems();
            $json['sub_total'] = config('app.currency_symbol', '$') . number_format($this->cart->total(), 2);
            $json['msg'] = $this->language_name == 'french'
                ? "L'article a été supprimé de votre panier."
                : 'Item has been removed from your shopping cart.';
        } else {
            $json['msg'] = $this->language_name == 'french'
                ? "L'élément du panier d'achat a été supprimé"
                : 'Shopping cart item remove has been failed';
        }
        
        // return response()->json($json);
        echo json_encode($json);
    }
    
    /**
     * Get cart items via AJAX for header dropdown
     * CI: ShoppingCarts->getCartItemByAjax() lines 853-857
     */
    public function getCartItemByAjax()
    {
        $data = [
            'BASE_URL' => url('/'),
            'cart' => $this->cart,
            'language_name' => $this->language_name,
        ];
        
        return view('elements.cart-items', $data);
    }
    
    /**
     * Save personalized image
     * CI: ShoppingCarts->saveImage() lines 859-888
     */
    public function saveImage(Request $request)
    {
        $base64_image = $request->input('base64_image');
        $base64_str = substr($base64_image, strpos($base64_image, ",") + 1);
        $decoded = base64_decode($base64_str);
        $png_url = "product-" . time() . ".png";
        
        $uploadPath = public_path('uploads/personailise');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        $result = file_put_contents($uploadPath . '/' . $png_url, $decoded);
        
        if ($result) {
            if (session()->has('personailise_image')) {
                $oldImage = session('personailise_image');
                if (file_exists($uploadPath . '/' . $oldImage)) {
                    unlink($uploadPath . '/' . $oldImage);
                }
            }
            session(['personailise_image' => $png_url]);
            echo json_encode($png_url);
        } else {
            echo json_encode(0);
        }
    }
}
