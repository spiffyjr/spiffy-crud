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
     * @param string $entityPrototype
     * @param string|integer $id
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function read($entityPrototype, $id, HydratorInterface $hydrator, array $options = array());

    /**
     * @param object $entity
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function create($entity, HydratorInterface $hydrator, array $options = array());

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function delete($entity, array $options = array());

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function update($entity, array $options = array());
}