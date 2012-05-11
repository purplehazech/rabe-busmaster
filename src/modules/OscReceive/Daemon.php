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
if (!defined('MODULE_NAME')) {
    define('MODULE_NAME', basename(__DIR__));
}

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
class OscReceive_Daemon
{
    /** 
     * constructor
     *  
     * @param Object $dispatcher        observer event dispatcher
     * @param Object $logger            default logger
     * @param Object $oscParser         OSC parser
     * @param Object $socketOscDispatch OSC push to osc dispatch socket
     */
    Function __construct(
        $dispatcher,
        $logger,
        $oscParser,
        $socketOscDispatch
    ) { 
        $this->_dispatcher = $dispatcher;
        $this->_logger = $logger;
        $this->_osc = $oscParser;
        $this->_workPoll = $socketOscDispatch;
    }

    /**
     * start daemon
     *
     * @return void
     */
    function start()
    {
        $this->_dispatcher->dispatch('/daemon/start');
        $this->startSocket();
    }

    /**
     * run stuff
     *
     * @return void
     */
    function run()
    {
        $this->runSocket();
    }

    /**
     * digest and dispatch a package
     *
     * @return void
     */
    function startSocket()
    {
        // @todo fixme into something generic
        $conf = parse_ini_file('/etc/busmaster/busmaster.ini', true);
    
        $ipaddr = $conf['osc']['listen_host'];
        $port = $conf['osc']['listen_port'];

        $this->socket = $this->_bindNewSocket($ipaddr, $port);
    }

    /**
     * method for receiving from socket
     *
     * @return Boolean
     */
    function runSocket()
    {
        if (socket_recvfrom($this->_socket, $buffer, 9999, 0, $name)) {
            $this->_socketName = $name;
    
            // parse incoming buffer
            $oscdata = $this->parseBuffer($buffer);

            // digest results in background
            $this->_workPoll->send(json_encode($oscdata));

            // log info 
            $this->_logger->debug(__CLASS__." digested an OSC message");
        }
    
        return true;
    }


    /**
     * parse osc
     *
     * @param Object $buffer raw osc buffer
     *
     * @return Array parsed osc data
     */
    protected function parseBuffer($buffer)
    {
        $this->_osc->setDataString($buffer);
        $this->_osc->parse();
        return $this->_osc->getResult();
    }

    /**
     * get a socket connection
     * 
     * @param String  $ipaddr ip to bind to
     * @param Integer $port   port to bind
     *
     * @return void
     */
    private function _bindNewSocket($ipaddr, $port)
    {
        $this->_logger->log(
            sprintf(
                'creating socket on %s:%s',
                $ipaddr,
                $port
            )
        );
    
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$socket) {
            $this->_logger->error(
                sprintf(
                    'could not create socket %s:%s',
                    $ipaddr,
                    $port
                )
            );
            throw new RuntimeException("could not create socket");
        }

        $this->_logger->log(sprintf('binding socket on %s:%s', $ipaddr, $port));

        $recv_socket = socket_bind($socket, $ipaddr, $port);
        if (!$recv_socket) {
            $this->_logger->error(
                sprintf(
                    'could not bind socket %s:%s',
                    $ipaddr,
                    $port
                )
            );
            throw new RuntimeException("could not bind socket");
        }

        $this->_logger->log(
            sprintf(
                'create and binded socket %s:%s', 
                $ipaddr, 
                $port
            )
        );

        return $socket;
    }
}