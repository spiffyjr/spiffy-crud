<?php

namespace SpiffyCrud;

use SpiffyCrud\Mapper\MapperInterface;
use SpiffyCrud\Model\AbstractModel;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CrudManager implements ServiceLocatorAwareInterface
{
    /**
     * @var HydratorInterface
     */
    protected $defaultHydrator;

    /**
     * @var MapperInterface
     */
    protected $defaultMapper;

    /**
     * @var FormManager
     */
    protected $formManager;

    /**
     * @var ModelManager
     */
    protected $modelManager;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @param ModelManager $modelManager
     * @param FormManager $formManager
     */
    public function __construct(ModelManager $modelManager, FormManager $formManager)
    {
        $this->modelManager = $modelManager;
        $this->formManager  = $formManager;
    }

    /**
     * @param MapperInterface $defaultMapper
     * @return CrudManager
     */
    public function setDefaultMapper(MapperInterface $defaultMapper)
    {
        $this->defaultMapper = $defaultMapper;
        return $this;
    }

    /**
     * @return MapperInterface
     */
    public function getDefaultMapper()
    {
        return $this->defaultMapper;
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param HydratorInterface $defaultHydrator
     * @return CrudManager
     */
    public function setDefaultHydrator(HydratorInterface $defaultHydrator)
    {
        $this->defaultHydrator = $defaultHydrator;
        return $this;
    }

    /**
     * @return HydratorInterface
     */
    public function getDefaultHydrator()
    {
        if (!$this->defaultHydrator instanceof HydratorInterface) {
            $this->defaultHydrator = new ClassMethods();
        }
        return $this->defaultHydrator;
    }

    /**
     * @param $name
     * @return \Zend\Form\Form
     */
    public function getForm($name)
    {
        return $this->getFormManager()->get($name);
    }

    /**
     * @param $name
     * @return Model\AbstractModel
     */
    public function getModel($name)
    {
        return $this->getModelManager()->get($name);
    }

    /**
     * @return \SpiffyCrud\FormManager
     */
    public function getFormManager()
    {
        return $this->formManager;
    }

    /**
     * @return \SpiffyCrud\ModelManager
     */
    public function getModelManager()
    {
        return $this->modelManager;
    }

    /**
     * @param AbstractModel $model
     * @param string|integer|null $id
     * @return mixed
     */
    public function read(AbstractModel $model, $id = null)
    {
        $this->validateModel($model);

        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);

        return $model->getMapper()->read($entity, $id, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return object
     */
    public function create(AbstractModel $model, array $data)
    {
        $this->validateModel($model);

        $entity   = $this->hydrateModelEntity($data, $model);
        $hydrator = $this->getHydratorFromModel($model);

        return $model->getMapper()->create($entity, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param string|integer $id
     * @param AbstractModel $model
     * @param array $data
     * @return object
     */
    public function update($id, AbstractModel $model, array $data)
    {
        $this->validateModel($model);

        $entity   = $this->hydrateModelEntity($data, $model);
        $hydrator = $this->getHydratorFromModel($model);

        return $model->getMapper()->update($entity, $id, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param mixed $id
     * @param AbstractModel $model
     * @return AbstractModel
     */
    public function delete($id, AbstractModel $model)
    {
        $this->validateModel($model);
        $model->getMapper()->delete($id, $model->getMapperOptions());
    }

    /**
     * @param array $data
     * @param AbstractModel $model
     * @return AbstractModel
     */
    public function hydrateModelEntity(array $data, AbstractModel $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);

        return $hydrator->hydrate($data, $entity);
    }

    /**
     * @param AbstractModel $model
     * @return array
     */
    public function extractModelEntity(AbstractModel $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);

        return $hydrator->extract($entity);
    }

    /**
     * @param AbstractModel $model
     * @return object
     * @throws \RuntimeException when model is missing an entity and entityClass.
     */
    public function getEntityFromModel(AbstractModel $model)
    {
        if ($model->getEntity()) {
            return $model->getEntity();
        }
        if ($model->getEntityClass()) {
            $entityClass = $model->getEntityClass();
            $model->setEntity(new $entityClass);

            return $model->getEntity();
        }
        throw new \RuntimeException('Model does not have an entity or entityClass');
    }

    /**
     * Gets the mapper for a model or the default is the model doesn't have one.
     *
     * @param AbstractModel $model
     * @return null|MapperInterface
     */
    public function getMapperFromModel(AbstractModel $model)
    {
        if ($model->getMapper()) {
            return $model->getMapper();
        }
        return $this->getDefaultMapper();
    }

    /**
     * Gets the hydrator for a model or the default is the model doesn't have one.
     *
     * @param AbstractModel $model
     * @return HydratorInterface
     */
    public function getHydratorFromModel(AbstractModel $model)
    {
        if ($model->getHydrator()) {
            return $model->getHydrator();
        }
        return $this->getDefaultHydrator();
    }

    /**
     * Validates that a model has all the required parameters.
     *
     * @param AbstractModel $model
     * @throws \RuntimeException if the model does not validate
     */
    protected function validateModel(AbstractModel $model)
    {
        if (!$model->getMapper() instanceof MapperInterface) {
            throw new \RuntimeException('Models require a mapper');
        }
    }
}
