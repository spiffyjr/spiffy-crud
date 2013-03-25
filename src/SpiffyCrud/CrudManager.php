<?php

namespace SpiffyCrud;

use SpiffyCrud\Form\Annotation\ModelListener;
use SpiffyCrud\Mapper\MapperInterface;
use SpiffyCrud\Model\AbstractModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Form;
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
     * @var AnnotationBuilder
     */
    protected $formBuilder;

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
     * @param \Zend\Form\Annotation\AnnotationBuilder $formBuilder
     * @return CrudManager
     */
    public function setFormBuilder(AnnotationBuilder $formBuilder)
    {
        $this->formBuilder = $formBuilder;
        return $this;
    }

    /**
     * @return \Zend\Form\Annotation\AnnotationBuilder
     */
    public function getFormBuilder()
    {
        if (!$this->formBuilder instanceof AnnotationBuilder) {
            $this->formBuilder = new AnnotationBuilder();
        }
        return $this->formBuilder;
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
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);
        $mapper   = $this->getMapperFromModel($model);

        return $mapper->read($entity, $id, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return object
     */
    public function create(AbstractModel $model, array $data)
    {
        $entity   = $this->hydrateModelEntity($data, $model);
        $hydrator = $this->getHydratorFromModel($model);
        $mapper   = $this->getMapperFromModel($model);

        return $mapper->create($entity, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param string|integer $id
     * @param AbstractModel $model
     * @param array $data
     * @return object
     */
    public function update($id, AbstractModel $model, array $data)
    {
        $entity   = $this->hydrateModelEntity($data, $model);
        $hydrator = $this->getHydratorFromModel($model);
        $mapper   = $this->getMapperFromModel($model);

        return $mapper->update($entity, $id, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param mixed $id
     * @param AbstractModel $model
     * @return void
     */
    public function delete($id, AbstractModel $model)
    {
        $mapper = $this->getMapperFromModel($model);
        $mapper->delete($id, $model->getMapperOptions());
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
     * @throws \RuntimeException if no mapper is available.
     * @return null|MapperInterface
     */
    public function getMapperFromModel(AbstractModel $model)
    {
        if ($model->getMapper()) {
            return $model->getMapper();
        } else if ($this->getDefaultMapper()) {
            return $this->getDefaultMapper();
        }
        throw new \RuntimeException('Model does not have a mapper and default mapper was not registered');
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
     * Gets a form for a model. Will pull from form manager, instantiate a class, or use the
     * default form builder in order.
     *
     * @param AbstractModel $model
     * @throws \RuntimeException if no form can be created or found from form manager
     * @return \Zend\Form\Form
     */
    public function getFormFromModel(AbstractModel $model)
    {
        $form = $model->getForm();
        if ($form instanceof Form) {
            return $form;
        } else if (is_string($form)) {
            if ($this->formManager->has($form)) {
                $form = $this->formManager->get($form);
            } else if (class_exists($form)) {
                $form = new $form;
            } else {
                throw new \RuntimeException('String for form given but could not be found.');
            }
        } else {
            $entity  = $this->getEntityFromModel($model);
            $builder = $this->getFormBuilder();

            $form = $builder->createForm($entity);
            $form->setHydrator($this->getHydratorFromModel($model));
        }

        if (!$form instanceof Form) {
            throw new \RuntimeException('Model forms should be a string or instance of Zend\Form\Form');
        }

        return $form;
    }
}
