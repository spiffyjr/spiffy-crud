<?php

namespace SpiffyCrud\Options;

use SpiffyCrud\Mapper\MapperInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CrudManagerFactory extends AbstractOptions
{
    /**
     * A string with a service locator resource or a HydratorInterface to
     * use as the default hydrator.
     *
     * @var string|HydratorInterface
     */
    protected $defaultHydrator = 'Zend\Stdlib\Hydrator\ClassMethods';

    /**
     * A string with a service locator resource or a MapperInterface to
     * use as the default mapper.
     *
     * @var string|MapperInterface
     */
    protected $defaultMapper = 'SpiffyCrudMapperDoctrineObject';

    /**
     * A string with a service locator resource or a \Zend\Form\Builder\AnnotationBuilder to
     * use as a form builder.
     *
     * @var string
     */
    protected $formBuilder = 'SpiffyCrudBuilderDoctrineOrm';

    /**
     * The service manager configuration for the model manager.
     *
     * @var array
     */
    protected $models;

    /**
     * @param string $formBuilder
     * @return CrudManagerFactory
     */
    public function setFormBuilder($formBuilder)
    {
        $this->formBuilder = $formBuilder;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    /**
     * @param array $models
     * @return CrudManagerFactory
     */
    public function setModels($models)
    {
        $this->models = $models;
        return $this;
    }

    /**
     * @return array
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * @param \SpiffyCrud\Mapper\MapperInterface|string $defaultMapper
     * @return CrudManagerFactory
     */
    public function setDefaultMapper($defaultMapper)
    {
        $this->defaultMapper = $defaultMapper;
        return $this;
    }

    /**
     * @return \SpiffyCrud\Mapper\MapperInterface|string
     */
    public function getDefaultMapper()
    {
        return $this->defaultMapper;
    }

    /**
     * @param string|\Zend\Stdlib\Hydrator\HydratorInterface $defaultHydrator
     * @return CrudManagerFactory
     */
    public function setDefaultHydrator($defaultHydrator)
    {
        $this->defaultHydrator = $defaultHydrator;
        return $this;
    }

    /**
     * @return string|\Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getDefaultHydrator()
    {
        return $this->defaultHydrator;
    }
}