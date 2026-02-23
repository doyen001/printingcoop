<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SectionsController extends Controller
{
    public function index()
    {
        $sections = DB::table('sections')->orderBy('id', 'desc')->get();
        
        // Convert to arrays like CI project
        $sections = $sections->map(function($section) {
            return (array) $section;
        })->toArray();
        
        // Get main store list like CI project
        $mainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
        
        return view('admin.sections.index', [
            'page_title' => 'Sections',
            'sections' => $sections,
            'mainStoreList' => $mainStoreList, // ← Add mainStoreList like CI project
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $section = $id ? DB::table('sections')->where('id', $id)->first() : null;
        
        // Get main store list like CI project
        $mainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
        
        // Prepare postData like CI project
        $postData = [];
        if ($section) {
            $postData = (array) $section;
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
                'description' => 'required',
                'description_french' => 'required',
                'content' => 'required',
                'content_french' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                // Set postData from request like CI project
                $postData = $request->except(['background_image', 'french_background_image']);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('postData', $postData);
            }
            
            // Prepare data like CI project
            $data = [
                'name' => $request->name,
                'name_french' => $request->name_french,
                'description' => $request->description,
                'description_french' => $request->description_french,
                'content' => $request->content,
                'content_french' => $request->content_french,
                'updated' => now(),
            ];
            
            // Handle file uploads like CI project
            if ($request->hasFile('background_image')) {
                $image = $request->file('background_image');
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Create upload directory if it doesn't exist
                $uploadPath = public_path('uploads/sections');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move file
                $image->move($uploadPath, $filename);
                $data['background_image'] = $filename;
            }
            
            if ($request->hasFile('french_background_image')) {
                $image = $request->file('french_background_image');
                $filename = time() . '_french_' . $image->getClientOriginalName();
                
                // Move file
                $image->move(public_path('uploads/sections'), $filename);
                $data['french_background_image'] = $filename;
            }
            
            if ($id) {
                $data['updated'] = now();
                DB::table('sections')->where('id', $id)->update($data);
                $message = 'Section updated successfully';
            } else {
                $data['created'] = now();
                $data['updated'] = now();
                DB::table('sections')->insert($data);
                $message = 'Section created successfully';
            }
            
            return redirect('admin/Sections')->with('message_success', $message);
        }
        
        return view('admin.sections.add_edit', [
            'page_title' => $id ? 'Edit Section' : 'Add Section',
            'section' => $section,
            'mainStoreList' => $mainStoreList,
            'postData' => $postData, // ← Add postData like CI project
        ]);
    }
    
    public function delete($id)
    {
        $section = DB::table('sections')->where('id', $id)->first();
        
        if ($section && $section->image) {
            $imagePath = public_path('uploads/sections/' . $section->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        DB::table('sections')->where('id', $id)->delete();
        
        return redirect('admin/Sections')->with('message_success', 'Section deleted successfully');
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('sections')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Section activated successfully' : 'Section deactivated successfully';
        
        return redirect('admin/Sections')->with('message_success', $message);
    }
}
