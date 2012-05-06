<?php
/**
 * Example Agent you may clone for starting new modules
 *
 * PHP Version 5
 *
 * @category   PoscHP
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2012 Lucas S. Bickel 2012 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://osc.purplehaze.ch
 */

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
 */

class ExampleAgent {

    function __construct($dc) {

        $this->_dispatcher = $dc->get('dispatcher');
        $this->_logger = $dc->get('logger');

        
        $this->_dispatcher->dispatch('/log/info', new LogEvent('loaded '.__CLASS__));
    }

    function run() {
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
