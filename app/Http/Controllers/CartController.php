<?php
namespace MyApp\Http\Controllers;

use MyApp\Http\Models\Cart;

/**
 * Facilitates the storing, retrieving, and modifying of items placed in a user's cart. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class CartController
{
	private $itemQuantity;
	
	private $aryParameters;
	
	public function __construct(array $parameters = null)
	{
		$this->itemQuantity = isset($parameters) ? count($parameters): null;
		
		$this->aryParameters = $parameters;
	}
	
    /**
     * Passes selected item to user's Cart Model.
     * Consolidates multiple selected products, albums into a single aggregate 
	 * with totaled quantities and prices.
     * @return void
     */	
	public function addToCart()
	{		
		$checkDuplicate = [];
		$objfindDuplicate = new \stdClass();
		$findDuplicate = [];
		$index = 0;
		foreach($this->aryParameters as $item){
			$checkDuplicate[$index][0] = $item["title"];
			$checkDuplicate[$index][1] = $item["year"];
			$checkDuplicate[$index][2] = $item["price"];
			if($item["title"] == $checkDuplicate[$index][0]){
				$objfindDuplicate->title = $item["title"];
				$objfindDuplicate->year = $item["year"];
				$objfindDuplicate->price = isset($objfindDuplicate->price)? floatval($objfindDuplicate->price) + floatval($item["price"]) : floatval($item["price"]);
				$objfindDuplicate->quantity = $item["quantity"];
				$objfindDuplicate->genre = $item["genre"];
				array_push($findDuplicate, $objfindDuplicate);
			}
			
			$item++;
			
			$cart = new Cart;
		    $cart->addToCart($findDuplicate);
		}
		
	}
	
	public function removeFromCart()
	{

	}
	
	public function getCart()
	{
		$cart = new Cart;
		/* Make sure the user making this transaction is authenticated */
		if($cart->verifyAuthentication())
			$cart->retrieveCart();
		else
			echo "You need to be logged in to perform this operation";
	}
}

