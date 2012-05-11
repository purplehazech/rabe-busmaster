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

require_once __DIR__.'/,,/Bootstrap.php';

/**
 * system under Test
 */
require_once 'modules/ExampleAgent/Daemon.php';

/**
 * Test class for ExampleAgent_Daemon.
 * Generated by PHPUnit on 2012-05-11 at 11:50:28.
 *
 * @category   Busmaster-Test
 * @package    Server
 * @subpackage Socket
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */
class ExampleAgent_DaemonTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ExampleAgent_Daemon
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $dispatchMock = $this->getMock(
             'Symfony\Component\EventDispatcher\EventDispatcher',
             array('dispatch')
        );
        $loggerMock = $this->getMock('Log');

        $this->_object = new ExampleAgent_Daemon(
            $dispatchMock,
            $loggerMock
        );

        $this->_dispatchMock = $dispatchMock;
        $this->_loggerMock = $loggerMock;
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
     * @covers {className}::{origMethodName}
     *
     * @return void
     */
    public function testStart()
    {
        $this->_dispatchMock
             ->expects($this->once())
             ->method('dispatch')
             ->with($this->equalTo('/daemon/start'));
        $this->_loggerMock
             ->expects($this->once())
             ->method('log')
             ->with($this->equalTo('starting ExampleAgent daemon'));

        $this->_object->start();
    }
}

