<?php
/**
 * OSC Receiving Agent
 * 
 * based on socket_* methods :(
 *
 * PHP Version 5
 *
 * @todo rewrite to stream_socket stuff (socket_* & System_Daemon do ! play nicely)
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
 * daemon name
 */
define('DAEMON_NAME', basename(__DIR__));

/**
 * common worker bootstrap
 */
require_once dirname(__FILE__).'/../Bootstrap.php';

/**
 * osc parser
 */
require_once 'Osc/Parse.php';

/**
 * osc receiver
 *
 * @category   PoscHP
 * @package    Server
 * @subpackage OSC
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://osc.purplehaze.ch
 */
class OscReceive
{
    /**
     * constructor
     *
     * @param Symfony\Component\DependencyInjection\ContainerInterface $dc DIC
     */
    function __construct($dc)
    {
        $this->_dispatcher = $dc->get('dispatcher');
        $this->_logger = $dc->get('logger');
            $this->_osc = $dc->get('oscReceive.oscParser');
        $this->_workPoll = $dc->get('oscReceive.pushSocket.oscDispatch');
        $this->_ctrlSocket = $dc->get('oscReceive.pollCtrl');
    }

    /**
     * daemonize & socketize
     *
     * @return void
     */
    function run()
    {
        $this->_dispatcher->dispatch('/daemon/start');
        $this->startSocket();
    }


    /**
     * digest and dispatch a package
     *
     * @return void
     */
    function startSocket()
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
                $oscdata = $this->parseBuffer($b);

                // digest results in background
                $this->_workPoll->send(json_encode($oscdata));

                // log info 
                $this->_logger->debug(__CLASS__." digested an OSC message");
            }
            usleep(500000); // 0.5 secs
        }
    
        return true;
    }

    /**
     * parse osc
     *
     * @param Object $b raw osc buffer
     *
     * @return Array parsed osc data
     */
    protected function parseBuffer($b)
    {
        $this->_osc->setDataString($b);
        $this->_osc->parse();
        return $this->_osc->getResult();
    }
}

// @todo repack into dc
$o = new OscReceive($dc);
$o->run();

