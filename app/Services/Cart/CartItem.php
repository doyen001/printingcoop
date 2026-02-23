<?php

namespace App\Services\Cart;

class CartItem
{
    /**
     * Cart item structure matching CI exactly
     * 
     * Required fields:
     * - id: Product ID
     * - qty: Quantity
     * - price: Price per unit
     * - name: Product name
     * - name_french: Product name in French
     * 
     * Optional fields:
     * - options: Array of product options
     *   - product_id: Product ID
     *   - product_image: Product image path
     *   - cart_images: Array of cart images
     *   - provider_product_id: Provider product ID (if provider product)
     *   - attribute_ids: Array of selected attributes
     *   - product_size: Array of size information
     *   - product_width_length: Array of width/length dimensions
     *   - product_depth_length_width: Array of depth dimensions
     *   - page_product_width_length: Array of page dimensions
     *   - recto_verso: Recto verso option
     *   - recto_verso_french: Recto verso in French
     *   - votre_text: Custom text option
     */
    
    public $id;
    public $qty;
    public $price;
    public $name;
    public $name_french;
    public $options = [];
    public $rowid;
    public $subtotal;
    
    /**
     * Create cart item from array
     * 
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        $item = new static();
        
        $item->id = $data['id'] ?? null;
        $item->qty = $data['qty'] ?? 1;
        $item->price = $data['price'] ?? 0;
        $item->name = $data['name'] ?? '';
        $item->name_french = $data['name_french'] ?? '';
        $item->options = $data['options'] ?? [];
        $item->rowid = $data['rowid'] ?? null;
        $item->subtotal = $data['subtotal'] ?? ($item->qty * $item->price);
        
        return $item;
    }
    
    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'qty' => $this->qty,
            'price' => $this->price,
            'name' => $this->name,
            'name_french' => $this->name_french,
            'options' => $this->options,
            'rowid' => $this->rowid,
            'subtotal' => $this->subtotal,
        ];
    }
    
    /**
     * Get product option
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }
    
    /**
     * Set product option
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }
    
    /**
     * Check if has specific option
     * 
     * @param string $key
     * @return bool
     */
    public function hasOption($key)
    {
        return isset($this->options[$key]);
    }
    
    /**
     * Get attribute IDs
     * 
     * @return array
     */
    public function getAttributeIds()
    {
        return $this->getOption('attribute_ids', []);
    }
    
    /**
     * Get product size
     * 
     * @return array
     */
    public function getProductSize()
    {
        return $this->getOption('product_size', []);
    }
    
    /**
     * Get product dimensions (width/length)
     * 
     * @return array
     */
    public function getProductWidthLength()
    {
        return $this->getOption('product_width_length', []);
    }
    
    /**
     * Get product depth dimensions
     * 
     * @return array
     */
    public function getProductDepthLengthWidth()
    {
        return $this->getOption('product_depth_length_width', []);
    }
    
    /**
     * Get page dimensions
     * 
     * @return array
     */
    public function getPageProductWidthLength()
    {
        return $this->getOption('page_product_width_length', []);
    }
    
    /**
     * Check if is provider product
     * 
     * @return bool
     */
    public function isProviderProduct()
    {
        return !empty($this->getOption('provider_product_id'));
    }
    
    /**
     * Get provider product ID
     * 
     * @return int|null
     */
    public function getProviderProductId()
    {
        return $this->getOption('provider_product_id');
    }
    
    /**
     * Get cart images
     * 
     * @return array
     */
    public function getCartImages()
    {
        return $this->getOption('cart_images', []);
    }
    
    /**
     * Get recto verso option
     * 
     * @return string|null
     */
    public function getRectoVerso()
    {
        return $this->getOption('recto_verso');
    }
    
    /**
     * Get custom text option
     * 
     * @return string|null
     */
    public function getVotreText()
    {
        return $this->getOption('votre_text');
    }
}
