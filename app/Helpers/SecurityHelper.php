<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Sanitize string input to prevent XSS
     */
    public static function sanitizeString($input)
    {
        if (!is_string($input)) {
            return $input;
        }
        
        // Remove HTML tags and special characters
        return trim(strip_tags($input));
    }
    
    /**
     * Sanitize array of inputs
     */
    public static function sanitizeArray(array $data)
    {
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitizeArray($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = self::sanitizeString($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Generate secure random token
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
}
