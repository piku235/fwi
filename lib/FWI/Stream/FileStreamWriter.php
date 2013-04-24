<?php
require_once 'InterfaceStreamWriter.php';
require_once 'FileOutputStream.php';
require_once LIB_DIR . 'FWI/Exception/OverwriteException.php';

class FileStreamWriter implements InterfaceStreamWriter 
{
	/**
	 * @var bool
	 */
	private $overwrite = false;
	
	/**
	 * Konstruktor
	 * 
	 * @param  bool $overwrite Czy nadpisać jeżeli wystąpi konflikt? (opcjonalne)
	 * @return void
	 */
	public function __construct($overwrite = false)
	{
		$this->overwrite = $overwrite;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Jungi\Bundle\ForumBundle\Http\Downloader\Stream\InterfaceStreamWriter::writeStream()
	 */
	public function writeStream($path)
	{
		if (file_exists($path) && !$this->overwrite) {
			throw new OverwriteException("Nie można nadpisać istniejącego pliku!");
		}
		
		return new FileOutputStream($path);
	}
}