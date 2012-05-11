<?php
/**
 * Example Agent you may clone for starting new modules
 *
 * PHP Version 5
 *
 * @todo fix this thing so getopt happens in bootstrap
 *
 * @category   Busmaster
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2012 Lucas S. Bickel 2012 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */

/**
 * daemon name
 *
 * This is needed by the daemon runner and it must be unique 
 * over the machine it is. Also 16 chars is the limit on this
 * name.
 */
if (!defined('MODULE_NAME')) {
    define('MODULE_NAME', basename(__DIR__));
}

/**
 * common worker bootstrap
 *
 * feel free to call this from you own bootstrap if you need 
 * more strapping. but please do try to use the automated 
 * dependency injenction tools.
 */
require_once dirname(__FILE__).'/../Bootstrap.php';

/**
 * Agent Class
 *
 * @category   PoscHP
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */
class ExampleAgent_Daemon
{
    /**
     * constructor
     *
     * in most modules this method will load alot more services via the 
     * dependency injection container passed from bootstrap. as of yet
     * it is undecided where that all the module documentation will
     * really live.
     *
     * @param Object $dispatcher Observer Event Dispatcher
     * @param Object $logger     Default Logger
     */
    function __construct($dispatcher, $logger)
    {
        $this->_dispatcher = $dispatcher;
        $this->_logger = $logger;
    }

    /**
     * start method
     * 
     * gets called when the module starts and may be used for registering
     * observers and thing. Be sure to dispatch a /daemon/start msg
     * after setup and the bootstrap will daemonize for you.
     *
     * @return void
     */
    public function start()
    {
        // log that we are running
        $this->_logger->log(sprintf('starting %s daemon', basename(__DIR__)));

        // daemonize 
        $this->_dispatcher->dispatch('/daemon/start');
    }

    /**
     * main daemon method
     *
     * This is the main body of the daemons while(true) part.
     *
     * @return true
     */
    function run()
    {
        // do one thing here
    }
}
