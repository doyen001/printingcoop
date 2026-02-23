<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shopping Cart Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration matches the CodeIgniter cart library settings.
    |
    */

    /**
     * Cart item structure
     * 
     * Required fields:
     * - id: Product ID
     * - qty: Quantity
     * - price: Price per unit
     * - name: Product name (English)
     * - name_french: Product name (French)
     * 
     * Optional fields:
     * - options: Array of product options
     */
    'item_structure' => [
        'required' => ['id', 'qty', 'price', 'name'],
        'optional' => ['name_french', 'options', 'rowid', 'subtotal'],
    ],

    /**
     * Cart options structure
     * 
     * Options array can contain:
     * - product_id: Product ID
     * - product_image: Product image path
     * - cart_images: Array of cart images from session
     * - provider_product_id: Provider product ID (if provider product)
     * - attribute_ids: Array of selected product attributes
     * - product_size: Array of size information
     *   - product_quantity: Quantity name
     *   - product_quantity_french: Quantity name (French)
     *   - product_size: Size name
     *   - product_size_french: Size name (French)
     *   - attribute: Array of additional attributes
     * - product_width_length: Array of width/length dimensions
     *   - product_width: Width value
     *   - product_length: Length value
     *   - product_total_page: Total pages
     *   - length_width_color_show: Show color option
     *   - length_width_color: Color selection (black/color)
     *   - length_width_color_french: Color in French
     * - product_depth_length_width: Array of depth dimensions
     *   - product_depth_width: Depth width
     *   - product_depth_length: Depth length
     *   - product_depth: Depth value
     *   - depth_width_length_quantity_show: Show quantity
     *   - depth_color: Color selection
     *   - depth_color_french: Color in French
     * - page_product_width_length: Array of page dimensions
     *   - page_product_width: Page width
     *   - page_product_length: Page length
     *   - page_product_total_page: Total pages
     *   - page_product_total_page_french: Total pages (French)
     *   - page_product_total_sheets: Total sheets
     *   - page_product_total_sheets_french: Total sheets (French)
     *   - page_length_width_color_show: Show color option
     *   - page_length_width_color: Color selection
     *   - page_length_width_color_french: Color in French
     *   - page_product_total_quantity: Total quantity
     *   - page_length_width_quantity_show: Show quantity
     * - recto_verso: Recto verso option (Yes/No)
     * - recto_verso_french: Recto verso in French (Oui/Non)
     * - votre_text: Custom text option
     */
    'options_structure' => [
        'product_id',
        'product_image',
        'cart_images',
        'provider_product_id',
        'attribute_ids',
        'product_size',
        'product_width_length',
        'product_depth_length_width',
        'page_product_width_length',
        'recto_verso',
        'recto_verso_french',
        'votre_text',
    ],

    /**
     * Session key for cart storage
     */
    'session_key' => 'cart_contents',

    /**
     * Number format settings
     */
    'number_format' => [
        'decimals' => 2,
        'decimal_point' => '.',
        'thousands_separator' => '',
    ],

];
