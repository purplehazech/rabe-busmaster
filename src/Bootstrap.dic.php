<?php

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
     * Gets the 'logger.console' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * 
     */
    protected function getLogger_ConsoleService()
    {
        return $this->services['logger.console'] = new \UL('console');
    }

    /**
     * Gets the 'logger.syslog' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * 
     */
    protected function getLogger_SyslogService()
    {
        return $this->services['logger.syslog'] = new \UL('syslog');
    }

    /**
     * Gets the 'oscdispatch.poll' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQPoll A ZMQPoll instance.
     */
    protected function getOscdispatch_PollService()
    {
        $this->services['oscdispatch.poll'] = $instance = new \ZMQPoll();

        $instance->add($this->get('oscdispatch.poll.socketwork'), 1);

        return $instance;
    }

    /**
     * Gets the 'oscdispatch.poll.socketwork' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscdispatch_Poll_SocketworkService()
    {
        $this->services['oscdispatch.poll.socketwork'] = $instance = $this->get('oscDispatch.pollZmq')->getSocket(7);

        $instance->setSockOpt(1, 1);
        $instance->connect('ipc:///tmp/osc-dispatch.poll.socket-work');

        return $instance;
    }

    /**
     * Gets the 'oscdispatch.pollctrl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscdispatch_PollctrlService()
    {
        $this->services['oscdispatch.pollctrl'] = $instance = $this->get('oscDispatch.pollZmq')->getSocket(7);

        $instance->setSockOpt(1, 1);
        $instance->connect('ipc:///tmp/osc-dispatch.poll.socket-ctrl');

        return $instance;
    }

    /**
     * Gets the 'oscdispatch.pollzmq' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQContext A ZMQContext instance.
     */
    protected function getOscdispatch_PollzmqService()
    {
        return $this->services['oscdispatch.pollzmq'] = new \ZMQContext();
    }

    /**
     * Gets the 'oscreceive.oscparser' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Osc_Parse A Osc_Parse instance.
     */
    protected function getOscreceive_OscparserService()
    {
        return $this->services['oscreceive.oscparser'] = new \Osc_Parse();
    }

    /**
     * Gets the 'oscreceive.pollctrl' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscreceive_PollctrlService()
    {
        $this->services['oscreceive.pollctrl'] = $instance = $this->get('oscReceive.pollZmq')->getSocket(7);

        $instance->setSockOpt(1, 1);
        $instance->connect('ipc:///tmp/osc-receive.poll.socket-ctrl');

        return $instance;
    }

    /**
     * Gets the 'oscreceive.pollzmq' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQContext A ZMQContext instance.
     */
    protected function getOscreceive_PollzmqService()
    {
        return $this->services['oscreceive.pollzmq'] = new \ZMQContext();
    }

    /**
     * Gets the 'oscreceive.pushsocket.oscdispatch' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return ZMQSocket A ZMQSocket instance.
     */
    protected function getOscreceive_Pushsocket_OscdispatchService()
    {
        $this->services['oscreceive.pushsocket.oscdispatch'] = $instance = $this->get('oscReceive.pollZmq')->getSocket(8);

        $instance->setSockOpt(1, 1);
        $instance->connect('ipc:///tmp/osc-dispatch.poll.socket-work');

        return $instance;
    }
}
