<?php

namespace SpiffyCrud;

use SpiffyCrud\Mapper\MapperInterface;
use SpiffyCrud\Model\AbstractModel;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CrudManager
{
    /**
     * @var HydratorInterface
     */
    protected $defaultHydrator;

    /**
     * @var FormManager
     */
    protected $formManager;

    /**
     * @var ModelManager
     */
    protected $modelManager;

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
     * @param \Zend\Stdlib\Hydrator\HydratorInterface $defaultHydrator
     * @return CrudManager
     */
    public function setDefaultHydrator(HydratorInterface $defaultHydrator)
    {
        $this->defaultHydrator = $defaultHydrator;
        return $this;
    }

    /**
     * @return \Zend\Stdlib\Hydrator\HydratorInterface
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

    public function create(AbstractModel $model, array $data)
    {
        $this->validateModel($model);

        $entity = $this->hydrateModelEntity($data, $model);
        return $model->getMapper()->create($entity, $model->getMapperOptions(), $model->getHydrator());
    }

    public function update($id, AbstractModel $model, array $data)
    {
        $this->validateModel($model);

        $entity = $this->hydrateModelEntity($data, $model);
        return $model->getMapper()->update($entity, $id, $model->getMapperOptions(), $model->getHydrator());
    }

    /**
     * @param mixed $id
     * @param AbstractModel $model
     * @return AbstractModel
     */
    public function delete($id, AbstractModel $model)
    {
        $this->validateModel($model);
        return $model->getMapper()->delete($id, $model->getMapperOptions());
    }

    /**
     * @param array $data
     * @param AbstractModel $model
     * @return AbstractModel
     */
    public function hydrateModelEntity(array $data, AbstractModel $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $model->getHydrator() ? $model->getHydrator() : $this->getDefaultHydrator();

        return $hydrator->hydrate($data, $entity);
    }

    /**
     * @param AbstractModel $model
     * @return array
     */
    public function extractModelEntity(AbstractModel $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $model->getHydrator() ? $model->getHydrator() : $this->getDefaultHydrator();

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
