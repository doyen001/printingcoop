<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

/**
 * Admin UsersController
 * Complete user management for admin panel
 * CI: application/controllers/admin/Users.php
 */
class UsersController extends Controller
{
    public $class_name = 'admin/Users/';

    /**
     * User listing with filters
     * CI: Users->index() lines 14-36
     */
    public function index($status = null)
    {
        try {
            $title = empty($status) ? 'All Users' : ucfirst($status) . ' Users';
            $page_status = !empty($status) ? $status : '';

            // Get store list (CI equivalent)
            $stores = DB::table('stores')->where('status', 1)->get();

            $data = [
                'page_title' => $title,
                'page_status' => $page_status,
                'status' => $status,
                'stores' => $stores,
                'BASE_URL' => url('/') . '/', // Same as CI
            ];
            
            Log::info('Users Index BASE_URL: ' . $data['BASE_URL']); // Debug log

            return view('admin.users.index', $data);
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@index: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error loading users: ' . $e->getMessage());
        }
    }

    /**
     * AJAX DataTables listing
     * CI: Users->ajaxList() lines 38-113
     */
    public function ajaxList(Request $request, $status = null)
    {
        try {
            $draw = intval($request->input('draw'));
            $start = intval($request->input('start'));
            $length = intval($request->input('length'));
            $search = $request->input('search');
            $searchValue = isset($search['value']) ? $search['value'] : '';

            // Get users using model
            $users = User::getDatatableUsers($status, $start, $length, $searchValue);
            $total = User::getDatatableUsersCount($status, $searchValue);

            $page_status = !empty($status) ? $status : '';
            $BASE_URL = url('/') . '/'; // Base URL with trailing slash
            $class_name = 'admin/Users/'; // Same as CI

            $data = [];
            foreach ($users as $user) {
                $statusBtn = '';
                if ($user->status == 1) {
                    $url = $BASE_URL . $class_name . 'activeInactive/' . $user->id . '/0/' . $page_status;
                    Log::info('Generated Active URL: ' . $url); // Debug log
                    $statusBtn = '<a href="' . $url . '">
                            <button type="submit" class="custon-active">Active</button>
                        </a>';
                } else {
                    $url = $BASE_URL . $class_name . 'activeInactive/' . $user->id . '/1/' . $page_status;
                    Log::info('Generated Inactive URL: ' . $url); // Debug log
                    $statusBtn = '<a href="' . $url . '">
                            <button type="submit" class="custon-active">Inactive</button>
                        </a>';
                }

                $actions = '<a class="view-btn" href="' . $BASE_URL . 'admin/Users/changePassword/' . $user->id . '/' . $page_status . '" style="color:#3c8dbc" title="Change Password">
                            <i class="fa far fa-eye fa-lg"></i> Change Password
                        </a>
                        <a class="view-btn" href="' . $BASE_URL . 'admin/Orders/index/all/' . $user->id . '" style="color:#3c8dbc" title="View orders">
                            <i class="fa far fa-eye fa-lg"></i> View orders
                        </a>
                        <a href="' . $BASE_URL . $class_name . 'deleteUser/' . $user->id . '/' . $page_status . '" style="color:#d71b23" title="delete" onclick="return confirm(\'Are you sure you want to delete this user?\');">
                            <i class="fa fa-trash fa-lg"></i>
                        </a>';

                $preferredStatus = '';
                if ($user->user_type == 2) {
                    $preferredStatus = '<b>(Preferred Customer)</b>';
                }

                $data[] = [
                    'customer_code' => 'CUST' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'website' => $user->website ?? '',
                    'name' => ucfirst($user->name) . ' ' . $preferredStatus,
                    'mobile' => $user->mobile,
                    'email' => $user->email,
                    'password' => '••••••••',
                    'last_login' => $user->last_login ? date('Y-m-d H:i:s', strtotime($user->last_login)) : 'Never',
                    'last_login_ip' => $user->last_login_ip ?? '',
                    'created' => date('Y-m-d H:i:s', strtotime($user->created)),
                    'status' => $statusBtn,
                    'action' => $actions,
                ];
            }

            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@ajaxList: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->input('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Search failed'
            ]);
        }
    }

    /**
     * Export users to CSV (CI project style)
     * CI: Users->exportCSV() lines 590-619
     */
    public function exportCSV($status = null)
    {
        try {
            // Use CI project style filename
            $filename = 'user-list-' . date('d') . '-' . date('m') . '-' . date('Y') . '.csv';
            
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");

            // Get users using CI project style method
            $lists = User::getUserList($status);
            
            // File creation (CI project style)
            $file = fopen('php://output', 'w');
            
            // CI project headers
            $header = array("Customer Code", "Name", "Mobile", "Email", "Last Login", "Last Login IP", "Created On", "Status");
            fputcsv($file, $header);

            foreach ($lists as $key => $list) {
                $data = array();
                $data['customer_code'] = 'CUST' . $list['id']; // CI project style
                $data['name'] = ucwords($list['name']);
                $data['mobile'] = $list['mobile'];
                $data['email'] = $list['email'];
                $data['last_login'] = $list['last_login'] ? date('Y-m-d H:i:s', strtotime($list['last_login'])) : '';
                $data['last_login_ip'] = $list['last_login_ip'] ?? '';
                $data['created'] = date('Y-m-d H:i:s', strtotime($list['created']));
                $data['status'] = $list['status'] == 1 ? "Active" : "Inactive";
                fputcsv($file, $data);
            }
            
            fclose($file);
            exit;
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@exportCSV: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Import users from CSV
     * CI: Users->ImportCSV()
     */
    public function importCSV(Request $request)
    {
        try {
            if (!$request->hasFile('csv')) {
                return redirect()->back()->with('message_error', 'Please select a CSV file');
            }

            $file = $request->file('csv');
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Remove header
            array_shift($data);
            
            $imported = 0;
            foreach ($data as $row) {
                if (count($row) >= 3) { // Minimum required fields
                    $userData = [
                        'name' => $row[0] ?? '',
                        'email' => $row[1] ?? '',
                        'mobile' => $row[2] ?? '',
                        'website' => $row[3] ?? '',
                        'password' => isset($row[4]) ? Hash::make($row[4]) : Hash::make('password'),
                        'status' => 1,
                        'user_type' => 1,
                    ];
                    
                    if (User::importCSV([$userData])) {
                        $imported++;
                    }
                }
            }
            
            return redirect()->back()->with('message_success', "Successfully imported {$imported} users");
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@importCSV: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Change user password (CI project style - handles both GET and POST)
     * CI: Users->changePassword() lines 153-234
     */
    public function changePassword($id, $page_status = '', $page_name = null)
    {
        try {
            $user = User::getUserById($id);
            
            if (!$user) {
                return redirect('admin/Users')->with('message_error', 'User not found');
            }

            // Handle POST request (CI project style)
            if (request()->isMethod('post')) {
                return $this->savePassword(request(), $id, $page_status, $page_name);
            }

            // Handle GET request - show form
            $data = [
                'page_title' => 'Change Password',
                'user' => $user,
                'id' => $id,
                'page_status' => $page_status,
            ];

            return view('admin.users.change_password', $data);
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@changePassword: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error loading user: ' . $e->getMessage());
        }
    }

    /**
     * Save changed password (CI project style)
     * CI: Users->changePassword() POST handling lines 161-234
     */
    public function savePassword(Request $request, $id, $page_status = '', $page_name = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:6|confirmed',
            ], [
                'password.required' => 'Enter Password',
                'password.min' => 'Password must be at least 6 characters',
                'password.confirmed' => 'Password confirmation does not match',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $password = $request->input('password');
            $hashedPassword = Hash::make($password);

            $result = DB::table('users')->where('id', $id)->update([
                'password' => $hashedPassword,
                'updated' => now(),
            ]);

            if ($result) {
                // Send email notification (CI project style)
                if (!empty($id) && !empty($password)) {
                    $user = User::getUserById($id);
                    
                    if ($user) {
                        // Get store information for email
                        $store = DB::table('stores')->where('id', $user->store_id ?? 1)->first();
                        
                        if ($store) {
                            $store_url = $store->url ?? url('/');
                            $langue_id = $store->langue_id ?? 1;
                            $login_url = $store_url . 'Logins';

                            $toEmail = $user->email;
                            $from_name = $store->name ?? 'Admin';
                            $from_email = $store->from_email ?? 'admin@example.com';

                            if ($langue_id == 2) {
                                $subject = "Réinitialiser le mot de passe par l'administrateur";
                                $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                        salut,' . $user->name . ',
                                        <br>';
                                $body .= "Votre mot de passe a été mis à jour par l'administrateur, veuillez vous connecter à votre compte<br>";
                                $body .= 'URL de connexion :	' . $login_url . '<br>
                                        Email :	' . $user->email . '<br>
                                        nouveau mot de passe:	' . $password . '<br>
                                    </span>
                                </div>';
                            } else {
                                $subject = "Reset Password By Admin";
                                $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                        Hi,' . $user->name . ',
                                        <br>
                                        Your password has been updated by admin.Please login your account<br>
                                        Login Url :	' . $login_url . '<br>
                                        Email :	' . $user->email . '<br>
                                        New Password:	' . $password . '<br>
                                    </span>
                               </div>';
                            }

                            // Send email (simplified version - CI project uses sendEmail function)
                            try {
                                \Mail::raw($body, function($message) use ($toEmail, $subject, $from_email, $from_name) {
                                    $message->to($toEmail)
                                            ->subject($subject)
                                            ->from($from_email, $from_name);
                                });
                            } catch (\Exception $e) {
                                Log::error('Email sending failed: ' . $e->getMessage());
                            }
                        }
                    }
                }

                // Redirect based on page name (CI project style)
                if ($page_name == 'preferred-customer') {
                    return redirect('admin/Users/preferredCustomer')->with('message_success', 'Change Password Successfully.');
                } else {
                    $redirect = $page_status ? 'admin/Users/index/' . $page_status : 'admin/Users';
                    return redirect($redirect)->with('message_success', 'Change Password Successfully.');
                }
            } else {
                return redirect()->back()->with('message_error', 'Change Password Unsuccessfully.');
            }

        } catch (\Exception $e) {
            Log::error('Error in UsersController@savePassword: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error changing password: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user active/inactive status
     * CI: Users->activeInactive() lines 294-355
     */
    public function activeInactive($id, $status, $page_status = null)
    {
        try {
            if (!in_array($status, [0, 1])) {
                return redirect()->back()->with('message_error', 'Invalid status');
            }

            $result = User::toggleStatus($id, $status);
            
            if ($result) {
                $message = $status == 1 ? 'User activated successfully' : 'User deactivated successfully';
                $redirect = $page_status ? 'admin/Users/index/' . $page_status : 'admin/Users';
                return redirect($redirect)->with('message_success', $message);
            } else {
                return redirect()->back()->with('message_error', 'Status update failed');
            }

        } catch (\Exception $e) {
            Log::error('Error in UsersController@activeInactive: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Delete user
     * CI: Users->deleteUser() lines 525-543
     */
    public function deleteUser($id, $page_status = null)
    {
        try {
            if (empty($id)) {
                return redirect()->back()->with('message_error', 'Invalid user ID');
            }

            $result = User::deleteUser($id);
            
            if ($result) {
                $redirect = $page_status ? 'admin/Users/index/' . $page_status : 'admin/Users';
                return redirect($redirect)->with('message_success', 'User deleted successfully');
            } else {
                return redirect()->back()->with('message_error', 'User deletion failed');
            }

        } catch (\Exception $e) {
            Log::error('Error in UsersController@deleteUser: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    /**
     * Preferred customer listing
     * CI: Users->preferredCustomer() lines 115-138
     */
    public function preferredCustomer($status = null)
    {
        try {
            $title = 'Preferred Customer';
            $page_status = !empty($status) ? $status : '';

            // Get store list (CI equivalent)
            $stores = DB::table('stores')->where('status', 1)->get();

            // Get preferred customer list
            $users = User::getPreferredCustomerUserList($status);

            $data = [
                'page_title' => $title,
                'page_status' => $page_status,
                'users' => $users,
                'stores' => $stores,
                'BASE_URL' => url('/') . '/', // Same as CI
            ];

            return view('admin.users.preferred_customer', $data);
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@preferredCustomer: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error loading preferred customers: ' . $e->getMessage());
        }
    }

    /**
     * Toggle preferred customer status
     * CI: Users->activeInactiveUserType() lines 418-523
     */
    public function activeInactiveUserType($id, $status, $page_status = null)
    {
        try {
            if (!in_array($status, [0, 1])) {
                return redirect()->back()->with('message_error', 'Invalid status');
            }

            $result = DB::table('users')->where('id', $id)->update([
                'preferred_status' => $status,
                'updated' => now(),
            ]);
            
            if ($result) {
                $message = $status == 1 
                    ? 'User Verified Successfully' 
                    : 'User Unverified Successfully';
                
                $redirect = $page_status ? 'admin/Users/preferredCustomer/' . $page_status : 'admin/Users/preferredCustomer';
                
                return redirect($redirect)->with('message_success', $message);
            } else {
                return redirect()->back()->with('message_error', 'Status update failed');
            }

        } catch (\Exception $e) {
            Log::error('Error in UsersController@activeInactiveUserType: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error updating preferred status: ' . $e->getMessage());
        }
    }

    /**
     * Toggle preferred customer active/inactive status
     * CI: Users->activeInactivePreferredCustomer() lines 357-416
     */
    public function activeInactivePreferredCustomer($id, $status, $page_status = null)
    {
        try {
            if (!in_array($status, [0, 1])) {
                return redirect()->back()->with('message_error', 'Invalid status');
            }

            $result = DB::table('users')->where('id', $id)->update([
                'status' => $status,
                'updated' => now(),
            ]);
            
            if ($result) {
                $message = $status == 1 
                    ? 'User Active Successfully' 
                    : 'User Inactive Successfully';
                
                $redirect = $page_status ? 'admin/Users/preferredCustomer/' . $page_status : 'admin/Users/preferredCustomer';
                
                return redirect($redirect)->with('message_success', $message);
            } else {
                return redirect()->back()->with('message_error', 'Status update failed');
            }

        } catch (\Exception $e) {
            Log::error('Error in UsersController@activeInactivePreferredCustomer: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error updating user status: ' . $e->getMessage());
        }
    }

    /**
     * Subscribe email listing
     * CI: Users->subscribeEmail() lines 557-572
     */
    public function subscribeEmail()
    {
        try {
            $title = 'Subscribe Email';
            
            // Get subscribe emails (CI equivalent)
            $emails = DB::table('subscribe_emails')
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
            
            // Get store list (CI equivalent)
            $stores = DB::table('stores')->where('status', 1)->get();

            $data = [
                'page_title' => $title,
                'emails' => $emails,
                'stores' => $stores,
                'BASE_URL' => url('/') . '/', // Same as CI
            ];

            return view('admin.users.subscribe_email', $data);
            
        } catch (\Exception $e) {
            Log::error('Error in UsersController@subscribeEmail: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error loading subscribe emails: ' . $e->getMessage());
        }
    }

    /**
     * Delete subscribe email
     * CI: Users->deleteSubscribeEmail() lines 573-588
     */
    public function deleteSubscribeEmail($id)
    {
        try {
            if (empty($id)) {
                return redirect()->back()->with('message_error', 'Missing information.');
            }

            $result = DB::table('subscribe_emails')->where('id', $id)->delete();
            
            if ($result) {
                return redirect('admin/Users/subscribeEmail')->with('message_success', 'Subscribe Email Delete Successfully.');
            } else {
                return redirect()->back()->with('message_error', 'Subscribe Email Delete Unsuccessfully.');
            }

        } catch (\Exception $e) {
            Log::error('Error in UsersController@deleteSubscribeEmail: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error deleting subscribe email: ' . $e->getMessage());
        }
    }
}
