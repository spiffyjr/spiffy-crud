<?php

namespace SpiffyCrud\Config;

use SpiffyConfig\Builder;
use SpiffyConfig\Config;
use SpiffyConfig\Handler\HandlerInterface;
use Zend\Console\Request as ConsoleRequest;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class RuntimeHandler extends AbstractListenerAggregate implements
    ServiceManager\ServiceLocatorAwareInterface,
    HandlerInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Config\Manager::EVENT_CONFIGURE, array($this, 'configure'));
    }

    /**
     * @param Config\Event $event
     */
    public function configure(Config\Event $event)
    {
        if ($this->serviceLocator->get('Request') instanceof ConsoleRequest) {
            return;
        }

        $resolver = $event->getResolver();
        $builder  = $event->getBuilder();
        $config   = $builder->build($resolver->resolve());

        if ($builder instanceof Builder\AbstractServiceManager) {
            if (isset($config['spiffy_crud']['manager'])) {
                $serviceConfig = new ServiceManager\Config($config['spiffy_crud']['manager']);
                $serviceConfig->configureServiceManager($this->getServiceLocator()->get('SpiffyCrud\CrudManager'));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
