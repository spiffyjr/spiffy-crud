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
        $entities = $this->objectManager->getRepository(get_class($entityPrototype))->findAll();

        $result = array();
        foreach ($entities as $entity) {
            if (is_object($entity)) {
                $result[] = $hydrator->extract($entity);
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function find($entityPrototype, $id, HydratorInterface $hydrator, array $options = array())
    {
        $entity = $this->objectManager->getRepository(get_class($entityPrototype))->find($id);

        if (is_object($entity)) {
            return $hydrator->extract($entity);
        }
        return $entity;
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