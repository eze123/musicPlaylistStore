<?php
namespace MyApp\Http\Controllers;

use MyApp\Http\Models\Orders;

class OrderController
{
	private $aryParameters;
	
	public function __construct(array $parameters = null)
	{

		$this->aryParameters = $parameters;
	}
	
	public function purchase()
	{		
		$orders = new Orders($this->aryParameters);
		/* Make sure the user making this transaction is authenticated */
		if($orders->verifyAuthentication()){
			//verify payment system: credit/debit/cheque/bank/
			$orders->addPurchase();
		}
			
	}
	
}