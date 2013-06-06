<?php

namespace SpiffyCrud\Service;

use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BuilderDoctrineOrmFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException(
                'The default configuration for DoctrineOrmBuilder requires a default setup of DoctrineORMModule.'
            );
        }

        return new AnnotationBuilder($serviceLocator->get('Doctrine\ORM\EntityManager'));
    }
}