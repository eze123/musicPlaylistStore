<?php
namespace MyApp\Http\Controllers;

use Illuminate\Http\Request;
use MyApp\Http\Models\Album;

/**
 * Facilitates transactions, interactions between web routes and the Album Model
 *
 * @author Eze Onukwube <ezeuchey2k@gmail.com>
 */
class AlbumController
{
	protected $requestGenre;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($genre = null)
    {
       isset($genre) ? $this->requestGenre = $genre : $this->requestGenre = null;
    }

	/**
     * Show the albums available in the application's  genre dashboard.
     *
     * @return \MyApp\Http\Models\Album
     */
	public function getAlbum()
	{
		$album = new Album;
		return $album->getAlbums($this->requestGenre);
	}
	
	public function getGenreAlbum()
	{
		$album = new Album;
		echo $album->getGenreAlbum($this->requestGenre);
	}
	
	public function addAlbum()
	{
		$album = new Album;
		echo $album->addAlbum($this->requestGenre);
	}
}