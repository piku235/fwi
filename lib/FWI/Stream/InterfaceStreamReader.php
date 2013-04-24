<?php

interface InterfaceStreamReader 
{
	/**
	 * Zwraca dane z podanego zasobu
	 * 
	 * @param  string $path Ścieżka
	 * @return InterfaceInputStream
	 */
	public function readStream($path);
}