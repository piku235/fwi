<?php
require_once 'FileStream.php';
require_once 'InterfaceOutputStream.php';
require_once LIB_DIR . 'FWI/Exception/StreamException.php';

class FileOutputStream extends FileStream implements InterfaceOutputStream 
{
	/**
	 * Konstruktor
	 * 
	 * @param  string $path Ścieżka docelowa
	 * @return void
	 */
	public function __construct($path)
	{
		$this->handler = @fopen($path, 'w');
		if (!$this->handler) {
			throw new StreamException("Wystąpił błąd \"$php_errormsg\" poczas próby utworznia pliku \"$path\".");
		}
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Jungi\Bundle\ForumBundle\Http\Downloader\Stream\InterfaceOutputStream::write()
	 */
	public function write($data)
	{
		$this->throwIfClosed();
		fwrite($this->handler, $data);
	}
}