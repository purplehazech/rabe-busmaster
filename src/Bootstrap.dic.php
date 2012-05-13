<?php
/**
 * Dependency Injection Controller
 *
 * generated by my army of bots!
 *
 * PHP Version 5
 *
 * @category   Busmaster
 * @package    Server
 * @subpackage Bootstrap
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright  2012 Lucas S. Bickel 2011 - Alle Rechte vorbehalten
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 */


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * BootstrapDic
 *
 * This class has been auto-generated
 * by the Symfony Dependency Injection Component.
 *
 * PHP Version 5
 *
 * @category   Busmaster
 * @package    Server
 * @subpackage Bootstrap
 * @author     Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license    http://www.gnu.org/licenses/gpl-3.0.txt GPLv3
 * @link       http://purplehaze.ch
 *
 */
// @codeCoverageIgnoreStart
// @codingStandardsIgnoreStart
/**
 * Sadly I found no really easy way to do this, all I really wanted
 * is to just ignore the line length sniff on this file and this 
 * file only. Maybe I'll take the time to fix when the stack is a
 * bit more complete, for now lets just hope devs dont misuse this
 * to much and start using stange service ids...
 * 
 * @SuppressWarnings(PHPMD)
 */
class BootstrapDic extends Container
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets the 'daemon' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return System_Daemon A System_Daemon instance.
     */
    protected function getDaemonService()
    {
        return $this->services['daemon'] = new \System_Daemon();
    }

    /**
     * Gets the 'dispatcher' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Symfony\Component\EventDispatcher\EventDispatcher A Symfony\Component\EventDispatcher\EventDispatcher instance.
     */
    protected function getDispatcherService()
    {
        return $this->services['dispatcher'] = new \Symfony\Component\EventDispatcher\EventDispatcher();
    }

    /**
     * Gets the 'event' service.
     *
     * @return Symfony\Component\EventDispatcher\Event A Symfony\Component\EventDispatcher\Event instance.
     */
    protected function getEventService()
    {
        return new \Symfony\Component\EventDispatcher\Event();
    }

    /**
     * Gets the 'exampleagentdaemon' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ExampleAgent A ExampleAgent instance.
     */
    protected function getExampleagentdaemonService()
    {
        $this->services['exampleagentdaemon'] = $instance = new \ExampleAgent($this->get('dispatcher'), $this->get('logger'));

        $instance->start();

        return $instance;
    }

    /**
     * Gets the 'logger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Log A Log instance.
     */
    protected function getLoggerService()
    {
        $this->services['logger'] = $instance = call_user_func(array('Log', 'factory'), 'composite');

        $instance->addChild($this->get('loggerconsole'));
        $instance->addChild($this->get('loggersyslog'));

        return $instance;
    }

    /**
     * Gets the 'loggerconsole' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Log A Log instance.
     */
    protected function getLoggerconsoleService()
    {
        return $this->services['loggerconsole'] = call_user_func(array('Log', 'factory'), 'console');
    }

    /**
     * Gets the 'loggersyslog' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Log A Log instance.
     */
    protected function getLoggersyslogService()
    {
        return $this->services['loggersyslog'] = call_user_func(array('Log', 'factory'), 'syslog');
    }

    /**
     * Gets the 'oscdispatchdaemon' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return OscDispatch_Daemon A OscDispatch_Daemon instance.
     */
    protected function getOscdispatchdaemonService()
    {
        $this->services['oscdispatchdaemon'] = $instance = new \OscDispatch_Daemon($this->get('dispatcher'), $this->get('logger'), $this->get('oscdispatchpoll'), $this->get('oscdispatchpollsocketwork'));

        $instance->run();

        return $instance;
    }

    /**
     * Gets the 'oscdispatchpoll' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQPoll A ZMQPoll instance.
     */
    protected function getOscdispatchpollService()
    {
        $this->services['oscdispatchpoll'] = $instance = new \ZMQPoll();

        $instance->add($this->get('oscdispatchpollsocketwork'), 1);

        return $instance;
    }

    /**
     * Gets the 'oscdispatchpollsocketctrl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscdispatchpollsocketctrlService()
    {
        $this->services['oscdispatchpollsocketctrl'] = $instance = $this->get('oscDispatchPollZmq')->getSocket(7);

        $instance->setSockOpt(1, 1);
        $instance->bind('ipc:///tmp/osc-dispatch.poll.socket-ctrl');

        return $instance;
    }

    /**
     * Gets the 'oscdispatchpollsocketwork' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscdispatchpollsocketworkService()
    {
        $this->services['oscdispatchpollsocketwork'] = $instance = $this->get('oscDispatchPollZmq')->getSocket(7);

        $instance->setSockOpt(1, 1);
        $instance->bind('tcp://0.0.0.0:5555');

        return $instance;
    }

    /**
     * Gets the 'oscdispatchpollzmq' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQContext A ZMQContext instance.
     */
    protected function getOscdispatchpollzmqService()
    {
        return $this->services['oscdispatchpollzmq'] = new \ZMQContext();
    }

    /**
     * Gets the 'oscreceivedaemon' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return OscReceive_Daemon A OscReceive_Daemon instance.
     */
    protected function getOscreceivedaemonService()
    {
        $this->services['oscreceivedaemon'] = $instance = new \OscReceive_Daemon($this->get('dispatcher'), $this->get('logger'), $this->get('oscreceiveoscparser'), $this->get('oscreceivepushsocketoscdispatch'));

        $instance->run();

        return $instance;
    }

    /**
     * Gets the 'oscreceiveoscparser' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Osc_Parse A Osc_Parse instance.
     */
    protected function getOscreceiveoscparserService()
    {
        return $this->services['oscreceiveoscparser'] = new \Osc_Parse();
    }

    /**
     * Gets the 'oscreceivepollctrl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscreceivepollctrlService()
    {
        $this->services['oscreceivepollctrl'] = $instance = $this->get('oscReceivePollZmq')->getSocket(7);

        $instance->setSockOpt(1, 1);
        $instance->connect('ipc:///tmp/osc-receive.poll.socket-ctrl');

        return $instance;
    }

    /**
     * Gets the 'oscreceivepollzmq' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQContext A ZMQContext instance.
     */
    protected function getOscreceivepollzmqService()
    {
        return $this->services['oscreceivepollzmq'] = new \ZMQContext();
    }

    /**
     * Gets the 'oscreceivepushsocketoscdispatch' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscreceivepushsocketoscdispatchService()
    {
        $this->services['oscreceivepushsocketoscdispatch'] = $instance = $this->get('oscReceivePollZmq')->getSocket(8);

        $instance->setSockOpt(1, 1);
        $instance->connect('tcp://0.0.0.0:5555');

        return $instance;
    }
}

// @codingStandardsIgnoreEnd