<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Admin ConfigurationsController - Complete site configuration management
 * CI: application/controllers/admin/Configrations.php (204 lines)
 */
class ConfigurationsController extends Controller
{
    /**
     * Display configurations list
     * CI: lines 15-31
     */
    public function index()
    {
        // Get configurations list (like CI project)
        $lists = DB::table('configurations')
            ->orderBy('main_store_id')
            ->get();
        
        // Get main store list (like CI project)
        $MainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        
        // Convert lists to array format like CI project's result_array()
        $lists_array = [];
        foreach ($lists as $list) {
            $lists_array[] = (array) $list;
        }
        
        $data = [
            'page_title' => 'Site Configrations',
            'lists' => $lists_array,
            'MainStoreList' => $MainStoreList,
            'sub_page_title' => 'Add New Page',
            'sub_page_url' => 'addEdit',
        ];
        
        return view('admin.configurations.index', $data);
    }
    
    /**
     * Edit configuration
     * CI: lines 33-44
     */
    public function addEdit(Request $request, $id = null)
    {
        // Get configuration data (like CI project)
        $configrations = [];
        if ($id) {
            $config_data = DB::table('configurations')->where('id', $id)->first();
            if ($config_data) {
                $configrations = (array) $config_data;
            }
        }
        
        // Get main store list (like CI project)
        $MainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        
        // Get custom pricing data if exists
        $custom_pricing = [];
        if (!empty($configrations['custom_pricing'])) {
            $custom_pricing = json_decode($configrations['custom_pricing'], true) ?? [];
        }
        
        // Set page title (like CI project)
        $page_title = 'Configrations';
        if (!empty($configrations['main_store_id']) && isset($MainStoreList[$configrations['main_store_id']])) {
            $page_title .= ' ' . $MainStoreList[$configrations['main_store_id']];
        }
        
        $data = [
            'page_title' => $page_title,
            'configrations' => $configrations,
            'MainStoreList' => $MainStoreList,
            'custom_pricing' => $custom_pricing,
            'main_page_url' => '',
        ];
        
        return view('admin.configurations.add_edit', $data);
    }
    
    /**
     * Save configuration
     * CI: lines 46-202
     */
    public function saveConfigurations(Request $request)
    {
        $id = $request->input('id');
        
        $data = [
            'contact_no' => $request->input('contact_no'),
            'office_timing' => $request->input('office_timing'),
            'copy_right' => $request->input('copy_right'),
            'address_one' => $request->input('address_one'),
            'contact_no_french' => $request->input('contact_no_french'),
            'office_timing_french' => $request->input('office_timing_french'),
            'copy_right_french' => $request->input('copy_right_french'),
            'address_one_french' => $request->input('address_one_french'),
            'log_alt_teg' => $request->input('log_alt_teg'),
            'log_alt_teg_french' => $request->input('log_alt_teg_french'),
            'updated' => now(),
        ];
        
        // Handle custom pricing
        $customPricing = $request->input('custom_pricing', []);
        $pricingData = [
            'turnaround_standard_price' => $customPricing['turnaround_standard_price'] ?? 0,
            'turnaround_rush_price' => $customPricing['turnaround_rush_price'] ?? 15,
            'turnaround_same_day_price' => $customPricing['turnaround_same_day_price'] ?? 25,
            'folding_price' => $customPricing['folding_price'] ?? 0.01,
            'drilling_price' => $customPricing['drilling_price'] ?? 0.02,
            'collate_price' => $customPricing['collate_price'] ?? 0.01,
        ];
        
        // Store pricing in announcement with marker
        $announcement = $request->input('announcement', '');
        $announcement = preg_replace('/<!-- CUSTOM_PRICING_DATA:.*? -->/', '', $announcement);
        $pricingJson = json_encode($pricingData);
        $data['announcement'] = $announcement . '<!-- CUSTOM_PRICING_DATA:' . $pricingJson . ' -->';
        
        $data['announcement_french'] = $request->input('announcement_french');
        
        // Handle logo image upload
        if ($request->hasFile('logo_image')) {
            $file = $request->file('logo_image');
            if ($file->isValid()) {
                $filename = time() . '_logo.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/logos', $filename);
                $data['logo_image'] = $filename;
                
                // Delete old image
                $oldImage = $request->input('old_image');
                if ($oldImage && Storage::exists('public/logos/' . $oldImage)) {
                    Storage::delete('public/logos/' . $oldImage);
                }
            }
        }
        
        // Handle French logo image upload
        if ($request->hasFile('logo_image_french')) {
            $file = $request->file('logo_image_french');
            if ($file->isValid()) {
                $filename = time() . '_logo_french.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/logos', $filename);
                $data['logo_image_french'] = $filename;
                
                // Delete old image
                $oldImage = $request->input('old_image_french');
                if ($oldImage && Storage::exists('public/logos/' . $oldImage)) {
                    Storage::delete('public/logos/' . $oldImage);
                }
            }
        }
        
        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            if ($file->isValid()) {
                $filename = time() . '_favicon.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/logos', $filename);
                $data['favicon'] = $filename;
                
                // Delete old favicon
                $oldFavicon = $request->input('old_favicon');
                if ($oldFavicon && Storage::exists('public/logos/' . $oldFavicon)) {
                    Storage::delete('public/logos/' . $oldFavicon);
                }
            }
        }
        
        // Handle French favicon upload
        if ($request->hasFile('french_favicon')) {
            $file = $request->file('french_favicon');
            if ($file->isValid()) {
                $filename = time() . '_favicon_french.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('public/logos', $filename);
                $data['french_favicon'] = $filename;
                
                // Delete old favicon
                $oldFavicon = $request->input('old_french_favicon');
                if ($oldFavicon && Storage::exists('public/logos/' . $oldFavicon)) {
                    Storage::delete('public/logos/' . $oldFavicon);
                }
            }
        }
        
        if ($id) {
            DB::table('configurations')->where('id', $id)->update($data);
            $message = 'Configurations updated successfully';
        } else {
            $data['created'] = now();
            DB::table('configurations')->insert($data);
            $message = 'Configurations created successfully';
        }
        
        return redirect('admin/Configurations')->with('message_success', $message);
    }
    
    /**
     * Email settings
     */
    public function emailSettings(Request $request)
    {
        if ($request->isMethod('post')) {
            $settings = [
                'smtp_host' => $request->input('smtp_host'),
                'smtp_port' => $request->input('smtp_port'),
                'smtp_user' => $request->input('smtp_user'),
                'smtp_password' => $request->input('smtp_password'),
                'smtp_encryption' => $request->input('smtp_encryption'),
                'from_email' => $request->input('from_email'),
                'from_name' => $request->input('from_name'),
            ];
            
            // Store in configurations or settings table
            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
            
            return redirect()->back()->with('message_success', 'Email settings updated successfully');
        }
        
        $settings = DB::table('settings')
            ->whereIn('key', ['smtp_host', 'smtp_port', 'smtp_user', 'smtp_password', 'smtp_encryption', 'from_email', 'from_name'])
            ->pluck('value', 'key');
        
        $data = [
            'page_title' => 'Email Settings',
            'settings' => $settings,
        ];
        
        return view('admin.configurations.email_settings', $data);
    }
    
    /**
     * Payment gateway settings
     */
    public function paymentSettings(Request $request)
    {
        if ($request->isMethod('post')) {
            $settings = [
                'paypal_mode' => $request->input('paypal_mode'),
                'paypal_client_id' => $request->input('paypal_client_id'),
                'paypal_secret' => $request->input('paypal_secret'),
                'stripe_publishable_key' => $request->input('stripe_publishable_key'),
                'stripe_secret_key' => $request->input('stripe_secret_key'),
                'payment_currency' => $request->input('payment_currency'),
            ];
            
            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
            
            return redirect()->back()->with('message_success', 'Payment settings updated successfully');
        }
        
        $settings = DB::table('settings')
            ->whereIn('key', ['paypal_mode', 'paypal_client_id', 'paypal_secret', 'stripe_publishable_key', 'stripe_secret_key', 'payment_currency'])
            ->pluck('value', 'key');
        
        $data = [
            'page_title' => 'Payment Gateway Settings',
            'settings' => $settings,
        ];
        
        return view('admin.configurations.payment_settings', $data);
    }
    
    /**
     * Shipping settings
     */
    public function shippingSettings(Request $request)
    {
        if ($request->isMethod('post')) {
            $settings = [
                'shipping_enabled' => $request->input('shipping_enabled', 0),
                'free_shipping_threshold' => $request->input('free_shipping_threshold'),
                'flat_rate_shipping' => $request->input('flat_rate_shipping'),
                'shipping_calculation_method' => $request->input('shipping_calculation_method'),
            ];
            
            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
            
            return redirect()->back()->with('message_success', 'Shipping settings updated successfully');
        }
        
        $settings = DB::table('settings')
            ->whereIn('key', ['shipping_enabled', 'free_shipping_threshold', 'flat_rate_shipping', 'shipping_calculation_method'])
            ->pluck('value', 'key');
        
        $data = [
            'page_title' => 'Shipping Settings',
            'settings' => $settings,
        ];
        
        return view('admin.configurations.shipping_settings', $data);
    }
    
    /**
     * Tax settings
     */
    public function taxSettings(Request $request)
    {
        if ($request->isMethod('post')) {
            $settings = [
                'tax_enabled' => $request->input('tax_enabled', 0),
                'tax_rate' => $request->input('tax_rate'),
                'tax_calculation_method' => $request->input('tax_calculation_method'),
                'tax_display_method' => $request->input('tax_display_method'),
            ];
            
            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
            
            return redirect()->back()->with('message_success', 'Tax settings updated successfully');
        }
        
        $settings = DB::table('settings')
            ->whereIn('key', ['tax_enabled', 'tax_rate', 'tax_calculation_method', 'tax_display_method'])
            ->pluck('value', 'key');
        
        $data = [
            'page_title' => 'Tax Settings',
            'settings' => $settings,
        ];
        
        return view('admin.configurations.tax_settings', $data);
    }
}
