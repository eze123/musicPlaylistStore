<?php
namespace MyApp\Http\Models;

use MyApp\Http\Models\Model;
use MyApp\Http\Models\Album;
use PDO;

/**
 * Breaks down the order purchase into distinct product components. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class OrderProducts extends Model
{
	protected $lastInsertedId; 
	protected $newOrderId;
	protected $aggregateProduct;
	protected $orderParameters;
	
    /**
     * @param int $lastInsertedId The id (primary key) of the recent purchase in the Order table.
     * @param int $newOrderId The id of the recent purchase in the Order table.
     * @param array $orderParameters This contains a full accounting of the purchase items made
     * @param array $aggregateProduct Consolidates duplicate/multiple products to obtain summed/total prices and quantities
	 */
	public function __construct($lastInsertedId, $newOrderId, $orderParameters, $aggregateProduct = null)
	{
		parent::__construct();
		
		$this->lastInsertedId = $lastInsertedId;
		$this->newOrderId = $newOrderId;
		$this->orderParameters = $orderParameters;
		isset($aggregateProduct) ? $this->aggregateProduct = $aggregateProduct : $this->aggregateProduct = null;
	}
	
	/**
	 * Adds the multiple product album purchases into the user account
	 * @param array $orderProducts
	 * @return void
	 */
	public function addPurchaseDetails(array $orderProducts)
	{
		foreach($orderProducts as $product){
			
			$album = new Album;
			$albumFID = $album->getId($product["title"], $product["year"]);
			
		    $stmt = "INSERT INTO order_products(order_FID, orderid, quantity, totalprice, album_FID, user_FID)VALUES(:order_FID, :orderid, :quantity, :totalprice, :album_FID, :user_FID)";
		    $stmt = $this->conn->prepare($stmt);
		
		    $stmt->bindParam(':order_FID', $this->lastInsertedId);
		    $stmt->bindParam(':orderid', $this->newOrderId);
			$stmt->bindParam(':quantity', $product["quantity"]);
			$stmt->bindParam(':totalprice', $product["price"]);
			$stmt->bindParam(':album_FID', $albumFID);
			$stmt->bindParam(':user_FID', $_SESSION['userId']);
			
			$stmt->execute();
		}
	}
	
	/**
	* Adds the single product album purchases into the user account
	*
	* @return void
	*/
	public function addSinglePurchaseDetails()
	{
		$singleProduct = [];
		foreach($this->orderParameters as $orders){
			$found = 0;
			foreach($this->aggregateProduct as $product){
				if($product["title"] == $orders["title"])
					$found++;
			}
			
			if($found == 0)
			    $singleProduct[] = $orders;//add to single
		}
		
		$this->addPurchaseDetails($singleProduct);
	}
	
}