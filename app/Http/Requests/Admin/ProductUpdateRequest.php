<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Add your authorization logic here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $productId = $this->route('id');
        
        return [
            // Basic Information
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($productId)->whereNull('deleted_at')
            ],
            'name_french' => [
                'required',
                'string',
                'max:255'
            ],
            'code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($productId)->whereNull('deleted_at')
            ],
            'code_french' => [
                'nullable',
                'string',
                'max:100'
            ],
            'model' => [
                'nullable',
                'string',
                'max:100'
            ],
            'model_french' => [
                'nullable',
                'string',
                'max:100'
            ],
            
            // Pricing
            'price' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/' // Validates decimal with 2 places max
            ],
            'discount' => [
                'nullable',
                'integer',
                'min:0',
                'max:100'
            ],
            
            // Categories
            'category_id' => [
                'required',
                'exists:categories,id,deleted_at,NULL'
            ],
            'sub_category_id' => [
                'nullable',
                'exists:sub_categories,id,deleted_at,NULL'
            ],
            
            // Descriptions
            'short_description' => [
                'nullable',
                'string',
                'max:500'
            ],
            'short_description_french' => [
                'nullable',
                'string',
                'max:500'
            ],
            'full_description' => [
                'nullable',
                'string'
            ],
            'full_description_french' => [
                'nullable',
                'string'
            ],
            
            // SEO
            'product_slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products')->ignore($productId)->whereNull('deleted_at'),
                'regex:/^[a-z0-9-]+$/'
            ],
            'page_title' => [
                'nullable',
                'string',
                'max:255'
            ],
            'page_title_french' => [
                'nullable',
                'string',
                'max:255'
            ],
            'meta_description_content' => [
                'nullable',
                'string',
                'max:160'
            ],
            'meta_description_content_french' => [
                'nullable',
                'string',
                'max:160'
            ],
            'meta_keywords_content' => [
                'nullable',
                'string',
                'max:255'
            ],
            'meta_keywords_content_french' => [
                'nullable',
                'string',
                'max:255'
            ],
            
            // Stock and Status
            'is_stock' => [
                'boolean'
            ],
            'status' => [
                'boolean'
            ],
            'featured' => [
                'boolean'
            ],
            'bestseller' => [
                'boolean'
            ],
            'today_deal' => [
                'boolean'
            ],
            'special' => [
                'boolean'
            ],
            
            // Tags
            'product_tag' => [
                'nullable',
                'string',
                'max:255'
            ],
            
            // Size Options
            'use_custom_size' => [
                'boolean'
            ],
            
            // Length/Width Settings
            'add_length_width' => [
                'boolean'
            ],
            'min_length' => [
                'required_if:add_length_width,true',
                'integer',
                'min:1'
            ],
            'max_length' => [
                'required_if:add_length_width,true',
                'integer',
                'min:1'
            ],
            'min_width' => [
                'required_if:add_length_width,true',
                'integer',
                'min:1'
            ],
            'max_width' => [
                'required_if:add_length_width,true',
                'integer',
                'min:1'
            ],
            'min_length_min_width_price' => [
                'required_if:add_length_width,true',
                'numeric',
                'min:0'
            ],
            'length_width_unit_price_black' => [
                'required_if:add_length_width,true',
                'numeric',
                'min:0'
            ],
            'length_width_price_color' => [
                'required_if:add_length_width,true',
                'numeric',
                'min:0'
            ],
            'length_width_color_show' => [
                'boolean'
            ],
            'length_width_pages_type' => [
                'required_if:add_length_width,true',
                'in:input,dropdown'
            ],
            'length_width_quantity_show' => [
                'boolean'
            ],
            'length_width_min_quantity' => [
                'required_if:length_width_quantity_show,true',
                'integer',
                'min:1'
            ],
            'length_width_max_quantity' => [
                'required_if:length_width_quantity_show,true',
                'integer',
                'min:1'
            ],
            
            // Page Settings
            'page_add_length_width' => [
                'boolean'
            ],
            'page_min_length' => [
                'required_if:page_add_length_width,true',
                'integer',
                'min:1'
            ],
            'page_max_length' => [
                'required_if:page_add_length_width,true',
                'integer',
                'min:1'
            ],
            'page_min_width' => [
                'required_if:page_add_length_width,true',
                'integer',
                'min:1'
            ],
            'page_max_width' => [
                'required_if:page_add_length_width,true',
                'integer',
                'min:1'
            ],
            'page_min_length_min_width_price' => [
                'required_if:page_add_length_width,true',
                'numeric',
                'min:0'
            ],
            'page_length_width_price_color' => [
                'required_if:page_add_length_width,true',
                'numeric',
                'min:0'
            ],
            'page_length_width_price_black' => [
                'required_if:page_add_length_width,true',
                'numeric',
                'min:0'
            ],
            'page_length_width_color_show' => [
                'boolean'
            ],
            'page_length_width_pages_type' => [
                'required_if:page_add_length_width,true',
                'in:input,dropdown'
            ],
            'page_length_width_pages_show' => [
                'boolean'
            ],
            'page_length_width_sheets_type' => [
                'required_if:page_length_width_pages_show,true',
                'in:input,dropdown'
            ],
            'page_length_width_sheets_show' => [
                'boolean'
            ],
            'page_length_width_quantity_type' => [
                'required_if:page_length_width_quantity_show,true',
                'in:input,dropdown'
            ],
            'page_length_width_quantity_show' => [
                'boolean'
            ],
            'page_length_width_min_quantity' => [
                'required_if:page_length_width_quantity_show,true',
                'integer',
                'min:1'
            ],
            'page_length_width_max_quantity' => [
                'required_if:page_length_width_quantity_show,true',
                'integer',
                'min:1'
            ],
            
            // Depth Settings
            'depth_add_length_width' => [
                'boolean'
            ],
            'min_depth' => [
                'required_if:depth_add_length_width,true',
                'integer',
                'min:1'
            ],
            'max_depth' => [
                'required_if:depth_add_length_width,true',
                'integer',
                'min:1'
            ],
            'depth_min_length' => [
                'required_if:depth_add_length_width,true',
                'integer',
                'min:1'
            ],
            'depth_max_length' => [
                'required_if:depth_add_length_width,true',
                'integer',
                'min:1'
            ],
            'depth_min_width' => [
                'required_if:depth_add_length_width,true',
                'integer',
                'min:1'
            ],
            'depth_max_width' => [
                'required_if:depth_add_length_width,true',
                'integer',
                'min:1'
            ],
            'depth_width_length_price' => [
                'required_if:depth_add_length_width,true',
                'numeric',
                'min:0'
            ],
            'depth_unit_price_black' => [
                'required_if:depth_add_length_width,true',
                'numeric',
                'min:0'
            ],
            'depth_price_color' => [
                'required_if:depth_add_length_width,true',
                'numeric',
                'min:0'
            ],
            'depth_color_show' => [
                'boolean'
            ],
            'depth_width_length_type' => [
                'required_if:depth_add_length_width,true',
                'in:input,dropdown'
            ],
            'depth_width_length_quantity_show' => [
                'boolean'
            ],
            'depth_min_quantity' => [
                'required_if:depth_width_length_quantity_show,true',
                'integer',
                'min:1'
            ],
            'depth_max_quantity' => [
                'required_if:depth_width_length_quantity_show,true',
                'integer',
                'min:1'
            ],
            
            // Additional Options
            'votre_text' => [
                'boolean'
            ],
            'recto_verso' => [
                'boolean'
            ],
            'recto_verso_price' => [
                'required_if:recto_verso,true',
                'numeric',
                'min:0'
            ],
            'call' => [
                'boolean'
            ],
            'phone_number' => [
                'required_if:call,true',
                'string',
                'max:20',
                'regex:/^[+]?[\d\s\-()]+$/' // Phone number validation
            ],
            
            // Shipping Box Dimensions
            'shipping_box_length' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'shipping_box_width' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'shipping_box_height' => [
                'nullable',
                'numeric',
                'min:0'
            ],
            'shipping_box_weight' => [
                'nullable',
                'numeric',
                'min:0'
            ],
        ];
    }

    /**
     * Get the custom validation messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'name.unique' => 'This product name already exists.',
            'name.max' => 'Product name may not be greater than 255 characters.',
            'name_french.required' => 'French product name is required.',
            'name_french.max' => 'French product name may not be greater than 255 characters.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'price.regex' => 'Price format is invalid. Use format like 99.99.',
            'discount.integer' => 'Discount must be an integer.',
            'discount.max' => 'Discount may not be greater than 100.',
            'category_id.required' => 'Category is required.',
            'category_id.exists' => 'Selected category is invalid.',
            'sub_category_id.exists' => 'Selected subcategory is invalid.',
            'product_slug.unique' => 'This slug already exists.',
            'product_slug.regex' => 'Slug may only contain letters, numbers, and hyphens.',
            'phone_number.regex' => 'Phone number format is invalid.',
            'phone_number.required_if' => 'Phone number is required when call option is enabled.',
        ];
    }

    /**
     * Get custom attributes for validator.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Product Name',
            'name_french' => 'French Product Name',
            'code' => 'Product Code',
            'code_french' => 'French Product Code',
            'model' => 'Product Model',
            'model_french' => 'French Product Model',
            'price' => 'Price',
            'discount' => 'Discount',
            'category_id' => 'Category',
            'sub_category_id' => 'Subcategory',
            'short_description' => 'Short Description',
            'short_description_french' => 'French Short Description',
            'full_description' => 'Full Description',
            'full_description_french' => 'French Full Description',
            'product_slug' => 'Product Slug',
            'page_title' => 'Page Title',
            'page_title_french' => 'French Page Title',
            'meta_description_content' => 'Meta Description',
            'meta_description_content_french' => 'French Meta Description',
            'meta_keywords_content' => 'Meta Keywords',
            'meta_keywords_content_french' => 'French Meta Keywords',
            'is_stock' => 'In Stock',
            'status' => 'Status',
            'featured' => 'Featured',
            'bestseller' => 'Bestseller',
            'today_deal' => 'Today Deal',
            'special' => 'Special',
            'product_tag' => 'Product Tags',
            'use_custom_size' => 'Use Custom Size',
            'add_length_width' => 'Add Length/Width',
            'min_length' => 'Min Length',
            'max_length' => 'Max Length',
            'min_width' => 'Min Width',
            'max_width' => 'Max Width',
            'min_length_min_width_price' => 'Min Length/Width Price',
            'length_width_unit_price_black' => 'Unit Price (Black)',
            'length_width_price_color' => 'Price (Color)',
            'length_width_color_show' => 'Show Color Option',
            'length_width_pages_type' => 'Pages Type',
            'length_width_quantity_show' => 'Show Quantity',
            'length_width_min_quantity' => 'Min Quantity',
            'length_width_max_quantity' => 'Max Quantity',
            'votre_text' => 'Your Text Option',
            'recto_verso' => 'Recto Verso',
            'recto_verso_price' => 'Recto Verso Price',
            'call' => 'Call Option',
            'phone_number' => 'Phone Number',
            'shipping_box_length' => 'Shipping Box Length',
            'shipping_box_width' => 'Shipping Box Width',
            'shipping_box_height' => 'Shipping Box Height',
            'shipping_box_weight' => 'Shipping Box Weight',
        ];
    }
}
