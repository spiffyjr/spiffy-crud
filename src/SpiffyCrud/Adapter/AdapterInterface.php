<?php

namespace SpiffyCrud\Adapter;

use Zend\Stdlib\Hydrator\HydratorInterface;

interface AdapterInterface
{
    /**
     * @param string $entityPrototype
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return array
     */
    public function findAll($entityPrototype, HydratorInterface $hydrator, array $options = array());

    /**
     * @param string $entityPrototype
     * @param string|integer $id
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function find($entityPrototype, $id, HydratorInterface $hydrator, array $options = array());

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function persist($entity, array $options = array());

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function remove($entity, array $options = array());
}