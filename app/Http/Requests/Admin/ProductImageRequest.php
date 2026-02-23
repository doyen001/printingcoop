<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Add your authorization logic here
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_images' => [
                'required',
                'array',
                'min:1',
                'max:10' // Maximum 10 images per upload
            ],
            'product_images.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:5120', // 5MB max file size
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            
            // For single image upload
            'file' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:5120', // 5MB max file size
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            
            // For template file upload
            'template_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,txt',
                'max:10240' // 10MB max file size
            ],
            
            'template_file.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,txt',
                'max:10240' // 10MB max file size
            ],
        ];
    }

    /**
     * Get the custom validation messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product_images.required' => 'At least one image is required.',
            'product_images.max' => 'You may upload a maximum of 10 images at once.',
            'product_images.*.required' => 'Image file is required.',
            'product_images.*.image' => 'Please upload a valid image file.',
            'product_images.*.mimes' => 'Only JPEG, PNG, GIF, and WebP images are allowed.',
            'product_images.*.max' => 'Image size may not exceed 5MB.',
            'product_images.*.dimensions' => 'Image dimensions must be between 100x100 and 2000x2000 pixels.',
            'file.image' => 'Please upload a valid image file.',
            'file.mimes' => 'Only JPEG, PNG, GIF, and WebP images are allowed.',
            'file.max' => 'Image size may not exceed 5MB.',
            'file.dimensions' => 'Image dimensions must be between 100x100 and 2000x2000 pixels.',
            'template_file.file' => 'Please upload a valid file.',
            'template_file.mimes' => 'Only PDF, DOC, DOCX, and TXT files are allowed.',
            'template_file.max' => 'File size may not exceed 10MB.',
            'template_file.*.file' => 'Please upload a valid file.',
            'template_file.*.mimes' => 'Only PDF, DOC, DOCX, and TXT files are allowed.',
            'template_file.*.max' => 'File size may not exceed 10MB.',
        ];
    }

    /**
     * Get custom attributes for validator.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'product_images' => 'Product Images',
            'product_images.*' => 'Product Image',
            'file' => 'Image File',
            'template_file' => 'Template File',
            'template_file.*' => 'Template File',
        ];
    }
}
