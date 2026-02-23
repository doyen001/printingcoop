<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    public function index()
    {
        $pages = DB::table('pages')->orderBy('id', 'desc')->get();
        
        return view('admin.pages.index', [
            'page_title' => 'Pages',
            'pages' => $pages,
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $page = $id ? DB::table('pages')->where('id', $id)->first() : null;
        
        // Get main store list like CI project
        $mainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
        
        // Prepare postData like CI project
        $postData = [];
        if ($page) {
            $postData = (array) $page;
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'main_store_id' => 'required',
                'title' => 'required|max:255',
                'title_french' => 'required|max:255',
                'shortOrder' => 'integer',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                // Set postData from request like CI project
                $postData = $request->all();
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('postData', $postData);
            }
            
            $data = [
                'main_store_id' => $request->main_store_id,
                'title' => $request->title,
                'title_french' => $request->title_french,
                'category_id' => $request->category_id ?? null,
                'description' => $request->description,
                'description_french' => $request->description_french,
                'shortOrder' => $request->shortOrder ?? 0,
                'page_title' => $request->page_title,
                'page_title_french' => $request->page_title_french,
                'meta_description_content' => $request->meta_description_content,
                'meta_description_content_french' => $request->meta_description_content_french,
                'meta_keywords_content' => $request->meta_keywords_content,
                'meta_keywords_content_french' => $request->meta_keywords_content_french,
                'display_on_footer' => $request->display_on_footer ?? 0,
                'display_on_top_menu' => $request->display_on_top_menu ?? 0,
                'display_on_footer_last_menu' => $request->display_on_footer_last_menu ?? 0,
                'updated' => now(),
            ];
            
            if ($id) {
                $data['id'] = $id;
            } else {
                // Generate slug for new pages like CI project
                $slug = $this->getSlug($request->title, $request->main_store_id);
                $data['slug'] = $slug;
                $data['created'] = now();
            }
            
            if ($id) {
                DB::table('pages')->where('id', $id)->update($data);
                $message = 'Page updated successfully';
            } else {
                DB::table('pages')->insert($data);
                $message = 'Page created successfully';
            }
            
            return redirect('admin/Pages')->with('message_success', $message);
        }
        
        return view('admin.pages.add_edit', [
            'page_title' => $id ? 'Edit Page' : 'Add Page',
            'page' => $page,
            'mainStoreList' => $mainStoreList,
            'postData' => $postData, // ← Add postData like CI project
        ]);
    }
    
    public function delete($id)
    {
        DB::table('pages')->where('id', $id)->delete();
        
        return redirect('admin/Pages')->with('message_success', 'Page deleted successfully');
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('pages')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Page activated successfully' : 'Page deactivated successfully';
        
        return redirect('admin/Pages')->with('message_success', $message);
    }
    
    /**
     * Generate slug for pages
     * Based on CI project's getSlug method
     */
    private function getSlug($title, $main_store_id)
    {
        $slug = strtolower(str_replace(' ', '-', $title));
        
        // Check if slug exists for this store
        $existing = DB::table('pages')
            ->where('slug', $slug)
            ->where('main_store_id', $main_store_id)
            ->first();
        
        if ($existing) {
            $slug = $slug . '-1';
        }
        
        return $slug;
    }
}
