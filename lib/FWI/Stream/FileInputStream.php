<?php
require_once 'FileStream.php';
require_once 'InterfaceInputStream.php';
require_once LIB_DIR . 'FWI/Exception/StreamException.php';

class FileInputStream extends FileStream implements InterfaceInputStream 
{
	/**
	 * Ilość przeczytanych bajtów
	 * 
	 * @var int
	 */
	private $readBytes = 0;
	
	/**
	 * Dane o strumieniu
	 * 
	 * @var array
	 */
	private $metaData = array();
	
	/**
	 * Konstruktor
	 * 
	 * @param  string $path Ścieżka
	 * @return void
	 */
	public function __construct($path)
	{
		$this->handler = @fopen($path, 'r');
		if (!$this->handler) {
			throw new StreamException("Wystąpił błąd \"$php_errormsg\" podczas próby otwarcia ścieżki \"$path\"");
		}
		
		// Szczegłówne dane o zasobie
		$this->metaData = stream_get_meta_data($this->handler);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InterfaceInputStream::read()
	 */
	public function read($length = 0)
	{
		$this->throwIfClosed();
		if (feof($this->handler)) {
			return self::EOF;
		} else if (!$length) {
			$length = $this->metaData['unread_bytes'] - $this->readBytes;
		}
		
		$this->readBytes += $length;
		return fread($this->handler, $length);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InterfaceInputStream::size()
	 */
	public function size()
	{
		return (int) $this->metaData['unread_bytes'];
	}
}