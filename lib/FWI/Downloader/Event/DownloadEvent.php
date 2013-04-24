<?php
require_once LIB_DIR . 'FWI/Event/InterfaceEvent.php';

class DownloadEvent implements InterfaceEvent
{
	/**
	 * @var string
	 */
	private $name = null;
	
	/**
	 * @var int
	 */
	private $readBytes = 0;
	
	/**
	 * @var int
	 */
	private $sourceSize = 0;
	
	/**
	 * @var string
	 */
	private $source = null;
	
	/**
	 * @var string
	 */
	private $dest = null;
	
	/**
	 * @var int
	 */
	private $transferSpeed = null;
	
	/**
	 * Konstruktor
	 * 
	 * @param  string $name 	  	 Nazwa wydarzenia
	 * @param  string $source	  	 Ścieżka do miejsca źródłowego
	 * @param  stirng $dest		  	 Ścieżka do miejsca docelowego
	 * @param  int    $readBytes  	 Ilość wczytanych bajtów
	 * @param  int	  $sourceSize 	 Wielkość źródła
	 * @param  int	  $transferSpeed Szybkość transferu (B/s)
	 * @return void
	 */
	public function __construct($name, $source, $dest, $readBytes, $sourceSize, $transferSpeed)
	{
		$this->name = $name;
		$this->readBytes = $readBytes;
		$this->sourceSize = $sourceSize;
		$this->source = $source;
		$this->dest = $dest;
		$this->transferSpeed = $transferSpeed;
	}
	
	/**
	 * Zwraca nazwę wydarzenia
	 * 
	 * @return void
	 */
	public function getEventName()
	{
		return $this->name;
	}
	
	/**
	 * Czas trwawnia (w ms)
	 * 
	 * @return int
	 */
	public function getTransferSpeed()
	{
		return $this->transferSpeed;
	}
	
	/**
	 * Zwraca ścieżkę do miejsca źródłwego
	 *
	 * @return string
	 */
	public function getSourcePath()
	{
		return $this->source;
	}
	
	/**
	 * Zwraca ścieżkę do miejsca docelowego
	 * 
	 * @return string
	 */
	public function getDestPath()
	{
		return $this->dest;
	}
	
	/**
	 * Zwraca liczbę wczytanych bajtów ze strumienia źródłowego
	 * 
	 * @return int
	 */
	public function getReadBytes()
	{
		return $this->readBytes;
	}
	
	/**
	 * Zwraca rozmiar źródła (w bajtach)
	 * 
	 * @return int
	 */
	public function getSourceSize()
	{
		return $this->sourceSize;
	}
}
