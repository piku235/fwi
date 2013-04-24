<?php
define('SITE_DIR', dirname(__FILE__) . '/');
define('LIB_DIR', SITE_DIR . 'lib/');

require_once LIB_DIR . 'FWI/Downloader/HttpDownloader.php';
require_once LIB_DIR . 'FWI/Stream/SocketStreamReader.php';
require_once LIB_DIR . 'FWI/Stream/FileStreamWriter.php';
require_once LIB_DIR . 'FWI/Event/EventDispatcher.php';

function calcPercent($readBytes, $sourceSize)
{
	return floor($readBytes / $sourceSize * 100);
}

function simple(DownloadEvent $event)
{
	echo json_encode(array(
		'read_bytes' => $event->getReadBytes(),
		'size' => $event->getSourceSize(),
		'percent' => calcPercent($event->getReadBytes(), $event->getSourceSize()),
		'transfer_speed' => $event->getTransferSpeed()
	)) . "\n";
	ob_flush();
}

header('Content-type: text/plain');

ob_implicit_flush(true);

$eventDis = new EventDispatcher();
$eventDis->addListener('download.read', 'simple');
$downloader = new HttpDownloader();
$downloader->setStreamReader(new SocketStreamReader());
$downloader->setStreamWriter(new FileStreamWriter(true));
$downloader->setEventDispatcher($eventDis);
$downloader->download($file, SITE_DIR . 'test.exe');
