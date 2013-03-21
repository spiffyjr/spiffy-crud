<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\CrudManager;
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
        $formManager  = $serviceLocator->get('SpiffyCrud\FormManager');
        $modelManager = $serviceLocator->get('SpiffyCrud\ModelManager');

        return new CrudManager($modelManager, $formManager);
    }
}