<?php
namespace MyApp\Http\Models;
if(!isset($_SESSION))
    session_start();

use MyApp\Http\Models\Model;
use MyApp\Http\Models\Album;
use MyApp\Http\Models\User;

/**
 * Cart model object. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class Cart extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	*@param array 
	*@return bool 
	*/
	public function addToCart(array $cartitem)
	{		
		$addStatus = true;
		foreach($cartitem as $cart){
			$stmt = $this->conn->prepare("INSERT INTO cart(title, price, quantity, album_FID, genre_FID, user_FID)VALUES(:title, :price, :quantity, :album_FID, :genre_FID, :user_FID)");
			
			$stmt->bindParam(':title', $cart->title);
			$stmt->bindParam(':price', $cart->price);
			$stmt->bindParam(':quantity', $cart->quantity);
			
			$album = new Album();
			$genreId = $album->getGenreId($cart->genre);
			$stmt->bindParam(':genre_FID', $genreId);
			
			$albumId = $album->getId($cart->title, $cart->year);
			$stmt->bindParam(':album_FID', $albumId);
			
			$stmt->bindParam(':user_FID', $_SESSION['userId']);
			
			if(!$stmt->execute()) $addStatus = false;
		}
		
		return $addStatus;//boolean
	}
	
	public function retrieveCart()
	{
		$stmt = "SELECT title, price, quantity, album_FID From cart WHERE :user_FID = user_FID";
		$stmt = $this->conn->prepare($stmt);
		$stmt->bindParam(':user_FID', $_SESSION['userId']);
		$stmt->execute();
		$result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$aryCart = [];
		foreach($result as $cart){
			$cartObject = new \stdClass();
			$cartObject->title = $cart['title'];
			/** no user supplied data used in query, therefore no risk of SQL injection, consequently no need for PDO binds **/
			$stmt2 = $this->conn->query("SELECT year FROM album WHERE id = '".$cart['album_FID']."'");
			$result2 = $stmt2->fetchAll();
			foreach($result2 as $year)
			    $cartObject->year = $year['year'];
			$cartObject->price =  $cart['price'];
			$cartObject->quantity =  $cart['quantity'];
			array_push($aryCart, $cartObject);
		}
		echo json_encode($aryCart);
	}
	
	public function verifyAuthentication()
	{
		$user = new User;
		return $user->isActiveSession();
	}
}