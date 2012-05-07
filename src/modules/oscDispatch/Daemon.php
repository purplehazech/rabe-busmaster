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
 * daemon name
 */
define('DAEMON_NAME', basename(__DIR__));

/**
 * common worker bootstrap
 */
require_once dirname(__FILE__).'/../Bootstrap.php';

class OscDispatch
{
    function __construct($dc) {
        $this->_dispatcher = $dc->get('dispatcher');
        $this->_logger = $dc->get('logger');
        $this->_workPoll = $dc->get('oscDispatch.poll');
        $this->_workSocket = $dc->get('oscDispatch.poll.socketWork');
        $this->_ctrlSocket = $dc->get('oscDispatch.poll.socketCtrl');
    }

    function run() {
        $this->_dispatcher->dispatch('/daemon/start');
        $this->_logger->log(sprintf('polling worker queue'));

        $r = $w = array();
        while(true) {
            $e = $this->_workPoll->poll($r, $w, 5000);
            if ($e) {
                // do the work
                $this->_logger->debug(sprintf('digesting OSC datagram'));
                $this->digest(json_decode($this->_workSocket->recv()));
            } else {
                if ($this->_ctrlSocket->recv(ZMQ::MODE_NOBLOCK)) {
                    exit;
                }
            }
        }
    }

    /**
     * digest and dispatch a package
     *
     * @param Object $oscdata
     * @return void
     *
     * @todo this will need to handle the osc address patterns as per spec
     * @todo refactor me into heaps of classes
     */
    function digest($oscdata)
    {
        $address = $oscdata->address;

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
