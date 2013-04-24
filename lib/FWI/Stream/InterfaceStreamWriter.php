<?php

interface InterfaceStreamWriter 
{
	/**
	 * Zapisuje dane do strumienia wyjściowego
	 * 
	 * @param  string $path Ścieżka docelowa
	 * @return InterfaceOutputStream
	 */
	public function writeStream($path);
}