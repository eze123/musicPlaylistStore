<?php
namespace MyApp\Http;

use PDO;
use MyApp\Http\DBConnection;

/**
 * Creates and facilitates the database connection
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class DB{
    
    /**
     * @var object
     * mysql connection instance
     */
    private static $instance = null;

    /**
     * @var PDO
     */
    private $dbconnect = null;

    /**
    * Enables parameterized query connection to database via PDO
    *
    * @throws benign PDOException if connection attempt fails
    */
    public function __construct() {

        try {
            $aryConnectionParms = new DBConnection();
            $this->dbconnect = new PDO("mysql:host={$aryConnectionParms->localhost['host']};dbname={$aryConnectionParms->localhost['dbname']}", $aryConnectionParms->localhost['username'], $aryConnectionParms->localhost['password']); 
            /** Set common attributes **/
            $this->dbconnect->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
			
			$status = $this->dbconnect->getAttribute(PDO::ATTR_CONNECTION_STATUS);
			
            return $this->dbconnect;
        }
        catch (PDOException $e) {

            echo "Connection attempt has failed ... Please contact the Administrator";//echo 'ERROR: ' . $e->getMessage();
        }
    }
    
    public function getDb() {
        if ($this->$dbconnect instanceof PDO) {
                return $this->dbconnect;
        }
    }
	
	/**
    * @return PDO instance
    */
    public static function getInstance() {
        if(!isset(self::$instance)) {
            self::$instance = new DB();
        }
            return self::$instance->dbconnect;
    }
   
}