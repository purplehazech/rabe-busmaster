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
     * @var String
     */
    var $socketRecvFromFunc = "socket_recvfrom";

    /**
     * @var String
     */
    var $socketCreateFunc = "socket_create";

    /**
     * @var String
     */
    var $socketBindFunc = "socket_bind";

    /** 
     * constructor
     *  
     * @param Object $dispatcher        observer event dispatcher
     * @param Object $logger            default logger
     * @param Object $oscParser         OSC parser
     * @param Object $socketOscDispatch OSC push to osc dispatch socket
     */
    function __construct(
        $dispatcher,
        $logger,
        $oscParser,
        $socketOscDispatch
    ) { 
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->osc = $oscParser;
        $this->workPoll = $socketOscDispatch;
    }

    /**
     * start daemon
     *
     * @return void
     */
    function start()
    {
        // @todo refactor and make configurable via /etc/busmaster.ini
        $ipaddr = '0.0.0.0';
        $port = 10000;
        $this->dispatcher->dispatch('/daemon/start');
        $this->socket = $this->_bindNewSocket($ipaddr, $port);
    }

    /**
     * run stuff
     *
     * @return void
     */
    function run()
    {
        $this->_runSocket();
    }

    /**
     * method for receiving from socket
     *
     * @return Boolean
     */
    private function _runSocket()
    {
        $func = $this->socketRecvFromFunc;
        if (call_user_function($func, $this->socket, $buffer, 9999, 0, $name)) {
            $this->socketName = $name;
    
            // parse incoming buffer
            $oscdata = $this->_parseBuffer($buffer);

            // digest results in background
            $this->workPoll->send(json_encode($oscdata));

            // log info 
            $this->logger->debug(__CLASS__." digested an OSC message");
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
    private function _parseBuffer($buffer)
    {
        $this->osc->setDataString($buffer);
        $this->osc->parse();
        return $this->osc->getResult();
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
        $this->logger->log(
            sprintf(
                'creating socket on %s:%s',
                $ipaddr,
                $port
            )
        );
    
        $func = $this->socketCreateFunc;
        $socket = call_user_function($func, AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$socket) {
            $this->logger->error(
                sprintf(
                    'could not create socket %s:%s',
                    $ipaddr,
                    $port
                )
            );
            throw new RuntimeException("could not create socket");
        }

        $this->logger->log(sprintf('binding socket on %s:%s', $ipaddr, $port));

        $func = $this->socketBindFunc;
        $recv_socket = call_user_function($func, $socket, $ipaddr, $port);
        if (!$recv_socket) {
            $this->logger->error(
                sprintf(
                    'could not bind socket %s:%s',
                    $ipaddr,
                    $port
                )
            );
            throw new RuntimeException("could not bind socket");
        }

        $this->logger->log(
            sprintf(
                'create and binded socket %s:%s', 
                $ipaddr, 
                $port
            )
        );

        return $socket;
    }
}
