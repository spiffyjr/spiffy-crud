<?php

namespace SpiffyCrud;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CrudManager extends ServiceManager
{
    const UNGROUPED_NAME = '__UNGROUPED__';

    /**
     * @var HydratorInterface
     */
    protected $defaultHydrator;

    /**
     * @var Adapter\AdapterInterface
     */
    protected $defaultAdapter;

    /**
     * @var AnnotationBuilder
     */
    protected $formBuilder;

    /**
     * {@inheritDoc}
     */
    public function get($name, $options = array(), $usePeeringServiceManagers = true)
    {
        $instance = parent::get($name, $usePeeringServiceManagers);
        $this->validateModel($instance);
        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function setService($name, $service, $shared = true)
    {
        if ($service) {
            $this->validateModel($service);
        }
        parent::setService($name, $service, $shared);
        return $this;
    }

    /**
     * @return array
     */
    public function getModelsAsGroup()
    {
        $models = $this->getRegisteredServices();
        $result = array();

        /** @var Model\ModelInterface $model */
        foreach ($models as $services) {
            foreach ($services as $service) {
                $model = $this->get($service);

                if ($model->getGroupName()) {
                    $result[$model->getGroupName()][] = $model;
                } else {
                    $result[self::UNGROUPED_NAME][] = $model;
                }
            }
        }
        return $result;
    }

    /**
     * @param AnnotationBuilder $formBuilder
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
     * @param Adapter\AdapterInterface $defaultAdapter
     * @return CrudManager
     */
    public function setDefaultAdapter(Adapter\AdapterInterface $defaultAdapter)
    {
        $this->defaultAdapter = $defaultAdapter;
        return $this;
    }

    /**
     * @return Adapter\AdapterInterface
     */
    public function getDefaultAdapter()
    {
        return $this->defaultAdapter;
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
     * @param Model\ModelInterface $model
     * @param string|integer $id
     * @return object
     */
    public function findEntity(Model\ModelInterface $model, $id)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);
        $adapter  = $this->getAdapterFromModel($model);

        return $adapter->find(get_class($entity), $id, $hydrator, $model->getAdapterOptions());
    }

    /**
     * @param Model\ModelInterface $model
     * @return array|\Traversable
     */
    public function findAllEntities(Model\ModelInterface $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);
        $adapter  = $this->getAdapterFromModel($model);

        return $adapter->findAll(get_class($entity), $hydrator, $model->getAdapterOptions());
    }

    /**
     * @param Model\ModelInterface $model
     * @return object
     */
    public function createEntity(Model\ModelInterface $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $adapter  = $this->getAdapterFromModel($model);

        return $adapter->create($entity, $model->getAdapterOptions());
    }

    /**
     * @param Model\ModelInterface $model
     * @return object
     */
    public function updateEntity(Model\ModelInterface $model)
    {
        $entity  = $this->getEntityFromModel($model);
        $adapter = $this->getAdapterFromModel($model);

        return $adapter->update($entity, $model->getAdapterOptions());
    }

    /**
     * @param Model\ModelInterface $model
     * @return void
     */
    public function removeEntity(Model\ModelInterface $model)
    {
        $adapter = $this->getAdapterFromModel($model);
        $entity  = $this->getEntityFromModel($model);

        $adapter->remove($entity, $model->getAdapterOptions());
    }

    /**
     * @param array $data
     * @param Model\ModelInterface $model
     * @return Model\ModelInterface
     */
    public function hydrateModelEntity(array $data, Model\ModelInterface $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);

        return $hydrator->hydrate($data, $entity);
    }

    /**
     * @param Model\ModelInterface $model
     * @return array
     */
    public function extractModelEntity(Model\ModelInterface $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);

        return $hydrator->extract($entity);
    }

    /**
     * @param Model\ModelInterface $model
     * @return object
     * @throws \RuntimeException when model is missing an entity and entityClass.
     */
    public function getEntityFromModel(Model\ModelInterface $model)
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
     * Gets the adapter for a model or the default is the model doesn't have one.
     *
     * @param Model\ModelInterface $model
     * @throws \RuntimeException if no adapter is available.
     * @return null|Adapter\AdapterInterface
     */
    public function getAdapterFromModel(Model\ModelInterface $model)
    {
        if ($model->getAdapter()) {
            return $model->getAdapter();
        } else if ($this->getDefaultAdapter()) {
            return $this->getDefaultAdapter();
        }
        throw new \RuntimeException('Model does not have a adapter and default adapter was not registered');
    }

    /**
     * Gets the hydrator for a model or the default is the model doesn't have one.
     *
     * @param Model\ModelInterface $model
     * @return HydratorInterface
     */
    public function getHydratorFromModel(Model\ModelInterface $model)
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
     * @param Model\ModelInterface $model
     * @param null|object $entity
     * @throws \RuntimeException if no form can be created or found from form manager
     * @throws \InvalidArgumentException when entity supplied does not match model entity class
     * @return \Zend\Form\Form
     */
    public function getFormFromModel(Model\ModelInterface $model, $entity = null)
    {
        if (is_object($entity)) {
            if (get_class($entity) != $model->getEntityClass()) {
                throw new \InvalidArgumentException('Supplied entity does not match model entityClass');
            }
        } else {
            $entity = $this->getEntityFromModel($model);
        }

        $form = $model->getForm();

        if ($form instanceof Form) {
            return $form;
        } else if (is_string($form)) {
            if ($this->getServiceLocator()->has($form)) {
                $form = $this->getServiceLocator()->get($form);
            } else if (class_exists($form)) {
                $form = new $form;
            } else {
                throw new \RuntimeException('String for form given but could not be found.');
            }
        } else {
            $builder = $this->getFormBuilder();
            $form    = $builder->createForm($entity);
            $form->add(array(
                'name' => 'submit',
                'type' => 'submit',
                'attributes' => array(
                    'value' => 'Submit'
                )
            ));
        }

        if (!$form instanceof Form) {
            throw new \RuntimeException('Model forms should be a string or instance of Zend\Form\Form');
        }
        $form->setHydrator($this->getHydratorFromModel($model));
        $form->bind($entity);

        return $form;
    }

    /**
     * @param mixed $model
     * @throws Exception\InvalidModelException
     * @return void
     */
    public function validateModel($model)
    {
        if (!$model instanceof Model\ModelInterface) {
            throw new Exception\InvalidModelException(sprintf(
                'Model of type %s is invalid; must implement %s\Model\ModelInterface',
                (is_object($model) ? get_class($model) : gettype($model)),
                __NAMESPACE__
            ));
        }

        if (!$model->getName()) {
            throw new Exception\InvalidModelException(sprintf(
                'Model "%s" is missing a name.',
                (is_object($model) ? get_class($model) : gettype($model)),
                __NAMESPACE__
            ));
        }
    }
}
