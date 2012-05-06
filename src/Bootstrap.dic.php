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
     * Gets the 'logger' service.
     *
     * This service is shared.
     * This method always returns the same instance of the service.
     *
     * @return Log A Log instance.
     */
    protected function getLoggerService()
    {
        return $this->services['logger'] = call_user_func(array('Log', 'factory'), 'file', '/var/log/busmaster.log', 'BUSMASTER');
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

        $instance->add(1);

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
}
