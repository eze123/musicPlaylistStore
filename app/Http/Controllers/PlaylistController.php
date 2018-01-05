<?php
namespace MyApp\Http\Controllers;

use MyApp\Http\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController //extends Controller
{
	protected $currentRequest;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request = null)
    {
       isset($request) ? $this->currentRequest = $request : $currentRequest = null;
    }
	
	public function findById(Playlist $id)
	{
		//$employee = $employeeRepository->find($id);
	}
	
	public function findAll()//public function getPlayList()
	{
		$playlist = new Playlist;
		return $playlist->getAll();
	}
	
	public function removePlayList($thisGenre)
	{
		
	}
	
}