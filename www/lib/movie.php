<?php 
/**
* Movie is a class that holds data relating to a movie.
*/
class Movie
{
	private $title;
	private $description;
	private $running_time;
	private $watch_links;
	private $year;
	private $genre;
	private $poster_link;
	private $class;
	private $awards;

	function __construct($title, $description = null, $watch_links = null,
		$running_time = null, $year = null, $genre = null, $poster_link = null, 
		$class = null, $awards = null)
	{
		$this->title = $title;
		$this->description = $description;
		$this->watch_links = $watch_links;
		$this->running_time = $running_time;
		$this->year = $year;
	}

	public function getTitle()
	{
		$t = $this->title;
		return $t;
	}

	public function getDescription()
	{
		$d = $this->description;
		return $d;
	}

	public function getWatchLinks()
	{
		$wl = $this->watch_links;
		return $wl;
	}

	public function getRunningTime()
	{
		$rt = $this->running_time;
		return $rt;
	}

	public function getYear()
	{
		$y = $this->year;
		return $y;
	}

}
 ?>