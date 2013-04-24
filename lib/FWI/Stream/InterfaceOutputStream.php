<?php

interface InterfaceOutputStream 
{
	/**
	 * Zapisuje dane do strumienia
	 * 
	 * @param  string $data Dane
	 * @return void
	 */
	public function write($data);
	
	/**
	 * Zamyka strumień
	 * 
	 * @return void
	 */
	public function close();
}