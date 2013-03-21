<?php

namespace SpiffyCrud\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;

interface MapperInterface
{
    /**
     * @param mixed $select
     * @param mixed|null $id
     * @param HydratorInterface $hydrator
     * @param object|null $entityPrototype
     * @return mixed
     */
    public function read($select, $id = null, HydratorInterface $hydrator = null, $entityPrototype = null);

    /**
     * @param mixed $entity
     * @param array $options
     * @param HydratorInterface $hydrator
     * @return mixed
     */
    public function create($entity, array $options = array(), HydratorInterface $hydrator = null);

    /**
     * @param mixed $where
     * @param array $options
     * @return mixed
     */
    public function delete($where, array $options = array());

    /**
     * @param mixed $entity
     * @param mixed|null $where
     * @param array $options
     * @param HydratorInterface $hydrator
     * @return mixed
     */
    public function update($entity, $where = null, array $options = array(), HydratorInterface $hydrator = null);
}