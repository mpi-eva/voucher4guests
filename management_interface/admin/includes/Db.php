<?php
namespace Voucher\ManagementInterface;

use Mysqli;

/**
 * Class Db
 */
class Db
{
    // The database connection
    protected $connection;
    // The database config
    protected $config;

    function __construct($config){
        $this->config = $config;
    }

    /**
     * Connect to the database
     *
     * @return bool|mysqli - false on failure / MySQLi object instance on success
     */
    public function connect()
    {
        // Try and connect to the database
        if (!isset($this->connection)) {

            $config = $this->config;

            $this->connection = new mysqli($config['db_host'], $config['db_user'], $config['db_password'],
                $config['db_base']);
        }

        // If connection was not successful, handle the error
        if ($this->connection === false) {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }

        return $this->connection;
    }

    /**
     * Query the database
     *
     * @param string $query - The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query)
    {
        // Connect to the database
        $connection = $this->connect();

        // Query the database
        $result = $connection->query($query);

        return $result;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param string $query - The query string
     * @return bool|array - False on failure / array Database rows on success
     */
    public function select($query)
    {
        $rows = array();
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Fetch the last error from the database
     *
     * @return string - Database error message
     */
    public function error()
    {
        $connection = $this->connect();

        return $connection->error;
    }

    /**
     * Quote and escape value for use in a database query
     *
     * @param string $value - The value to be quoted and escaped
     * @return string - The quoted and escaped string
     */
    public function quote($value)
    {
        $connection = $this->connect();

        return "'" . $connection->real_escape_string($value) . "'";
    }
}