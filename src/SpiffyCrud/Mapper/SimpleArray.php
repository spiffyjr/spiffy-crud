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
     * @param object $entity
     * @param string|integer $id
     * @param HydratorInterface $hydrator
     * @return object
     */
    public function read($entity, $id, HydratorInterface $hydrator)
    {
        $this->checkIndex($id);
        return $hydrator->hydrate($this->data[$id], $entity);
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
     * @param string|integer $where
     * @param null|string $entityPrototype
     * @param array $options
     * @return void
     */
    public function delete($where, $entityPrototype = null, array $options = array())
    {
        $this->checkIndex($where);
        unset($this->data[$where]);
    }

    /**
     * @param object $entity
     * @param mixed|null $where
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function update($entity, $where = null, HydratorInterface $hydrator, array $options = array())
    {
        $this->checkIndex($where);
        $this->data[$where] = $hydrator->extract($entity);
        return $entity;
    }

    /**
     * @param string|integer $index
     * @throws \InvalidArgumentException on missing or invalid index
     * @throws \OutOfBoundsException if the index is out of bounds
     */
    protected function checkIndex($index)
    {
        if (!is_string($index) && !is_int($index)) {
            throw new \InvalidArgumentException('invalid index type');
        }
        if (!isset($this->data[$index])) {
            throw new \OutOfBoundsException('invalid index');
        }
    }
}