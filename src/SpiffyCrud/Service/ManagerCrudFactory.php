<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\CrudManager;
use SpiffyCrud\FormManager;
use SpiffyCrud\ModelManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ManagerCrudFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config  = $serviceLocator->get('Configuration');
        $options = new ManagerCrudFactoryOptions(isset($config['spiffy-crud']) ? $config['spiffy-crud'] : array());

        $formManager  = new FormManager(new Config($options->getForms()));
        $modelManager = new ModelManager(new Config($options->getModels()));
        $crudManager  = new CrudManager($modelManager, $formManager);

        // set the parent locators
        $modelManager->setServiceLocator($serviceLocator);
        $formManager->setServiceLocator($serviceLocator);
        $crudManager->setServiceLocator($serviceLocator);

        if ($options->getDefaultHydrator()) {
            $crudManager->setDefaultHydrator($this->get($options->getDefaultHydrator(), $serviceLocator));
        }

        if ($options->getDefaultMapper()) {
            $crudManager->setDefaultMapper($this->get($options->getDefaultMapper(), $serviceLocator));
        }

        if ($options->getFormBuilder()) {
            $crudManager->setFormBuilder($this->get($options->getFormBuilder(), $serviceLocator));
        }

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

        throw new \RuntimeException('Builder could not be found');
    }
}