<?php

/**
 * doc
 */

namespace App\Models;

use App\Helpers\DatabaseUtility;
use App\Helpers\Utility;

class Admin extends User
{
    /**
     * The primary key identifying the admin.
     * @var int
     */
    private int $adminId;
    /**
     * Username used to access the system.
     * @var string
     */
    private string $userName;

    /**
     * The permission level of the admin.
     * @var int
     */
    private int $permission;

    /**
     * The password used for authentication.
     * @var string
     */
    private string $password;

    /**
     * Construct a new Admin object.
     *
     * @param DatabaseUtility $databaseUtility The database utility object.
     * @param int|null $adminId The unique identifier of the admin.
     * @param int|null $userId The unique identifier of the admin user.
     * @param string $firstName The first name of the admin user.
     * @param string $lastName The last name of the admin user.
     * @param string $email The email of the admin user.
     * @param string $phone The phone number of the admin user.
     * @param string $password The password of the admin account.
     * @param int $permission The permission level of the admin.
     */
    public function __construct(
        DatabaseUtility $databaseUtility,
        ?int $adminId,
        ?int $userId,
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $password,
        int $permission
    ) {
        parent::__construct($databaseUtility, $userId, $firstName, $lastName, $email, $phone);
        $this->adminId = $adminId;
        $this->setPassword($password);
        $this->setPermission($permission);
    }

    /**
     * Get the admin's ID.
     * @return int The admin's ID.
     */
    public function getAdminId(): int
    {
        return $this->adminId;
    }

    /**
     * Get the admin's username.
     * @return string The admin's username.
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * Set the admin's username.
     * @param string $userName The username to set.
     */
    public function setUserName(string $userName): void
    {
        $this->userName = Utility::sanitize($userName);
    }

    /**
     * Get the admin's permission level.
     * @return int The admin's permission level.
     */
    public function getPermission(): int
    {
        return $this->permission;
    }

    /**
     * Set the admin's permission level.
     * @param int $permission The permission level to set.
     */
    public function setPermission(int $permission): void
    {
        $this->permission = $permission;
    }

    /**
     * Get the admin's password.
     * @return string The admin's password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the admin's password.
     * @param string $password The password to set.
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash(Utility::sanitize($password), PASSWORD_DEFAULT);
    }
}
