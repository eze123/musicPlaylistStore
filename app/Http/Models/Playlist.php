<?php
namespace MyApp\Http\Models;

use PDO;
use \MyApp\Http\Models\Model;

/**
 * Playlist model object. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class Playlist extends Model
{
    public function __construct()
	{
		parent::__construct();	

	}
	
	public function getAll()
	{
		$stmt = "SELECT title FROM genre";
        $stmt = $this->conn->prepare($stmt);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result, JSON_FORCE_OBJECT);
	}
	
	public function setPlaylist()
	{
		
	}

}