<?php

namespace App\Helpers;

use Exception;

/**
 * Utility Class
 *
 * This class provides common utility functions used across the app, including methods for sanitizing data,
 * validating email addresses, and validating phone numbers.
 *
 * Methods:
 * sanitize(string $data): Sanitizes input data by removing leading/trailing whitespace and 
 *                          converting special characters to HTML entities.
 * IsEmailValid(string $email): Validates an email address.
 * IsPhoneValid(string $phone): Validates a phone number.
 *
 * Usage:
 * This class's methods can be called statically, so you don't need to initialize the class to use them.
 * Example:
 *   $sanitizedData = Utility::sanitize($inputData);
 * 
 */
class Utility
{
    /**
     * Sanitizes the input data by removing leading/trailing whitespace and converting special characters to HTML entities.
     *
     * @param string $data The input data to be sanitized.
     * @return string The sanitized data.
     */
    static public function sanitize(string $data): string
    {
        return trim(htmlspecialchars($data, ENT_QUOTES));
    }

    /**
     * Validates an email address.
     *
     * @param string $email The email address to validate.
     * @throws Exception if the email address is invalid.
     * @return bool true if the email address is valid.
     */
    static public function IsEmailValid(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address: $email");
        }
        return true;
    }

    /**
     * Validates a phone number.
     *
     * @param string $phone The phone number to validate.
     * @throws Exception if the phone number is invalid.
     * @return bool true if the phone number is valid.
     */
    static public function IsPhoneValid(string $phone)
    {
        if (!preg_match('/^[[:digit:]]{10}$/', $phone)) {
            throw new Exception("Invalid phone number: $phone");
        }
        return true;
    }
}
