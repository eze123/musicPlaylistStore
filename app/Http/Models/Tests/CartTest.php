<?php
namespace MyApp\Http\Models\Test;

use PHPUnit\Framework\TestCase;
use MyApp\Http\Models\Cart;

class CartTest extends TestCase
{
	protected $cart;
	
	protected function setUP()
	{
	    $this->cart = new Cart();	
	}
	public function testAddToCart_Stub()
	{		
		$Expected_cartItems = ["albumName" => "Thriller", "Quantity" => "1", "Price" => "$5.00"];
		
		$stub = $this->createMock(Cart::class);
		$stub->method('addToCart')
		     ->will($this->returnValue('foo'));
			 
		$this->assertEquals($Expected_cartItems, $stub->addToCart());
	}
	
	public function testAddToCart_Mock()
	{
		$stub = $this->createMock(Cart::class);
		$stub->expects($this->once())
		     ->method('addToCart')
			 ->with(["albumName" => "Unforgettable", "Quantity" => "2", "Price" => "$6.99"])
			 ->will($this->returnValue(true));
			 
		$this->assertEquals(false, $stub->addToCart());
	}
	
	public function testAddToCart_testReturnArgument_Stub()
	{
		$Expected_cartItems = ["albumName" => "Thriller", "Quantity" => "1", "Price" => "$5.00"];
		
		$stub = $this->createMock(Cart::class);
		$stub->expects($this->any())
		     ->method('addToCart')
		     //->with(["albumName" => "Unforgettable", "Quantity" => "2", "Price" => "$6.99"])
			 ->will($this->returnArgument(0));
			 
		$this->assertEquals($Expected_cartItems, $stub->addToCart(["albumName" => "Unforgettable", "Quantity" => "2", "Price" => "$6.99"]));
	}
}
