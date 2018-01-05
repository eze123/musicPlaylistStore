<?php
namespace MyApp\Http\Controllers\Tests;

use PHPUnit\FrameWork\TestCase;
use MyApp\Http\Controllers\PlaylistController;
use MyApp\Http\Models\Playlist;

class PlaylistControllerTest extends TestCase
{
	private $playlist;
	private $playlistModel;
	
	protected function setUp()
	{
		$this->playlist = new PlaylistController();
		
		$this->playlistModel = new Playlist();
        //$this->playlistModel->getAll();
	}
	
	public function testGetPlayList_From_Stub()
	{
		//Set up a fake playlist database row
        $playlistRow = array(
            //'id' => 1,
            'title' => 'Rock'
         );
		 
		//Create the stub for the PlaylistController class
		$playlist_stub = $this->getMockBuilder('PlaylistController')
		    ->setMethods(['getPlayList'])
		     ->getMock(); // object to mock
			 
		//Configure the stub
		$playlist_stub->expects($this->once()) // number of times to be called
		         ->method('getPlayList') // method name
				 //->with($playlistModel) // parameters that are expected
				 ->will($this->returnValue($playlistRow)); // return value ->will($this->returnValue($this->playlistModel));
				 
		//$this->assertEquals($this->playlistModel, $this->playlist->getPlayList());
		$this->assertContains("Jazz", $playlist_stub->getPlayList());
	}
	
	public function testGetPlayList_Execute_Class_From_Test()
	{
		//Set up a fake playlist database row
        /*$playlistRow = array(
            //'id' => 1,
            'title' => 'Rock'
         );*/
		 
		 $desired_response = array(
            //'id' => 1,
			'0' => array(
			    'title' => array(
			        "Rock"
			    )
			),
			'1' => array(
			    'title' => array(
			        "Jazz"
			    )
			)
         );
		 
		 //$actualPlaylist = $this->playlistModel->getAll();
		//Call the actual method  from the PlaylistController class
		$actual_response = $this->playlist->getPlayList();
        $this->assertEquals($desired_response, $actual_response);//$this->assertEquals($desired_response, $actual_response);
		
		//this is a somewhat flawed unit test because it is dependent/contingent upon an external dependency of the unit ie $this->playlistModel->getAll()
		//truth is, as a controller it is more of a proxy to the playlist model object and doesn't actually do much by itself
		
        /*$actualPlaylist = $this->playlistModel->getAll();
		$this->assertEquals($playlistRow, $actualPlaylist);*/
	}
	
	protected function tearDown()
	{
		$this->playlist = null;
	}
}


