<?php
/**
 * OSC Dispatching Agent
 *
 * PHP Version 5
 *
 * @category   PoscHP
 * @package    Server
 * @subpackage OSC
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2011 Lucas S. Bickel 2011 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://osc.purplehaze.ch
 */

/**
 * common worker bootstrap
 */
require_once dirname(__FILE__).'/../Bootstrap.php';

class OscDispatch
{
    function __construct($dc) {
        $this->_dispatcher = $dc->get('dispatcher');
        $this->_logger = $dc->get('logger');
        $this->_workPoll = $dc->get('osc-dispatch.poll');
        $this->_ctrlPoll = $dc->get('osc-dispatch.poll-ctrl');
    }

    function run() {
        $this->_dispatcher->dispatch('/daemon/start');

        $r = $w = array();
        while(true) {
            $e = $this->_workPoll->poll($r, $w, 5000);
            if ($e) {
                // do the work
                $this->digest();
            } else {
                if ($this->_pollCtrl->recv(ZMQ::MODE_NOBLOCK)) {
                    exit;
                }
            }
        }
    }

    /**
     * digest and dispatch a package
     *
     * @return void
     *
     * @todo this will need to handle the osc address patterns as per spec
     * @todo refactor me into heaps of classes
     */
    function digest($address)
    {
        // @todo fix trailing \0 byte probles in parser where they arise
        $address = str_replace("\0", '', $address);

        switch($address) {
    
        case "#bundle":
            throw new RuntimeExecption("Bundles not implemented");
            break;
    
        default:
            $function = 'noop';
        }

        $this->_logger->log(
            sprintf(
                "Handling Message for %s with %s",
                $address,
                $function
            )
        );
    }
}

$o = new OscDispatch($dc);
$o->run();
