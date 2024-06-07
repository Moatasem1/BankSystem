<?php

namespace App\Models;

require "App/models/User.php";

use App\Helpers\DatabaseUtility;
use App\Helpers\Utility;
use App\Models\User;
use Exception;

/**
 * Client class
 * 
 * The Client class represents a client in the banking application, providing
 * properties and methods essential for managing client-specific information.
 * 
 * Attributes:
 * - int $accountNumber: The account number for the client, which is the primary key.
 * - string $pinCode: The hashed PIN code for the client's account.
 * - float $accountBalance: The account balance for the client.
 * 
 * Methods:
 * - __construct(DatabaseUtility $databaseUtility, int $accountNumber, string $userId, string $firstName, string $lastName, string $email, string $phone, string $pinCode, float $accountBalance): Constructs a new Client object.
 * - getAccountNumber(): int: Gets the account number of the client.
 * - getPinCode(): string: Gets the hashed PIN code of the client.
 * - setPinCode(string $pinCode): void: Sets and hashes the PIN code of the client.
 * - getAccountBalance(): float: Gets the account balance of the client.
 * - setAccountBalance(float $accountBalance): void: Sets the account balance of the client.
 * - save(): bool: Saves client data to the database.
 * - update(): bool: Updates client data in the database.
 * - delete(): bool: Deletes client data from the database.
 * - getClientByAccountNumber(DatabaseUtility $dbUtility, int $accountNumber): ?Client: Retrieves a client by their account number from the database.
 * - getClientByName(DatabaseUtility $dbUtility, string $firstName, string $lastName): ?Client: Retrieves a client by their first and last name from the database.
 * - updatePinCode(string $pinCode): bool: Updates the client's PIN code.
 * - updateAccountBalance(float $accountBalance): bool: Updates the client's account balance.
 * - isPasswordMatch(string $pinCode): bool: Verifies if the provided PIN code matches the stored hashed PIN code.
 * - withdraw()
 * - deposit()
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
 */
class Client extends User
{
    /**
     * The account number of the client, the primary key.
     * @var int
     */
    private int $accountNumber;

    /**
     * The hashed PIN code of the client.
     * @var string
     */
    private string $pinCode;

    /**
     * The account balance of the client.
     * @var float
     */
    private float $accountBalance;

    /**
     * Construct a new Client object.
     *
     * @param DatabaseUtility $databaseUtility The database utility object.
     * @param int|null $accountNumber The account number of the client.
     * @param int|null $userId The unique identifier of the user.
     * @param string $firstName The first name of the user.
     * @param string $lastName The last name of the user.
     * @param string $email The email of the user.
     * @param string $phone The phone number of the user.
     * @param string $pinCode The PIN code of the client's account.
     * @param float $accountBalance The account balance of the client.
     */
    public function __construct(
        DatabaseUtility $databaseUtility,
        ?int $accountNumber,
        ?int $userId,
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $pinCode,
        float $accountBalance
    ) {
        parent::__construct($databaseUtility, $userId, $firstName, $lastName, $email, $phone);
        $this->setAccountBalance($accountBalance);
        $this->setPinCode($pinCode);
        $this->accountNumber = $accountNumber;
    }

    /**
     * Get the account number of the client.
     *
     * @return int The account number of the client.
     */
    public function getAccountNumber(): int
    {
        return $this->accountNumber;
    }

    /**
     * Get the hashed PIN code of the client.
     *
     * @return string The hashed PIN code of the client.
     */
    public function getPinCode(): string
    {
        return $this->pinCode;
    }

    /**
     * Set and hash the PIN code of the client.
     *
     * @param string $pinCode The new PIN code of the client.
     * @return void
     */
    public function setPinCode(string $pinCode): void
    {
        $this->pinCode = password_hash(Utility::sanitize($pinCode), PASSWORD_DEFAULT);
    }

    /**
     * Get the account balance of the client.
     *
     * @return float The account balance of the client.
     */
    public function getAccountBalance(): float
    {
        return $this->accountBalance;
    }

    /**
     * Set the account balance of the client.
     *
     * @param float $accountBalance The new account balance of the client.
     * @return void
     */
    public function setAccountBalance(float $accountBalance): void
    {
        $this->accountBalance = ($accountBalance > 0) ? $accountBalance : 0;
    }

    /**
     * Save client data to the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    public function save(): bool
    {
        parent::save();
        $sql = "INSERT INTO CLIENTS (user_id, pin_code, account_balance) VALUES (?, ?, ?)";
        $stmt = $this->dbUtility->prepare(
            $sql,
            [parent::getUserId(), $this->getPinCode(), $this->getAccountBalance()]
        );

        $this->dbUtility->execute($stmt);
        $this->accountNumber = $this->dbUtility->getLastInsertedId();

        return true;
    }

    /**
     * Update client data in the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    public function update(): bool
    {
        parent::update();
        $sql = "UPDATE CLIENTS SET pin_code = ?, account_balance = ? WHERE account_number = ?";

        $stmt = $this->dbUtility->prepare(
            $sql,
            [$this->getPinCode(), $this->getAccountBalance(), $this->getAccountNumber()]
        );

        return $this->dbUtility->execute($stmt);
    }

    /**
     * Delete client data from the database.
     *
     * @return bool True if the process was successful, false otherwise.
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM CLIENTS WHERE account_number = ?";

        $stmt = $this->dbUtility->prepare(
            $sql,
            [$this->getAccountNumber()]
        );

        $this->dbUtility->execute($stmt);

        return parent::delete();
    }

    /**
     * Retrieve a client by their account number from the database.
     *
     * @param DatabaseUtility $dbUtility The database utility object.
     * @param int $accountNumber The account number of the client to retrieve.
     * @return Client|null The client object if found, null otherwise.
     */
    public static function getClientByAccountNumber(DatabaseUtility $dbUtility, int $accountNumber): ?Client
    {
        $sql = "SELECT * FROM CLIENTS WHERE account_number = ?";
        $stmt = $dbUtility->prepare($sql, [$accountNumber]);
        $result = $dbUtility->execute($stmt);

        if ($result->num_rows == 1) {
            $row = $dbUtility->getmysqliResultAsArray($result);

            $user = parent::getUserById($dbUtility, $row[0]["user_id"]);
            return new Client(
                $dbUtility,
                $row[0]["account_number"],
                $row[0]['user_id'],
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
                $user->getPhone(),
                $row[0]["pin_code"],
                $row[0]["account_balance"]
            );
        }

        return null;
    }

    /**
     * Retrieve a client by their first and last name from the database.
     *
     * @param DatabaseUtility $dbUtility The database utility object.
     * @param string $firstName The first name of the client to retrieve.
     * @param string $lastName The last name of the client to retrieve.
     * @return Client|null The client object if found, null otherwise.
     */
    public static function getClientByName(DatabaseUtility $dbUtility, string $firstName, string $lastName): ?Client
    {
        $user = parent::getUserByName($dbUtility, $firstName, $lastName);

        $sql = "SELECT * FROM CLIENTS WHERE user_id = ?";
        $stmt = $dbUtility->prepare($sql, [$user->getUserId()]);
        $result = $dbUtility->execute($stmt);

        if ($result->num_rows == 1) {
            $row = $dbUtility->getmysqliResultAsArray($result);

            return new Client(
                $dbUtility,
                $row[0]["account_number"],
                $row[0]['user_id'],
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
                $user->getPhone(),
                $row[0]["pin_code"],
                $row[0]["account_balance"]
            );
        }

        return null;
    }

    /**
     * Update a specific field of the client in the database.
     *
     * @param string $field The field to update.
     * @param mixed $value The new value for the field.
     * @return bool True if the update was successful, false otherwise.
     */
    private function updateField(string $field, $value): bool
    {
        $sql = "UPDATE CLIENTS SET $field = ? WHERE account_number = ?";
        $stmt = $this->dbUtility->prepare($sql, [$value, $this->getAccountNumber()]);
        return $this->dbUtility->execute($stmt);
    }

    /**
     * Update the client's PIN code.
     *
     * @param string $pinCode The new PIN code.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updatePinCode(string $pinCode): bool
    {
        $this->setPinCode($pinCode);
        return $this->updateField('pin_code', $this->getPinCode());
    }

    /**
     * Update the client's account balance.
     *
     * @param float $accountBalance The new account balance.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateAccountBalance(float $accountBalance): bool
    {
        $this->setAccountBalance($accountBalance);
        return $this->updateField('account_balance', $this->getAccountBalance());
    }

    /**
     * Verify if the provided PIN code matches the stored hashed PIN code.
     *
     * @param string $pinCode The PIN code to verify.
     * @return bool True if the PIN codes match, false otherwise.
     */
    public function isPasswordMatch(string $pinCode): bool
    {
        return password_verify($pinCode, $this->pinCode);
    }
    /**
     * Withdraws a specified amount from the account.
     *
     * @param float $amount The amount to be withdrawn.
     * @return bool True if withdrawal is successful, false otherwise.
     */
    public function withdraw(float $amount): bool
    {
        if ($amount < 0) {
            return false; // Negative amount cannot be withdrawn
        }

        if ($this->accountBalance >= $amount) {
            return $this->updateAccountBalance($this->accountBalance - $amount);
        }

        return false; // Insufficient funds for withdrawal
    }

    /**
     * Deposits a specified amount into the account.
     *
     * @param float $amount The amount to be deposited.
     */
    public function deposit(float $amount)
    {
        if ($amount > 0) {
            return $this->updateAccountBalance($this->accountBalance + $amount);
        }

        return false; //// Negative amount cannot be deposit
    }
}
