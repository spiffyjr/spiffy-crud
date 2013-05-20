<?php

namespace SpiffyCrud;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class FormManager extends ServiceManager implements
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

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

    public function get($name, $usePeeringServiceManagers = true)
    {
        /** @var Form $instance */
        $instance = parent::get($name, $usePeeringServiceManagers);

        if (!is_object($instance) || !$instance instanceof Form) {
            throw new \RuntimeException('registered forms must be an instance of Zend\Form\Form');
        }

        return $instance;
    }
}
