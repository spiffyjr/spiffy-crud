<?php

namespace SpiffyCrud\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DatatableFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $pluginManager
     * @return Datatable
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        /** @var \Zend\View\HelperPluginManager $pluginManager */
        $serviceLocator = $pluginManager->getServiceLocator();

        return new Datatable($serviceLocator->get('SpiffyCrud\CrudManager'));
    }
}