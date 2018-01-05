<?php
namespace MyApp\Http\Models;

use MyApp\Http\DB;

/**
 * Base model that acts as a proxy for connecting to the underlying database. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class Model
{	
	/**
     * mysql connection instance
	 * @return PDO
     */
    private static $instance = null;
	
	protected $conn;
	
	public function __construct()
	{
		$this->conn = DB::getInstance();
	}
	
    public static function getInstance() {
            if(!isset(self::$instance)) {
                self::$instance = new DB();
            }
            return self::$instance->dbconnect;
        }

    public function closeConnection() {

        $this->dbconnect = null;
    }

}