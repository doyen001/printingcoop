<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Admin DiscountsController - Complete discount code management
 * CI: application/controllers/admin/Discounts.php (117 lines)
 */
class DiscountsController extends Controller
{
    /**
     * Display discounts listing
     * CI: lines 14-26
     */
    public function index($type = 'current')
    {
        $discounts = DB::table('discounts')
            ->orderBy('created', 'desc')
            ->paginate(20);
        
        $data = [
            'page_title' => 'Discount Codes',
            'discounts' => $discounts,
            'type' => $type,
        ];
        
        return view('admin.discounts.index', $data);
    }
    
    /**
     * Add/Edit discount code
     * CI: lines 28-77
     */
    public function addEdit(Request $request, $id = null)
    {
        $discount = null;
        
        if ($id) {
            $discount = DB::table('discounts')->where('id', $id)->first();
            if (!$discount) {
                return redirect('admin/Discounts')->with('message_error', 'Discount not found');
            }
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'code' => 'required|max:50|unique:discounts,code' . ($id ? ',' . $id : ''),
                'discount_type' => 'required|in:discount_percent,discount_amount',
                'discount' => 'required|numeric|min:0',
                'discount_valid_from' => 'required|date',
                'discount_valid_to' => 'required|date|after:discount_valid_from',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()
                    ->with('message_error', 'Missing information.');
            }
            
            $data = [
                'code' => strtoupper($request->code),
                'discount_type' => $request->discount_type,
                'discount' => $request->discount,
                'discount_valid_from' => $request->discount_valid_from,
                'discount_valid_to' => $request->discount_valid_to,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($id) {
                DB::table('discounts')->where('id', $id)->update($data);
                $message = 'Discount code updated successfully';
            } else {
                $data['created'] = now();
                DB::table('discounts')->insert($data);
                $message = 'Discount code created successfully';
            }
            
            return redirect('admin/Discounts')->with('message_success', $message);
        }
        
        $data = [
            'page_title' => $id ? 'Edit Discount Code' : 'Add New Discount Code',
            'discount' => $discount,
        ];
        
        return view('admin.discounts.add_edit', $data);
    }
    
    /**
     * Toggle discount active/inactive status
     * CI: lines 79-98
     */
    public function activeInactive($id, $status)
    {
        if (!in_array($status, [0, 1])) {
            return redirect()->back()->with('message_error', 'Invalid status');
        }
        
        DB::table('discounts')->where('id', $id)->update([
            'status' => $status,
            'updated' => now(),
        ]);
        
        $message = $status == 1 
            ? 'Discount code activated successfully' 
            : 'Discount code deactivated successfully';
        
        return redirect()->back()->with('message_success', $message);
    }
    
    /**
     * Delete discount code
     * CI: lines 100-115
     */
    public function deleteDiscount($id)
    {
        if (empty($id)) {
            return redirect()->back()->with('message_error', 'Missing information');
        }
        
        DB::table('discounts')->where('id', $id)->delete();
        
        return redirect('admin/Discounts')->with('message_success', 'Discount code deleted successfully');
    }
    
    /**
     * Validate discount code (for cart application)
     */
    public function validateDiscount(Request $request)
    {
        $code = strtoupper($request->input('code'));
        $cartTotal = $request->input('cart_total', 0);
        
        $json = ['status' => 0, 'msg' => '', 'discount_amount' => 0];
        
        if (empty($code)) {
            $json['msg'] = 'Please enter a discount code';
            return response()->json($json);
        }
        
        $discount = DB::table('discounts')
            ->where('code', $code)
            ->where('status', 1)
            ->first();
        
        if (!$discount) {
            $json['msg'] = 'Invalid discount code';
            return response()->json($json);
        }
        
        // Check if discount is expired
        $currentDate = now()->format('Y-m-d');
        if ($currentDate < $discount->discount_valid_from || $currentDate > $discount->discount_valid_to) {
            $json['msg'] = 'This discount code has expired';
            return response()->json($json);
        }
        
        // Check usage limit
        if ($discount->discount_code_limit > 0 && $discount->usage_count >= $discount->discount_code_limit) {
            $json['msg'] = 'This discount code has reached its usage limit';
            return response()->json($json);
        }
        
        // Calculate discount amount
        if ($discount->discount_type == 'percentage') {
            $discountAmount = ($cartTotal * $discount->discount) / 100;
        } else {
            $discountAmount = $discount->discount;
        }
        
        // Ensure discount doesn't exceed cart total
        if ($discountAmount > $cartTotal) {
            $discountAmount = $cartTotal;
        }
        
        $json['status'] = 1;
        $json['msg'] = 'Discount code applied successfully';
        $json['discount_amount'] = round($discountAmount, 2);
        $json['discount_code'] = $code;
        $json['discount_type'] = $discount->discount_type;
        $json['discount_value'] = $discount->discount;
        
        return response()->json($json);
    }
    
    /**
     * Apply discount to cart
     */
    public function applyDiscount(Request $request)
    {
        $code = strtoupper($request->input('code'));
        $userId = session('loginId');
        
        if (!$userId) {
            return response()->json(['status' => 0, 'msg' => 'Please login first']);
        }
        
        // Validate discount
        $validation = $this->validateDiscount($request);
        $validationData = json_decode($validation->getContent(), true);
        
        if ($validationData['status'] == 0) {
            return response()->json($validationData);
        }
        
        // Store discount in session
        session([
            'discount_code' => $code,
            'discount_amount' => $validationData['discount_amount'],
            'discount_type' => $validationData['discount_type'],
            'discount_value' => $validationData['discount_value'],
        ]);
        
        return response()->json($validationData);
    }
    
    /**
     * Remove discount from cart
     */
    public function removeDiscount()
    {
        session()->forget(['discount_code', 'discount_amount', 'discount_type', 'discount_value']);
        
        return response()->json([
            'status' => 1,
            'msg' => 'Discount code removed successfully',
        ]);
    }
    
    /**
     * Increment discount usage count (called after order placement)
     */
    public static function incrementUsage($code)
    {
        DB::table('discounts')
            ->where('code', strtoupper($code))
            ->increment('usage_count');
    }
}
