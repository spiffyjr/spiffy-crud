<?php

namespace SpiffyCrud\Model;

use Zend\Stdlib\AbstractOptions;

abstract class AbstractModel extends AbstractOptions implements ModelInterface
{
    /**
     * Custom name to use for display purposes.
     *
     * @var string
     */
    protected $displayName;

    /**
     * String for the hydrator to pull from the hydrator manager.
     *
     * @var string
     */
    protected $hydratorName;

    /**
     * The class for the entity.
     *
     * @var string
     */
    protected $entityClass;

    /**
     * The spec to use for creating the custom form for this entity. If no form
     * is present then forms will be built using an annotation builder from the model
     * prototype. Arrays are passed to the form factory and strings are retrieved
     * from the form manager, 'forms' abstract service factory (if registered), and
     * finaly instantiated directly if the class exists.
     *
     * @var array|string
     */
    protected $formSpec;

    /**
     * Additional options for the view such as column setup, etc.
     *
     * @var array
     */
    protected $viewOptions = array();

    /**
     * Additional options for the adapter such as table_name.
     *
     * @var array
     */
    protected $adapterOptions = array();

    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $class
     * @return AbstractModel
     */
    public function setEntityClass($class)
    {
        $this->entityClass = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param array $adapterOptions
     * @return AbstractModel
     */
    public function setAdapterOptions(array $adapterOptions)
    {
        $this->adapterOptions = $adapterOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getAdapterOptions()
    {
        return $this->adapterOptions;
    }

    /**
     * @param array $viewOptions
     * @return AbstractModel
     */
    public function setViewOptions(array $viewOptions)
    {
        $this->viewOptions = $viewOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getViewOptions()
    {
        return $this->viewOptions;
    }

    /**
     * @param string $hydratorName
     * @return $this
     */
    public function setHydratorName($hydratorName)
    {
        $this->hydratorName = $hydratorName;
        return $this;
    }

    /**
     * @return string
     */
    public function getHydratorName()
    {
        return $this->hydratorName;
    }

    /**
     * @param array|string $formSpec
     * @return $this
     */
    public function setFormSpec($formSpec)
    {
        $this->formSpec = $formSpec;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getFormSpec()
    {
        return $this->formSpec;
    }
}