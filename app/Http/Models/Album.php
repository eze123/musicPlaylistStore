<?php
namespace MyApp\Http\Models;

use MyApp\Http\Models\Model;
use PDO;

/**
 * Album model object. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class Album extends Model
{
	protected $albumGenre;
	
    public function __construct()
	{
		parent::__construct();	

	}
	
	/**
     * Retrieves the total number of records in the album table by music genre
     * @var PDO $conn
     * @return integer
     */
    private function getAlbumTotalByGenre(){
        $stmt = "SELECT * FROM album WHERE genre_FID = :genre_FID";
        $stmt = $this->conn->prepare($stmt);
		$stmt->bindParam(':genre_FID', $this->albumGenre);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($result);
    }
	
	public function getGenreId($genre)
	{
	    $stmt = "SELECT id FROM genre WHERE title = :title";
        $stmt = $this->conn->prepare($stmt);
		$stmt->bindParam(':title', $genre);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $album)
			$genreId = $album["id"];
		
		$this->albumGenre = $genreId;
		return isset($genreId) ? $genreId : null;
	}
	
	public function getId(string $title, int $year)
	{
		$stmt = "SELECT id FROM album WHERE title = :title and year = :year";
		$stmt = $this->conn->prepare($stmt);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':year', $year);
		
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result['id'];
	}
	
	public function getAlbums($genre)
	{
		$genreId = Album::getGenreId($genre);
		$stmt = "SELECT title, artist FROM album WHERE genre_FID = :genre_FID";
		$stmt = $this->conn->prepare($stmt);
		$stmt->bindParam(':genre_FID', $genreId);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
        return json_encode($result, JSON_FORCE_OBJECT);
	}

	 /**
     * Retrieves records in the album table, customized for jQuery Datatables.
     * @var PDO $conn
     * @var $_REQUEST $start
     * @var $_REQUEST $length
     * @return json
     */
    public function getGenreAlbum($genre)
	{
        $genreId = Album::getGenreId($genre);
		$strStart = $_REQUEST["start"];
        $strLength = $_REQUEST["length"];
        
        $sqlStatement = "SELECT title, artist, year, price FROM album WHERE genre_FID = :genre_FID ORDER BY id LIMIT $strLength OFFSET $strStart";
            
        $pdoStatement = $this->conn->prepare($sqlStatement);
		$pdoStatement->bindParam(':genre_FID', $genreId);
		$pdoStatement->execute();
		/* Fetch the entire row(s) */
	    $pdoResult = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
        
        $object = new \stdClass();
        $object->draw = (int)$_REQUEST["draw"];
        $object->recordsTotal = Album::getAlbumTotalByGenre();
        $object->recordsFiltered = Album::getAlbumTotalByGenre();
        
        for($k=0; $k<count($pdoResult); $k++){
            $a = array();
			foreach($pdoResult[$k] as $key=>$val)
				array_push($a, $val);
			$object->data[] = $a;
		}
		
		$out = json_encode($object);

		return $out;//echo $out;
        
    }
	
	public function addAlbum($genre)
	{
		$genreId = Album::getGenreId($genre);
		$stmt = "INSERT INTO album (title, artist, year, price, genre_FID) VALUES (:title, :artist, :year, :price, :genre_FID)";
		$stmt = $this->conn->prepare($stmt);
		$stmt->bindParam(':title', $_POST['title']);
		$stmt->bindParam(':artist', $_POST['artist']);
		$stmt->bindParam(':year', $_POST['year']);
		$stmt->bindParam(':price', $_POST['price']);
		$stmt->bindParam(':genre_FID', $genreId);
		$stmt->execute();
		return json_encode($stmt->rowCount(), JSON_FORCE_OBJECT);
	}

}