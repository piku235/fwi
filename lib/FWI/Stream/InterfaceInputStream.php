<?php

interface InterfaceInputStream 
{
	const EOF = -1;
	
	/**
	 * Czyta zawartość ze strumienia
	 * 
	 * @param  int $length Długość w bajtach (opcjonalne)
	 * @return string|EOF
	 */
	public function read($length = 0);
	
	/**
	 * Rozmiar strumienia (w bajtach)
	 * 
	 * @return int
	 */
	public function size();
	
	/**
	 * Zamyka strumień
	 * 
	 * @return void
	 */
	public function close();
}