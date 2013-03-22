<?php

namespace SpiffyCrud\Mapper;

use Zend\Stdlib\Hydrator\HydratorInterface;

interface MapperInterface
{
    /**
     * @param string $entityPrototype
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function readAll($entityPrototype, HydratorInterface $hydrator, array $options = array());

    /**
     * @param object $entity
     * @param string|integer $id
     * @param HydratorInterface $hydrator
     * @return object
     */
    public function read($entity, $id, HydratorInterface $hydrator);

    /**
     * @param object $entity
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function create($entity, HydratorInterface $hydrator, array $options = array());

    /**
     * @param string|integer $where
     * @param string $entityPrototype
     * @param array $options
     * @return void
     */
    public function delete($where, $entityPrototype, array $options = array());

    /**
     * @param object $entity
     * @param mixed|null $where
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function update($entity, $where = null, HydratorInterface $hydrator, array $options = array());
}