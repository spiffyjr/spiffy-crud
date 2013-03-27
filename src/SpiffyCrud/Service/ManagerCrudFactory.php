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
        $options = new ManagerCrudFactoryOptions(isset($config['spiffycrud']) ? $config['spiffycrud'] : array());

        $formManager  = new FormManager(new Config($options->getForms()));
        $modelManager = new ModelManager(new Config($options->getModels()));
        $crudManager  = new CrudManager($modelManager, $formManager);

        $hydrator = $options->getDefaultHydrator();
        if ($hydrator) {
            if (is_string($hydrator) && $serviceLocator->has($hydrator)) {
                $hydrator = $serviceLocator->get($hydrator);
            } else if (class_exists($hydrator)) {
                $hydrator = new $hydrator;
            } else {
                throw new \RuntimeException('Hydrator could not be found');
            }
            $crudManager->setDefaultHydrator($hydrator);
        }

        $mapper = $options->getDefaultMapper();
        if ($mapper) {
            if (is_string($mapper) && $serviceLocator->has($mapper)) {
                $mapper = $serviceLocator->get($mapper);
            } else if (class_exists($mapper)) {
                $mapper = new $mapper;
            } else {
                throw new \RuntimeException('Mapper could not be found');
            }
            $crudManager->setDefaultMapper($mapper);
        }

        $builder = $options->getFormBuilder();
        if ($builder) {
            if (is_string($builder) && $serviceLocator->has($builder)) {
                $builder = $serviceLocator->get($builder);
            } else if (class_exists($mapper)) {
                $builder = new $builder;
            } else {
                throw new \RuntimeException('Builder could not be found');
            }
            $crudManager->setFormBuilder($builder);
        }

        return $crudManager;
    }
}