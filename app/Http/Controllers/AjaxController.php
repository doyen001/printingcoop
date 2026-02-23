<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Public AJAX Controller (replicate CI Ajax.php)
 * 
 * Endpoints:
 * - getCategoryDropDownListByAjax() - Get categories by menu (lines 15-30)
 * - getSubCategoryDropDownListByAjax() - Get subcategories (lines 32-45)
 * - getProductDropDownListByAjax() - Get products by menu (lines 47-59)
 * - removeProductImage() - Remove product image (lines 61-92)
 */
class AjaxController extends Controller
{
    /**
     * Get category dropdown list by AJAX (replicate CI Ajax->getCategoryDropDownListByAjax lines 15-30)
     * 
     * @param int|null $menu_id Menu ID
     * @return string HTML options
     */
    public function getCategoryDropDownListByAjax($menu_id = null)
    {
        $options = '<option value="">Select Category</option>';
        
        if (!empty($menu_id)) {
            $categoryList = $this->getCategoryDropDownList($menu_id);
            
            foreach ($categoryList as $key => $val) {
                $options .= '<option value="' . $key . '">' . $val . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * Get subcategory dropdown list by AJAX (replicate CI Ajax->getSubCategoryDropDownListByAjax lines 32-45)
     * 
     * @param int|null $menu_id Menu ID
     * @param int|null $category_id Category ID
     * @return string HTML options
     */
    public function getSubCategoryDropDownListByAjax($menu_id = null, $category_id = null)
    {
        $options = '<option value="">Select Sub Category</option>';
        
        if (!empty($menu_id) && !empty($category_id)) {
            $categoryList = $this->getSubCategoryDropDownList($menu_id, $category_id);
            
            foreach ($categoryList as $key => $val) {
                $options .= '<option value="' . $key . '">' . $val . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * Get product dropdown list by AJAX (replicate CI Ajax->getProductDropDownListByAjax lines 47-59)
     * 
     * @param int|null $menu_id Menu ID
     * @return string HTML options
     */
    public function getProductDropDownListByAjax($menu_id = null)
    {
        $options = '<option value="">Select Product Name</option>';
        
        if (!empty($menu_id)) {
            $productList = $this->getProductDropDownList($menu_id);
            
            foreach ($productList as $key => $val) {
                $options .= '<option value="' . $key . '">' . $val . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * Remove product image (replicate CI Ajax->removeProductImage lines 61-92)
     * 
     * @param int $product_id Product ID
     * @param int|null $image_id Image ID
     * @param string|null $imageName Image filename
     * @return string 1 for success, 0 for failure
     */
    public function removeProductImage($product_id, $image_id = null, $imageName = null)
    {
        if ($this->deleteProductImageById($image_id)) {
            $ProductImages = $this->getProductImageDataByProductId($product_id);
            
            if (!empty($ProductImages)) {
                $product_main_image = isset($ProductImages[0]) ? $ProductImages[0] : '';
                $postData = ['id' => $product_id, 'product_image' => $product_main_image['image']];
                $this->saveProduct($postData);
            }
            
            // Delete image files from all size directories
            $imagePaths = [
                public_path('uploads/products/small/' . $imageName),
                public_path('uploads/products/medium/' . $imageName),
                public_path('uploads/products/large/' . $imageName),
                public_path('uploads/products/' . $imageName),
            ];
            
            foreach ($imagePaths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            echo 1;
            exit();
        } else {
            echo 0;
            exit();
        }
    }
    
    // ========== Helper Methods ==========
    
    private function getCategoryDropDownList($menu_id)
    {
        $categories = DB::table('categories')
            ->where('menu_id', $menu_id)
            ->where('status', 1)
            ->pluck('name', 'id')
            ->toArray();
        
        return $categories;
    }
    
    private function getSubCategoryDropDownList($menu_id, $category_id)
    {
        $query = DB::table('sub_categories')
            ->where('category_id', $category_id)
            ->where('status', 1);
        
        if ($menu_id) {
            $query->where('menu_id', $menu_id);
        }
        
        return $query->pluck('name', 'id')->toArray();
    }
    
    private function getProductDropDownList($menu_id)
    {
        $products = DB::table('products')
            ->where('menu_id', $menu_id)
            ->where('status', 1)
            ->pluck('name', 'id')
            ->toArray();
        
        return $products;
    }
    
    private function deleteProductImageById($image_id)
    {
        return DB::table('product_images')->where('id', $image_id)->delete();
    }
    
    private function getProductImageDataByProductId($product_id)
    {
        $images = DB::table('product_images')->where('product_id', $product_id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $images->toArray());
    }
    
    private function saveProduct($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('products')->where('id', $id)->update($data);
            return $id;
        }
        return false;
    }
}
