<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

/**
 * Cart Service - Manages shopping cart session
 * Replicates CI Cart library functionality
 */
class CartService
{
    protected $cartContents = [];
    
    public function __construct()
    {
        $this->cartContents = Session::get('cart_contents', []);
    }
    
    /**
     * Insert item into cart
     */
    public function insert($data)
    {
        if (!isset($data['id']) || !isset($data['qty']) || !isset($data['price']) || !isset($data['name'])) {
            return false;
        }
        
        // Generate unique row ID
        $rowid = md5($data['id'] . serialize($data['options'] ?? []));
        
        $this->cartContents[$rowid] = [
            'rowid' => $rowid,
            'id' => $data['id'],
            'qty' => $data['qty'],
            'price' => $data['price'],
            'name' => $data['name'],
            'name_french' => $data['name_french'] ?? '',
            'subtotal' => $data['qty'] * $data['price'],
            'options' => $data['options'] ?? [],
        ];
        
        $this->saveCart();
        return true;
    }
    
    /**
     * Update cart item
     */
    public function update($data)
    {
        if (!isset($data['rowid'])) {
            return false;
        }
        
        $rowid = $data['rowid'];
        
        if (!isset($this->cartContents[$rowid])) {
            return false;
        }
        
        if (isset($data['qty'])) {
            $this->cartContents[$rowid]['qty'] = $data['qty'];
            $this->cartContents[$rowid]['subtotal'] = $data['qty'] * $this->cartContents[$rowid]['price'];
        }
        
        $this->saveCart();
        return true;
    }
    
    /**
     * Remove item from cart
     */
    public function remove($rowid)
    {
        if (isset($this->cartContents[$rowid])) {
            unset($this->cartContents[$rowid]);
            $this->saveCart();
            return true;
        }
        return false;
    }
    
    /**
     * Get cart contents
     */
    public function contents()
    {
        return $this->cartContents;
    }
    
    /**
     * Get specific cart item
     */
    public function getItem($rowid)
    {
        return $this->cartContents[$rowid] ?? null;
    }
    
    /**
     * Get total items in cart
     */
    public function totalItems()
    {
        $total = 0;
        foreach ($this->cartContents as $item) {
            $total += $item['qty'];
        }
        return $total;
    }
    
    /**
     * Get cart total
     */
    public function total()
    {
        $total = 0;
        foreach ($this->cartContents as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }
    
    /**
     * Destroy cart
     */
    public function destroy()
    {
        $this->cartContents = [];
        Session::forget('cart_contents');
    }
    
    /**
     * Save cart to session
     */
    protected function saveCart()
    {
        Session::put('cart_contents', $this->cartContents);
    }
}
