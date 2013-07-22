<?php

namespace SpiffyCrud\Adapter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DoctrineObjectFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException
     * @return DoctrineObject
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException(
                'The default configuration for DoctrineObject adapter requires a default setup of DoctrineORMModule.'
            );
        }

        return new DoctrineObject($serviceLocator->get('Doctrine\ORM\EntityManager'));
    }
}