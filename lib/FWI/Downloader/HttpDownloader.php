<?php
require_once 'Event/DownloadEvent.php';

class HttpDownloader
{
    /**
     * @var array
     */
    protected $options = array();
    
    /**
     * @var InterfaceStreamWriter
     */
    protected $streamWriter = null;
    
    /**
     * @var InterfaceStreamReader
     */
    protected $streamReader = null;
    
    /**
     * @var EventDisptacher
     */
    protected $eventDispatcher = null;
    
    /**
     * Konstruktor
     * 
     * @param  array $options Opcje (opcjonalne)
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->options = array_merge(array(
        	'time_limit' => 1800,
        	'buffer_length' => 16384
        ), $options);
    }
    
    /**
     * Ustawia daną opcję
     * 
     * @param  string $name Nazwa
     * @param  mixed  $val  Wartość
     * @return void
     */
    public function setOption($name, $val)
    {
    	$this->options[$name] = $val;
    }
    
    /**
     * Zwraca daną opcję
     * 
     * @param  string $name Nazwa
     * @return mixed
     */
    public function getOption($name)
    {
    	if (!array_key_exists($name, $this->options)) {
    		return null;
    	}
    	
    	return $this->options[$name];
    }
    
    /**
     * Ustawia dyspozytora odpowiedzialnego za wydarzenia
     * 
     * @param  EventDispatcher $dispatcher Dyspozytor wydarzeń
     * @return void
     */
    public function setEventDispatcher(EventDispatcher $dispatcher)
    {
    	$this->eventDispatcher = $dispatcher;
    }
    
    /**
     * Zwraca dyspozytora wydarzeń
     * 
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
    	return $this->eventDispatcher;
    }
    
    /**
     * Ustawia czytnik strumienia zasobów HTTP
     * 
     * @param  InterfaceStreamReader $reader Czytnik strumienia
     * @return void
     */
    public function setStreamReader(InterfaceStreamReader $reader)
    {
    	$this->streamReader = $reader;
    }
    
    /**
     * Ustawia obiekt, który zapisuje dane ze strumienia wejściowego
     * 
     * @param  InterfaceStreamWriter $writer Obiekt zapisujący
     * @return void
     */
    public function setStreamWriter(InterfaceStreamWriter $writer)
    {
    	$this->streamWriter = $writer;
    }
    
    /**
     * Ściąga podany zasób
     * 
     * @param  string $source Adres do zasobu
     * @param  string $dest   Ścieżka systemowa (z nazwą pliku lub bez)
     * @return void
     */
    public function download($source, $dest)
    {
        ini_set("max_execution_time", $this->options['time_limit']);
        
        // Otowrzenie źródła
    	$inputStream = $this->streamReader->readStream($source);
    	
    	// Wczytanie i zapisanie danych ze źródła
    	$outputStream = $this->streamWriter->writeStream($dest);
    	$readBytes = 0;
    	$startTime = microtime(true);
    	while (InterfaceInputStream::EOF !== ($data = $inputStream->read($this->options['buffer_length']))) {
    		$currTime = microtime(true);
    		$currentBytes = strlen($data);
    		$readBytes += $currentBytes;
    		
    		// Zapisanie do miejsca docelowego danych
    		$outputStream->write($data);
    		
    		// Szybkość transferu
    		$transferSpeed = floor($readBytes / ($currTime - $startTime));
    		
    		// Powiadomienie o wydarzeniu
    		$this->eventDispatcher->exec(new DownloadEvent('download.read', $source, $dest, $readBytes, $inputStream->size(), $transferSpeed));
    	}
    }
}