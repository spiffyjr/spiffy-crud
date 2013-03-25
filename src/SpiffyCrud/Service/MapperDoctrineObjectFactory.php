<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\Mapper\DoctrineObject;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MapperDoctrineObjectFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException(
                'The default configuration for DoctrineObject requires a default setup of DoctrineORMModule.'
            );
        }

        return new DoctrineObject($serviceLocator->get('Doctrine\ORM\EntityManager'));
    }
}