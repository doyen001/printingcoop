<?php

if (!function_exists('getProductImage')) {
    /**
     * Get product image URL based on image name and type
     * Replicated from CI project's getProductImage function
     */
    function getProductImage($imageName = null, $type = 'small')
    {
        $imageUrl = '';

        if (!empty($imageName)) {
            $basePath = public_path('assets/images/products/');
            $baseUrl = url('assets/images/products/');
            
            switch ($type) {
                case 'small':
                    $imagePath = $basePath . 'small/' . $imageName;
                    if (file_exists($imagePath)) {
                        $imageUrl = $baseUrl . 'small/' . $imageName;
                    }
                    break;
                case 'medium':
                    $imagePath = $basePath . 'medium/' . $imageName;
                    if (file_exists($imagePath)) {
                        $imageUrl = $baseUrl . 'medium/' . $imageName;
                    }
                    break;
                case 'large':
                    $imagePath = $basePath . 'large/' . $imageName;
                    if (file_exists($imagePath)) {
                        $imageUrl = $baseUrl . 'large/' . $imageName;
                    }
                    break;
                default:
                    $imagePath = $basePath . $imageName;
                    if (file_exists($imagePath)) {
                        $imageUrl = $baseUrl . $imageName;
                    }
                    break;
            }
        }

        return $imageUrl;
    }
}
