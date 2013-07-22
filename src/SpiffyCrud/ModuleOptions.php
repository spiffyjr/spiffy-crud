<?php

namespace SpiffyCrud;

use SpiffyCrud\Adapter\AdapterInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ModuleOptions extends AbstractOptions
{
    /**
     * A string with a service locator resource or a HydratorInterface to
     * use as the default hydrator.
     *
     * @var string|HydratorInterface
     */
    protected $defaultHydrator = 'Zend\Stdlib\Hydrator\ClassMethods';

    /**
     * A string with a service locator resource or a AdapterInterface to
     * use as the default adapter.
     *
     * @var string|AdapterInterface
     */
    protected $defaultAdapter = 'SpiffyCrud\Adapter\DoctrineObject';

    /**
     * A string with a service locator resource or a \Zend\Form\Builder\AnnotationBuilder to
     * use as a form builder.
     *
     * @var string
     */
    protected $formBuilder = 'DoctrineORMModule\Form\Annotation\AnnotationBuilder';

    /**
     * An array of models to register..
     *
     * @var array
     */
    protected $models = array() ;

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
     * @param \SpiffyCrud\Adapter\AdapterInterface|string $defaultAdapter
     * @return CrudManagerFactory
     */
    public function setDefaultAdapter($defaultAdapter)
    {
        $this->defaultAdapter = $defaultAdapter;
        return $this;
    }

    /**
     * @return \SpiffyCrud\Adapter\AdapterInterface|string
     */
    public function getDefaultAdapter()
    {
        return $this->defaultAdapter;
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