<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * MyAccountsController
 * CI: application/controllers/MyAccounts.php (403 lines)
 */
class MyAccountsController extends Controller
{
    /**
     * Check if user is logged in
     * CI: lines 6-15
     */
    protected function checkLogin()
    {
        if (!session('loginId')) {
            return redirect('/');
        }
        return null;
    }
    
    /**
     * Display account information
     * CI: lines 17-33
     */
    public function index()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $postData = DB::table('users')->where('id', $loginId)->first();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Mon compte' : 'My Account',
            'postData' => $postData,
        ];
        
        return view('my_accounts.index', $data);
    }
    
    /**
     * Edit account information
     * CI: lines 35-89
     */
    public function EditAccount(Request $request)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $postData = DB::table('users')->where('id', $loginId)->first();
        $postData = (array) $postData;
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'fname' => 'required|max:50',
                'lname' => 'required|max:50',
                'mobile' => 'nullable|max:20',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('message_error', 'Missing information.');
            }
            
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $mobile = $request->input('mobile');
            $name = empty($lname) ? $fname : $fname . ' ' . $lname;
            
            DB::table('users')->where('id', $loginId)->update([
                'fname' => $fname,
                'lname' => $lname,
                'mobile' => $mobile,
                'name' => $name,
                'updated' => date('Y-m-d H:i:s'),
            ]);
            
            // Update session
            $LoginUser = DB::table('users')->where('id', $loginId)->first();
            session([
                'loginId' => $LoginUser->id,
                'loginName' => $LoginUser->name,
                'loginPic' => $LoginUser->profile_pic ?? '',
            ]);
            
            return redirect('MyAccounts')->with('message_success', 'My account information updated successfully');
        }
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Modifier mon compte' : 'Edit My Account',
            'postData' => $postData,
        ];
        
        return view('my_accounts.edit_account', $data);
    }
    
    /**
     * Display change password page
     * CI: lines 90-98
     */
    public function changePassword()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Changer le mot de passe' : 'Change Password',
        ];
        
        return view('my_accounts.change_password', $data);
    }
    
    /**
     * Save new password
     * CI: lines 99-130
     */
    public function saveChangePassword(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $forgot_mobile = $request->input('account_email');
        $forgot_password = $request->input('new_password');
        
        $json = ['status' => 0, 'msg' => ''];
        
        $user = DB::table('users')->where('email', $forgot_mobile)->first();
        
        if ($user) {
            DB::table('users')->where('email', $forgot_mobile)->update([
                'password' => md5($forgot_password),
                'updated' => date('Y-m-d H:i:s'),
            ]);
            
            $json['status'] = 1;
            $json['msg'] = $language_name == 'french' 
                ? 'Votre mot de passe a été mis à jour avec succès.'
                : 'Your password has been updated successfully.';
        } else {
            $json['msg'] = $language_name == 'french'
                ? "L'identifiant de messagerie n'existe pas."
                : 'Email id does not exist';
        }
        
        return response()->json($json);
    }
    
    /**
     * Manage addresses
     * CI: lines 132-144
     */
    public function manageAddress()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        // Get address list with joins like CI: getAddressListByUserId
        $address = DB::table('addresses as Address')
            ->select([
                'Address.*', 
                'State.name as StateName', 
                'city.name as cityName', 
                'Country.iso2 as CountryName'
            ])
            ->leftJoin('states as State', 'State.id', '=', 'Address.state')
            ->leftJoin('cities as city', 'city.id', '=', 'Address.city')
            ->leftJoin('countries as Country', 'Country.id', '=', 'Address.country')
            ->where('Address.user_id', $loginId)
            ->orderBy('Address.default_delivery_address', 'desc')
            ->get();
        
        // Get countries like CI: getCountries
        $countries = DB::table('countries')
            ->select('*')
            ->orderBy('name', 'asc')
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? "Gérer l'adresse" : 'Manage Address',
            'address' => $address,
            'countries' => $countries,
        ];
        
        return view('my_accounts.manage_address', $data);
    }
    
    /**
     * Add or edit address
     * CI: lines 146-231
     */
    public function addEditAddress(Request $request, $id = null)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        
        $postData = [];
        if (!empty($id)) {
            $postData = DB::table('addresses')->where('id', $id)->first();
            $postData = (array) $postData;
            $page_title = 'Address updated successfully';
            $data_page_title = $language_name == 'french' ? "Modifier l'adresse" : 'Edit Address';
        } else {
            $page_title = 'New address added successfully';
            $data_page_title = $language_name == 'french' ? 'Ajouter une nouvelle adresse' : 'Add New Address';
        }
        
        $countries = DB::table('countries')->orderBy('name', 'asc')->get();
        $country_id = $postData['country'] ?? '';
        $state_id = $postData['state'] ?? '';
        $states = !empty($country_id) ? DB::table('states')->where('country_id', $country_id)->orderBy('name', 'asc')->get() : [];
        $citys = !empty($state_id) ? DB::table('cities')->where('state_id', $state_id)->orderBy('name', 'asc')->get() : [];
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|max:100',
                'last_name' => 'required|max:100',
                'mobile' => 'required|max:20',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required',
                'pin_code' => 'required|max:20',
            ]);
            
            $postData = [
                'id' => $request->input('id'),
                'user_id' => $loginId,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'company_name' => $request->input('company_name'),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'pin_code' => $request->input('pin_code'),
                'mobile' => $request->input('mobile'),
                'address' => $request->input('address'),
                'country' => $request->input('country'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'landmark' => $request->input('landmark'),
                'alternate_phone' => $request->input('alternate_phone'),
                'address_type' => $request->input('address_type'),
                'default_delivery_address' => $request->input('default_delivery_address', 0),
            ];
            
            if ($request->ajax()) {
                return $this->addAddressByAjax($request, $postData, $validator);
            }
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('message_error', 'Missing information.');
            }
            
            if (!empty($postData['id'])) {
                DB::table('addresses')->where('id', $postData['id'])->update($postData);
                $insert_id = $postData['id'];
            } else {
                unset($postData['id']);
                $postData['created'] = date('Y-m-d H:i:s');
                $insert_id = DB::table('addresses')->insertGetId($postData);
            }
            
            // Check default delivery address
            if (!empty($postData['default_delivery_address'])) {
                DB::table('addresses')
                    ->where('user_id', $loginId)
                    ->where('id', '!=', $insert_id)
                    ->update(['default_delivery_address' => 0]);
            }
            
            return redirect('MyAccounts/manageAddress')->with('message_success', $page_title);
        }
        
        $data = [
            'page_title' => $data_page_title,
            'postData' => $postData,
            'countries' => $countries,
            'states' => $states,
            'citys' => $citys,
        ];
        
        return view('my_accounts.add_edit_address', $data);
    }
    
    /**
     * Delete address
     * CI: lines 233-257
     */
    public function deleteAddress($id = null)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        if (!empty($id)) {
            $page_title = 'Address delete';
            if (config('store.language_name', 'english') == 'French') {
                $page_title = "Suppression d'adresse";
            }
            
            $id = base64_decode($id);
            $postData = DB::table('addresses')->where('id', $id)->first();

            if (empty($postData->default_delivery_address)) {
                $deleted = DB::table('addresses')->where('id', $id)->delete();
                if ($deleted) {
                    return redirect('MyAccounts/manageAddress')->with('message_success', $page_title . ' Successfully.');
                } else {
                    return redirect('MyAccounts/manageAddress')->with('message_error', $page_title . ' Unsuccessfully.');
                }
            } else {
                return redirect('MyAccounts/manageAddress')
                    ->with('message_error', 'this address is default delivery address so this address you can not deleted');
            }
        } else {
            return redirect('MyAccounts/manageAddress')->with('message_error', 'Missing information.');
        }
    }
    
    /**
     * Notification page
     * CI: lines 259-266
     */
    public function notification()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => 'Notification',
        ];
        
        return view('my_accounts.notification', $data);
    }
    
    /**
     * Logout
     * CI: lines 268-272
     */
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
    
    /**
     * Send OTP for password reset
     * CI: lines 274-332
     */
    public function sendOtp(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $email = $request->input('account_email');
        
        $json = ['status' => 0, 'msg' => ''];
        
        $user = DB::table('users')->where('email', $email)->first();
        
        if ($user) {
            $otp = rand(100000, 999999);
            
            $main_store_data = config('store.main_store_data');
            $from_name = $main_store_data['name'] ?? 'Store';
            $from_email = $main_store_data['from_email'] ?? 'noreply@store.com';
            
            if ($language_name == 'french') {
                $massage = $otp . " est le code de réinitialisation du mot de passe.code est confidentiel, veuillez ne pas partager ce code avec qui que ce soit pour assurer la sécurité des comptes";
                $subject = 'réinitialiser le mot de passe';
            } else {
                $massage = $otp . ' is reset password code.code is confidential, Please do not share this code with anyone to ensure accounts security';
                $subject = 'Reset Password';
            }
            
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $massage . '</span></div>';
            
            // TODO: Implement email sending
            // sendEmail($email, $subject, $body, $from_email, $from_name);
            
            $json['status'] = 1;
            $json['msg'] = $language_name == 'french'
                ? 'Veuillez vérifier que votre code de mot de passe de réinitialisation de messagerie a été envoyé à votre identifiant de messagerie:' . $email
                : 'Please check your mail reset password code has been sent to your email id: ' . $email;
            $json['otp'] = $otp;
        } else {
            $json['msg'] = $language_name == 'french'
                ? "L'identifiant de messagerie n'existe pas"
                : 'Email id does not exist';
        }
        
        return response()->json($json);
    }
    
    /**
     * Add address via AJAX
     * CI: lines 334-370
     */
    public function addAddressByAjax(Request $request, $postData, $validator)
    {
        $loginId = session('loginId');
        
        $response = [
            'status' => 'error',
            'msg' => '',
            'errors' => [],
            'data' => '',
        ];
        
        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->toArray();
        } else {
            if (!empty($postData['id'])) {
                DB::table('addresses')->where('id', $postData['id'])->update($postData);
                $insert_id = $postData['id'];
                $response['msg'] = 'Address Updated Successfully';
                $response['updated'] = 1;
            } else {
                unset($postData['id']);
                $postData['created'] = date('Y-m-d H:i:s');
                $insert_id = DB::table('addresses')->insertGetId($postData);
                $response['msg'] = 'New Address Added Successfully';
                $response['updated'] = 0;
            }
            
            // Check default delivery address
            if (!empty($postData['default_delivery_address'])) {
                DB::table('addresses')
                    ->where('user_id', $loginId)
                    ->where('id', '!=', $insert_id)
                    ->update(['default_delivery_address' => 0]);
            }
            
            $address = DB::table('addresses')->where('id', $insert_id)->first();
            $response['status'] = 'success';
            $response['data'] = view('elements.addresses-list', ['address' => $address])->render();
        }
        
        echo json_encode($response);
        exit();
    }
    
    /**
     * Get states dropdown via AJAX
     * CI: lines 372-386
     */
    public function getStateDropDownListByAjax($country_id)
    {
        $options = '<option value="">--Select State--</option>';
        
        if (!empty($country_id)) {
            $stateList = DB::table('states')
                ->where('country_id', $country_id)
                ->orderBy('name', 'asc')
                ->get();
            
            foreach ($stateList as $val) {
                $options .= '<option value="' . $val->id . '">' . $val->name . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * Get cities dropdown via AJAX
     * CI: lines 387-401
     */
    public function getCityDropDownListByAjax($state_id)
    {
        $options = '<option value="">--Select City--</option>';
        
        if (!empty($state_id)) {
            $cityList = DB::table('cities')
                ->where('state_id', $state_id)
                ->orderBy('name', 'asc')
                ->get();
            
            foreach ($cityList as $val) {
                $options .= '<option value="' . $val->id . '">' . $val->name . '</option>';
            }
        }
        
        echo $options;
        exit();
    }
    
    /**
     * View order history
     */
    public function orderHistory()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $orders = DB::table('product_orders')
            ->where('user_id', $loginId)
            ->orderBy('id', 'desc')
            ->paginate(20);
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Historique des commandes' : 'Order History',
            'orders' => $orders,
        ];
        
        return view('my_accounts.order_history', $data);
    }
    
    /**
     * View order details
     */
    public function viewOrder($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $order = DB::table('product_orders')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->first();
        
        if (!$order) {
            return redirect('MyAccounts/orderHistory')->with('message_error', 'Order not found');
        }
        
        $orderItems = DB::table('product_order_items')
            ->where('order_id', $id)
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Détails de la commande' : 'Order Details',
            'order' => $order,
            'orderItems' => $orderItems,
        ];
        
        return view('my_accounts.view_order', $data);
    }
    
    /**
     * View wishlist
     */
    public function wishlist()
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $wishlist = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->where('wishlists.user_id', $loginId)
            ->select('wishlists.*', 'products.name', 'products.product_image', 'products.price')
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Liste de souhaits' : 'My Wishlist',
            'wishlist' => $wishlist,
        ];
        
        return view('my_accounts.wishlist', $data);
    }
    
    /**
     * Remove item from wishlist
     */
    public function removeWishlist($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $loginId = session('loginId');
        
        DB::table('wishlists')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->delete();
        
        return redirect('MyAccounts/wishlist')->with('message_success', 'Item removed from wishlist');
    }
}
