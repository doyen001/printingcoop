<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    public function index()
    {
        $services = DB::table('services')->orderBy('id', 'desc')->get();
        
        return view('admin.services.index', [
            'page_title' => 'Services',
            'services' => $services,
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $service = $id ? DB::table('services')->where('id', $id)->first() : null;
        
        // Get main store list like CI project
        $mainStoreList = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();
        
        // Prepare postData like CI project
        $postData = [];
        if ($service) {
            $postData = (array) $service;
        }
        
        if ($request->isMethod('post')) {
            $rules = [
                'main_store_id' => 'required',
                'name' => 'required|max:255',
                'name_french' => 'required|max:255',
                'description' => 'required',
                'description_french' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                // Set postData from request like CI project
                $postData = $request->except(['files', 'files_french']);
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('postData', $postData);
            }
            
            $data = [
                'name' => $request->name,
                'name_french' => $request->name_french,
                'description' => $request->description,
                'description_french' => $request->description_french,
                'status' => $request->status ?? 1,
                'main_store_id' => $request->main_store_id ?? 1,
                'updated' => now(),
            ];
            
            // Handle file uploads like CI project
            $saveData = true;
            
            if ($request->hasFile('files')) {
                $image = $request->file('files');
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Create upload directory if it doesn't exist
                $uploadPath = public_path('uploads/banners/small/');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Move file
                $image->move($uploadPath, $filename);
                
                // Create large folder and resize
                $largePath = $uploadPath . '/large';
                if (!file_exists($largePath)) {
                    mkdir($largePath, 0755, true);
                }
                
                // Copy to large folder and resize
                $this->resizeServiceImage($filename, 1920, 428);
                $data['service_image'] = $filename;
            } else if (!$id) {
                // New service requires image
                return redirect()->back()
                    ->with('file_message_error', 'Select service images of banner')
                    ->withInput();
            }
            
            if ($request->hasFile('files_french')) {
                $image = $request->file('files_french');
                $filename = time() . '_french_' . $image->getClientOriginalName();
                
                // Move file
                $image->move(public_path('uploads/services'), $filename);
                
                // Create large folder and resize
                $this->resizeServiceImage($filename, 1920, 428);
                $data['service_image_french'] = $filename;
            }
            
            if ($saveData) {
                if ($id) {
                    $data['updated'] = now();
                    DB::table('services')->where('id', $id)->update($data);
                    $message = 'Service updated successfully';
                } else {
                    $data['created'] = now();
                    $data['updated'] = now();
                    DB::table('services')->insert($data);
                    $message = 'Service created successfully';
                }
                
                return redirect('admin/Services')->with('message_success', $message);
            }
        }
        
        return view('admin.services.add_edit', [
            'page_title' => $id ? 'Edit Service' : 'Add Service',
            'service' => $service,
            'mainStoreList' => $mainStoreList,
            'postData' => $postData, // ← Add postData like CI project
        ]);
    }
    
    public function delete($id)
    {
        $service = DB::table('services')->where('id', $id)->first();
        
        if ($service && $service->service_image) {
            $imagePath = public_path('uploads/services/' . $service->service_image);
            if (file_exists($imagePath)) unlink($imagePath);
        }
        
        DB::table('services')->where('id', $id)->delete();
        
        return redirect('admin/Services')->with('message_success', 'Service deleted successfully');
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('services')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'Service activated successfully' : 'Service deactivated successfully';
        
        return redirect('admin/Services')->with('message_success', $message);
    }
    
    /**
     * Resize service image to large folder
     * Based on CI project's resizeImage method
     */
    private function resizeServiceImage($filename, $width, $height)
    {
        $sourcePath = public_path('uploads/services/' . $filename);
        $largePath = public_path('uploads/services/large/' . $filename);
        
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
