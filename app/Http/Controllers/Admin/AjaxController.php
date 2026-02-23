<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Admin AJAX Controller (replicate CI admin/Ajax.php)
 * 
 * Endpoints:
 * - getCategoryDropDownListByAjax() - Get categories by menu (lines 15-27)
 * - getSubCategoryDropDownListByAjax() - Get subcategories (lines 29-42)
 * - getPrinterSeriesListByAjax() - Get printer series (lines 44-55)
 * - getProductDropDownListByAjax() - Get products by menu (lines 56-68)
 * - removeProductImage() - Remove product image (lines 70-105)
 * - getSubCategoryAndProductDropDownListByAjax() - Get subcategories and products (lines 107-133)
 * - getActiveProductDropDownListByAjax() - Get active products by subcategory (lines 135-152)
 */
class AjaxController extends Controller
{
    /**
     * Get category dropdown list by AJAX (replicate CI admin/Ajax->getCategoryDropDownListByAjax lines 15-27)
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
     * Get subcategory dropdown list by AJAX (replicate CI admin/Ajax->getSubCategoryDropDownListByAjax lines 29-42)
     * 
     * @param int|null $category_id Category ID
     * @return string HTML options
     */
    public function getSubCategoryDropDownListByAjax($category_id = null)
    {
        $options = '<option value="">Select Sub Category</option>';
        
        if ($category_id) {
            $categoryList = $this->getSubCategoryDropDownList(null, $category_id);
            
            foreach ($categoryList as $key => $val) {
                $options .= '<option value="' . $key . '">' . $val . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * Get printer series list by AJAX (replicate CI admin/Ajax->getPrinterSeriesListByAjax lines 44-55)
     * 
     * @param int|null $printer_brand_id Printer brand ID
     * @return string HTML options
     */
    public function getPrinterSeriesListByAjax($printer_brand_id = null)
    {
        $options = '<option value="">Select Printer Series</option>';
        
        if (!empty($printer_brand_id)) {
            $categoryList = $this->getPrinterSeriesListById($printer_brand_id);
            
            foreach ($categoryList as $key => $val) {
                $options .= '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * Get product dropdown list by AJAX (replicate CI admin/Ajax->getProductDropDownListByAjax lines 56-68)
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
     * Remove product image (replicate CI admin/Ajax->removeProductImage lines 70-105)
     * 
     * @param Request $request
     * @return string 1 for success, 0 for failure
     */
    public function removeProductImage(Request $request)
    {
        $product_id = $request->input('product_id');
        $image_id = $request->input('id');
        $imageName = $request->input('image_name');
        
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
    
    /**
     * Get subcategory and product dropdown lists by AJAX (replicate CI admin/Ajax->getSubCategoryAndProductDropDownListByAjax lines 107-133)
     * 
     * @param int|null $category_id Category ID
     * @return string JSON response
     */
    public function getSubCategoryAndProductDropDownListByAjax($category_id = null)
    {
        $json = [];
        $productList = $Sub_Carray_List = [];
        
        if (!empty($category_id)) {
            $List = $this->getActiveSubCategoryAndProductListBycategoryId($category_id);
            $Sub_Carray_List = $List['sub_categories'];
            $productList = $List['products'];
        }
        
        $options = '<option value="">Select Sub Category</option>';
        foreach ($Sub_Carray_List as $key => $val) {
            $options .= '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
        }
        
        $json['sub_category'] = $options;
        
        $options = '<option value="">Select Product </option>';
        
        foreach ($productList as $key => $val) {
            $options .= '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
        }
        
        $json['product_list'] = $options;
        
        echo json_encode($json);
        exit();
    }
    
    /**
     * Get active product dropdown list by AJAX (replicate CI admin/Ajax->getActiveProductDropDownListByAjax lines 135-152)
     * 
     * @param int|null $sub_category_id Subcategory ID
     * @return string JSON response
     */
    public function getActiveProductDropDownListByAjax($sub_category_id = null)
    {
        $json = [];
        $productList = [];
        
        if (!empty($sub_category_id)) {
            $List = $this->getActiveProductListBySubCategoryId($sub_category_id);
            $productList = $List;
        }
        
        $options = '<option value="">Select Product </option>';
        foreach ($productList as $key => $val) {
            $options .= '<option value="' . $val['id'] . '">' . $val['name'] . '</option>';
        }
        
        $json['product_list'] = $options;
        
        echo json_encode($json);
        exit();
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
    
    private function getPrinterSeriesListById($printer_brand_id)
    {
        $series = DB::table('printer_series')
            ->where('printer_brand_id', $printer_brand_id)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $series->toArray());
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
    
    private function getActiveSubCategoryAndProductListBycategoryId($category_id)
    {
        $sub_categories = DB::table('sub_categories')
            ->where('category_id', $category_id)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();
        
        $products = DB::table('products')
            ->where('category_id', $category_id)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();
        
        return [
            'sub_categories' => array_map(function($item) {
                return (array) $item;
            }, $sub_categories->toArray()),
            'products' => array_map(function($item) {
                return (array) $item;
            }, $products->toArray()),
        ];
    }
    
    private function getActiveProductListBySubCategoryId($sub_category_id)
    {
        $products = DB::table('products')
            ->where('sub_category_id', $sub_category_id)
            ->where('status', 1)
            ->select('id', 'name')
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $products->toArray());
    }
}
