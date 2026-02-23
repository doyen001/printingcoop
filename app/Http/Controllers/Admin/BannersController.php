<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BannersController extends Controller
{
    public function index()
    {
        $banners = DB::table('banners')->orderBy('id', 'desc')->get();
        
        // Convert to arrays like CI project
        $banners = $banners->map(function($banner) {
            return (array) $banner;
        })->toArray();
        
        // Get main store list like CI project
        $mainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
        
        return view('admin.banners.index', [
            'page_title' => 'Banners',
            'banners' => $banners,
            'mainStoreList' => $mainStoreList, // ← Add mainStoreList like CI project
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $banner = $id ? DB::table('banners')->where('id', $id)->first() : null;
        
        // Get main store list like CI project
        $mainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
        
        // Prepare postData like CI project
        $postData = [];
        if ($banner) {
            $postData = (array) $banner;
        }
        
        if ($request->isMethod('post')) {
            // Debug: Log the request data
            \Log::info('Banner form submitted', [
                'request_data' => $request->all(),
                'files' => $request->hasFile('files'),
                'files_french' => $request->hasFile('files_french'),
                'id' => $id
            ]);
            
            $rules = [
                'main_store_id' => 'required',
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
                'short_description' => 'max:150',
                'short_description_french' => 'max:150',
            ];
            
            if (!$id) {
                $rules['files'] = 'required|image|mimes:jpeg,png,gif|max:1024';
            }
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                \Log::error('Banner validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                // Set postData from request like CI project, but exclude file uploads
                $postData = $request->except(['files', 'files_french']);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('postData', $postData);
            }
            
            // Prepare data like CI project
            $data = [
                'main_store_id' => $request->main_store_id,
                'name' => $request->name,
                'name_french' => $request->name_french,
                'short_description' => $request->short_description,
                'short_description_french' => $request->short_description_french,
                'updated' => now(),
            ];
            
            // Handle file uploads like CI project
            $saveData = true;
            $uploadData = [];
            $uploadDataFrench = [];
            
            if ($request->hasFile('files')) {
                $image = $request->file('files');
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Create upload directory if it doesn't exist
                $uploadPath = public_path('uploads/banners');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move file
                $image->move($uploadPath, $filename);
                $uploadData['file_name'] = $filename;
                
                // Create large folder and resize
                $largePath = $uploadPath . '/large';
                if (!file_exists($largePath)) {
                    mkdir($largePath, 0755, true);
                }
                
                // Copy to large folder and resize
                $this->resizeBannerImage($filename, 1920, 428);
                $data['banner_image'] = $filename;
            } else if (!$id) {
                // New banner requires image
                return redirect()->back()
                    ->with('file_message_error', 'Select images of banner')
                    ->withInput();
            }
            
            if ($request->hasFile('files_french')) {
                $image = $request->file('files_french');
                $filename = time() . '_french_' . $image->getClientOriginalName();
                
                // Move file
                $image->move(public_path('uploads/banners'), $filename);
                $uploadDataFrench['file_name'] = $filename;
                
                // Create large folder and resize
                $this->resizeBannerImage($filename, 1920, 428);
                $data['banner_image_french'] = $filename;
            }
            
            if ($saveData) {
                \Log::info('Attempting to save banner', [
                    'data' => $data,
                    'id' => $id
                ]);
                
                if ($id) {
                    $data['updated'] = now();
                    $result = DB::table('banners')->where('id', $id)->update($data);
                    \Log::info('Banner update result', ['result' => $result]);
                    $message = 'Banner updated successfully';
                } else {
                    $data['created'] = now();
                    $data['updated'] = now();
                    $result = DB::table('banners')->insert($data);
                    \Log::info('Banner insert result', ['result' => $result]);
                    $message = 'Banner created successfully';
                }
                
                return redirect('admin/Banners')->with('message_success', $message);
            } else {
                \Log::error('Banner saveData is false');
            }
        }
        
        return view('admin.banners.add_edit', [
            'page_title' => $id ? 'Edit Banner' : 'Add Banner',
            'banner' => $banner,
            'mainStoreList' => $mainStoreList,
            'postData' => $postData, // ← Add postData like CI project
        ]);
    }
    
    public function delete($id)
    {
        $banner = DB::table('banners')->where('id', $id)->first();
        
        if ($banner && $banner->banner_image) {
            $imagePath = public_path('uploads/banners/' . $banner->banner_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Also delete large version
            $largeImagePath = public_path('uploads/banners/large/' . $banner->banner_image);
            if (file_exists($largeImagePath)) {
                unlink($largeImagePath);
            }
        }
        
        if ($banner && $banner->banner_image_french) {
            $frenchImagePath = public_path('uploads/banners/' . $banner->banner_image_french);
            if (file_exists($frenchImagePath)) {
                unlink($frenchImagePath);
            }
            
            // Also delete large version
            $largeFrenchImagePath = public_path('uploads/banners/large/' . $banner->banner_image_french);
            if (file_exists($largeFrenchImagePath)) {
                unlink($largeFrenchImagePath);
            }
        }
        
        DB::table('banners')->where('id', $id)->delete();
        
        return redirect('admin/Banners')->with('message_success', 'Banner deleted successfully');
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('banners')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Banner activated successfully' : 'Banner deactivated successfully';
        
        return redirect('admin/Banners')->with('message_success', $message);
    }
    
    /**
     * Resize banner image to large folder
     * Based on CI project's resizeImage method
     */
    private function resizeBannerImage($filename, $width, $height)
    {
        $sourcePath = public_path('uploads/banners/' . $filename);
        $largePath = public_path('uploads/banners/large/' . $filename);
        
        if (file_exists($sourcePath)) {
            // Get image info
            $imageInfo = getimagesize($sourcePath);
            $mime = $imageInfo['mime'];
            
            // Create image resource based on mime type
            switch ($mime) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($sourcePath);
                    break;
                default:
                    return false;
            }
            
            // Get original dimensions
            $originalWidth = imagesx($source);
            $originalHeight = imagesy($source);
            
            // Calculate new dimensions
            $ratio = min($width / $originalWidth, $height / $originalHeight);
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
            
            // Create new image
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Handle transparency for PNG
            if ($mime == 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            // Resize and save
            imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Save based on mime type
            switch ($mime) {
                case 'image/jpeg':
                    imagejpeg($newImage, $largePath, 90);
                    break;
                case 'image/png':
                    imagepng($newImage, $largePath, 9);
                    break;
                case 'image/gif':
                    imagegif($newImage, $largePath);
                    break;
            }
            
            // Free memory
            imagedestroy($source);
            imagedestroy($newImage);
            
            return true;
        }
        
        return false;
    }
}
