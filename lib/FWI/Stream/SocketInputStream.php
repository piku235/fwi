<?php
require_once 'InterfaceInputStream.php';
require_once LIB_DIR . 'FWI/Exception/StreamException.php';

class SocketInputStream implements InterfaceInputStream
{
	/**
	 * @var resource
	 */
	private $handler = null;
	
	/**
	 * Ilość przeczytanych bajtów
	 *
	 * @var int
	 */
	private $readBytes = 0;
	
	/**
	 * @var bool
	 */
	private $closed = false;
	
	/**
	 * @var array
	 */
	private $responseHeaders = array();
	
	/**
	 * Konstruktor
	 * 
	 * @param  string $host		   Host
	 * @param  string $path 	   Ścieżka
	 * @param  int 	  $connTimeout Czas oczekiwania połączenia się z serwerem (sekundy, opcjonalne)
	 * @return void
	 */
	public function __construct($host, $path, $connTimeout = 30)
	{
		$path = '/' . ltrim($path, '/');
		$this->handler = fsockopen($host, 80, $errno, $error, $connTimeout);
		if ($errno) {
			throw new StreamException("Wystąpił błąd o kodzie \"$errno\" z wiadomością \"$error\" podczas połączenia z hostem \"$host\".");
		}
		
		// Wysyła żądanie 
		$this->sendRequest($host, $path);
		
		// Oczytanie nagłówków odpowiedźi
		$this->readResponseHeaders();
	}
	
	/**
	 * Zwraca nagłówki odpowiedźi
	 * 
	 * @return array
	 */
	public function getResponseHeaders()
	{
		return $this->responseHeaders;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InterfaceInputStream::read()
	 */
	public function read($length = 0)
	{
		if (feof($this->handler)) {
			return self::EOF;
		} else if (!$length) {
			$length = $this->responseHeaders['content-length'] - $this->readBytes;;
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
		return (int) $this->responseHeaders['content-length'];
	}
	
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
	 * Wysyła żądanie do zasobu
	 * 
	 * @param  string $host Host
	 * @param  string $path Ścieżka
	 * @return void
	 */
	public function sendRequest($host, $path)
	{
		$headers = "GET $path HTTP/1.1\r\n";
		$headers .= "Host: $host\r\n";
		$headers .= "Accept: */*\r\n";
		$headers .= "Connection: close\r\n\r\n";
			
		// Wysłanie nagłówka żądania
		fwrite($this->handler, $headers);
	}
	
	/**
	 * Zwraca nagłówki z odpowiedźi serwera
	 *
	 * @return void
	 */
	private function readResponseHeaders()
	{
		// Oczyt nagłówka odpowiedźi
		$outputHeaders = null;
		$line = null;
		do {
			$outputHeaders .= $line = fgets($this->handler);
		} while (!feof($this->handler) && $line != "\r\n");
	
		// Cała reszta
		$result = array(
			'protocol' => null,
			'protocol_name' => null,
			'protocol_version' => null,
			'status_code' => null,
			'status_text' => null
		);
		 
		$eol = strpos($outputHeaders, "\r\n");
		$firstLine = substr($outputHeaders, 0, $eol);
		$theRest = substr($outputHeaders, $eol + 1);
		 
		if (preg_match('/^(?P<protocol>(?P<protocol_name>[A-Z]+)\/(?P<protocol_version>\d(?:\.\d){1,}))\s+(?P<status_code>\d{3})\s+(?P<status_text>[\w\s]+)$/', $firstLine, $matches)) {
			$this->responseHeaders = array_merge($result, array(
				'protocol' => $matches['protocol'],
				'protocol_name' => $matches['protocol_name'],
				'protocol_version' => $matches['protocol_version'],
				'status_code' => (int) $matches['status_code'],
				'status_text' => $matches['status_text']
			));
		}
		 
		foreach (explode("\r\n", $theRest) as $header) {
			if (!$header) {
				continue;
			}
		
			$semicolonPos = strpos($header, ':');
			$this->responseHeaders[strtolower(trim(substr($header, 0, $semicolonPos), "\r\n "))] = trim(substr($header, $semicolonPos + 1), "\r\n ");
		}
	}
}