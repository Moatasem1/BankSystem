<?php

namespace App\Models;

use Exception;
use mysqli;

/**
 * User class
 * 
 * The User class serves as the base class for all user types within the application,
 * providing common properties and methods that are essential for managing users info.
 * 
 * Attributes:
 * - string $firstName: The first name of the user.
 * - string $lastName: The last name of the user.
 * - string $email: The email address of the user.
 * - string $phone: The phone number of the user.
 * 
 * Methods:
 * - __construct(string $firstName, string $lastName, string $email, string $phone): Constructs a new User object.
 * - getFirstName(): string: Gets the first name of the user.
 * - setFirstName(string $firstName): void: Sets the first name of the user.
 * - getLastName(): string: Gets the last name of the user.
 * - setLastName(string $lastName): void: Sets the last name of the user.
 * - getEmail(): string: Gets the email address of the user.
 * - setEmail(string $email): void: Sets the email address of the user.
 * - getPhone(): string: Gets the phone number of the user.
 * - setPhone(string $phone): void: Sets the phone number of the user.
 * - getFullName(): string: Gets the full name of the user.
 * 
 * Usage: 
 * It can be extended by more specific user types that implement additional functionalities and attributes
 * tailored to their specific roles within the application.
 */

class User
{
    /**
     * The connection to the database.
     * @var mysqli
     */
    private mysqli $dbConn;

    /**
     * The id of the user the primary key.
     * @var int
     */
    private int $id;


    /**
     * The first name of the user.
     * @var string
     */
    private string $firstName;

    /**
     * The last name of the user.
     * @var string
     */
    private string $lastName;

    /**
     * The email of the user.
     * @var string
     */
    private string $email;

    /**
     * The phone of the user.
     * @var string
     */
    private string $phone;

    /**
     * Construct a new User object.
     *
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param string $email The email of the user.
     * @param string $phone The phone number of the user.
     */
    public function __construct(string $firstName, string $lastName, string $email, string $phone)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
    }

    /**
     * Get the database connection.
     *
     * @return mysqli The mysqli database connection.
     */
    public function getDbConn(): mysqli
    {
        return $this->dbConn;
    }

    /**
     * Set the database connection.
     *
     * @param mysqli $conn The mysqli database connection.
     */
    public function setDbConn(mysqli $conn): void
    {
        $this->dbConn = $conn;
    }

    /**
     * Get the id of the user.
     *
     * @return int The id of the user.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the first name of the user.
     *
     * @return string The first name of the user.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the user.
     *
     * @param string $firstName The new first name of the user.
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the last name of the user.
     *
     * @return string The last name of the user.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the user.
     *
     * @param string $lastName The new last name of the user.
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the email of the user.
     *
     * @return string The email of the user.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email of the user.
     *
     * @param string $email The new email of the user.
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the phone number of the user.
     *
     * @return string The phone number of the user.
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Set the phone number of the user.
     *
     * @param string $phone The new phone number of the user.
     * @return void
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * Get the full name for the user, 
     * The full name is the first name followed by a space and the last name.
     * @return string The full name of the user
     */
    public function getFullName(): string
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }

    /**
     * Save user data to the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    public function save(): bool
    {
        if (!$this->dbConn) {
            throw new Exception("Database connection not set.");
        }

        $sql = "INSERT INTO users (first_name, last_name,email,phone) VALUES (?, ?,?,?)";

        if ($stmt = $this->dbConn->prepare($sql)) {
            $stmt->bind_param("sss", $this->firstName, $this->lastName, $this->email, $this->phone);
            $insertedId = $this->dbConn->insert_id;
            $result = $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare statement: " . $this->dbConn->error);
        }

        if ($result) {
            $this->$insertedId;
            return true;
        } else {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
    }
}
