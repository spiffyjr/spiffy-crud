<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\ModelManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModelManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException if no model configuration given
     * @return ModelManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        if (!isset($config['spiffycrud']['models'])) {
            throw new \RuntimeException('No model configuration given');
        }

        return new ModelManager(new Config($config['spiffycrud']['models']));
    }
}