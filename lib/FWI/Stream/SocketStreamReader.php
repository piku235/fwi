<?php
require_once 'InterfaceStreamReader.php';
require_once 'SocketInputStream.php';
require_once LIB_DIR . 'FWI/Exception/StreamException.php';

class SocketStreamReader implements InterfaceStreamReader 
{
	/**
	 * @var int
	 */
	private $connectionTimeout = 30;
	
	/**
	 * @var bool
	 */
	private $followRedirects = true;
	
	/**
	 * Konstruktor
	 * 
	 * @param  int $connTimeout Maks. czas oczekiwania połączenia się z serwerem (sekundy, opcjonalne)
	 * @return void
	 */
	public function __construct($connTimeout = 30, $followRedirects = true)
	{
		$this->connectionTimeout = $connTimeout;
		$this->followRedirects = (bool) $followRedirects;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see InterfaceStreamReader::readStream()
	 */
	public function readStream($path)
	{
		// Części adresu
		$urlParts = parse_url($path);
		
		// Sprawdza poprawność adresu url
		if (!$urlParts) {
			throw new StreamException("Podany adres URL \"$path\" jest niepoprwany!");
		}
		
		$inputStream = new SocketInputStream($urlParts['host'], $urlParts['path'], $this->connectionTimeout);
		$headers = $inputStream->getResponseHeaders();
		if (preg_match('/^30\d$/', $headers['status_code'])) { // Możliwe przekierowanie do zasobu
			if (!$this->followRedirects) {
				throw new StreamException("Wykryto, że podany adres \"$path\" podaje adres przekierowania przy wyłączonej opcji \"followRedirects\".");
			}
		
			return $this->readStream($headers['location']);
		} else if (preg_match('/^20\d$/', $headers['status_code'])) { // Wszystko przebiegło normalnie
			return $inputStream;
		}
		
		throw new StreamException("Serwer \"$path\" udzielił błędną odpowiedź o kodzie \"{$headers['status_code']}\" i wiadomości \"{$headers['status_text']}\".");
	}
}