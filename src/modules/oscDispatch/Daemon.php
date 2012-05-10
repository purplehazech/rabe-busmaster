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
 * @copyright  2012 Lucas S. Bickel 2012 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://osc.purplehaze.ch
 */

/**
 * daemon name
 */
define('MODULE_NAME', basename(__DIR__));

/**
 * common worker bootstrap
 */
require_once dirname(__FILE__).'/../Bootstrap.php';

/**
 * osc dispatcher
 *
 * @category   PoscHP
 * @package    Server
 * @subpackage OSC
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://osc.purplehaze.ch
 */
class OscDispatch
{
    /**
     * constructor
     *
     * @param Object $dispatcher                Observer Event Dispaycher
     * @param Object $logger                    Default Logger
     * @param Object $oscDispatchPoll           OSC Dispatcher Poll
     * @param Object $oscDispatchPollSocketWork OSC Dispatcher Poll Socket
     * @param Object $oscDispatchPollSocketCtrl OSC Dispatcher Ctrl Socket
     */
    function __construct(
        $dispatcher,
        $logger,
        $oscDispatchPoll,
        $oscDispatchPollSocketWork,
        $oscDispatchPollSocketCtrl
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_logger = $logger;
        $this->_workPoll = $oscDispatchPoll;
        $this->_workSocket = $oscDispatchPollSocketWork;
        $this->_ctrlSocket = $oscDispatchPollSocketCtrl;
    }

    /**
     * main daemon method
     *
     * @return void
     */
    function run()
    {
        $this->_dispatcher->dispatch('/daemon/start');
        $this->_logger->log(sprintf('polling worker queue'));

        $read = $write = array();
        $done = false;
        while (!$done) {
            $event = $this->_workPoll->poll($read, $write, 5000);
            if ($event) {
                // do the work
                $this->_logger->debug(sprintf('digesting OSC datagram'));
                $this->digest(json_decode($this->_workSocket->recv()));
            } else {
                if ($this->_ctrlSocket->recv(ZMQ::MODE_NOBLOCK)) {
                    $done = true;
                }
            }
        }
    }

    /**
     * digest and dispatch a package
     *
     * @param Object $oscdata pre parsed and unpacked data
     *
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
