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

        return new CrudManager($modelManager, $formManager);
    }
}