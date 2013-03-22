<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\FormManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ManagerFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException if no form configuration given
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        if (!isset($config['spiffycrud']['forms'])) {
            throw new \RuntimeException('No form configuration given');
        }

        return new FormManager(new Config($config['spiffycrud']['forms']));
    }
}