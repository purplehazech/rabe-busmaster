<?php
/**
 * Example Agent you may clone for starting new modules
 *
 * PHP Version 5
 *
 * @todo fix this thing so daemonizing & also as a plus getopt happen in bootstrap
 * @todo also wouldn't it be nice not to 'while(true)'?
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
define('MODULE_NAME', basename(__DIR__));

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
class ExampleAgent
{
    /**
     * constructor
     *
     * in most modules this method will load alot more services via the 
     * dependency injection container passed from bootstrap. as of yet
     * it is undecided where that all the module documentation will
     * really live.
     *
     * @param Symfony\Component\DependencyInjection\ContainerInterface $dic DIC
     */
    function __construct($dic)
    {
        $this->_dispatcher = $dic->get('dispatcher');
        $this->_logger = $dic->get('logger');
    }

    /**
     * main daemon method
     *
     * quite literally this is the while(true) part of the whole php
     * based daemon stuff. Be sure to dispatch a /daemon/start msg
     * and you're safe to go. 
     *
     * @return true
     */
    function run()
    {
        // log that we are running
        $this->_logger->log(sprintf('Starting %s daemon', __CLASS__));

        // daemonize 
        $this->_dispatcher->dispatch('/daemon/start');

        // main loop
        while (true) {
            // do one thing here

            // wait for some time
            usleep(500000); // 0.5 secs
        }
    }
}

$o = new ExampleAgent($dc);
$o->run();
