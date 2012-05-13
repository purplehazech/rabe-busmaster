<?php
/**
 * Osc Receive Tests
 *
 * PHP Version 5
 *
 * @category   Busmaster-Test
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2012 Lucas S. Bickel 2012 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */

require_once __DIR__.'/../Bootstrap.php';

require_once 'modules/OscReceive/Daemon.php';

/**
 * Test class for OscReceive_Daemon.
 * Generated by PHPUnit on 2012-05-11 at 14:47:46.
 *
 * @category   Busmaster-Test
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */
class OscReceive_DaemonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var OscReceive_Daemon
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $dispatchMock = $this->getMock(
            'stdClass',
            array('dispatch')
        );
        $loggerMock = $this->getMock(
            'stdClass',
            array('log', 'debug', 'error')
        );
        $oscMock = $this->getMock(
            'stdClass',
            array('setDataString', 'parse', 'getResult')
        );
        $workPollMock = $this->getMock(
            'stdClass',
            array('send')
        );

        $this->object = new OscReceive_Daemon(
            $dispatchMock,
            $loggerMock,
            $oscMock,
            $workPollMock
        );

        // test shunting for low level methods
        $this->object->socketRecvFromFunc = __CLASS__.'::socketRecvfrom';
        $this->object->socketCreateFunc = __CLASS__.'::socketCreate';
        $this->object->socketBindFunc = __CLASS__.'::socketBind';
        if (!defined('AF_INET')) {
            define('AF_INET', 'AF_INET');
        }
        if (!defined('SOCK_DGRAM')) {
            define('SOCK_DGRAM', 'SOCK_DGRAM');
        }
        if (!defined('SOL_UDP')) {
            define('SOL_UDP', 'SOL_UDP');
        }

        $this->dispatchMock = $dispatchMock;
        $this->loggerMock = $loggerMock;
        $this->oscMock = $oscMock;
        $this->workPollMock = $workPollMock;
    }

    /**
     * shunt for socket_recvfrom
     *
     * @return Boolean
     */
    static function socketRecvfrom()
    {
        return true;
    }

    /**
     * @var Boolean
     */
    static $socketCreateRet = true;

    /**
     * shunt for socket_create
     *
     * @return Boolean
     */
    static function socketCreate()
    {
        return self::$socketCreateRet;
    }

    /**
     * @var Boolean
     */
    static $socketBindRet = true;

    /**
     * shunt for socket_bind
     *
     * @return Boolean
     */
    static public function socketBind()
    {
        return self::$socketBindRet;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
    }

    /**
     * test constructor
     *
     * @covers OscReceive_Daemon::__construct
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->dispatchMock
            ->expects($this->never())
            ->method('dispatch');

        new OscReceive_Daemon(
            $this->dispatchMock,
            $this->loggerMock,
            $this->oscMock,
            $this->workPollMock
        );
    }

    /**
     * test start method
     *
     * @covers OscReceive_Daemon::start
     *
     * @return void
     */
    public function testStart()
    {
        $this->dispatchMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo('/daemon/start'));

        $this->object->start();
    }

    /**
     * test run method
     *
     * @covers OscReceive_Daemon::run
     *
     * @return void
     */
    public function testRun()
    {
        $this->object->socket = new stdClass;

        $this->object->run();
    }

    /**
     * test runSocket method
     *
     * @covers OscReceive_Daemon::_runSocket
     * @covers OscReceive_Daemon::_parseBuffer
     *
     * @return void
     */
    public function testRunSocket()
    {
        $this->oscMock
            ->expects($this->once())
            ->method('setDataString')
            ->with($this->equalTo(''));
        $this->oscMock
            ->expects($this->once())
            ->method('parse');
        $this->oscMock
            ->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue(json_decode('{"address":"\/test\/1"}')));

        $this->workPollMock
            ->expects($this->once())
            ->method('send')
            ->with($this->equalTo('{"address":"\/test\/1"}'));

        $this->loggerMock
            ->expects($this->once())
            ->method('debug')
            ->with($this->equalTo('OscReceive_Daemon digested an OSC message'));

        $this->object->socket = new stdClass;

        $this->assertTrue($this->object->run());
        $this->assertEquals($this->object->socketName, '');
    }

    /**
     * test socket binding
     *
     * @covers OscReceive_Daemon::_bindNewSocket
     *
     * @return void
     *
     * @todo loggerMock
     */
    public function testBindNewSocket()
    {
        $this->object->start();

        // socket_create errors
        $this->setExpectedException('RuntimeException');
        self::$socketCreateRet = false;
        self::$socketBindRet = true;

        $this->object->start();

        // socket_bind errors
        $this->setExpectedException('RuntimeException');
        self::$socketCreateRet = true;
        self::$socketBindRet = false;

        $this->object->start();
    }
}

