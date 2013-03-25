<?php

namespace SpiffyCrud\Model;

use Zend\Stdlib\AbstractOptions;
use Zend\Stdlib\Hydrator\HydratorInterface;

abstract class AbstractModel extends AbstractOptions
{
    /**
     * The hydrator used to hydrate/extract data from the entity.
     *
     * @var \Zend\Stdlib\Hydrator\HydratorInterface|null
     */
    protected $hydrator;

    /**
     * The class for the entity.
     *
     * @var string
     */
    protected $entityClass;

    /**
     * The entity which is set from $entityClass if avialable.
     *
     * @var null|object
     */
    protected $entity;

    /**
     * The mapper used to persist data to storage.
     *
     * @var \SpiffyCrud\Mapper\MapperInterface
     */
    protected $mapper;

    /**
     * Additional options for the mapper such as table_name.
     *
     * @var array
     */
    protected $mapperOptions = array();

    /**
     * The form used to take user input for hydrating the entity.
     *
     * @var \Zend\Form\Form
     */
    protected $form;

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
     * @param \SpiffyCrud\Mapper\MapperInterface $mapper
     * @return AbstractModel
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * @return \SpiffyCrud\Mapper\MapperInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param \Zend\Form\Form $form
     * @return AbstractModel
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return \Zend\Form\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param null|\Zend\Stdlib\Hydrator\HydratorInterface $hydrator
     * @return AbstractModel
     */
    public function setHydrator(HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     * @return null|\Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     * @param array $mapperOptions
     * @return AbstractModel
     */
    public function setMapperOptions(array $mapperOptions)
    {
        $this->mapperOptions = $mapperOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getMapperOptions()
    {
        return $this->mapperOptions;
    }

    /**
     * @param null|object $entity
     * @return AbstractModel
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return null|object
     */
    public function getEntity()
    {
        return $this->entity;
    }
}