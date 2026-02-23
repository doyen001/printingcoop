<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * WishlistsController - Complete wishlist management
 * CI: application/controllers/Wishlists.php (77 lines)
 */
class WishlistsController extends Controller
{
    /**
     * Check if user is logged in
     */
    protected function checkLogin()
    {
        if (!session('loginId')) {
            return redirect('Logins');
        }
        return null;
    }
    
    /**
     * View wishlist
     * CI: lines 19-26
     */
    public function index()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $wishlists = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->where('wishlists.user_id', $loginId)
            ->select(
                'wishlists.*',
                'products.name',
                'products.name_french',
                'products.product_image',
                'products.price',
                'products.status as product_status',
                'products.is_stock'
            )
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Liste de souhaits' : 'Wishlist',
            'wishlists' => $wishlists,
        ];
        
        return view('wishlists.index', $data);
    }
    
    /**
     * Add to wishlist via AJAX
     * CI: lines 28-54
     */
    public function addByAjax(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $json = ['status' => 0, 'msg' => ''];
        
        if (!session('loginId')) {
            $json['msg'] = $language_name == 'french' 
                ? 'Veuillez vous connecter en premier'
                : 'Please login first';
            echo json_encode($json);
            return;
        }
        
        $loginId = session('loginId');
        $product_id = $request->input('product_id');
        
        $product = DB::table('products')->where('id', $product_id)->first();
        
        if (!empty($product)) {
            $count = DB::table('wishlists')
                ->where('user_id', $loginId)
                ->where('product_id', $product_id)
                ->count();
            
            if ($count == 0) {
                DB::table('wishlists')->insert([
                    'user_id' => $loginId,
                    'product_id' => $product_id,
                    'created' => now(),
                    'updated' => now(),
                ]);
                
                $totalCount = DB::table('wishlists')->where('user_id', $loginId)->count();
                
                $json['status'] = 1;
                $json['count'] = $totalCount;
                $json['msg'] = $language_name == 'french'
                    ? ucfirst(strtolower($product->name_french ?? $product->name)) . ' est ajouté à votre liste de souhaits.'
                    : ucfirst(strtolower($product->name)) . ' is added to your wishlist.';
            } else {
                $json['msg'] = $language_name == 'french'
                    ? 'Ce produit est déjà ajouté dans votre liste de souhaits.'
                    : 'This product is already added in your wishlist.';
            }
        } else {
            $json['msg'] = $language_name == 'french'
                ? "Le produit n'existe pas"
                : 'Product does not exist';
        }
        
        echo json_encode($json);
    }
    
    /**
     * Delete from wishlist via AJAX
     * CI: lines 56-75
     */
    public function deleteWishlist(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $id = $request->input('wishlist_id');
        $json = ['status' => 0, 'msg' => ''];
        
        if (!session('loginId')) {
            $json['msg'] = $language_name == 'french'
                ? 'Veuillez vous connecter en premier'
                : 'Please login first';
            echo json_encode($json);
            return;
        }
        
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $deleted = DB::table('wishlists')
                ->where('id', $id)
                ->where('user_id', $loginId)
                ->delete();
            
            if ($deleted) {
                $count = DB::table('wishlists')->where('user_id', $loginId)->count();
                
                $json['status'] = 1;
                $json['count'] = $count;
                $json['msg'] = $language_name == 'french'
                    ? 'Produit supprimé de la liste de souhaits avec succès.'
                    : 'Product removed from wishlist successfully.';
            } else {
                $json['msg'] = $language_name == 'french'
                    ? 'Échec de la suppression du produit de la liste de souhaits.'
                    : 'Product removal from wishlist unsuccessful.';
            }
        } else {
            $json['msg'] = $language_name == 'french'
                ? 'Informations manquantes.'
                : 'Missing information.';
        }
        
        echo json_encode($json);
    }
    
    /**
     * Remove from wishlist (GET method)
     */
    public function remove($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $deleted = DB::table('wishlists')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->delete();
        
        if ($deleted) {
            $message = $language_name == 'french'
                ? 'Produit supprimé de la liste de souhaits avec succès.'
                : 'Product removed from wishlist successfully.';
            return redirect('Wishlists')->with('message_success', $message);
        }
        
        return redirect('Wishlists')->with('message_error', 'Failed to remove product');
    }
    
    /**
     * Move wishlist item to cart
     */
    public function moveToCart($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $wishlistItem = DB::table('wishlists')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->first();
        
        if ($wishlistItem) {
            // Check if product already in cart
            $cartItem = DB::table('shopping_carts')
                ->where('user_id', $loginId)
                ->where('product_id', $wishlistItem->product_id)
                ->first();
            
            if ($cartItem) {
                // Update quantity
                DB::table('shopping_carts')
                    ->where('id', $cartItem->id)
                    ->increment('quantity');
            } else {
                // Add to cart
                $product = DB::table('products')->where('id', $wishlistItem->product_id)->first();
                
                DB::table('shopping_carts')->insert([
                    'user_id' => $loginId,
                    'product_id' => $wishlistItem->product_id,
                    'quantity' => 1,
                    'price' => $product->price ?? 0,
                    'created' => now(),
                    'updated' => now(),
                ]);
            }
            
            // Remove from wishlist
            DB::table('wishlists')->where('id', $id)->delete();
            
            $message = $language_name == 'french'
                ? 'Produit déplacé vers le panier avec succès.'
                : 'Product moved to cart successfully.';
            
            return redirect('ShoppingCarts')->with('message_success', $message);
        }
        
        return redirect('Wishlists')->with('message_error', 'Product not found');
    }
    
    /**
     * Get wishlist count
     */
    public function getWishlistCount()
    {
        if (!session('loginId')) {
            return response()->json(['count' => 0]);
        }
        
        $loginId = session('loginId');
        $count = DB::table('wishlists')->where('user_id', $loginId)->count();
        
        return response()->json(['count' => $count]);
    }
    
    /**
     * Share wishlist
     */
    public function shareWishlist()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $wishlists = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->where('wishlists.user_id', $loginId)
            ->select('products.name', 'products.product_image', 'products.price')
            ->get();
        
        $user = DB::table('users')->where('id', $loginId)->first();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Partager la liste de souhaits' : 'Share Wishlist',
            'wishlists' => $wishlists,
            'user' => $user,
        ];
        
        return view('wishlists.share', $data);
    }
    
    /**
     * Clear all wishlist items
     */
    public function clearWishlist()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        DB::table('wishlists')->where('user_id', $loginId)->delete();
        
        $message = $language_name == 'french'
            ? 'Liste de souhaits vidée avec succès.'
            : 'Wishlist cleared successfully.';
        
        return redirect('Wishlists')->with('message_success', $message);
    }
}
