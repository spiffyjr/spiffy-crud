<?php

namespace SpiffyCrud\Mapper;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Stdlib\Hydrator\HydratorInterface;

class DoctrineObject implements MapperInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $entityPrototype
     * @param null|HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function readAll($entityPrototype, HydratorInterface $hydrator = null, array $options = array())
    {
        return $this->objectManager->getRepository($entityPrototype)->findAll();
    }

    /**
     * @param string $entityPrototype
     * @param string|integer $id
     * @param HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function read($entityPrototype, $id, HydratorInterface $hydrator, array $options = array())
    {
        return $this->objectManager->getRepository($entityPrototype)->find($id);
    }

    /**
     * @param object $entity
     * @param null|HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function create($entity, HydratorInterface $hydrator = null, array $options = array())
    {
        return $this->persist($entity);
    }

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function delete($entity, array $options = array())
    {
        $this->objectManager->remove($entity);
        $this->objectManager->flush();
    }

    /**
     * @param object $entity
     * @param array $options
     * @return mixed
     */
    public function update($entity, array $options = array())
    {
        return $this->persist($entity);
    }


    /**
     * @param object $entity
     * @return object $entity
     */
    protected function persist($entity)
    {
        $this->objectManager->persist($entity);
        $this->objectManager->flush();

        return $entity;
    }
}