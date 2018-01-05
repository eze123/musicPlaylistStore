<?php
namespace MyApp\Http\Models;

use MyApp\Http\Models\Model;
use MyApp\Http\Models\User;
use MyApp\Http\Models\OrderProducts;
use PDO;

/**
 * Order model object that handles transactions made for albums. 
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class Orders extends Model
{
	private $lastOrderid;
	
	private $orderParameters;
	
	public function __construct(array $parameters = null)
	{
		parent::__construct();
		
		$this->orderParameters = $parameters;
		
		$this->getLastOrderId();
	}
	
	private function getLastOrderId()
	{
		$stmt = $this->conn->query("SELECT orderid FROM orders ORDER BY id DESC LIMIT 1");
		if($stmt->rowCount() > 0){
		    foreach($stmt as $orderid)
		        $this->lastOrderid = $orderid;
		}
		else
			$this->lastOrderid = 0;
	}
	
	
	private function cancelPurchase()
	{
		
	}
	
	/**
	* Aggregates multiple purchases made for the same product album
	* @array $flaggedOccurence
	* @return array $flaggedOccurence with prices and quantities consolidated
	*/
	private function aggregateTotals(array $flaggedOccurence)
	{
		 foreach($flaggedOccurence as &$aggregate){
			 foreach($this->orderParameters as $orders){
				 if($aggregate["title"] == $orders["title"]){
					
					isset($aggregate["price"])?
					    $aggregate["price"] = floatval($aggregate["price"]) + floatval($orders["price"]):
						    $aggregate["price"] = floatval($orders["price"]);
							
					isset($aggregate["quantity"])?
					    $aggregate["quantity"] = intval($aggregate["quantity"]) + intval($orders["quantity"]):
						    $aggregate["quantity"] = intval($orders["quantity"]);
							
				 }
			 }
		 }
		 
		 return $flaggedOccurence;
	}
	
	public function verifyAuthentication()
	{
		$user = new User;
		return $user->isActiveSession();
	}
	
	public function addPurchase()
	{
		if(count($this->orderParameters) > 0){
			$iteratorIndex = 0;
			$isFound = 0;
			$checkDuplicate = [];
			$iterator = [];
			foreach($this->orderParameters as $order){
				
                foreach($iterator as $iterate){
					if($iterate["title"] == $order["title"]){
						echo "found".$iterate["title"];
						$checkDuplicate[$isFound]["title"] = $order["title"];
					    $checkDuplicate[$isFound]["year"] = $order["year"];
						$isFound++;
					}
				}
				
			    $stmt = $this->conn->prepare("INSERT INTO orders(orderid, orderdate, user_FID)VALUES(:orderid, :orderdate, :user_FID)");
			    $today = date("Y-m-d H:i:s");
				$newOrderId = floatval($this->lastOrderid) + 1;
			    $stmt->bindParam(':orderid', $newOrderId);
			    $stmt->bindParam(':orderdate', $today);
			    $stmt->bindParam(':user_FID', $_SESSION['userId']);

				
				array_push($iterator, $order);
			}
			
			if($stmt->execute()){
				$orderProducts = new OrderProducts($this->conn->lastInsertId(), $newOrderId, $this->orderParameters, $this->aggregateTotals($checkDuplicate));
				$orderProducts->addPurchaseDetails($this->aggregateTotals($checkDuplicate));
				$orderProducts->addSinglePurchaseDetails();
			}
		}
		
	}
}



