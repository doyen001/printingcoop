<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AdminAuthService;

class DashboardController extends Controller
{
    /**
     * Admin dashboard (replicate CI Dashboards->index lines 13-50)
     */
    public function index()
    {
        // Get current admin using CI-compatible AdminAuthService first, then Laravel auth
        $admin = AdminAuthService::getCurrentAdmin() ?: auth()->guard('admin')->user();
        
        if (!$admin) {
            return redirect('pcoopadmin')->with('message_error', 'Please login to access admin panel');
        }
        
        $adminLoginRole = $admin->role;
        $data = [];
        
        // Get total users count (line 19)
        $totalUser = $this->getCountUser();
        $data['totalUser'] = $totalUser;
        
        // Get recent users list (line 20)
        $userList = $this->getListNewUser();
        $data['userList'] = $userList;
        
        // Get total orders count (line 23)
        $data['totalOrder'] = $this->getCountOuder();
        
        // Get total unresolved tickets (line 24)
        $data['totalUnresolvedTicket'] = $this->getCountTicket(0);
        
        // Get total subscribe emails (line 26)
        $data['totalSubscribeEmail'] = $this->getCountSubscribeEmail();
        
        // Get total products (line 27)
        $data['totalProducts'] = $this->getCountProducts();
        
        // Get total cancelled orders in last 24 hours (lines 28-31)
        $query = DB::select('SELECT count(id) as total_order FROM product_orders WHERE updated >= now() - INTERVAL 1 DAY AND status=6');
        $totalCancelOrder = $query;
        $data['totalCancelOrder'] = !empty($totalCancelOrder[0]->total_order) ? $totalCancelOrder[0]->total_order : 0;
        
        // Get total sales in last 24 hours (lines 33-36)
        $query = DB::select('SELECT sum(total_amount) as total_sale FROM product_orders WHERE updated >= now() - INTERVAL 1 DAY AND status IN(2,3,4,5)');
        $totalSale = $query;
        $data['totalSale'] = !empty($totalSale[0]->total_sale) ? $totalSale[0]->total_sale : 0;
        
        // Role-based view (lines 45-49)
        if ($adminLoginRole != 'admin') {
            return view('admin.dashboard.sub_admin_layout', $data);
        } else {
            return view('admin.dashboard.dashboard', $data);
        }
    }
    
    // ========== Private Helper Methods (replicate Model methods) ==========
    
    /**
     * Get total user count (replicate User_Model->getCountUser)
     */
    private function getCountUser($status = null)
    {
        $query = DB::table('users');
        
        if ($status == 'active') {
            $query->where('status', 1);
        } else if ($status == 'inactive') {
            $query->where('status', 0);
        }
        
        return $query->count();
    }
    
    /**
     * Get list of new users (replicate User_Model->getListNewUser)
     */
    private function getListNewUser($limit = 10)
    {
        $users = DB::table('users')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $users->toArray());
    }
    
    /**
     * Get total order count (replicate ProductOrder_Model->getCountOuder)
     */
    private function getCountOuder($status = null)
    {
        $query = DB::table('product_orders');
        
        if ($status !== null) {
            $query->where('status', $status);
        }
        
        return $query->count();
    }
    
    /**
     * Get ticket count (replicate Ticket_Model->getCountTicket)
     */
    private function getCountTicket($status = null)
    {
        $query = DB::table('tickets');
        
        if ($status !== null) {
            $query->where('status', $status);
        }
        
        return $query->count();
    }
    
    /**
     * Get subscribe email count (replicate Product_Model->getCountSubscribeEmail)
     */
    private function getCountSubscribeEmail()
    {
        return DB::table('subscribe_emails')->count();
    }
    
    /**
     * Get products count (replicate Product_Model->getCountProducts)
     */
    private function getCountProducts()
    {
        return DB::table('products')->count();
    }
    
    /**
     * Display accounts list
     * CI: Accounts->index() lines 14-32
     */
    public function accountIndex($status = null)
    {
        $title = empty($status) ? 'All Sub Admin' : ucfirst($status) . ' Sub Admin';
        $page_status = !empty($status) ? $status : '';
        
        // Get admin list with status filter
        $query = DB::table('admins')
            ->select('admins.*') // Select all fields including username
            ->where('role', 'subadmin') // Sub admin role (like CI)
            ->orderBy('id', 'desc');
            
        if ($status) {
            $query->where('status', $status == 'active' ? 1 : 0);
        }
        
        $lists = $query->get();
        
        // Get stores for display
        $stores = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id');
        
        return view('admin.accounts.index', [
            'page_title' => $title,
            'page_status' => $page_status,
            'lists' => $lists,
            'stores' => $stores,
        ]);
    }
    
    /**
     * Change password for admin account
     * CI: Users->changePassword() lines 153-234 (simplified for admin)
     */
    public function changePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules = [
                'password' => 'required|min:6|confirmed',
            ];
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            // Get current admin using CI-compatible AdminAuthService first, then Laravel auth
            $admin = AdminAuthService::getCurrentAdmin() ?: auth()->guard('admin')->user();
            
            if (!$admin) {
                return redirect('pcoopadmin')->with('message_error', 'Please login to access admin panel');
            }
            
            $adminId = $admin->id;
            $password = md5($request->password); // Use MD5 for CI compatibility
            
            DB::table('admins')->where('id', $adminId)->update([
                'password' => $password,
                'updated' => now(),
            ]);
            
            return redirect('admin/dashboard')->with('message_success', 'Password changed successfully');
        }
        
        return view('admin.dashboard.change_password');
    }
    
    /**
     * Add/Edit admin account
     * CI: Accounts->add_edit() (simplified for admin)
     */
    public function accountAddEdit(Request $request, $id = null)
    {
        $account = null;
        if ($id) {
            $account = DB::table('admins')->where('id', $id)->first();
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255',
                'username' => 'required|max:255',
                'mobile' => 'max:20',
                'address' => 'max:500',
            ];
            
            // Only require password for new accounts
            if (!$id) {
                $rules['password'] = 'required|min:6';
            }
            
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'role' => 'subadmin',
                'updated' => now(),
            ];
            
            // Handle store assignments
            if ($request->has('store_ids')) {
                $data['store_ids'] = implode(',', $request->store_ids);
            }
            
            // Handle password
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }
            
            if ($id) {
                DB::table('admins')->where('id', $id)->update($data);
                $message = 'Account updated successfully';
            } else {
                $data['created'] = now();
                $data['status'] = 1;
                $data['role'] = 'subadmin'; // Sub admin role
                DB::table('admins')->insert($data);
                $message = 'Account created successfully';
            }
            
            // Handle module permissions
            if ($id) {
                // Clear existing permissions
                DB::table('admin_modules')->where('admin_id', $id)->delete();
                DB::table('admin_sub_modules')->where('admin_id', $id)->delete();
            }
            
            // Save new permissions
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'attribute_id_') === 0 && is_array($value)) {
                    $moduleId = substr($key, 13); // Remove 'attribute_id_' prefix
                    
                    // Save main module permission
                    DB::table('admin_modules')->insert([
                        'admin_id' => $id ?: DB::getPdo()->lastInsertId(),
                        'module_id' => $moduleId,
                    ]);
                    
                    // Save sub-module permissions
                    foreach ($value as $submoduleId) {
                        DB::table('admin_sub_modules')->insert([
                            'admin_id' => $id ?: DB::getPdo()->lastInsertId(),
                            'module_id' => $moduleId,
                            'sub_module_id' => $submoduleId,
                        ]);
                    }
                }
            }
            
            return redirect('admin/Accounts')->with('message_success', $message);
        }
        
        // Get stores for assignment
        $stores = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name')
            ->get();
        
        // Get account's assigned stores
        $assignedStores = [];
        if ($account && !empty($account->store_ids)) {
            $assignedStores = explode(',', $account->store_ids);
        }
        
        // Get modules and permissions
        $modules = DB::select("
            SELECT m.id as module_id, m.module_name,
                   sm.id as sub_module_id, sm.sub_module_name
            FROM modules AS m 
            LEFT JOIN sub_modules AS sm ON m.id = sm.module_id AND m.status = 1 AND sm.status = 1
            ORDER BY m.order, m.module_name, sm.module_id, sm.order, sm.sub_module_name
        ");
        
        // Organize modules like CI does
        $attributesList = [];
        foreach ($modules as $module) {
            if (!array_key_exists($module->module_id, $attributesList)) {
                $attributesList[$module->module_id] = [
                    'name' => $module->module_name,
                    'items' => [],
                ];
            }
            
            if ($module->sub_module_id != null) {
                $attributesList[$module->module_id]['items'][$module->sub_module_id] = $module->sub_module_name;
            }
        }
        
        // Get account's existing permissions
        $productAttributes = [];
        if ($id) {
            $adminModules = DB::select("
                SELECT *
                FROM admin_modules AS m 
                LEFT JOIN admin_sub_modules AS sm ON m.admin_id = sm.admin_id AND m.module_id = sm.module_id
                WHERE m.admin_id = ?
            ", [$id]);
            
            foreach ($adminModules as $module) {
                if (!array_key_exists($module->module_id, $productAttributes)) {
                    $productAttributes[$module->module_id] = [
                        'data' => [
                            'admin_id' => $id,
                            'module_id' => $module->module_id,
                        ],
                        'items' => [],
                    ];
                }
                
                if ($module->sub_module_id != null) {
                    $productAttributes[$module->module_id]['items'][$module->sub_module_id] = [
                        'admin_id' => $id,
                        'module_id' => $module->module_id,
                        'sub_module_id' => $module->sub_module_id,
                    ];
                }
            }
        }
        
        return view('admin.accounts.add_edit', [
            'page_title' => $id ? 'Edit Account' : 'Add New Account',
            'account' => $account,
            'stores' => $stores,
            'assignedStores' => $assignedStores,
            'attributesList' => $attributesList,
            'productAttributes' => $productAttributes,
        ]);
    }
    
    /**
     * Delete admin account
     * CI: Accounts->delete()
     */
    public function accountDelete($id, $status = null)
    {
        DB::table('admins')->where('id', $id)->delete();
        
        return redirect('admin/Accounts')->with('message_success', 'Account deleted successfully');
    }
    
    /**
     * Toggle admin account status
     * CI: Accounts->activeInactive()
     */
    public function accountActiveInactive($id, $status, $page_status = null)
    {
        DB::table('admins')->where('id', $id)->update([
            'status' => $status,
            'updated' => now(),
        ]);
        
        $message = $status == 1 ? 'Account activated successfully' : 'Account deactivated successfully';
        
        return redirect('admin/Accounts/' . $page_status)->with('message_success', $message);
    }
}
