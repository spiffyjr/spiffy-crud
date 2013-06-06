<?php

namespace SpiffyCrud;

use SpiffyCrud\Mapper\MapperInterface;
use SpiffyCrud\Model\AbstractModel;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CrudManager implements ServiceLocatorAwareInterface
{
    const UNGROUPED_NAME = '__UNGROUPED__';

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
     * @var AbstractModel[]
     */
    protected $models = array();

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator = null;

    /**
     * @var array map of characters to be replaced through strtr
     */
    protected $canonicalNamesReplacements = array('-' => '', '_' => '', ' ' => '', '\\' => '', '/' => '');

    /**
     * @param AbstractModel $model
     * @throws \InvalidArgumentException
     * @return CrudManager
     */
    public function addModel(AbstractModel $model)
    {
        $model->init();

        $name = get_class($model);
        $name = $this->canonicalize($name);

        if (!$model->getName()) {
            throw new \InvalidArgumentException(sprintf(
                'missing model name for %s',
                get_class($model)
            ));
        }

        if (isset($this->models[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'model with name "%s" (%s) is already registered',
                $name,
                get_class($model)
            ));
        }

        $this->models[$name] = $model;
        return $this;
    }

    /**
     * @param array $models
     * @return CrudManager
     */
    public function setModels(array $models)
    {
        foreach ($models as $model) {
            $this->addModel($model);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getModelsAsGroup()
    {
        $models = $this->getModels();
        $result = array();

        /** @var AbstractModel $model */
        foreach ($models as $canonicalName => $model) {
            if ($model->getGroupName()) {
                $result[$model->getGroupName()][$canonicalName] = $model;
            } else {
                $result[self::UNGROUPED_NAME][$canonicalName] = $model;
            }
        }
        return $result;
    }

    /**
     * @param $name
     * @return null|\SpiffyCrud\Model\AbstractModel
     */
    public function getModel($name)
    {
        $canonicalName = $this->canonicalize($name);
        return isset($this->models[$canonicalName]) ? $this->models[$canonicalName] : null;
    }

    /**
     * @return array
     */
    public function getModels()
    {
        return $this->models;
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
     * @param AbstractModel $model
     * @param string|integer $id
     * @return object
     */
    public function read(AbstractModel $model, $id)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);
        $mapper   = $this->getMapperFromModel($model);

        return $mapper->read(get_class($entity), $id, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param AbstractModel $model
     * @return array|\Traversable
     */
    public function readAll(AbstractModel $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);
        $mapper   = $this->getMapperFromModel($model);

        return $mapper->readAll(get_class($entity), $hydrator, $model->getMapperOptions());
    }

    /**
     * @param AbstractModel $model
     * @return object
     */
    public function create(AbstractModel $model)
    {
        $entity   = $this->getEntityFromModel($model);
        $hydrator = $this->getHydratorFromModel($model);
        $mapper   = $this->getMapperFromModel($model);

        return $mapper->create($entity, $hydrator, $model->getMapperOptions());
    }

    /**
     * @param AbstractModel $model
     * @return object
     */
    public function update(AbstractModel $model)
    {
        $entity = $this->getEntityFromModel($model);
        $mapper = $this->getMapperFromModel($model);

        return $mapper->update($entity, $model->getMapperOptions());
    }

    /**
     * @param AbstractModel $model
     * @return void
     */
    public function delete(AbstractModel $model)
    {
        $mapper = $this->getMapperFromModel($model);
        $entity = $this->getEntityFromModel($model);

        $mapper->delete($entity, $model->getMapperOptions());
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
     * @param string $string
     * @return string
     */
    public function canonicalize($string)
    {
        return strtolower(strtr($string, $this->canonicalNamesReplacements));
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
     * @param null|object $entity
     * @throws \RuntimeException if no form can be created or found from form manager
     * @throws \InvalidArgumentException when entity supplied does not match model entity class
     * @return \Zend\Form\Form
     */
    public function getFormFromModel(AbstractModel $model, $entity = null)
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
}
