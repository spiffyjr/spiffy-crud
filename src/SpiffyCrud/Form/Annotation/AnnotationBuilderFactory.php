<?php

namespace SpiffyCrud\Form\Annotation;

use DoctrineORMModule\Form\Annotation\AnnotationBuilder;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AnnotationBuilderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @throws \RuntimeException
     * @return AnnotationBuilder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Doctrine\ORM\EntityManager')) {
            throw new \RuntimeException(
                'The default configuration for AnnotationBuilder requires a default setup of DoctrineORMModule.'
            );
        }

        return new AnnotationBuilder($serviceLocator->get('Doctrine\ORM\EntityManager'));
    }
}
