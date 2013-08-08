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
        /** @var \SpiffyCrud\Adapter\AdapterManager $serviceLocator */
        $sl = $serviceLocator->getServiceLocator();

        if (!$sl->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException(
                'The default configuration for DoctrineObject adapter requires a default setup of DoctrineORMModule.'
            );
        }

        return new DoctrineObject($sl->get('Doctrine\ORM\EntityManager'));
    }
}