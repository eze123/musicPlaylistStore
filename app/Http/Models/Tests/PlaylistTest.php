<?php
namespace MyApp\Http\Models\Tests;

use PHPUnit\Framework\TestCase;
use MyApp\Http\Models\Playlist;

class PlaylistTest extends TestCase
{
	private $playlist;
	
	protected function setUp()
	{
		$this->playlist = new Playlist;
	}
	public function testGetAll_From_Stub()
	{
		//Create the stub for the PlaylistController class
		$playlist_stub = $this->getMockBuilder('Playlist')
		    ->setMethods(['getAll'])
		     ->getMock(); // object to mock
			 
		//Configure the stub
		$playlist_stub->expects($this->once()) // number of times to be called
		    ->method('getAll') // method name
		    //->with($playlistModel) // parameters that are expected
		    ->will($this->returnValue(['0'=>['title' => 'Rock']]));
			
		//$expected_array = ['0'=>['title' => 'Rock']];
		$expected_array = array('0'=>['title' => 'Rock']);
			
		$this->assertEquals($expected_array, $playlist_stub->getAll());
	}
	
	public function testGetAll_Execute_Class_From_Test()
	{
		$desired_response = array(
			'0' => array(
			    'title' => "Rock"
			),
			'1' => array(
			    'title' => "Jazz"
			)
			//added to pass test. Uncomment in order to have a passing test
			,
			'2' => array(
			    'title' => "Metal"
			),
			'3' => array(
			    'title' => "Alternative"
			),
			'4' => array(
			    'title' => "Disco"
			),
			'5' => array(
			    'title' => "Blues"
			),
			'6' => array(
			    'title' => "Latin"
			),
			'7' => array(
			    'title' => "Reggae"
			),
			'8' => array(
			    'title' => "Pop"
			),
			'9' => array(
			    'title' => "Classical"
			)
        );
		
		$actual_response = $this->playlist->getAll();
        $this->assertEquals($desired_response, $actual_response);
	}
}



