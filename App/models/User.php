<?php

namespace App\Models;

require "App/Helpers/Utility.php";

use App\Helpers\DatabaseUtility;
use App\Helpers\Utility;
use Exception;
use mysqli;
use mysqli_stmt;

/**
 * User class
 * 
 * The User class serves as the base class for all user types within the application,
 * providing common properties and methods that are essential for managing user information.
 * 
 * Attributes:
 * - DatabaseUtility $dbUtility: The database utility object.
 * - int $id: The unique identifier of the user.
 * - string $firstName: The first name of the user.
 * - string $lastName: The last name of the user.
 * - string $email: The email address of the user.
 * - string $phone: The phone number of the user.
 * 
 * Methods:
 * - __construct(DatabaseUtility $databaseUtility, int $id, string $firstName, string $lastName, string $email, string $phone): Constructs a new User object.
 * - getId(): int: Gets the id of the user.
 * - getFirstName(): string: Gets the first name of the user.
 * - setFirstName(string $firstName): void: Sets the first name of the user.
 * - getLastName(): string: Gets the last name of the user.
 * - setLastName(string $lastName): void: Sets the last name of the user.
 * - getEmail(): string: Gets the email address of the user.
 * - setEmail(string $email): void: Sets the email address of the user.
 * - getPhone(): string: Gets the phone number of the user.
 * - setPhone(string $phone): void: Sets the phone number of the user.
 * - getFullName(): string: Gets the full name of the user.
 * - save(): bool: Saves user data to the database.
 * - update(): bool: Updates user data in the database.
 * - delete(): bool: Deletes user data from the database.
 * - updateFirstName(string $firstName): bool: Updates the first name of the user in the database.
 * - updateLastName(string $lastName): bool: Updates the last name of the user in the database.
 * - updateEmail(string $email): bool: Updates the email address of the user in the database.
 * - updatePhone(string $phone): bool: Updates the phone number of the user in the database.
 * - getUserById(DatabaseUtility $dbUtility, int $id): ?User: Retrieves a user by their ID from the database.
 * - getUserByName(DatabaseUtility $dbUtility, string $firstName, string $lastName): ?User: Retrieves a user by their first and last name from the database.
 * 
 * Usage: 
 * It can be extended by more specific user types that implement additional functionalities and attributes
 * tailored to their specific roles within the application.
 */


class User
{
    /**
     * The connection to the database.
     * @var DatabaseUtility
     */
    protected DatabaseUtility $dbUtility;

    /**
     * The id of the user the primary key.
     * @var int
     */
    private int $user_id;

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
     * @param DatabaseUtility $databaseUtility The database utility object.
     * @param int $id The unique identifier of the user.
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param string $email The email of the user.
     * @param string $phone The phone number of the user.
     */
    public function __construct(DatabaseUtility $databaseUtility, int $id, string $firstName, string $lastName, string $email, string $phone)
    {
        $this->dbUtility = $databaseUtility;
        $this->user_id = $id;
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setEmail($email);
        $this->setPhone($phone);
    }


    /**
     * Get the id of the user.
     *
     * @return int The id of the user.
     */
    public function getUserId(): int
    {
        return $this->user_id;
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
        $this->firstName = Utility::sanitize($firstName);
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
        $this->lastName = Utility::sanitize($lastName);
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
        $this->email = Utility::sanitize($email);
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
        $this->phone = Utility::sanitize($phone);
    }

    /**
     * Get the full name for the user, 
     * The full name is the first name followed by a space and the last name.
     *
     * @return string The full name of the user.
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
    protected function save(): bool
    {
        $sql = "INSERT INTO USERS (first_name,last_name,email,phone)
        values (?,?,?,?)";
        $stmt = $this->dbUtility->prepare(
            $sql,
            [$this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPhone()]
        );

        $this->dbUtility->execute($stmt);
        $this->user_id = $this->dbUtility->getLastInsertedId();
        return true;
    }

    /**
     * Update user data in the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    protected function update(): bool
    {
        $sql = "UPDATE USERS SET first_name = ?,last_name = ?, email = ?, phone = ? where id = ?";

        $stmt = $this->dbUtility->prepare(
            $sql,
            [$this->getFirstName(), $this->getLastName(), $this->getEmail(), $this->getPhone(), $this->getUserId()]
        );

        return $this->dbUtility->execute($stmt);
    }

    /**
     * Update a specific field of the user.
     *
     * @param string $field The field to update.
     * @param mixed $value The new value of the field.
     * @return bool True if the update was successful, false otherwise.
     */
    private function updateField(string $field, $value): bool
    {
        $sql = "UPDATE USERS SET $field = ? WHERE id = ?";
        $stmt = $this->dbUtility->prepare($sql, [$value, $this->getUserId()]);
        return $this->dbUtility->execute($stmt);
    }

    /**
     * Update the first name of the user.
     *
     * @param string $firstName The new first name of the user.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateFirstName(string $firstName): bool
    {
        $this->setFirstName($firstName);
        return $this->updateField('first_name', $firstName);
    }

    /**
     * Update the last name of the user.
     *
     * @param string $lastName The new last name of the user.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateLastName(string $lastName): bool
    {
        $this->setLastName($lastName);
        return $this->updateField('last_name', $lastName);
    }

    /**
     * Update the email of the user.
     *
     * @param string $email The new email of the user.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateEmail(string $email): bool
    {
        $this->setEmail($email);
        return $this->updateField('email', $email);
    }

    /**
     * Update the phone number of the user.
     *
     * @param string $phone The new phone number of the user.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updatePhone(string $phone): bool
    {
        $this->setPhone($phone);
        return $this->updateField('phone', $phone);
    }

    /**
     * Delete user data from the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    protected function delete(): bool
    {
        $sql = "DELETE FROM USERS where id = ?";

        $stmt = $this->dbUtility->prepare(
            $sql,
            [$this->getUserId()]
        );

        return $this->dbUtility->execute($stmt);
    }

    /**
     * Retrieve a user by their ID from the database.
     *
     * @param DatabaseUtility $dbUtility The database utility object.
     * @param int $id The ID of the user to retrieve.
     * @return User|null The user object if found, null otherwise.
     */
    protected static function getUserById(DatabaseUtility $dbUtility, int $id): ?User
    {
        $sql = "SELECT * FROM USERS WHERE ID = ?";
        $stmt = $dbUtility->prepare($sql, [$id]);
        $result = $dbUtility->execute($stmt);

        if ($result->num_rows == 1) {
            $row = $dbUtility->getmysqliResultAsArray($result);
            return new User(
                $dbUtility,
                $row[0]["id"],
                $row[0]['first_name'],
                $row[0]['last_name'],
                $row[0]['email'],
                $row[0]['phone']
            );
        }

        return null;
    }

    /**
     * Retrieve a user by their first and last name from the database.
     *
     * @param DatabaseUtility $dbUtility The database utility object.
     * @param string $firstName The first name of the user to retrieve.
     * @param string $lastName The last name of the user to retrieve.
     * @return User|null The user object if found, null otherwise.
     */
    protected static function getUserByName(DatabaseUtility $dbUtility, string $firstName, string $lastName): ?User
    {
        $sql = "SELECT * FROM USERS WHERE LOWER(first_name) = LOWER(?) AND LOWER(last_name) = LOWER(?)";
        $stmt = $dbUtility->prepare($sql, [$firstName, $lastName]);
        $result = $dbUtility->execute($stmt);

        if ($result->num_rows == 1) {
            $row = $dbUtility->getmysqliResultAsArray($result);
            return new User(
                $dbUtility,
                $row[0]["id"],
                $row[0]['first_name'],
                $row[0]['last_name'],
                $row[0]['email'],
                $row[0]['phone']
            );
        }

        return null;
    }
}
