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

class OscReceive {


    function __construct($dc) {
        $this->_dispatcher = $dc->get('dispatcher');
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

        $this->_dispatcher->dispatch('/log/info', new LogEvent('Creating Socket and starting Listener'));
    
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        $r = socket_bind($socket, $ip, $port);
    
        $osc = new Osc_Parse;
    
        while (true) {
            if (socket_recvfrom($socket, $b, 9999, 0, $f, $p)) {
    
                // parse incoming buffer
                $osc->setDataString($b);
                $osc->parse();
    
                // digest result in background
                // @todo send message to mq
                $log[] = "Digested a message";
            }
            usleep(500000); // 0.5 secs
        }
    
        return true;
    }
}

// @todo repack into dc
$o = new OscReceive;
$o->start_socket();

