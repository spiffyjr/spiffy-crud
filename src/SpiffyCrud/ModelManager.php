<?php

namespace SpiffyCrud;

use SpiffyCrud\Model\AbstractModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class ModelManager extends ServiceManager implements
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    public function get($name, $usePeeringServiceManagers = true)
    {
        /** @var AbstractModel $instance */
        $instance = parent::get($name, $usePeeringServiceManagers);

        if (!is_object($instance) || !$instance instanceof AbstractModel) {
            throw new \RuntimeException('registered models must be an instance of SpiffyCrud\Model\AbstractModel');
        }

        return $instance;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ModelManager
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
