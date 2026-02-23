<?php

namespace App\Services\Cart;

use Illuminate\Support\Facades\Session;

class CartService
{
    /**
     * Cart contents stored in session
     */
    protected $cart_contents = [];
    
    /**
     * Initialize cart from session
     */
    public function __construct()
    {
        $this->cart_contents = Session::get('cart_contents', [
            'cart_total' => 0,
            'total_items' => 0,
        ]);
    }
    
    /**
     * Insert item into cart (replicate CI Cart->insert)
     * 
     * @param array $items Cart item data
     * @return bool|string Returns rowid on success, FALSE on failure
     */
    public function insert($items = [])
    {
        // If single item, convert to array
        if (!isset($items[0])) {
            if (!$this->_insert($items)) {
                return false;
            }
            return $items['rowid'];
        }
        
        // Multiple items
        $success = true;
        foreach ($items as $item) {
            if (!$this->_insert($item)) {
                $success = false;
            }
        }
        
        return $success;
    }
    
    /**
     * Insert single item (replicate CI Cart->_insert)
     * 
     * @param array $items
     * @return bool
     */
    protected function _insert($items = [])
    {
        // Validate required fields
        if (!isset($items['id'], $items['qty'], $items['price'], $items['name'])) {
            return false;
        }
        
        // Validate quantity
        $items['qty'] = (float) $items['qty'];
        if ($items['qty'] == 0) {
            return false;
        }
        
        // Validate price
        $items['price'] = (float) $items['price'];
        
        // Create unique row ID
        $items['rowid'] = $this->_create_unique_id($items);
        
        // Prepare options
        if (!isset($items['options']) || !is_array($items['options'])) {
            $items['options'] = [];
        }
        
        // Calculate subtotal
        $items['subtotal'] = ($items['qty'] * $items['price']);
        
        // Add to cart
        $this->cart_contents[$items['rowid']] = $items;
        
        // Update totals
        $this->_save_cart();
        
        return true;
    }
    
    /**
     * Update cart item (replicate CI Cart->update)
     * 
     * @param array $items
     * @return bool
     */
    public function update($items = [])
    {
        // If single item, convert to array
        if (!isset($items[0])) {
            if (!$this->_update($items)) {
                return false;
            }
            return true;
        }
        
        // Multiple items
        $success = true;
        foreach ($items as $item) {
            if (!$this->_update($item)) {
                $success = false;
            }
        }
        
        return $success;
    }
    
    /**
     * Update single item (replicate CI Cart->_update)
     * 
     * @param array $items
     * @return bool
     */
    protected function _update($items = [])
    {
        // Validate rowid
        if (!isset($items['rowid']) || !isset($this->cart_contents[$items['rowid']])) {
            return false;
        }
        
        // Update quantity if provided
        if (isset($items['qty'])) {
            $items['qty'] = (float) $items['qty'];
            
            // Remove item if quantity is 0
            if ($items['qty'] == 0) {
                unset($this->cart_contents[$items['rowid']]);
                $this->_save_cart();
                return true;
            }
            
            $this->cart_contents[$items['rowid']]['qty'] = $items['qty'];
        }
        
        // Recalculate subtotal
        $this->cart_contents[$items['rowid']]['subtotal'] = 
            ($this->cart_contents[$items['rowid']]['qty'] * $this->cart_contents[$items['rowid']]['price']);
        
        $this->_save_cart();
        
        return true;
    }
    
    /**
     * Remove item from cart (replicate CI Cart->remove)
     * 
     * @param string $rowid
     * @return bool
     */
    public function remove($rowid)
    {
        if (!isset($this->cart_contents[$rowid])) {
            return false;
        }
        
        unset($this->cart_contents[$rowid]);
        $this->_save_cart();
        
        return true;
    }
    
    /**
     * Get cart contents (replicate CI Cart->contents)
     * 
     * @param bool $newest_first
     * @return array
     */
    public function contents($newest_first = false)
    {
        $cart = $this->cart_contents;
        
        // Remove totals from items
        unset($cart['total_items']);
        unset($cart['cart_total']);
        
        if ($newest_first) {
            $cart = array_reverse($cart);
        }
        
        return $cart;
    }
    
    /**
     * Get specific item (replicate CI Cart->get_item)
     * 
     * @param string $row_id
     * @return array|bool
     */
    public function get_item($row_id)
    {
        return isset($this->cart_contents[$row_id]) ? $this->cart_contents[$row_id] : false;
    }
    
    /**
     * Check if cart has item (replicate CI Cart->has_options)
     * 
     * @param string $row_id
     * @return bool
     */
    public function has_options($row_id = '')
    {
        return (isset($this->cart_contents[$row_id]['options']) && count($this->cart_contents[$row_id]['options']) > 0);
    }
    
    /**
     * Get item options (replicate CI Cart->product_options)
     * 
     * @param string $row_id
     * @return array
     */
    public function product_options($row_id = '')
    {
        return isset($this->cart_contents[$row_id]['options']) ? $this->cart_contents[$row_id]['options'] : [];
    }
    
    /**
     * Get cart total (replicate CI Cart->total)
     * 
     * @return float
     */
    public function total()
    {
        return $this->cart_contents['cart_total'] ?? 0;
    }
    
    /**
     * Get total items (replicate CI Cart->total_items)
     * 
     * @return int
     */
    public function total_items()
    {
        return $this->cart_contents['total_items'] ?? 0;
    }
    
    /**
     * Destroy cart (replicate CI Cart->destroy)
     * 
     * @return void
     */
    public function destroy()
    {
        $this->cart_contents = [
            'cart_total' => 0,
            'total_items' => 0,
        ];
        
        Session::forget('cart_contents');
    }
    
    /**
     * Create unique row ID (replicate CI Cart->_create_unique_id)
     * 
     * @param array $item
     * @return string
     */
    protected function _create_unique_id($item)
    {
        if (!isset($item['options']) || count($item['options']) === 0) {
            return md5($item['id']);
        }
        
        $option_ids = [];
        foreach ($item['options'] as $key => $val) {
            if (is_array($val)) {
                $option_ids[] = $key . ':' . md5(serialize($val));
            } else {
                $option_ids[] = $key . ':' . $val;
            }
        }
        
        return md5($item['id'] . implode('|', $option_ids));
    }
    
    /**
     * Save cart to session and update totals (replicate CI Cart->_save_cart)
     * 
     * @return void
     */
    protected function _save_cart()
    {
        // Calculate totals
        $total = 0;
        $items = 0;
        
        foreach ($this->cart_contents as $key => $val) {
            // Skip non-cart items
            if (!is_array($val) || !isset($val['price'], $val['qty'])) {
                continue;
            }
            
            $total += ($val['price'] * $val['qty']);
            $items += $val['qty'];
        }
        
        // Update totals
        $this->cart_contents['total_items'] = $items;
        $this->cart_contents['cart_total'] = $total;
        
        // Save to session
        Session::put('cart_contents', $this->cart_contents);
    }
    
    /**
     * Format number (replicate CI Cart->format_number)
     * 
     * @param float $n
     * @return float
     */
    protected function _format_number($n = '')
    {
        if ($n == '') {
            return '';
        }
        
        // Remove anything that isn't a number or decimal point
        $n = (float) $n;
        
        return number_format($n, 2, '.', '');
    }
}
