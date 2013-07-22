<?php

namespace SpiffyCrud\Adapter;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Stdlib\Hydrator\HydratorInterface;

class DoctrineObject implements AdapterInterface
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
     * {@inheritDoc}
     */
    public function findAll($entityPrototype, HydratorInterface $hydrator = null, array $options = array())
    {
        return $this->objectManager->getRepository($entityPrototype)->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function find($entityPrototype, $id, HydratorInterface $hydrator, array $options = array())
    {
        return $this->objectManager->getRepository($entityPrototype)->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function create($entity, array $options = array())
    {
        return $this->persist($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($entity, array $options = array())
    {
        $this->objectManager->remove($entity);
        $this->objectManager->flush();
    }

    /**
     * {@inheritDoc}
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