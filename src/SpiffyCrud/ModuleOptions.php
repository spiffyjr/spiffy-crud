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
     * A string with a service name for locating the adapter for persistence.
     *
     * @var string|AdapterInterface
     */
    protected $adapter = 'SpiffyCrud\Adapter\DoctrineObject';

    /**
     * A string with a service locator resource or a \Zend\Form\Builder\AnnotationBuilder to
     * use as a form builder.
     *
     * @var string
     */
    protected $formBuilder = 'DoctrineORMModule\Form\Annotation\AnnotationBuilder';

    /**
     * An array of models to register with the crud manager.
     *
     * @var array
     */
    protected $models = array();

    /**
     * An array of forms to register with the form manager for models to use.
     *
     * @var array
     */
    protected $forms = array() ;

    /**
     * @param string $formBuilder
     * @return $this
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
     * @return $this
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
     * @param \SpiffyCrud\Adapter\AdapterInterface|string $adapter
     * @return $this
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
        return $this;
    }

    /**
     * @return \SpiffyCrud\Adapter\AdapterInterface|string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string|\Zend\Stdlib\Hydrator\HydratorInterface $defaultHydrator
     * @return $this
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

    /**
     * @param array $forms
     * @return $this
     */
    public function setForms($forms)
    {
        $this->forms = $forms;
        return $this;
    }

    /**
     * @return array
     */
    public function getForms()
    {
        return $this->forms;
    }
}