<?php

namespace SpiffyCrud\Mapper;

use SpiffyCrud\Mapper\MapperInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

class SimpleArray implements MapperInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @return SimpleArray
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
    /**
     * @param object $entityPrototype
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function readAll($entityPrototype, HydratorInterface $hydrator, array $options = array())
    {
        $result = array();
        foreach($this->data as $key => $data) {
            $result[$key] = $hydrator->hydrate($data, new $entityPrototype);
        }
        return $result;
    }

    /**
     * @param object $entityPrototype
     * @param string|integer $id
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function read($entityPrototype, $id, HydratorInterface $hydrator, array $options = array())
    {
        return $hydrator->hydrate($this->data[$id], $entityPrototype);
    }

    /**
     * @param object $entity
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function create($entity, HydratorInterface $hydrator, array $options = array())
    {
        $this->data[] = $hydrator->extract($entity);
        return $entity;
    }

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function delete($entity, array $options = array())
    {
        unset($this->data[$this->getEntityIndex($entity)]);
    }

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function update($entity, array $options = array())
    {
        $this->data[$this->getEntityIndex($entity)] = $entity;
        return $entity;
    }

    /**
     * @param object $entity
     * @return null|int
     */
    protected function getEntityIndex($entity)
    {
        foreach ($this->data as $index => $data) {
            if ($entity === $data) {
                return $index;
            }
        }
        return null;
    }
}