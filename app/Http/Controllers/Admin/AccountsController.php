<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\AdminAuthService;

class AccountsController extends Controller
{
    public function index($status = null)
    {
        $title = empty($status) ? 'All Sub Admin' : ucfirst($status) . ' Sub Admin';
        $page_status = !empty($status) ? $status : '';
        
        $lists = DB::table('admins')
            ->where('role', 'subadmin')
            ->when($status, function($query, $status) {
                if ($status == 'active') {
                    return $query->where('status', 1);
                } else if ($status == 'inactive') {
                    return $query->where('status', 0);
                }
            })
            ->orderBy('id', 'desc')
            ->get();
        
        $stores = DB::table('stores')
            ->orderBy('name', 'asc')
            ->get();
        
        // Convert stores to key-value array for easy lookup (like CI project)
        $stores_array = [];
        foreach ($stores as $store) {
            $stores_array[$store->id] = $store->name;
        }
        
        // Convert lists to array format like CI project's result_array()
        $lists_array = [];
        foreach ($lists as $list) {
            $lists_array[] = (array) $list;
        }
        
        return view('admin.accounts.index', [
            'page_title' => $title,
            'page_status' => $page_status,
            'sub_page_title' => 'Add New Sub Admin',
            'lists' => $lists_array,
            'stores' => $stores_array,
        ]);
    }
    
    public function addEdit($id = null)
    {
        $page_title = 'Add New Sub Admin';
        
        if ($id) {
            $page_title = 'Edit Sub Admin';
        }
        
        $postData = [];
        if ($id) {
            $postData = DB::table('admins')->where('id', $id)->first();
            if ($postData) {
                $postData = (array) $postData;
            }
        }
        
        if (request()->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:admins,email,' . $id,
                'username' => 'required|unique:admins,username,' . $id,
                'password' => $id ? 'nullable|min:6' : 'required|min:6',
                'store_ids' => 'required',
            ];
            
            $validator = Validator::make(request()->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            $data = [
                'name' => request()->name,
                'email' => request()->email,
                'mobile' => request()->mobile,
                'username' => request()->username,
                'role' => 'subadmin',
                'address' => request()->address,
                'status' => 1, // Default status to 1 (active) like CI
            ];
            
            // Handle store_ids
            $store_ids = request()->store_ids;
            if (!empty($store_ids)) {
                $data['store_ids'] = implode(',', $store_ids);
            } else {
                $data['store_ids'] = '';
            }
            
            // Handle password - use MD5 to match CI project
            if (request()->password) {
                $data['password'] = md5(request()->password);
            }
            
            // Add timestamps
            if ($id) {
                // If editing and password is empty, don't update it
                if (empty(request()->password)) {
                    unset($data['password']);
                }
                $data['updated'] = now();
                DB::table('admins')->where('id', $id)->update($data);
                $insert_id = $id;
                $message = 'Sub Admin updated successfully';
            } else {
                $data['created'] = now();
                $data['updated'] = now();
                DB::table('admins')->insertGetId($data);
                $insert_id = DB::getPdo()->lastInsertId();
                $message = 'Sub Admin created successfully';
            }
            
            // Save admin module permissions (like CI project)
            if ($insert_id > 0) {
                $this->saveAdminAttributesData(request(), $insert_id);
            }
            
            return redirect('admin/Accounts')->with('message_success', $message);
        }
        
        // Get modules with sub-modules structured like CI getModuleList()
        $modulesData = DB::table('modules as m')
            ->leftJoin('sub_modules as sm', function($join) {
                $join->on('m.id', '=', 'sm.module_id')
                     ->where('m.status', '=', 1)
                     ->where('sm.status', '=', 1);
            })
            ->select('m.id as module_id', 'm.module_name', 'sm.id as sub_module_id', 'sm.sub_module_name')
            ->orderBy('m.order')
            ->orderBy('m.module_name')
            ->orderBy('sm.module_id')
            ->orderBy('sm.order')
            ->orderBy('sm.sub_module_name')
            ->get();
        
        // Structure the data like CI does
        $AttributesList = [];
        foreach ($modulesData as $module) {
            if (!array_key_exists($module->module_id, $AttributesList)) {
                $AttributesList[$module->module_id] = [
                    'name' => $module->module_name,
                    'items' => [],
                ];
            }
            
            if ($module->sub_module_id != null) {
                $AttributesList[$module->module_id]['items'][$module->sub_module_id] = $module->sub_module_name;
            }
        }
        
        // Get admin modules with sub-modules structured like CI getAdminModuleByAdminId()
        $ProductAttributes = [];
        if ($id) {
            $adminModulesData = DB::table('admin_modules as m')
                ->leftJoin('admin_sub_modules as sm', function($join) use ($id) {
                    $join->on('m.admin_id', '=', 'sm.admin_id')
                         ->on('m.module_id', '=', 'sm.module_id')
                         ->where('m.admin_id', '=', $id);
                })
                ->where('m.admin_id', $id)
                ->select('m.*', 'sm.id as sub_module_id', 'sm.sub_module_id as sm_sub_module_id')
                ->get();
            
            // Structure the data like CI does
            foreach ($adminModulesData as $module) {
                if (!array_key_exists($module->module_id, $ProductAttributes)) {
                    $ProductAttributes[$module->module_id] = [
                        'data' => [
                            'admin_id' => $id,
                            'module_id' => $module->module_id,
                        ],
                        'items' => [],
                    ];
                }
                
                if ($module->sub_module_id != null) {
                    $ProductAttributes[$module->module_id]['items'][$module->sm_sub_module_id] = [
                        'admin_id' => $id,
                        'module_id' => $module->module_id,
                        'sub_module_id' => $module->sm_sub_module_id,
                    ];
                }
            }
        }
        
        return view('admin.accounts.add_edit', [
            'page_title' => $page_title,
            'postData' => $postData,
            'StoreList' => DB::table('stores')->orderBy('name', 'asc')->get(),
            'AttributesList' => $AttributesList,
            'ProductAttributes' => $ProductAttributes,
        ]);
    }
    
    public function changePassword()
    {
        $page_title = 'Change Password';
        $postData = [];
        $success = false;
        
        // Get current logged in admin user (CI project style)
        $adminLoginId = session('admin_login')->id;
        
        if (empty($adminLoginId)) {
            return redirect('pcoopadmin')->with('message_error', 'Please login first.');
        }
        
        // Get admin data (CI project style)
        $adminData = DB::table('admins')->where('id', $adminLoginId)->first();
        
        if (!$adminData) {
            return redirect('pcoopadmin')->with('message_error', 'Admin account not found.');
        }
        
        $postData = (array) $adminData;
        
        if (request()->isMethod('post')) {
            $rules = [
                'email' => 'required|email',
            ];
            
            $validator = Validator::make(request()->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Update postData with submitted email (CI project style)
            $postData['email'] = request()->email;
            
            // Generate reset password link (like CI)
            $url = url('pcoopadmin/reset-password/' . base64_encode($postData['id']));
            
            $toEmail = $postData['email'];
            $subject = 'Reset Password';
            $name = $postData['name'] ?? 'Admin';
            
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Dear ' . $name . ',
                <br>
                    You have requested to reset your password. Please click on the link below to reset your password.
                </span>
            </div>
            <div style="margin: 25px 0px;"><a style="font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;" href="' . $url . '">Reset Password</a>
            </div>';
            
            // Simple email template
            $fullBody = '<!DOCTYPE html><html><head><title>' . $subject . '</title></head><body>' . $body . '</body></html>';
            
            try {
                Mail::html($fullBody, function($message) use ($toEmail, $subject, $name) {
                    $message->to($toEmail, $name)
                            ->subject($subject);
                });
                
                session()->flash('message_success', 'Password reset link has been sent to your email address.');
                $success = true;
            } catch (\Exception $e) {
                session()->flash('message_error', 'Failed to send email. Please try again.');
            }
        }
        
        return view('admin.accounts.change_password', [
            'page_title' => $page_title,
            'postData' => $postData,
            'success' => $success,
        ]);
    }
    
    public function delete($id)
    {
        DB::table('admins')->where('id', $id)->delete();
        
        return redirect('admin/Accounts')->with('message_success', 'Sub Admin deleted successfully');
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('admins')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Sub Admin activated successfully' : 'Sub Admin deactivated successfully';
        
        return redirect('admin/Accounts')->with('message_success', $message);
    }
    
    public function logout()
    {
        // Logout using AdminAuthService (CI-compatible)
        AdminAuthService::logout();
        
        // Also clear any Laravel auth session that might exist
        if (auth()->guard('admin')->check()) {
            auth()->guard('admin')->logout();
        }
        
        return redirect('admin/login');
    }
    
    /**
     * Save admin module permissions (replicate CI saveAdminAttributesData)
     */
    private function saveAdminAttributesData($request, $admin_id)
    {
        // Get modules list like CI project
        $modulesData = DB::table('modules as m')
            ->leftJoin('sub_modules as sm', function($join) {
                $join->on('m.id', '=', 'sm.module_id')
                     ->where('m.status', '=', 1)
                     ->where('sm.status', '=', 1);
            })
            ->select('m.id as module_id', 'm.module_name', 'sm.id as sub_module_id', 'sm.sub_module_name')
            ->orderBy('m.order')
            ->orderBy('m.module_name')
            ->orderBy('sm.module_id')
            ->orderBy('sm.order')
            ->orderBy('sm.sub_module_name')
            ->get();
        
        // Structure the data like CI does
        $AttributesList = [];
        foreach ($modulesData as $module) {
            if (!array_key_exists($module->module_id, $AttributesList)) {
                $AttributesList[$module->module_id] = [
                    'name' => $module->module_name,
                    'items' => [],
                ];
            }
            
            if ($module->sub_module_id != null) {
                $AttributesList[$module->module_id]['items'][$module->sub_module_id] = $module->sub_module_name;
            }
        }
        
        // Clear existing permissions
        DB::table('admin_modules')->where('admin_id', $admin_id)->delete();
        DB::table('admin_sub_modules')->where('admin_id', $admin_id)->delete();
        
        $attributes_data = [];
        $attributes_item_data = [];
        
        // Process module permissions like CI project
        foreach ($AttributesList as $key => $val) {
            $attribute_name = 'attribute_id_' . $key;
            $attribute_id = $request->input($attribute_name);
            
            if (!empty($attribute_id)) {
                $attributes_sdata = [
                    'admin_id' => $admin_id,
                    'module_id' => $attribute_id,
                ];
                $attributes_data[] = $attributes_sdata;
                
                $product_attribute_item_ids = $request->input('attribute_item_id_' . $attribute_id, []);
                
                foreach ($product_attribute_item_ids as $subkey => $subval) {
                    if (!empty($subval)) {
                        $attributes_item_sdata = [
                            'admin_id' => $admin_id,
                            'module_id' => $attribute_id,
                            'sub_module_id' => $subval,
                        ];
                        $attributes_item_data[] = $attributes_item_sdata;
                    }
                }
            }
        }
        
        // Insert module permissions
        if (!empty($attributes_data)) {
            DB::table('admin_modules')->insert($attributes_data);
        }
        
        // Insert sub-module permissions
        if (!empty($attributes_item_data)) {
            DB::table('admin_sub_modules')->insert($attributes_item_data);
        }
    }
}
