<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    // Categories Management
    
    public function index()
    {
        $categories = DB::table('categories')->orderBy('category_order')->get();
        $lists = [];
        
        foreach ($categories as $category) {
            $lists[] = (array) $category;
        }
        
        return view('admin.categories.index', [
            'page_title' => 'Categories',
            'categories' => $lists,
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $category = $id ? DB::table('categories')->where('id', $id)->first() : null;
        $stores = DB::table('stores')->where('status', 1)->get();
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $slug = $this->createSlug($request->name, 'categories', 'category_slug', $id);
            
            $data = [
                'name' => $request->name,
                'name_french' => $request->name_french,
                'category_slug' => $slug,
                'category_order' => $request->category_order ?? 0,
                'category_dispersion' => $request->category_dispersion,
                'category_dispersion_french' => $request->category_dispersion_french,
                'page_title' => $request->page_title,
                'page_title_french' => $request->page_title_french,
                'meta_description_content' => $request->meta_description_content,
                'meta_description_content_french' => $request->meta_description_content_french,
                'meta_keywords_content' => $request->meta_keywords_content,
                'meta_keywords_content_french' => $request->meta_keywords_content_french,
                'show_main_menu' => $request->show_main_menu ?? 0,
                'show_our_printed_product' => $request->show_our_printed_product ?? 0,
                'show_footer_menu' => $request->show_footer_menu ?? 0,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            // Handle multi-store image uploads
            if (isset($stores) && count($stores) > 0) {
                foreach ($stores as $store) {
                    $key = $store->id;
                    $fileField = $key . 'files';
                    $oldImageField = $key . 'old_image';
                    
                    // Debug: Log what we're checking
                    \Log::info('Checking for file field: ' . $fileField);
                    \Log::info('Has file: ' . ($request->hasFile($fileField) ? 'true' : 'false'));
                    
                    if ($request->hasFile($fileField)) {
                        $image = $request->file($fileField);
                        $filename = time() . '_' . $key . '_' . $image->getClientOriginalName();
                        
                        \Log::info('Processing image: ' . $filename);
                        
                        // Create large directory if it doesn't exist
                        $largePath = public_path('uploads/category/large');
                        if (!file_exists($largePath)) {
                            mkdir($largePath, 0755, true);
                        }
                        
                        // Move original file
                        $image->move(public_path('uploads/category'), $filename);
                        
                        // Create resized version in large folder
                        $this->resizeCategoryImage($filename);
                        
                        // Save to categories_images table
                        $imageData = [
                            'category_id' => $id ?: 0, // Will be updated after category creation
                            'main_store_id' => $key,
                            'image' => $filename,
                            'created' => now(),
                            'updated' => now(),
                        ];
                        
                        // Delete old image if exists
                        $old_image = $request->input($oldImageField);
                        if ($old_image && file_exists(public_path('uploads/category/' . $old_image))) {
                            unlink(public_path('uploads/category/' . $old_image));
                        }
                        if ($old_image && file_exists(public_path('uploads/category/large/' . $old_image))) {
                            unlink(public_path('uploads/category/large/' . $old_image));
                        }
                        
                        // Update or insert image record
                        $existingImageId = $request->input($key . 'category_image_id');
                        if ($existingImageId) {
                            DB::table('categories_images')->where('id', $existingImageId)->update($imageData);
                        } else {
                            DB::table('categories_images')->insert($imageData);
                        }
                        
                        \Log::info('Image saved successfully for store: ' . $key);
                    }
                }
            }
            
            if ($id) {
                DB::table('categories')->where('id', $id)->update($data);
                $message = 'Category updated successfully';
            } else {
                $data['created'] = now();
                $categoryId = DB::table('categories')->insertGetId($data);
                
                // Update category_id in images table for new category
                if ($categoryId) {
                    DB::table('categories_images')
                        ->where('category_id', 0)
                        ->whereIn('main_store_id', function($query) use ($stores) {
                            $query->select('id')->from('stores');
                        })
                        ->update(['category_id' => $categoryId]);
                }
                
                $message = 'Category created successfully';
            }
            
            return redirect('admin/Categories')->with('message_success', $message);
        }
        
        return view('admin.categories.add_edit', [
            'page_title' => $id ? 'Edit Category' : 'Add Category',
            'category' => $category,
            'stores' => DB::table('stores')->where('status', 1)->get(),
            'categoryImages' => $id ? $this->getCategoryImages($id) : $this->getEmptyCategoryImages()
        ]);
    }
    
    public function deleteCategory($id)
    {
        // CI equivalent: Category_Model->deleteCategory() - only deletes database records
        DB::table('categories')->where('id', $id)->delete();
        DB::table('sub_categories')->where('category_id', $id)->delete();
        
        return redirect('admin/Categories')->with('message_success', 'Category deleted successfully');
    }
    
    /**
     * Get category images for each store
     * CI equivalent: Category_Model->getCategoriesImagesDataBy()
     */
    private function getCategoryImages($categoryId)
    {
        if (!$categoryId) {
            return [];
        }
        
        $stores = DB::table('stores')->where('status', 1)->get();
        $categoryImages = [];
        
        foreach ($stores as $store) {
            $imageData = DB::table('categories_images')
                ->where('category_id', $categoryId)
                ->where('main_store_id', $store->id)
                ->first();
                
            $categoryImages[$store->id] = [
                'id' => $imageData->id ?? 0,
                'image' => $imageData->image ?? ''
            ];
        }
        
        return $categoryImages;
    }
    
    /**
     * Get empty category images structure for new categories
     * CI equivalent: Creates empty structure for all stores when no category exists
     */
    private function getEmptyCategoryImages()
    {
        $stores = DB::table('stores')->where('status', 1)->get();
        $categoryImages = [];
        
        foreach ($stores as $store) {
            $categoryImages[$store->id] = [
                'id' => 0,
                'image' => ''
            ];
        }
        
        return $categoryImages;
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('categories')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Category activated successfully' : 'Category deactivated successfully';
        
        return redirect('admin/Categories')->with('message_success', $message);
    }
    
    // Sub-Categories Management
    
    public function subCategories($category_id = null)
    {
        $query = DB::table('sub_categories')
            ->join('categories', 'sub_categories.category_id', '=', 'categories.id')
            ->select('sub_categories.*', 'categories.name as category_name')
            ->orderBy('sub_categories.sub_category_order');
        
        if ($category_id) {
            $query->where('sub_categories.category_id', $category_id);
        }
        
        $subCategories = $query->get();
        $categories = DB::table('categories')->where('status', 1)->get();
        
        return view('admin.categories.sub_categories', [
            'page_title' => 'Sub Categories',
            'subCategories' => $subCategories,
            'categories' => $categories,
            'category_id' => $category_id,
        ]);
    }
    
    public function addEditSubCategory(Request $request, $id = null)
    {
        $subCategory = $id ? DB::table('sub_categories')->where('id', $id)->first() : null;
        
        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
                'category_id' => 'required|exists:categories,id',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $slug = $this->createSlug($request->name, 'sub_categories', 'subcategory_slug', $id);
            
            $data = [
                'name' => $request->name,
                'name_french' => $request->name_french,
                'category_id' => $request->category_id,
                'subcategory_slug' => $slug,
                'sub_category_order' => $request->sub_category_order ?? 0,
                'sub_category_dispersion' => $request->sub_category_dispersion,
                'sub_category_dispersion_french' => $request->sub_category_dispersion_french,
                'page_title' => $request->page_title,
                'page_title_french' => $request->page_title_french,
                'meta_description_content' => $request->meta_description_content,
                'meta_description_content_french' => $request->meta_description_content_french,
                'meta_keywords_content' => $request->meta_keywords_content,
                'meta_keywords_content_french' => $request->meta_keywords_content_french,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/sub_categories'), $filename);
                $data['subcategory_image'] = $filename;
                
                if ($id && $subCategory && $subCategory->subcategory_image) {
                    $oldPath = public_path('uploads/sub_categories/' . $subCategory->subcategory_image);
                    if (file_exists($oldPath)) unlink($oldPath);
                }
            }
            
            if ($id) {
                DB::table('sub_categories')->where('id', $id)->update($data);
                $message = 'Sub-category updated successfully';
            } else {
                $data['created'] = now();
                DB::table('sub_categories')->insert($data);
                $message = 'Sub-category created successfully';
            }
            
            return redirect('admin/Categories/subCategories')->with('message_success', $message);
        }
        
        $categories = DB::table('categories')->where('status', 1)->orderBy('category_order', 'asc')->pluck('name', 'id')->toArray();
        
        return view('admin.categories.add_edit_sub_category', [
            'page_title' => $id ? 'Edit Sub Category' : 'Add Sub Category',
            'subCategory' => $subCategory,
            'categories' => $categories,
        ]);
    }
    
    public function deleteSubCategory($id)
    {
        $subCategory = DB::table('sub_categories')->where('id', $id)->first();
        
        if ($subCategory && $subCategory->subcategory_image) {
            $imagePath = public_path('uploads/sub_categories/' . $subCategory->subcategory_image);
            if (file_exists($imagePath)) unlink($imagePath);
        }
        
        DB::table('sub_categories')->where('id', $id)->delete();
        
        return redirect('admin/Categories/subCategories')->with('message_success', 'Sub-category deleted successfully');
    }
    
    public function activeInactiveSubCategory($id, $status)
    {
        DB::table('sub_categories')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Sub-category activated successfully' : 'Sub-category deactivated successfully';
        
        return redirect('admin/Categories/subCategories')->with('message_success', $message);
    }
    
    // Tags Management
    
    public function tag()
    {
        $tags = DB::table('tags')->orderBy('tag_order')->get();
        
        return view('admin.categories.tags', [
            'page_title' => 'Tags',
            'tags' => $tags,
        ]);
    }
    
    public function addEditTag(Request $request, $id = null)
    {
        $tag = $id ? DB::table('tags')->where('id', $id)->first() : null;
        
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
                'tag_order' => $request->tag_order ?? 0,
                'font_class' => $request->font_class,
                'proudly_display_your_brand' => $request->proudly_display_your_brand ?? 0,
                'montreal_book_printing' => $request->montreal_book_printing ?? 0,
                'footer' => $request->footer ?? 0,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            // Handle English image upload
            if ($request->hasFile('files')) {
                $image = $request->file('files');
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Create large directory if it doesn't exist
                $largePath = public_path('uploads/category/large');
                if (!file_exists($largePath)) {
                    mkdir($largePath, 0755, true);
                }
                
                // Move original file
                $image->move(public_path('uploads/category'), $filename);
                $data['image'] = $filename;
                
                // Create resized version in large folder
                $this->resizeCategoryImage($filename);
                
                // Delete old image if exists
                $old_image = $request->old_image;
                if ($old_image && file_exists(public_path('uploads/category/' . $old_image))) {
                    unlink(public_path('uploads/category/' . $old_image));
                }
                if ($old_image && file_exists(public_path('uploads/category/large/' . $old_image))) {
                    unlink(public_path('uploads/category/large/' . $old_image));
                }
            }
            
            // Handle French image upload
            if ($request->hasFile('files_french')) {
                $image_french = $request->file('files_french');
                $filename_french = time() . '_french_' . $image_french->getClientOriginalName();
                
                // Create large directory if it doesn't exist
                $largePath = public_path('uploads/category/large');
                if (!file_exists($largePath)) {
                    mkdir($largePath, 0755, true);
                }
                
                // Move original file
                $image_french->move(public_path('uploads/category'), $filename_french);
                $data['image_french'] = $filename_french;
                
                // Create resized version in large folder
                $this->resizeCategoryImage($filename_french);
                
                // Delete old French image if exists
                $old_image_french = $request->old_image_french;
                if ($old_image_french && file_exists(public_path('uploads/category/' . $old_image_french))) {
                    unlink(public_path('uploads/category/' . $old_image_french));
                }
                if ($old_image_french && file_exists(public_path('uploads/category/large/' . $old_image_french))) {
                    unlink(public_path('uploads/category/large/' . $old_image_french));
                }
            }
            
            if ($id) {
                DB::table('tags')->where('id', $id)->update($data);
                $message = 'Tag updated successfully';
            } else {
                $data['created'] = now();
                DB::table('tags')->insert($data);
                $message = 'Tag created successfully';
            }
            
            return redirect('admin/Categories/tag')->with('message_success', $message);
        }
        
        return view('admin.categories.add_edit_tag', [
            'page_title' => $id ? 'Edit Tag' : 'Add New Tag',
            'tag' => $tag,
        ]);
    }
    
    public function deleteTag($id)
    {
        DB::table('tags')->where('id', $id)->delete();
        
        return redirect('admin/Categories/tag')->with('message_success', 'Tag deleted successfully');
    }
    
    public function activeInactiveTag($id, $status)
    {
        DB::table('tags')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Tag activated successfully' : 'Tag deactivated successfully';
        
        return redirect('admin/Categories/tag')->with('message_success', $message);
    }
    
    // Helper Methods
    
    protected function createSlug($name, $table, $column, $id = null)
    {
        $slug = Str::slug($name);
        $count = 1;
        
        while (true) {
            $query = DB::table($table)->where($column, $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
            
            if ($query->count() == 0) {
                break;
            }
            
            $slug = Str::slug($name) . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
    
    /**
     * Resize category image to create large version
     * Based on CI project's resizeImage method
     */
    private function resizeCategoryImage($filename, $width = 200, $height = 200)
    {
        try {
            $source_path = public_path('uploads/category/' . $filename);
            $target_path = public_path('uploads/category/large/' . $filename);
            
            // Check if source file exists
            if (!file_exists($source_path)) {
                return false;
            }
            
            // Get image info
            $image_info = getimagesize($source_path);
            if (!$image_info) {
                return false;
            }
            
            $image_type = $image_info[2];
            
            // Create image resource based on type
            switch ($image_type) {
                case IMAGETYPE_JPEG:
                    $source_image = imagecreatefromjpeg($source_path);
                    break;
                case IMAGETYPE_PNG:
                    $source_image = imagecreatefrompng($source_path);
                    break;
                case IMAGETYPE_GIF:
                    $source_image = imagecreatefromgif($source_path);
                    break;
                default:
                    return false;
            }
            
            if (!$source_image) {
                return false;
            }
            
            // Get original dimensions
            $original_width = imagesx($source_image);
            $original_height = imagesy($source_image);
            
            // Create new image with specified dimensions
            $target_image = imagecreatetruecolor($width, $height);
            
            // Resize image (don't maintain ratio like CI)
            imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $width, $height, $original_width, $original_height);
            
            // Save resized image
            switch ($image_type) {
                case IMAGETYPE_JPEG:
                    imagejpeg($target_image, $target_path, 90);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($target_image, $target_path, 9);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($target_image, $target_path);
                    break;
            }
            
            // Free memory
            imagedestroy($source_image);
            imagedestroy($target_image);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Image resize failed: ' . $e->getMessage());
            return false;
        }
    }
}
