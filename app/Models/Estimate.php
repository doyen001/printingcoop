<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Estimate extends Model
{
    protected $table = 'estimates';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'contact_name',
        'company_name',
        'email',
        'phone_number',
        'street',
        'city',
        'province',
        'country',
        'postal_code',
        'product_type',
        'product_name',
        'has_quote_form',
        'same_quote_request',
        'qty_1',
        'qty_2',
        'qty_3',
        'more_qty',
        'flat_size',
        'finish_size',
        'finish_size',
        'paper_stock'
    ];
    
    protected $casts = [
        'has_quote_form' => 'boolean',
        'same_quote_request' => 'boolean',
    ];

    /**
     * Get all estimates (CI equivalent: getAllEstimates)
     */
    public static function getAllEstimates()
    {
        return DB::table('estimates')
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })->toArray();
    }

    /**
     * Get estimate data by ID (CI equivalent: getEstimateDataById)
     */
    public static function getEstimateDataById($id)
    {
        $result = DB::table('estimates')->where('id', $id)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Save estimate data (CI equivalent: saveEstimateData)
     */
    public static function saveEstimateData($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        
        if (!empty($id)) {
            // Update existing
            $data['updated_at'] = now();
            $result = DB::table('estimates')->where('id', $id)->update($data);
            return $result ? $id : 0;
        } else {
            // Create new
            $data['created_at'] = now();
            $data['updated_at'] = now();
            return DB::table('estimates')->insertGetId($data);
        }
    }

    /**
     * Delete estimate (CI equivalent: deleteProductEstimates)
     */
    public static function deleteProductEstimates($id)
    {
        $result = DB::table('estimates')->where('id', $id)->delete();
        return $result ? 1 : 0;
    }

    /**
     * Get validation rules (CI equivalent: $rules)
     */
    public static function getValidationRules()
    {
        return [
            'contact_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'flat_size' => 'required|string|max:255',
            'finish_size' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:255|min:6|max:15',
            'postal_code' => 'required|string|max:255|min:6|max:10',
            'product_type' => 'nullable|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'has_quote_form' => 'nullable|boolean',
            'same_quote_request' => 'nullable|boolean',
            'qty_1' => 'nullable|string|max:255',
            'qty_2' => 'nullable|string|max:255',
            'qty_3' => 'nullable|string|max:255',
            'more_qty' => 'nullable|string|max:255',
            'paper_stock' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get validation messages (CI equivalent: custom error messages)
     */
    public static function getValidationMessages()
    {
        return [
            'contact_name.required' => 'Contact Name is required',
            'company_name.required' => 'Company Name is required',
            'street.required' => 'Street is required',
            'city.required' => 'City is required',
            'country.required' => 'Country is required',
            'flat_size.required' => 'Flat Size is required',
            'finish_size.required' => 'Finish Size is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'phone_number.required' => 'Phone Number is required',
            'phone_number.min' => 'Phone Number must be at least 6 characters',
            'phone_number.max' => 'Phone Number may not be greater than 15 characters',
            'postal_code.required' => 'Postal Code is required',
            'postal_code.min' => 'Postal Code must be at least 6 characters',
            'postal_code.max' => 'Postal Code may not be greater than 10 characters',
        ];
    }
}
