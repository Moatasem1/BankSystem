<?php

namespace App\Helpers;

use mysqli;
use mysqli_stmt;
use mysqli_result;
use Exception;

/**
 * DatabaseUtility
 * This class is used to handle database operations.
 * 
 * Attributes:
 * mysqli $dbConn : Stores the database connection.
 * 
 * Methods:
 * __construct(string $serverName, string $databaseName, string $userName, string $password) : Initializes the object with a new MySQLi database connection.
 * prepare(string $sql, array $params = []) : Prepares an SQL statement for execution.
 * execute(mysqli_stmt $stmt, int &$insertedId = null) : Executes a prepared statement.
 * getmysqliResultAsArray(mysqli_result $result) : Fetches the result of a query as an array.
 * __destruct() : Closes the database connection when the object is destroyed.
 * 
 * Usage:
 *
 * Example usage:
 * 
 * // Create a new instance of DatabaseUtility with a database connection
 * $dbUtility = new DatabaseUtility("localhost", "username", "password","database");
 * 
 * // Prepare and execute a simple SELECT statement
 * $sql = "SELECT * FROM users WHERE id = ?";
 * $stmt = $dbUtility->prepare($sql, [123]);
 * $result = $dbUtility->execute($stmt);
 * 
 * // Fetch the result as an array
 * $rows = $dbUtility->getmysqliResultAsArray($result);
 * 
 * // Process the retrieved data
 */


class DatabaseUtility
{
    /**
     * @var mysqli The MySQLi database connection.
     */
    private mysqli $dbConn;

    /**
     * DatabaseUtility constructor.
     *
     * Initializes the object with a new MySQLi database connection.
     *
     * @param string $serverName The server name where the database is hosted.
     * @param string $databaseName The name of the database.
     * @param string $userName The username used to connect to the database.
     * @param string $password The password used to connect to the database.
     *
     * @throws Exception If the connection to the database fails.
     */

    public function __construct(string $serverName,  string $userName, string $password, string $databaseName)
    {
        $this->dbConn = new mysqli($serverName, $userName, $password, $databaseName);

        if ($this->dbConn->connect_error) {
            throw new Exception("Connection failed: " . $this->dbConn->connect_error);
        }
    }

    /**
     * Determines the types of parameters in an array for binding with prepared statements.
     *
     * @param array $params An array of parameters.
     *
     * @return string A string representing the types of parameters.
     * @throws Exception If an unsupported data type or null value is encountered.
     */
    private function determineTypes(array $params): string
    {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param)) {
                $types .= 's';
            } else {
                throw new Exception("Unsupported data type or null value.");
            }
        }
        return $types;
    }

    /**
     * Prepares an SQL statement for execution.
     *
     * @param string $sql The SQL query, replace values pramater with ?
     * @param array $params An optional array of parameters for prepared statement.
     *
     * @return mysqli_stmt The prepared statement.
     * @throws Exception If the statement preparation fails.
     */
    public function prepare(string $sql, array $params = []): mysqli_stmt
    {
        $stmt = $this->dbConn->prepare($sql);

        if ($stmt) {
            // Get type of parameter as string to use it as parameter in bind_param.
            $types = $this->determineTypes($params);

            // Bind parameters if provided.
            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            return $stmt;
        } else {
            throw new Exception("Failed to prepare statement: " . $this->dbConn->error);
        }
    }

    /**
     * Executes a prepared statement.
     *
     * @param mysqli_stmt $stmt The prepared statement.
     * @param int|null $insertedId An optional reference to store the last inserted ID.
     *
     * @return mysqli_result | bool The result of the execution.
     * @throws Exception If the statement execution fails.
     */
    public function execute(mysqli_stmt $stmt): mysqli_result | bool
    {
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
    }

    public function getLastInsertedId(): int
    {
        return $this->dbConn->insert_id;
    }

    /**
     * Fetches the result of a query as an array.
     *
     * @param mysqli_result $result The MySQLi result object.
     *
     * @return array An array representing the result set.
     */
    public function getmysqliResultAsArray(mysqli_result $result): array
    {
        $rows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        return $rows;
    }

    /**
     * DatabaseUtility destructor.
     *
     * Closes the MySQLi database connection when the object is destroyed.
     */

    public function __destruct()
    {
        $this->dbConn->close();
    }
}
