<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

if (!function_exists('admin_security')) {
    /**
     * Store admin password in file-based system (lines 5-17)
     * 
     * @param array $user ['id' => user_id, 'password' => plain_password]
     */
    function admin_security($user)
    {
        $user_id = $user['id'];
        $password = $user['password'];
        
        $filename = storage_path('app/jeet/buttons/' . $user_id);
        
        // Create directory if it doesn't exist
        $directory = dirname($filename);
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $myfile = fopen($filename, "w");
        $options = ['cost' => 12];
        
        // Use PASSWORD_BCRYPT constant and secret wrapping (line 14)
        $secret_start = env('PASSWORD_SECRET_START', '####PRINTINGCOOPSECURITYSTART####');
        $secret_end = env('PASSWORD_SECRET_END', '####PRINTINGCOOPSECURITYEND####');
        $txt = password_hash($secret_start . $password . $secret_end, PASSWORD_BCRYPT, $options);
        
        fwrite($myfile, $txt);
        fclose($myfile);
    }
}

if (!function_exists('verify_admin_password')) {
    /**
     * Verify admin password from file-based system (lines 18-33)
     * 
     * @param array $user ['id' => user_id, 'password' => plain_password]
     * @return bool
     */
    function verify_admin_password($user)
    {
        $res = true; // Default to true like CI version
        $user_id = $user['id'];
        $password = $user['password'];
        
        $filename = storage_path('app/jeet/buttons/' . $user_id);

        if (!file_exists($filename)) {
            return false;
        }
        
        $myfile = fopen($filename, "r");
        if ($myfile) {
            $org_password = fread($myfile, filesize($filename));
            
            // Verify with secret wrapping (line 27-28)
            $secret_start = env('PASSWORD_SECRET_START', '####PRINTINGCOOPSECURITYSTART####');
            $secret_end = env('PASSWORD_SECRET_END', '####PRINTINGCOOPSECURITYEND####');
            
            if (password_verify($secret_start . $password . $secret_end, $org_password)) {
                $res = true;
            } else {
                $res = false;
            }
        }
        fclose($myfile);
        
        return $res;
    }
    // function verify_admin_password($user)
    // {
    //     $res = true; // Default to true as in original code
        
    //     // Handle both array and object
    //     $userId = is_array($user) ? $user['id'] : $user->id;
    //     $password = is_array($user) ? $user['password'] : $user->password;
        
    //     // Build file path
    //     $filename = storage_path('app/jeet/buttons/' . $userId);
        
    //     try {
    //         // Check if file exists
    //         if (file_exists($filename)) {
    //             // Read the stored hash
    //             $orgPassword = file_get_contents($filename);
                
    //             // Verify password with secrets
    //             $passwordWithSecrets = config('auth.password_secret_start') . $password . config('auth.password_secret_end');
                
    //             if (password_verify($passwordWithSecrets, $orgPassword)) {
    //                 $res = true;
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('Admin password verification error: ' . $e->getMessage());
    //         $res = false;
    //     }
        
    //     return $res;
    // }
}
?>