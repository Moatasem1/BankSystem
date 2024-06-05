<?php

namespace App\Helpers;

/**
 * Utility Class
 *
 * This class provides common utility functions used across the app, including methods for sanitizing data.
 *
 * Methods:
 * sanitize(string $data): Sanitizes input data by removing leading/trailing whitespace and 
 *                          converting special characters to HTML entities
 *
 * Usage:
 * This class's methods can be called statically, so you don't need to initialize the class to use them.
 * Example:
 *   $sanitizedData = Utility::sanitize($inputData);
 */
class Utility
{
    /**
     * Sanitizes the input data by removing leading/trailing whitespace and converting special characters to HTML entities.
     *
     * @param string $data The input data to be sanitized.
     * @return string The sanitized data.
     */
    static public function sanitize($data): string
    {
        return trim(htmlspecialchars($data, ENT_QUOTES));
    }
}
