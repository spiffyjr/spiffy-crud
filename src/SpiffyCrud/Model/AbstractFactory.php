<?php

namespace SpiffyCrud\Model;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractFactory implements AbstractFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return null !== $this->getConfig($serviceLocator, $requestedName);
    }

    /**
     * {@inheritDoc}
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $this->getConfig($serviceLocator, $requestedName);
        $model  = new Model($config);

        return $model;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $name
     * @return array|null
     */
    protected function getConfig(ServiceLocatorInterface $serviceLocator, $name)
    {
        /** @var \SpiffyCrud\CrudManager $serviceLocator */
        $sl     = $serviceLocator->getServiceLocator();
        $config = $sl->get('Configuration');

        return isset($config['spiffy_crud']['models'][$name]) ? $config['spiffy_crud']['models'][$name] : null;
    }
}
