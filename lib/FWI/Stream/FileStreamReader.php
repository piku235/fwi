<?php
require_once 'InterfaceStreamReader.php';
require_once 'FileInputStream.php';

class FileStreamReader implements InterfaceStreamReader 
{
	/**
	 * (non-PHPdoc)
	 * @see \Jungi\Bundle\ForumBundle\Http\Downloader\Stream\InterfaceStreamReader::readStream()
	 */
	public function readStream($path)
	{
		return new FileInputStream($path);
	}
}