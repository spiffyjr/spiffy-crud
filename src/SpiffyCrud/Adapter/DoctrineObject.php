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
     * @param object $entity
     * @param array $options
     * @return object $entity
     */
    public function persist($entity, array $options = array())
    {
        $this->objectManager->persist($entity);
        $this->objectManager->flush();

        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll($entityPrototype, HydratorInterface $hydrator, array $options = array())
    {
        return $this->objectManager->getRepository(get_class($entityPrototype))->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function find($entityPrototype, $id, HydratorInterface $hydrator, array $options = array())
    {
        return $this->objectManager->getRepository(get_class($entityPrototype))->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function remove($entity, array $options = array())
    {
        $this->objectManager->remove($entity);
        $this->objectManager->flush();
    }
}