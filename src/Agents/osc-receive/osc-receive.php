<?php
/**
 * OSC Receiving Agent
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
 */
require_once dirname(__FILE__).'/../Bootstrap.php';

/**
 * osc parser
 */
require_once 'Osc/Parse.php';

class OscReceive
{
    function __construct($dc) {
        $this->_dispatcher = $dc->get('dispatcher');
        $this->_logger = $dc->get('logger');
    	$this->_osc = $dc->get('oscReceive.oscParser');
	$this->_workPoll = $dc->get('oscReceive.pushSocket.oscDispatch');
        $this->_ctrlPoll = $dc->get('oscReceive.pollCtrl');
    }

    function run() {
        $this->_dispatcher->dispatch('/daemon/start');
        $this->start_socket();
    }


    /**
     * digest and dispatch a package
     *
     * @return void
     */
    function start_socket()
    {
        //$workload = $job->workload();
    
        $conf = parse_ini_file('/etc/busmaster/busmaster.ini', true);
    
        $ip = $conf['osc']['listen_host'];
        $port = $conf['osc']['listen_port'];

        $this->_logger->log(sprintf('Creating socket on %s:%s', $ip, $port));
    
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        $r = socket_bind($socket, $ip, $port);
    
        while (true) {
            if (socket_recvfrom($socket, $b, 9999, 0, $f, $p)) {
    
                // parse incoming buffer
    		$oscdata = $this->parse_buffer($b);

                // digest results in background
		$this->_workPoll->send(json_encode($oscdata));

		// log info 
                $this->_logger->debug(__CLASS__."Digested a message");
            }
            usleep(500000); // 0.5 secs
        }
    
        return true;
    }

    protected function parse_buffer()
    {
        $this->_osc->setDataString($b);
        $this->_osc->parse();
        return $this->_osc->getResult();
    }
}

// @todo repack into dc
$o = new OscReceive($dc);
$o->run();

