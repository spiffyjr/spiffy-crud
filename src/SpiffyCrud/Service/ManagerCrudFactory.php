<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\CrudManager;
use SpiffyCrud\Options\CrudManagerFactory as CrudManagerFactoryOptions;
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
        $config = $serviceLocator->get('Configuration');
        $config = isset($config['spiffy-crud']) ? $config['spiffy-crud'] : array();

        $options     = new CrudManagerFactoryOptions($config);
        $crudManager = new CrudManager();
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

        foreach($options->getModels() as $modelName => $model) {
            $crudManager->addModel($modelName, $this->get($model, $serviceLocator));
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