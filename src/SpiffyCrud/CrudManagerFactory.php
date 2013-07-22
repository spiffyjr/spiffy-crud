<?php

namespace SpiffyCrud;

use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CrudManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $config = isset($config['spiffy_crud']) ? $config['spiffy_crud'] : array();

        $options     = new ModuleOptions($config);
        $crudManager = new CrudManager();

        if ($options->getDefaultHydrator()) {
            $crudManager->setDefaultHydrator($this->get($options->getDefaultHydrator(), $serviceLocator));
        }

        if ($options->getDefaultAdapter()) {
            $crudManager->setDefaultAdapter($this->get($options->getDefaultAdapter(), $serviceLocator));
        }

        if ($options->getFormBuilder()) {
            $crudManager->setFormBuilder($this->get($options->getFormBuilder(), $serviceLocator));
        }

        $config = new Config($options->getModels());
        $config->configureServiceManager($crudManager);

        return $crudManager;
    }

    /**
     * Generic method for getting from service locator, or creating a class.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param string $input
     * @throws \RuntimeException if the resource could not be found
     * @return mixed
     */
    protected function get($input, ServiceLocatorInterface $serviceLocator)
    {
        if (is_string($input) && $serviceLocator->has($input)) {
            return $serviceLocator->get($input);
        } else if (class_exists($input)) {
            return new $input;
        }

        throw new \RuntimeException(sprintf(
            'Service "%s" could not be found',
            $input
        ));
    }
}