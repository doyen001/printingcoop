<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductProviderController extends Controller
{
    /**
     * Update provider product price rate
     */
    public function providerProductPriceRate(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            // Get provider product (CI equivalent: Provider_Model->getProduct)
            $product = \App\Models\ProviderProduct::leftJoin('products', 'products.id', '=', 'provider_products.product_id')
                ->select('provider_products.*', 'products.name AS product_name')
                ->where('provider_products.id', $id)
                ->first();
                
            if (!$product) {
                abort(404);
            }
            
            return view('admin.products.provider_product_price_rate', compact('product', 'id'));
        }
        
        $priceRate = $request->input('price_rate');
        
        try {
            $product = \App\Models\ProviderProduct::findOrFail($id);
            $product->price_rate = $priceRate;
            $product->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Price rate updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update price rate'
            ], 500);
        }
    }
    
    /**
     * Update provider product price rate total
     */
    public function providerProductPriceRateTotal(Request $request, $id)
    {
        $priceRate = $request->input('price_rate');
        
        try {
            $product = \App\Models\ProviderProduct::findOrFail($id);
            $product->price_rate = $priceRate;
            $product->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Price rate updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update price rate'
            ], 500);
        }
    }
    
    /**
     * Search products for provider binding
     */
    public function searchProduct(Request $request)
    {
        $searchText = $request->input('searchtext');
        
        $products = Product::where('name', 'like', "%{$searchText}%")
            ->select(['id', 'name', 'product_image'])
            ->limit(10)
            ->get();
            
        return response()->json($products);
    }
    
    /**
     * Bind a product to a provider
     */
    public function providerProductBind(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            // Get provider product (CI equivalent: Provider_Model->getProduct)
            $product = \App\Models\ProviderProduct::leftJoin('products', 'products.id', '=', 'provider_products.product_id')
                ->select('provider_products.*', 'products.name AS product_name')
                ->where('provider_products.id', $id)
                ->first();
                
            if (!$product) {
                abort(404);
            }
            
            return view('admin.products.provider_product_bind', compact('product', 'id'));
        }
        
        $productId = $request->input('product_id');
        
        try {
            $providerProduct = \App\Models\ProviderProduct::findOrFail($id);
            $providerProduct->product_id = $productId;
            $providerProduct->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Product bound successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to bind product'
            ], 500);
        }
    }
    
    /**
     * Unbind a product from a provider
     */
    public function providerProductUnbind($id)
    {
        try {
            $providerProduct = \App\Models\ProviderProduct::findOrFail($id);
            $providerProduct->product_id = null;
            $providerProduct->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Product unbound successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unbind product'
            ], 500);
        }
    }
}
