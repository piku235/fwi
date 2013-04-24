<?php

class EventDispatcher
{
	/**
	 * @var array
	 */
	private $listeners = array();
	
	/**
	 * Dodaje słuchacza
	 * 
	 * @param  $eventName Nazwa wydarzenia Nazwa wydarzenia
	 * @param  callback   $listener 	   Słuchacz (funkcja callback)
	 * @return void
	 */
	public function addListener($eventName, $listener)
	{
		if (!isset($this->listeners[$eventName])) {
			$this->listeners[$eventName] = array();
		}
		
		$this->listeners[$eventName][] = $listener;
	}
	
	/**
	 * Wywołuje słuchaczy na podaną komendę
	 * 
	 * @param  InterfaceEvent $event Wydarzenie
	 * @return void
	 */
	public function exec(InterfaceEvent $event)
	{
		if (!isset($this->listeners[$event->getEventName()])) {
			return;
		}
		
		foreach ($this->listeners[$event->getEventName()] as $listener) {
			call_user_func($listener, $event);
		}
	}
}