<?php

namespace App\Models;

use Exception;
use mysqli;

require_once "../config/database_connection.php";

/**
 * Client class
 * 
 * The Client class represents a client in the banking application, providing
 * properties and methods essential for managing client-specific information.
 * 
 * Attributes:
 * - string $accountNumber: The account number for the client, which is the primary key
 * - string $pinCode: The PIN code for the client's account.
 * - string $accountBalance: The account balance for the client.
 * 
 * Methods:
 * - __construct(string $firstName, string $lastName, string $email, string $phone, string $accountNumber, string $pinCode, string $accountBalance): Constructs a new Client object.
 * - getAccountNumber(): string: Gets the account number of the client.
 * - setAccountNumber(string $accountNumber): void: Sets the account number of the client.
 * - getPinCode(): string: Gets the PIN code of the client.
 * - setPinCode(string $pinCode): void: Sets the PIN code of the client.
 * - getAccountBalance(): string: Gets the account balance of the client.
 * - setAccountBalance(string $accountBalance): void: Sets the account balance of the client.
 * 
 * Inherited Methods from User:
 * - getFirstName(): string: Gets the first name of the user.
 * - setFirstName(string $firstName): void: Sets the first name of the user.
 * - getLastName(): string: Gets the last name of the user.
 * - setLastName(string $lastName): void: Sets the last name of the user.
 * - getEmail(): string: Gets the email of the user.
 * - setEmail(string $email): void: Sets the email of the user.
 * - getPhone(): string: Gets the phone number of the user.
 * - setPhone(string $phone): void: Sets the phone number of the user.
 * - getFullName(): string: Gets the full name of the user.
 * 
 * Usage: This class extends the User class and adds functionalities specific to clients in the banking application.
 */
class Client extends User
{
    /**
     * The connection to the database.
     * @var mysqli
     */
    private mysqli $dbConn;

    /**
     * The account number for the client.
     * @var string
     */
    private string $accountNumber;

    /**
     * The PIN code for the client's account.
     * @var string
     */
    private string $pinCode;

    /**
     * The account balance for the client.
     * @var string
     */
    private string $accountBalance;

    /**
     * Construct a new Client object.
     *
     * @param string $firstName The first name of the client.
     * @param string $lastName The last name of the client.
     * @param string $email The email of the client.
     * @param string $phone The phone number of the client.
     * @param string $accountNumber The account number for the client.
     * @param string $pinCode The PIN code for the client's account.
     * @param string $accountBalance The account balance for the client.
     */
    public function __construct(string $firstName, string $lastName, string $email, string $phone, string $accountNumber, string $pinCode, string $accountBalance)
    {
        parent::__construct($firstName, $lastName, $email, $phone);
        $this->accountNumber = $this->sanitize($accountNumber);
        $this->pinCode = $this->sanitize($pinCode);
        $this->accountBalance = $this->sanitize($accountBalance);
    }

    /**
     * Sanitize client info.
     * 
     * @param string $data The data to be sanitized.
     * @return string The sanitized data.
     */
    private function sanitize(string $data): string
    {
        return trim(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
    }

    /**
     * Set the database connection.
     *
     * @param mysqli $conn The connection to the database.
     */
    public function setConnection(mysqli $conn): void
    {
        $this->dbConn = $conn;
    }

    /**
     * Get the database connection.
     *
     * @return mysqli The connection to the database.
     */
    public function getConnection(): mysqli
    {
        return $this->dbConn;
    }

    /**
     * Get the account number of the client.
     *
     * @return string The account number of the client.
     */
    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }


    /**
     * Get the PIN code of the client.
     *
     * @return string The PIN code of the client.
     */
    public function getPinCode(): string
    {
        return $this->pinCode;
    }

    /**
     * Set the PIN code of the client.
     *
     * @param string $pinCode The new PIN code of the client.
     */
    public function setPinCode(string $pinCode): void
    {
        $this->pinCode = $this->sanitize($pinCode);
    }

    /**
     * Get the account balance of the client.
     *
     * @return string The account balance of the client.
     */
    public function getAccountBalance(): string
    {
        return $this->accountBalance;
    }

    /**
     * Set the account balance of the client.
     *
     * @param string $accountBalance The new account balance of the client.
     */
    public function setAccountBalance(string $accountBalance): void
    {
        $this->accountBalance = $this->sanitize($accountBalance);
    }

    /**
     * Save client data to the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    public function save(): bool
    {
        if (!$this->dbConn) {
            throw new Exception("Database connection not set.");
        }

        $sql = "INSERT INTO client (account_number, pin_code, account_balance) VALUES (?, ?, ?)";

        if ($stmt = $this->dbConn->prepare($sql)) {
            $stmt->bind_param("sss", $this->accountNumber, $this->pinCode, $this->accountBalance);
            $result = $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare statement: " . $this->dbConn->error);
        }

        if ($result) {
            return true;
        } else {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
    }
}
