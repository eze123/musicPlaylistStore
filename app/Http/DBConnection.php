<?php
namespace MyApp\Http;

/**
 * The class is basically a configuration file that collates login credentials to the database
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class DBConnection
{
    /**
     * Database Connection Settings
    */
    public $localhost = array(
    	'driver' => 'mysql',
    	'host' => '***',
    	'dbname' => 'musicstore',
    	'username' => '***',
    	'password' => '***'
	);
	
	/**
    * @return array The valid database login profile
    */
    public function __construct() {
        return $this->localhost;
    }
}