<?php namespace Wpjscc\Api\Traits;

use Event;
use Winter\Storm\Support\Traits\Emitter;

/**
 * Adds system event related features to any class.
 *
 * @package winter\wn-system-module
 * @author Alexey Bobkov, Samuel Georges
 */

trait EventEmitter
{
    use Emitter {
        Emitter::bindEvent as on;
        Emitter::bindEventOnce as once;
    }


    /**
     * Fires a combination of local and global events. The first segment is removed
     * from the event name locally and the local object is passed as the first
     * argument to the event globally. Halting is also enabled by default.
     *
     * For example:
     *
     *     $this->fireSystemEvent('list.myEvent', ['my value']);
     *
     * Is equivalent to:
     *
     *     $this->fireEvent('list.myEvent', ['myvalue'], true);
     *
     *     Event::fire('list.myEvent', [$this, 'myvalue'], true);
     *
     * @param string $event Event name
     * @param array $params Event parameters
     * @param boolean $halt Halt after first non-null result
     * @return mixed
     */
    public function emit($event, $params = [], $halt = true)
    {
        $result = [];


        $longArgs = array_merge([$this], $params);

        /*
         * Local event first
         */
        if ($response = $this->fireEvent($event, $params, $halt)) {

            if ($halt) {
                return $response;
            }

            $result = array_merge($result, $response);
        }

        /*
         * Global event second
         */
        if ($response = Event::fire($event, $longArgs, $halt)) {
            if ($halt) {
                return $response;
            }
            $result = array_merge($result, $response);
        }

        return $result;
    }
    

}
