<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\CrudManager;
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
        /** @var $formManager \SpiffyCrud\FormManager */
        $formManager  = $serviceLocator->get('SpiffyCrudManagerForm');

        /** @var $modelManager \SpiffyCrud\ModelManager */
        $modelManager = $serviceLocator->get('SpiffyCrudManagerModel');

        return new CrudManager($modelManager, $formManager);
    }
}