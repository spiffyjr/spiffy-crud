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
    protected $defaultHydrator = 'DoctrineModule\Stdlib\Hydrator\DoctrineObject';

    /**
     * A string with a service name for locating the adapter from the AdapterManager for persistence.
     *
     * @var string|AdapterInterface
     */
    protected $defaultAdapter = 'DoctrineObject';

    /**
     * A string with a service locator resource or a \Zend\Form\Builder\AnnotationBuilder to
     * use as a form builder.
     *
     * @var string
     */
    protected $formBuilder = 'DoctrineORMModule\Form\Annotation\AnnotationBuilder';

    /**
     * Services to register with the manager.
     *
     * @var array
     */
    protected $manager = array();

    /**
     * An array of adapters to register with the adapter manager.
     *
     * @var array
     */
    protected $adapters = array();

    /**
     * An array of controllers to register with the controller manager. This is handled by the
     * SpiffyCrud\Controller\AbstractFactory.
     *
     * @var array
     */
    protected $controllers = array();

    /**
     * An array of models to register with the crud manager. This is handled by the
     * SpiffyCrud\Model\AbstractFactory.
     *
     * @var array
     */
    protected $models = array();

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
     * @param \SpiffyCrud\Adapter\AdapterInterface|string $defaultAdapter
     * @return $this
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
     * @param array $manager
     * @return $this
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return array
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param array $controllers
     * @return $this
     */
    public function setControllers($controllers)
    {
        $this->controllers = $controllers;
        return $this;
    }

    /**
     * @return array
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    /**
     * @param array $adapters
     * @return $this
     */
    public function setAdapters($adapters)
    {
        $this->adapters = $adapters;
        return $this;
    }

    /**
     * @return array
     */
    public function getAdapters()
    {
        return $this->adapters;
    }
}