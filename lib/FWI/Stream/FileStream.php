<?php
require_once LIB_DIR . 'FWI/Exception/StreamException.php';

abstract class FileStream 
{
	/**
	 * @var bool
	 */
	protected $closed = false;
	
	/**
	 * @var resource
	 */
	protected $handler = null;
	
	/**
	 * Zamyka połączenie strumienia
	 *
	 * @return void
	 */
	public function close()
	{
		if ($this->closed) {
			return;
		}
	
		$this->closed = true;
		fclose($this->handler);
	}
	
	/**
	 * Destrkuktor
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this->close();
	}
	
	/**
	 * Wyrzuca wyjątek gdy plik jest zamknięty
	 * 
	 * @return void
	 * @throws StreamException
	 */
	protected function throwIfClosed()
	{
		if (!$this->closed) {
			return;
		}
		
		throw new StreamException("Nie można przeprowadzić żadnych operacji na pliku, ponieważ jest on zamknięty!");
	}
}
