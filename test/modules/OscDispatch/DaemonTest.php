<?php
/**
 * Example Agent you may clone for starting new modules
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

/**
 * system under Test
 */
require_once 'modules/OscDispatch/Daemon.php';

/**
 * Test class for OscDispatch_Daemon.
 * Generated by PHPUnit on 2012-05-11 at 13:44:07.
 *
 * @category   Busmaster-Test
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */
class OscDispatch_DaemonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var OscDispatch_Daemon
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
            array('log', 'debug')
        );
        $workPollMock = $this->getMock(
            'stdClass',
            array('poll')
        );
        $workSocketMock = $this->getMock(
            'stdClass',
            array('recv')
        );

        $this->object = new OscDispatch_Daemon(
            $dispatchMock,
            $loggerMock,
            $workPollMock,
            $workSocketMock
        );

        $this->dispatchMock = $dispatchMock;
        $this->loggerMock = $loggerMock;
        $this->workPollMock = $workPollMock;
        $this->workSocketMock = $workSocketMock;
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
     * @covers OscDispatch_Daemon::__construct
     *
     * @return void
     */
    public function testConstruct()
    {
        $this->dispatchMock
            ->expects($this->never())
            ->method('dispatch');
        
        $this->object = new OscDispatch_Daemon(
            $this->dispatchMock,
            $this->loggerMock,
            $this->workPollMock,
            $this->workSocketMock
        );
    }

    /**
     * @covers OscDispatch_Daemon::start
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
     * @covers OscDispatch_Daemon::run
     * 
     * @return void
     */
    public function testRun()
    {
        $this->loggerMock
            ->expects($this->any())
            ->method('debug');

        $this->workPollMock
            ->expects($this->once())
            ->method('poll')
            ->with(
                $this->equalTo(array()),
                $this->equalTo(array()),
                $this->equalTo(5000)
            )
            ->will($this->returnValue(true));

        $this->workSocketMock
            ->expects($this->once())
            ->method('recv')
            ->will($this->returnValue('{"address":"/hello/world"}'));

        $this->object->run();
    }

    /**
     * @covers OscDispatch_Daemon::run
     * 
     * @return void
     */
    public function testDigest()
    {
        $this->loggerMock
            ->expects($this->once())
            ->method('log')
            ->with($this->equalTo('handling hessage /hello/world with noop'));

        $this->object->digest(json_decode('{"address":"/hello/world"}'));

        $this->setExpectedException('RuntimeException');
        $this->object->digest(json_decode('{"address":"#bundle"}'));
    }
}

