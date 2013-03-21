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
     * @param mixed $id
     * @param mixed $input
     * @return mixed|object
     */
    public function find($id, $input)
    {
        return $this->objectManager->getRepository(get_class($input))->find($id);
    }

    /**
     * @param $input
     * @return mixed
     * @throws \InvalidArgumentException if input is not an object
     */
    public function findAll($input)
    {
        if (!is_object($input)) {
            throw new \InvalidArgumentException('object expected');
        }
        return $this->objectManager->getRepository(get_class($input))->findAll();
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
        $repository = $this->objectManager->getRepository($select);
        if ($id) {
            return $repository->find($id);
        }
        return $repository->findAll();
    }

    /**
     * @param mixed $entity
     * @param array $options
     * @param HydratorInterface $hydrator
     * @return mixed
     */
    public function create($entity, array $options = array(), HydratorInterface $hydrator = null)
    {
        return $this->persist($entity);
    }

    /**
     * @param mixed $where
     * @param array $options
     * @return mixed
     */
    public function delete($where, array $options = array())
    {
        $this->objectManager->remove($where);
        return $where;
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