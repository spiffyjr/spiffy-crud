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
     * @param object $entity
     * @param string|integer $id
     * @param null|HydratorInterface $hydrator
     * @return object
     */
    public function read($entity, $id, HydratorInterface $hydrator = null)
    {
        return $this->objectManager->getRepository(get_class($entity))->find($id);
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
     * @param string|integer $where
     * @param string $entityPrototype
     * @param array $options
     * @return void
     */
    public function delete($where, $entityPrototype, array $options = array())
    {
        $entity = $this->read(new $entityPrototype, $where, null);
        $this->objectManager->remove($entity);
        $this->objectManager->flush();
    }

    /**
     * @param object $entity
     * @param mixed|null $where
     * @param null|HydratorInterface $hydrator
     * @param array $options
     * @return object
     */
    public function update($entity, $where = null, HydratorInterface $hydrator = null, array $options = array())
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