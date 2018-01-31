<?php
namespace Guslists\Db;

use PDO;
use PDOException;

/**
 * Handling database connection
 *
 */
class DbConnect
{

    private $conn;

    public function __construct()
    {
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    public function connect()
    {
        include_once dirname(__FILE__) . '/config.php';
        try {
            // Connecting to mysql database
            //$this->conn = new PDO(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
            $this->conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        // returing connection resource
        return $this->conn;
    }
}

?>