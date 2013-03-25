<?php

namespace SpiffyCrud\Service;

use SpiffyCrud\Mapper\MapperInterface;
use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ManagerCrudFactoryOptions extends AbstractOptions
{
    /**
     * A string with a service locator resource or a HydratorInterface to
     * use as the default hydrator.
     *
     * @var string|HydratorInterface
     */
    protected $defaultHydrator;

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
     * The service manager configuration for the form manager.
     *
     * @var array
     */
    protected $forms;

    /**
     * The service manager configuration for the model manager.
     *
     * @var array
     */
    protected $models;

    /**
     * @param string $formBuilder
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
     * @return ManagerCrudFactoryOptions
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
     * @param array $forms
     * @return ManagerCrudFactoryOptions
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

    /**
     * @param \SpiffyCrud\Mapper\MapperInterface|string $defaultMapper
     * @return ManagerCrudFactoryOptions
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
     * @return ManagerCrudFactoryOptions
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