<?php

namespace App\Services\Pricing;

use Illuminate\Support\Facades\DB;

class PriceCalculator
{
    /**
     * Calculate product price (replicate CI Products->calculatePrice lines 436-869)
     * 
     * @param array $params Request parameters
     * @param string $language Language (English/French)
     * @return array Response with price and validation errors
     */
    public function calculatePrice(array $params, string $language = 'English')
    {
        $response = [];
        
        $product_id = $params['product_id'] ?? null;
        $price = $params['price'] ?? 0;
        $quantity = $params['quantity'] ?? 1;
        $quantity_id = $params['product_quantity_id'] ?? null;
        $size_id = $params['product_size_id'] ?? null;
        $add_length_width = $params['add_length_width'] ?? null;
        $page_add_length_width = $params['page_add_length_width'] ?? null;
        $depth_add_length_width = $params['depth_add_length_width'] ?? null;
        $recto_verso = $params['recto_verso'] ?? null;
        $recto_verso_price = $params['recto_verso_price'] ?? null;
        
        // Extract multiple attributes (lines 456-480)
        $multiple_attributes = $this->extractMultipleAttributes($params);
        
        // Check for provider product pricing (lines 482-485)
        $price_newprint = $this->getFullPrice($product_id, $quantity_id, $size_id, $multiple_attributes);
        
        if ($price_newprint > 0) {
            $price = $price_newprint;
        } else {
            // Original price logic (lines 489-505)
            $attributes = $this->extractSingleAttributes($params);
            
            $price += $this->getSumExtraPriceOfSingleAttributes($product_id, $attributes);
            $price += $this->getSumExtraPriceOfQuantity($product_id, $quantity_id);
            $price += $this->getSumExtraPriceOfQuantitySize($product_id, $quantity_id, $size_id);
            $price += $this->getSumExtraPriceOfMultipleAttributes($product_id, $quantity_id, $size_id, $multiple_attributes);
            
            // Get product data for dimension calculations (line 507)
            $Product = $this->getProductData($product_id);
            
            // Width/Length calculations (lines 509-603)
            if (!empty($add_length_width)) {
                $result = $this->calculateWidthLength($params, $Product, $language);
                if (isset($result['error'])) {
                    $response = array_merge($response, $result['error']);
                } else {
                    $price += $result['extra_price'];
                    $response = array_merge($response, $result['response']);
                }
            }
            
            // Depth calculations (lines 605-720)
            if (!empty($depth_add_length_width)) {
                $result = $this->calculateDepth($params, $Product, $language);
                if (isset($result['error'])) {
                    $response = array_merge($response, $result['error']);
                } else {
                    $price += $result['extra_price'];
                    $response = array_merge($response, $result['response']);
                }
            }
            
            // Page calculations (lines 722-855)
            if (!empty($page_add_length_width)) {
                $result = $this->calculatePage($params, $Product, $language);
                if (isset($result['error'])) {
                    $response = array_merge($response, $result['error']);
                } else {
                    $price += $result['extra_price'];
                    $response = array_merge($response, $result['response']);
                }
            }
            
            // Recto verso pricing (lines 857-860)
            if (!empty($recto_verso) && $recto_verso == "Yes" && !empty($recto_verso_price)) {
                $price = $price + (($price * $recto_verso_price) / 100);
            }
        }
        
        $response['success'] = 1;
        $response['price'] = number_format($price * $quantity, 2);
        
        return $response;
    }
    
    /**
     * Extract multiple attributes from params (lines 456-480)
     */
    private function extractMultipleAttributes(array $params)
    {
        $multiple_attributes = [];
        
        foreach ($params as $key => $val) {
            if ($val == '') {
                continue;
            }
            
            if (preg_match('/^multiple_attribute_([0-9]+)$/i', $key, $m)) {
                $attribute_id = $m[1];
                $attribute_item_id = $val;
                $multiple_attributes[] = [$attribute_id, $attribute_item_id];
            }
        }
        
        usort($multiple_attributes, function ($a, $b) {
            if ($a[0] < $b[0]) {
                return -1;
            } else if ($a[0] > $b[0]) {
                return 1;
            }
            return 0;
        });
        
        return $multiple_attributes;
    }
    
    /**
     * Extract single attributes from params (lines 489-500)
     */
    private function extractSingleAttributes(array $params)
    {
        $attributes = [];
        
        foreach ($params as $key => $val) {
            if ($val == '') {
                continue;
            }
            
            if (preg_match('/^attribute_id_([0-9]+)$/i', $key, $m)) {
                $attribute_id = $m[1];
                $attribute_item_id = $val;
                $attributes[] = [$attribute_id, $attribute_item_id];
            }
        }
        
        return $attributes;
    }
    
    /**
     * Get full price from provider (Sina API) (line 482)
     */
    private function getFullPrice($product_id, $quantity_id, $size_id, $multiple_attributes)
    {
        $s_multiple_attributes = [];
        foreach ($multiple_attributes as $attribute) {
            $s_multiple_attributes[] = "$attribute[0] - $attribute[1]";
        }
        
        // Query full_price table for provider products
        $result = DB::table('full_price')
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->where('size_id', $size_id)
            ->where('attribute', join(',', $s_multiple_attributes))
            ->value('price');
        
        return $result ?? 0;
    }
    
    /**
     * Get sum of extra prices for single attributes (lines 3010-3038)
     */
    private function getSumExtraPriceOfSingleAttributes($product_id, $attributes)
    {
        if (empty($attributes)) {
            return 0;
        }
        
        $attribute_items = [];
        foreach ($attributes as $attribute) {
            $attribute_items[] = $attribute[1];
        }
        
        $result = DB::table('product_attribute_item_datas')
            ->where('product_id', $product_id)
            ->whereIn('attribute_item_id', $attribute_items)
            ->sum('extra_price');
        
        return $result ?? 0;
    }
    
    /**
     * Get extra price for quantity (lines 3040-3052)
     */
    private function getSumExtraPriceOfQuantity($product_id, $quantity_id)
    {
        if (empty($quantity_id)) {
            return 0;
        }
        
        $result = DB::table('product_quantity')
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->value('price');
        
        return $result ?? 0;
    }
    
    /**
     * Get extra price for quantity + size (lines 3054-3067)
     */
    private function getSumExtraPriceOfQuantitySize($product_id, $quantity_id, $size_id)
    {
        if (empty($quantity_id) || empty($size_id)) {
            return 0;
        }
        
        $result = DB::table('product_size_new')
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->where('size_id', $size_id)
            ->value('extra_price');
        
        return $result ?? 0;
    }
    
    /**
     * Get sum of extra prices for multiple attributes (lines 3069-3099)
     */
    private function getSumExtraPriceOfMultipleAttributes($product_id, $quantity_id, $size_id, $multiple_attributes)
    {
        if (empty($multiple_attributes)) {
            return 0;
        }
        
        $attribute_items = [];
        foreach ($multiple_attributes as $attribute) {
            $attribute_items[] = $attribute[1];
        }
        
        $result = DB::table('size_multiple_attributes')
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->where('size_id', $size_id)
            ->whereIn('attribute_item_id', $attribute_items)
            ->sum('extra_price');
        
        return $result ?? 0;
    }
    
    /**
     * Get product data
     */
    private function getProductData($product_id)
    {
        $product = DB::table('products')->where('id', $product_id)->first();
        return $product ? (array) $product : [];
    }
    
    /**
     * Calculate width/length pricing (lines 509-603)
     */
    private function calculateWidthLength(array $params, array $Product, string $language)
    {
        $product_length = $params['product_length'] ?? null;
        $product_width = $params['product_width'] ?? null;
        $product_total_page = $params['product_total_page'] ?? null;
        $length_width_quantity_show = $params['length_width_quantity_show'] ?? 0;
        $length_width_color = $params['length_width_color'] ?? null;
        
        $min_length = $Product['min_length'];
        $max_length = $Product['max_length'];
        $min_width = $Product['min_width'];
        $max_width = $Product['max_width'];
        $length_width_min_quantity = $Product['length_width_min_quantity'];
        $length_width_max_quantity = $Product['length_width_max_quantity'];
        $min_length_min_width_price = $Product['min_length_min_width_price'];
        $length_width_unit_price_black = $Product['length_width_unit_price_black'];
        $length_width_price_color = $Product['length_width_price_color'];
        $length_width_color_show = $Product['length_width_color_show'];
        $length_width_pages_type = $Product['length_width_pages_type'];
        
        $response = [
            'product_length' => $product_length,
            'product_length_error' => '',
            'product_width' => $product_width,
            'product_width_error' => '',
            'product_total_page' => $product_total_page,
            'product_total_page_error' => '',
        ];
        
        // Validation (lines 538-574)
        if (empty($product_length)) {
            $response['product_length'] = '';
            $response['product_length_error'] = $language == 'French' 
                ? 'Veuillez saisir la longueur' 
                : 'Please enter length';
            return ['error' => $response];
        } else if ($product_length < $min_length || $product_length > $max_length) {
            $response['product_length'] = 0;
            $response['product_length_error'] = $language == 'French'
                ? 'Veuillez saisir la longueur entre ' . $this->showValue($min_length) . ' et ' . $this->showValue($max_length)
                : 'Please enter length between ' . $this->showValue($min_length) . ' and ' . $this->showValue($max_length);
            return ['error' => $response];
        } else if (empty($product_width)) {
            $response['product_width'] = '';
            $response['product_width_error'] = $language == 'French'
                ? 'Veuillez saisir la largeur'
                : 'Please enter width';
            return ['error' => $response];
        } else if ($product_width < $min_width || $product_width > $max_width) {
            $response['product_width'] = 0;
            $response['product_width_error'] = $language == 'French'
                ? 'Veuillez saisir la largeur entre ' . $this->showValue($min_width) . ' et ' . $this->showValue($max_width)
                : 'Please enter width between ' . $this->showValue($min_width) . ' and ' . $this->showValue($max_width);
            return ['error' => $response];
        } else if (empty($product_total_page) && $length_width_quantity_show == 1) {
            $response['product_total_page'] = '';
            $response['product_total_page_error'] = $language == 'French'
                ? 'Veuillez saisir la quantité'
                : 'Please enter quantity';
            return ['error' => $response];
        } else if (!empty($product_total_page) && $length_width_quantity_show == 1 && $length_width_pages_type == 'input' && ($product_total_page < $length_width_min_quantity || $product_total_page > $length_width_max_quantity)) {
            $response['product_total_page'] = 0;
            $response['product_total_page_error'] = $language == 'French'
                ? 'Veuillez saisir la quantité entre ' . $this->showValue($length_width_min_quantity) . ' et ' . $this->showValue($length_width_max_quantity)
                : 'Please enter quantity between ' . $this->showValue($length_width_min_quantity) . ' and ' . $this->showValue($length_width_max_quantity);
            return ['error' => $response];
        }
        
        // Calculate price (lines 575-593)
        $rq_area = $product_length * $product_width;
        $extra_price = 0;
        
        if ($length_width_color_show == 1) {
            if ($length_width_color == 'black') {
                $extra_price = $length_width_unit_price_black * $rq_area;
            } else if ($length_width_color == 'color') {
                $extra_price = $length_width_price_color * $rq_area;
            } else {
                $extra_price = $min_length_min_width_price * $rq_area;
            }
        } else {
            $extra_price = $min_length_min_width_price * $rq_area;
        }
        
        if ($length_width_quantity_show == 1 && !empty($product_total_page)) {
            $extra_price = $product_total_page * $extra_price;
        }
        
        return [
            'extra_price' => $extra_price,
            'response' => $response
        ];
    }
    
    /**
     * Calculate depth pricing (lines 605-720)
     */
    private function calculateDepth(array $params, array $Product, string $language)
    {
        $product_depth = $params['product_depth'] ?? null;
        $product_depth_length = $params['product_depth_length'] ?? null;
        $product_depth_width = $params['product_depth_width'] ?? null;
        $product_depth_total_page = $params['product_depth_total_page'] ?? null;
        $depth_width_length_quantity_show = $params['depth_width_length_quantity_show'] ?? 0;
        $depth_color = $params['depth_color'] ?? null;
        
        $min_depth = $Product['min_depth'];
        $max_depth = $Product['max_depth'];
        $depth_min_length = $Product['depth_min_length'];
        $depth_max_length = $Product['depth_max_length'];
        $depth_min_width = $Product['depth_min_width'];
        $depth_max_width = $Product['depth_max_width'];
        $depth_min_quantity = $Product['depth_min_quantity'];
        $depth_max_quantity = $Product['depth_max_quantity'];
        $depth_width_length_price = $Product['depth_width_length_price'];
        $depth_unit_price_black = $Product['depth_unit_price_black'];
        $depth_price_color = $Product['depth_price_color'];
        $depth_color_show = $Product['depth_color_show'];
        $depth_width_length_type = $Product['depth_width_length_type'];
        
        $response = [
            'product_depth_length' => $product_depth_length,
            'product_depth_length_error' => '',
            'product_depth_width' => $product_depth_width,
            'product_depth_width_error' => '',
            'product_depth' => $product_depth,
            'product_depth_error' => '',
            'product_depth_total_page' => $product_depth_total_page,
            'product_depth_total_page_error' => '',
        ];
        
        // Validation (lines 640-687)
        if (empty($product_depth_length)) {
            $response['product_depth_length'] = '';
            $response['product_depth_length_error'] = $language == 'French'
                ? 'Veuillez saisir la longueur'
                : 'Please enter length';
            return ['error' => $response];
        } else if ($product_depth_length < $depth_min_length || $product_depth_length > $depth_max_length) {
            $response['product_depth_length'] = 0;
            $response['product_depth_length_error'] = $language == 'French'
                ? 'Veuillez saisir la longueur entre ' . $this->showValue($depth_min_length) . ' et ' . $this->showValue($depth_max_length)
                : 'Please enter length between ' . $this->showValue($depth_min_length) . ' and ' . $this->showValue($depth_max_length);
            return ['error' => $response];
        } else if (empty($product_depth_width)) {
            $response['product_depth_width'] = '';
            $response['product_depth_width_error'] = $language == 'French'
                ? 'Veuillez saisir la largeur'
                : 'Please enter width';
            return ['error' => $response];
        } else if ($product_depth_width < $depth_min_width || $product_depth_width > $depth_max_width) {
            $response['product_depth_width'] = 0;
            $response['product_depth_width_error'] = $language == 'French'
                ? 'Veuillez saisir la largeur entre ' . $this->showValue($depth_min_width) . ' and ' . $this->showValue($depth_max_width)
                : 'Please enter width between ' . $this->showValue($depth_min_width) . ' and ' . $this->showValue($depth_max_width);
            return ['error' => $response];
        } else if (empty($product_depth)) {
            $response['product_depth'] = '';
            $response['product_depth_error'] = $language == 'French'
                ? 'Please enter depth'
                : 'Please enter depth';
            return ['error' => $response];
        } else if ($product_depth < $min_depth || $product_depth > $max_depth) {
            $response['product_depth'] = 0;
            $response['product_depth_error'] = $language == 'French'
                ? 'Veuillez saisir la profondeur entre ' . $this->showValue($min_depth) . ' et ' . $this->showValue($max_depth)
                : 'Please enter depth between ' . $this->showValue($min_depth) . ' and ' . $this->showValue($max_depth);
            return ['error' => $response];
        } else if (empty($product_depth_total_page) && $depth_width_length_quantity_show == 1) {
            $response['product_depth_total_page'] = '';
            $response['product_depth_total_page_error'] = $language == 'French'
                ? 'Veuillez saisir la quantité'
                : 'Please enter quantity';
            return ['error' => $response];
        } else if (!empty($product_depth_total_page) && $depth_width_length_quantity_show == 1 && $depth_width_length_type == 'input' && ($product_depth_total_page < $depth_min_quantity || $product_depth_total_page > $depth_max_quantity)) {
            $response['product_depth_total_page'] = 0;
            $response['product_depth_total_page_error'] = $language == 'French'
                ? 'Veuillez saisir la quantité entre ' . $this->showValue($depth_min_quantity) . ' et ' . $this->showValue($depth_max_quantity)
                : 'Please enter quantity between ' . $this->showValue($depth_min_quantity) . ' and ' . $this->showValue($depth_max_quantity);
            return ['error' => $response];
        }
        
        // Calculate price (lines 688-707)
        $rq_area = $product_depth_length * $product_depth_width * $product_depth;
        $extra_price = 0;
        
        if ($depth_color_show == 1) {
            if ($depth_color == 'black') {
                $extra_price = $depth_unit_price_black * $rq_area;
            } else if ($depth_color == 'color') {
                $extra_price = $depth_price_color * $rq_area;
            } else {
                $extra_price = $depth_width_length_price * $rq_area;
            }
        } else {
            $extra_price = $depth_width_length_price * $rq_area;
        }
        
        if ($depth_width_length_quantity_show == 1 && !empty($product_depth_total_page)) {
            $extra_price = $product_depth_total_page * $extra_price;
        }
        
        return [
            'extra_price' => $extra_price,
            'response' => $response
        ];
    }
    
    /**
     * Calculate page pricing (lines 722-855)
     */
    private function calculatePage(array $params, array $Product, string $language)
    {
        $page_product_length = $params['page_product_length'] ?? null;
        $page_product_width = $params['page_product_width'] ?? null;
        $page_product_total_page = $params['page_product_total_page'] ?? null;
        $page_product_total_sheets = $params['page_product_total_sheets'] ?? null;
        $page_length_width_pages_show = $params['page_length_width_pages_show'] ?? 0;
        $page_length_width_sheets_show = $params['page_length_width_sheets_show'] ?? 0;
        $page_length_width_quantity_show = $params['page_length_width_quantity_show'] ?? 0;
        $page_product_total_quantity = $params['page_product_total_quantity'] ?? null;
        $page_length_width_color = $params['page_length_width_color'] ?? null;
        
        $page_min_length = $Product['page_min_length'];
        $page_max_length = $Product['page_max_length'];
        $page_min_width = $Product['page_min_width'];
        $page_max_width = $Product['page_max_width'];
        $page_min_length_min_width_price = $Product['page_min_length_min_width_price'];
        $page_length_width_price_color = $Product['page_length_width_price_color'];
        $page_length_width_price_black = $Product['page_length_width_price_black'];
        $page_length_width_min_quantity = $Product['page_length_width_min_quantity'];
        $page_length_width_max_quantity = $Product['page_length_width_max_quantity'];
        $page_length_width_color_show = $Product['page_length_width_color_show'];
        $page_length_width_quantity_type = $Product['page_length_width_quantity_type'];
        
        $response = [
            'page_product_length' => $page_product_length,
            'page_product_length_error' => '',
            'page_product_width' => $page_product_width,
            'page_product_width_error' => '',
            'page_product_total_page' => $page_product_total_page,
            'page_product_total_page_error' => '',
            'page_product_total_sheets' => $page_product_total_sheets,
            'page_product_total_sheets_error' => '',
            'page_product_total_quantity' => $page_product_total_quantity,
            'page_product_total_quantity_error' => '',
        ];
        
        // Validation (lines 763-810)
        if (empty($page_product_length)) {
            $response['page_product_length'] = '';
            $response['page_product_length_error'] = $language == 'French'
                ? 'Veuillez saisir la longueur de la page'
                : 'Please enter Page length';
            return ['error' => $response];
        } else if ($page_product_length < $page_min_length || $page_product_length > $page_max_length) {
            $response['page_product_length'] = 0;
            $response['page_product_length_error'] = $language == 'French'
                ? 'Veuillez saisir la longueur de la page entre ' . $this->showValue($page_min_length) . ' et ' . $this->showValue($page_max_length)
                : 'Please enter page length between ' . $this->showValue($page_min_length) . ' and ' . $this->showValue($page_max_length);
            return ['error' => $response];
        } else if (empty($page_product_width)) {
            $response['page_product_width'] = '';
            $response['page_product_width_error'] = $language == 'French'
                ? 'Veuillez saisir la largeur de la page'
                : 'Please enter page width';
            return ['error' => $response];
        } else if ($page_product_width < $page_min_width || $page_product_width > $page_max_width) {
            $response['page_product_width'] = 0;
            $response['page_product_width_error'] = $language == 'French'
                ? 'Veuillez saisir la largeur de page entre ' . $this->showValue($page_min_width) . ' et ' . $this->showValue($page_max_width)
                : 'Please enter page width between ' . $this->showValue($page_min_width) . ' and ' . $this->showValue($page_max_width);
            return ['error' => $response];
        } else if (empty($page_product_total_page) && $page_length_width_pages_show == 1) {
            $response['page_product_total_page'] = '';
            $response['page_product_total_page_error'] = $language == 'French'
                ? 'Veuillez sélectionner des pages'
                : 'Please select pages';
            return ['error' => $response];
        } else if (empty($page_product_total_sheets) && $page_length_width_sheets_show == 1) {
            $response['page_product_total_sheets'] = '';
            $response['page_product_total_sheets_error'] = $language == 'French'
                ? 'Veuillez sélectionner une feuille par bloc'
                : 'Please Select Sheet per pad';
            return ['error' => $response];
        } else if (empty($page_product_total_quantity) && $page_length_width_quantity_show == 1) {
            $response['page_product_total_quantity'] = $page_product_total_quantity;
            $response['page_product_total_quantity_error'] = $language == 'French'
                ? 'Veuillez saisir la quantité'
                : 'Please enter quantity';
            return ['error' => $response];
        } else if (!empty($page_product_total_quantity) && $page_length_width_quantity_show == 1 && $page_length_width_quantity_type == 'input' && ($page_product_total_quantity < $page_length_width_min_quantity || $page_product_total_quantity > $page_length_width_max_quantity)) {
            $response['page_product_total_quantity'] = 0;
            $response['page_product_total_quantity_error'] = $language == 'French'
                ? 'Veuillez saisir la quantité entre ' . $this->showValue($page_length_width_min_quantity) . ' et ' . $this->showValue($page_length_width_max_quantity)
                : 'Please enter quantity between ' . $this->showValue($page_length_width_min_quantity) . ' and ' . $this->showValue($page_length_width_max_quantity);
            return ['error' => $response];
        }
        
        // Calculate price (lines 812-842)
        $rq_area = $page_product_length * $page_product_width;
        $extra_price = 0;
        
        if ($page_length_width_color_show == 1) {
            if ($page_length_width_color == 'black') {
                $extra_price = $page_length_width_price_black * $rq_area;
            } else if ($page_length_width_color == 'color') {
                $extra_price = $page_length_width_price_color * $rq_area;
            } else {
                $extra_price = $page_min_length_min_width_price * $rq_area;
            }
        } else {
            $extra_price = $page_min_length_min_width_price * $rq_area;
        }
        
        if (!empty($page_product_total_page) && $page_length_width_pages_show == 1) {
            $page_product_total_page_error = explode('-', $page_product_total_page);
            $page_extra_price = $page_product_total_page_error[0] * $extra_price;
            $page_product_total_page = $page_product_total_page_error[0];
            
            if (!empty($page_product_total_sheets) && $page_length_width_sheets_show == 1) {
                $sheets_extra_price = $page_product_total_sheets * $extra_price;
                if ($page_extra_price > 0 || $sheets_extra_price > 0) {
                    $extra_price = $page_extra_price + $sheets_extra_price;
                }
            }
        }
        
        if (!empty($page_product_total_quantity) && $page_length_width_quantity_show == 1) {
            $extra_price = $page_product_total_quantity * $extra_price;
        }
        
        return [
            'extra_price' => $extra_price,
            'response' => $response
        ];
    }
    
    /**
     * Format value for display (helper function)
     */
    private function showValue($value)
    {
        return number_format($value, 2, '.', '');
    }
}
