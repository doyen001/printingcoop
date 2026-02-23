<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Admin StoresController - Complete store management
 * CI: application/controllers/admin/Stores.php (158 lines)
 */
class StoresController extends Controller
{
    /**
     * Display stores list
     * CI: lines 15-29
     */
    public function index()
    {
        // Get stores list (like CI project)
        $lists = DB::table('stores')
            ->orderBy('id', 'desc')
            ->get();
        
        // Get language list (like CI project)
        $language = DB::table('language')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        
        // Get currency list (like CI project)
        $currency = DB::table('currency')
            ->orderBy('currency_name')
            ->pluck('currency_name', 'id')
            ->toArray();
        
        // Convert lists to array format like CI project's result_array()
        $lists_array = [];
        foreach ($lists as $list) {
            $lists_array[] = (array) $list;
        }
        
        $data = [
            'page_title' => 'Stores',
            'sub_page_title' => 'Add New Store',
            'sub_page_url' => 'addEdit',
            'lists' => $lists_array,
            'language' => $language,
            'currency' => $currency,
        ];
        
        return view('admin.stores.index', $data);
    }
    
    /**
     * Add/Edit store
     * CI: lines 31-156
     */
    public function addEdit(Request $request, $id = null)
    {
        $page_title = 'Add New Store';
        if (!empty($id)) {
            $page_title = 'Edit Store';
        }
        
        // Get store data (like CI project)
        $postData = [];
        if ($id) {
            $store_data = DB::table('stores')->where('id', $id)->first();
            if ($store_data) {
                $postData = (array) $store_data;
            }
        }
        
        // Get lists for dropdowns (like CI project)
        $lists = DB::table('stores')->orderBy('id', 'desc')->get();
        $language = DB::table('language')->orderBy('name')->pluck('name', 'id')->toArray();
        $currency = DB::table('currency')->orderBy('currency_name')->pluck('currency_name', 'id')->toArray();
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'url' => 'required|max:255',
                'address' => 'required',
                'langue_id' => 'required|integer',
            ];
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            // Handle file upload for PDF logo
            $pdfLogoPath = $postData['pdf_template_logo'] ?? '';
            if ($request->hasFile('pdf_template_logo')) {
                $file = $request->file('pdf_template_logo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/logo'), $filename);
                $pdfLogoPath = $filename;
            }
            
            // Handle file upload for email template logo
            $emailLogoPath = $postData['email_template_logo'] ?? '';
            if ($request->hasFile('logo_image')) {
                $file = $request->file('logo_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/logo'), $filename);
                $emailLogoPath = $filename;
            }
            
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'url' => $request->url,
                'address' => $request->address,
                'langue_id' => $request->langue_id,
                'order_id_prefix' => $request->order_id_prefix,
                'show_language_translation' => $request->show_language_translation ?? 0,
                'show_all_categories' => $request->show_all_categories ?? 0,
                'from_email' => $request->from_email ?: 'info@printing.coop',
                'admin_email1' => $request->admin_email1 ?: 'info@printing.coop',
                'admin_email2' => $request->admin_email2 ?: 'imprimeur.coop@gmail.com',
                'admin_email3' => $request->admin_email3 ?: 'techbull.in@gmail.com',
                'clover_mode' => $request->clover_mode ?? 0,
                'clover_sandbox_api_key' => $request->clover_sandbox_api_key,
                'clover_sandbox_secret' => $request->clover_sandbox_secret,
                'clover_api_key' => $request->clover_api_key,
                'clover_secret' => $request->clover_secret,
                'paypal_payment_mode' => $request->paypal_payment_mode ?: 'sendbox',
                'paypal_sandbox_business_email' => $request->paypal_sandbox_business_email ?: 'sb-ks2ro721209@business.example.com',
                'paypal_business_email' => $request->paypal_business_email ?: 'imprimeur.coop@gmail.com',
                'flag_ship' => $request->flag_ship ?? 'no',
                'email_footer_line' => $request->email_footer_line,
                'email_template_logo' => $emailLogoPath,
                'invoice_pdf_company' => $request->invoice_pdf_company,
                'order_pdf_company' => $request->order_pdf_company,
                'pdf_template_logo' => $pdfLogoPath,
            ];
            
            if ($id) {
                DB::table('stores')->where('id', $id)->update($data);
                $message = 'Store updated successfully';
            } else {
                DB::table('stores')->insert($data);
                $message = 'Store created successfully';
            }
            
            return redirect('admin/Stores')->with('message_success', $message);
        }
        
        return view('admin.stores.add_edit', [
            'page_title' => $page_title,
            'postData' => $postData,
            'lists' => $lists,
            'language' => $language,
            'currency' => $currency,
            'main_page_url' => '',
        ]);
    }
    
    /**
     * Delete store
     */
    public function delete($id)
    {
        DB::table('stores')->where('id', $id)->delete();
        
        return redirect('admin/Stores')->with('message_success', 'Store deleted successfully');
    }
    
    /**
     * Toggle store status
     */
    public function activeInactive($id, $status)
    {
        DB::table('stores')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Store activated successfully' : 'Store deactivated successfully';
        
        return redirect('admin/Stores')->with('message_success', $message);
    }
}
