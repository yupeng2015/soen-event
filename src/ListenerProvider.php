<?php 
declare(strict_types=1);

namespace Soen\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * Class ListenerProvider
 * @package Soen\Event
 */
class ListenerProvider implements ListenerProviderInterface
{

    /**
     * @var []ListenerInterface
     */
    protected $listeners = [];

    /**
     * EventDispatcher constructor.
     * @param ListenerInterface ...$listeners
     */
    public function __construct(ListenerInterface ...$listeners)
    {
    	/*重组事件的 数组格式*/
    	foreach ($listeners as $listener){
		    $events = (new $listener)->event();
		    $tmpEvents = [];
		    foreach ($events as $event) {
			    $eventClassName = get_class($event);
			    if(in_array($eventClassName, $tmpEvents)){
				    array_push($tmpEvents[$eventClassName], $listener);
				    continue;
			    }
			    $tmpEvents[$eventClassName][] = $listener;
		    }
	    }
        $this->listeners = $listeners;
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
    	// TODO
        $class    = get_class($event);
        $iterable = [];
        foreach ($this->listeners as $listener) {
            if (in_array($class, $listener->events())) {
                $iterable[] = [$listener, 'process'];
            }
        }
        return $iterable;
    }

}
