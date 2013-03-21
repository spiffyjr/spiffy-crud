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
     * @param mixed $select
     * @param mixed|null $id
     * @param HydratorInterface $hydrator
     * @param object|null $entityPrototype
     * @return mixed
     */
    public function read($select, $id = null, HydratorInterface $hydrator = null, $entityPrototype = null)
    {
        if ($select == 'all') {
            return $this->data;
        }
        $this->checkIndex($select);
        return $this->data[$select];
    }

    /**
     * @param mixed $entity
     * @param array $options
     * @param HydratorInterface $hydrator
     * @return mixed
     */
    public function create($entity, array $options = array(), HydratorInterface $hydrator = null)
    {
        $this->data[] = $entity;
        return $entity;
    }

    /**
     * @param mixed $where
     * @param array $options
     * @return mixed
     */
    public function delete($where, array $options = array())
    {
        $this->checkIndex($where);
        $deleted = $this->data[$where];
        unset($this->data[$where]);
        return $deleted;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $where
     * @param array $options
     * @param HydratorInterface $hydrator
     * @return mixed
     */
    public function update($entity, $where = null, array $options = array(), HydratorInterface $hydrator = null)
    {
        $this->checkIndex($where);
        $this->data[$where] = $entity;
        return $entity;
    }

    /**
     * @param string|int $index
     * @throws \InvalidArgumentException on missing or invalid index
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