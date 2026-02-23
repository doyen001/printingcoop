<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttributesController extends Controller
{
    // Multiple Attributes Management
    
    public function multipleAttributes()
    {
        $attributes = DB::table('attributes')
            ->where('attribute_type', 'multiple')
            ->orderBy('show_order')
            ->paginate(20);
        
        return view('admin.attributes.multiple_attributes', [
            'page_title' => 'Multiple Attributes',
            'attributes' => $attributes,
        ]);
    }
    
    public function addEditMultipleAttribute(Request $request, $id = null)
    {
        $attribute = $id ? DB::table('attributes')->where('id', $id)->first() : null;
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $data = [
                'name' => $request->name,
                'name_french' => $request->name_french,
                'attribute_type' => 'multiple',
                'show_order' => $request->show_order ?? 0,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($id) {
                DB::table('attributes')->where('id', $id)->update($data);
                $message = 'Multiple attribute updated successfully';
            } else {
                $data['created'] = now();
                DB::table('attributes')->insert($data);
                $message = 'Multiple attribute created successfully';
            }
            
            return redirect('admin/Attributes/multipleAttributes')->with('message_success', $message);
        }
        
        return view('admin.attributes.add_edit_multiple_attribute', [
            'page_title' => $id ? 'Edit Multiple Attribute' : 'Add Multiple Attribute',
            'attribute' => $attribute,
        ]);
    }
    
    public function deleteMultipleAttribute($id)
    {
        DB::table('attributes')->where('id', $id)->delete();
        DB::table('attribute_items')->where('attribute_id', $id)->delete();
        
        return redirect('admin/Attributes/multipleAttributes')->with('message_success', 'Multiple attribute deleted successfully');
    }
    
    public function activeInactiveMultipleAttribute($id, $status)
    {
        DB::table('attributes')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Multiple attribute activated successfully' : 'Multiple attribute deactivated successfully';
        
        return redirect('admin/Attributes/multipleAttributes')->with('message_success', $message);
    }
    
    // Attribute Items Management
    
    public function attributeItems($attribute_id)
    {
        $attribute = DB::table('attributes')->where('id', $attribute_id)->first();
        
        if (!$attribute) {
            return redirect('admin/Attributes/multipleAttributes')->with('message_error', 'Attribute not found');
        }
        
        $items = DB::table('attribute_items')
            ->where('attribute_id', $attribute_id)
            ->orderBy('show_order')
            ->paginate(20);
        
        return view('admin.attributes.attribute_items', [
            'page_title' => 'Attribute Items - ' . $attribute->name,
            'attribute' => $attribute,
            'items' => $items,
        ]);
    }
    
    public function addEditAttributeItem(Request $request, $attribute_id, $id = null)
    {
        $attribute = DB::table('attributes')->where('id', $attribute_id)->first();
        
        if (!$attribute) {
            return redirect('admin/Attributes/multipleAttributes')->with('message_error', 'Attribute not found');
        }
        
        $item = $id ? DB::table('attribute_items')->where('id', $id)->first() : null;
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $data = [
                'attribute_id' => $attribute_id,
                'name' => $request->name,
                'name_french' => $request->name_french,
                'show_order' => $request->show_order ?? 0,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($id) {
                DB::table('attribute_items')->where('id', $id)->update($data);
                $message = 'Attribute item updated successfully';
            } else {
                $data['created'] = now();
                DB::table('attribute_items')->insert($data);
                $message = 'Attribute item created successfully';
            }
            
            return redirect('admin/Attributes/attributeItems/' . $attribute_id)->with('message_success', $message);
        }
        
        return view('admin.attributes.add_edit_attribute_item', [
            'page_title' => $id ? 'Edit Attribute Item' : 'Add Attribute Item',
            'attribute' => $attribute,
            'item' => $item,
        ]);
    }
    
    public function deleteAttributeItem($attribute_id, $id)
    {
        DB::table('attribute_items')->where('id', $id)->delete();
        
        return redirect('admin/Attributes/attributeItems/' . $attribute_id)->with('message_success', 'Attribute item deleted successfully');
    }
    
    // Single Attributes Management
    
    public function singleAttributes()
    {
        $attributes = DB::table('attributes')
            ->where('attribute_type', 'single')
            ->orderBy('show_order')
            ->paginate(20);
        
        return view('admin.attributes.single_attributes', [
            'page_title' => 'Single Attributes',
            'attributes' => $attributes,
        ]);
    }
    
    public function addEditSingleAttribute(Request $request, $id = null)
    {
        $attribute = $id ? DB::table('attributes')->where('id', $id)->first() : null;
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $data = [
                'name' => $request->name,
                'name_french' => $request->name_french,
                'attribute_type' => 'single',
                'show_order' => $request->show_order ?? 0,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($id) {
                DB::table('attributes')->where('id', $id)->update($data);
                $message = 'Single attribute updated successfully';
            } else {
                $data['created'] = now();
                DB::table('attributes')->insert($data);
                $message = 'Single attribute created successfully';
            }
            
            return redirect('admin/Attributes/singleAttributes')->with('message_success', $message);
        }
        
        return view('admin.attributes.add_edit_single_attribute', [
            'page_title' => $id ? 'Edit Single Attribute' : 'Add Single Attribute',
            'attribute' => $attribute,
        ]);
    }
    
    public function deleteSingleAttribute($id)
    {
        DB::table('attributes')->where('id', $id)->delete();
        DB::table('attribute_items')->where('attribute_id', $id)->delete();
        
        return redirect('admin/Attributes/singleAttributes')->with('message_success', 'Single attribute deleted successfully');
    }
    
    public function activeInactiveSingleAttribute($id, $status)
    {
        DB::table('attributes')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Single attribute activated successfully' : 'Single attribute deactivated successfully';
        
        return redirect('admin/Attributes/singleAttributes')->with('message_success', $message);
    }
}
