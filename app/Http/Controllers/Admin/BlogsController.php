<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogsController extends Controller
{
    public function index()
    {
        $blogs = DB::table('blogs')->orderBy('id', 'desc')->get();
        
        return view('admin.blogs.index', [
            'page_title' => 'Blogs',
            'blogs' => $blogs,
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $blog = $id ? DB::table('blogs')->where('id', $id)->first() : null;
        
        // Get category list like CI project
        $categoryData = DB::table('blog_category')
            ->where('status', 1)
            ->orderBy('category_name', 'asc')
            ->get()
            ->map(function($category) {
                return (array) $category;
            })
            ->toArray();
        
        // Get store list like CI project
        $storeList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($store) {
                return (array) $store;
            })
            ->toArray();
        
        // Prepare postData like CI project
        $postData = [];
        if ($blog) {
            $postData = (array) $blog;
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'title' => 'required|max:255',
                'title_french' => 'required|max:255',
                'content' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                // Set postData from request like CI project
                $postData = $request->except(['store_id']);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('postData', $postData);
            }
            
            $data = [
                'title' => $request->title,
                'title_french' => $request->title_french,
                'blog_slug' => Str::slug($request->title),
                'content' => $request->content,
                'content_french' => $request->content_french,
                'populer' => $request->populer ?? 0,
                'category_id' => $request->category_id,
                'page_title' => $request->page_title,
                'page_title_french' => $request->page_title_french,
                'meta_description_content' => $request->meta_description_content,
                'meta_description_content_french' => $request->meta_description_content_french,
                'meta_keywords_content' => $request->meta_keywords_content,
                'meta_keywords_content_french' => $request->meta_keywords_content_french,
                'store_id' => is_array($request->store_id) ? implode(',', $request->store_id) : ($request->store_id ?? '1'),
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            // Handle file upload like CI project
            $Filename = $request->file('files') ? $request->file('files')->getClientOriginalName() : '';
            $uploadData = array();

            if (!empty($Filename)) {
                $image = $request->file('files');
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Create upload directories if they don't exist
                $uploadPath = public_path('uploads/blogs');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Move uploaded file
                if ($image->move($uploadPath, $filename)) {
                    $data['image'] = $filename;
                    
                    // Create resized versions like CI project
                    $this->resizeImage($filename, 'small', '', '', 'blogs');
                    $this->resizeImage($filename, 'medium', '', '', 'blogs');
                    $this->resizeImage($filename, 'large', 2000, 1333, 'blogs');
                } else {
                    return redirect()->back()
                        ->with('file_message_error', 'File upload failed')
                        ->withInput();
                }
            } else {
                if (empty($id)) {
                    return redirect()->back()
                        ->with('file_message_error', 'Select images of banner')
                        ->withInput();
                }
            }
            
            if ($id) {
                DB::table('blogs')->where('id', $id)->update($data);
                $message = 'Blog updated successfully';
            } else {
                $data['created'] = now();
                DB::table('blogs')->insert($data);
                $message = 'Blog created successfully';
            }
            
            return redirect('admin/Blogs')->with('message_success', $message);
        }
        
        return view('admin.blogs.add_edit', [
            'page_title' => $id ? 'Edit Blog' : 'Add Blog',
            'blog' => $blog,
            'categoryData' => $categoryData,
            'storeList' => $storeList,
            'postData' => $postData, // ← Add postData like CI project
        ]);
    }
    
    public function delete($id)
    {
        $blog = DB::table('blogs')->where('id', $id)->first();
        
        if ($blog && $blog->image) {
            $imagePath = public_path('uploads/blogs/' . $blog->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        DB::table('blogs')->where('id', $id)->delete();
        
        return redirect('admin/Blogs')->with('message_success', 'Blog deleted successfully');
    }
    
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect('admin/Blogs');
        }
        
        // Get store list like CI project
        $storeList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($store) {
                return (array) $store;
            })
            ->toArray();
        
        // Get blog data like CI project
        $blog = DB::table('blogs')->where('id', $id)->first();
        
        // Get blog comments like CI project
        $blogComments = DB::table('blog_comments')
            ->where('blog_id', $id)
            ->orderBy('created', 'desc')
            ->get();
        
        return view('admin.blogs.view', [
            'page_title' => 'Blog Details',
            'blog' => $blog,
            'blogComments' => $blogComments,
            'storeList' => $storeList,
        ]);
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('blogs')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Blog activated successfully' : 'Blog deactivated successfully';
        
        return redirect('admin/Blogs')->with('message_success', $message);
    }
    
    // Blog Categories Management
    public function Category()
    {
        $blogs = DB::table('blog_category')->orderBy('id', 'desc')->get();
        
        return view('admin.blogs.category', [
            'page_title' => 'Blogs Category',
            'blogs' => $blogs,
        ]);
    }
    
    public function addEditCategory(Request $request, $id = null)
    {
        $blog = $id ? DB::table('blog_category')->where('id', $id)->first() : null;
        
        // Get store list like CI project
        $storeList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($store) {
                return (array) $store;
            })
            ->toArray();
        
        // Prepare postData like CI project
        $postData = [];
        if ($blog) {
            $postData = (array) $blog;
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'category_name' => 'required|max:255',
                'category_name_french' => 'required|max:255',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                // Set postData from request like CI project
                $postData = $request->except(['store_id']);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('postData', $postData);
            }
            
            $data = [
                'category_name' => $request->category_name,
                'category_name_french' => $request->category_name_french,
                'page_title' => $request->page_title,
                'page_title_french' => $request->page_title_french,
                'meta_description_content' => $request->meta_description_content,
                'meta_description_content_french' => $request->meta_description_content_french,
                'meta_keywords_content' => $request->meta_keywords_content,
                'meta_keywords_content_french' => $request->meta_keywords_content_french,
                'store_id' => is_array($request->store_id) ? implode(',', $request->store_id) : ($request->store_id ?? '1'),
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($id) {
                DB::table('blog_category')->where('id', $id)->update($data);
                $message = 'Blog category updated successfully';
            } else {
                $data['created'] = now();
                DB::table('blog_category')->insert($data);
                $message = 'Blog category created successfully';
            }
            
            return redirect('admin/Blogs/Category')->with('message_success', $message);
        }
        
        return view('admin.blogs.add_edit_category', [
            'page_title' => $id ? 'Edit Category' : 'Add New Category',
            'blog' => $blog,
            'storeList' => $storeList,
            'postData' => $postData, // ← Add postData like CI project
        ]);
    }
    
    public function deleteCategory($id)
    {
        DB::table('blog_category')->where('id', $id)->delete();
        
        return redirect('admin/Blogs/Category')->with('message_success', 'Blog category deleted successfully');
    }
    
    public function activeInactiveCategory($id, $status)
    {
        DB::table('blog_category')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Blog category activated successfully' : 'Blog category deactivated successfully';
        
        return redirect('admin/Blogs/Category')->with('message_success', $message);
    }
    
    /**
     * Resize image like CI project
     */
    private function resizeImage($filename, $size, $width = '', $height = '', $folder)
    {
        $sourcePath = public_path('uploads/' . $folder . '/' . $filename);
        
        // Create size-specific directory
        $sizePath = public_path('uploads/' . $folder . '/' . $size);
        if (!file_exists($sizePath)) {
            mkdir($sizePath, 0777, true);
        }
        
        $destinationPath = $sizePath . '/' . $filename;
        
        // Copy original file to size directory (simple approach like CI)
        if (file_exists($sourcePath)) {
            copy($sourcePath, $destinationPath);
        }
    }
}
