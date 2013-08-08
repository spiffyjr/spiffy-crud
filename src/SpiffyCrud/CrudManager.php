<?php

namespace SpiffyCrud;

use SpiffyCrud\Adapter;
use SpiffyCrud\Exception;
use Zend\Form;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class CrudManager extends AbstractPluginManager
{
    /**
     * @var Adapter\AdapterInterface
     */
    protected $defaultAdapter = 'DoctrineObject';

    /**
     * @var string
     */
    protected $defaultHydrator = 'ClassMethods';

    /**
     * @var Form\Annotation\AnnotationBuilder
     */
    protected $formBuilder;

    /**
     * @var Adapter\AdapterManager
     */
    protected $adapterManager;

    /**
     * @var Form\FormElementManager
     */
    protected $formElementManager;

    /**
     * @var Form\Factory
     */
    protected $formFactory;

    /**
     * @var HydratorPluginManager
     */
    protected $hydratorManager;

    /**
     * @var object[]
     */
    protected $prototypes;

    /**
     * @param string $name
     * @return array
     */
    public function findAll($name)
    {
        /** @var Model\ModelInterface $model */
        $model     = $this->get($name);
        $hydrator  = $this->getHydrator($name);
        $prototype = $this->getPrototype($name);

        return $this->getAdapter($name)->findAll(
            $prototype,
            $hydrator,
            $model->getAdapterOptions()
        );
    }

    /**
     * @param string $name
     * @param mixed $id
     * @return object
     */
    public function find($name, $id)
    {
        /** @var Model\ModelInterface $model */
        $model     = $this->get($name);
        $hydrator  = $this->getHydrator($name);
        $prototype = $this->getPrototype($name);

        return $this->getAdapter($name)->find(
            $prototype,
            $id,
            $hydrator,
            $model->getAdapterOptions()
        );
    }

    /**
     * @param string $name
     * @param object $entity
     * @return object
     */
    public function persist($name, $entity)
    {
        /** @var Model\ModelInterface $model */
        $model = $this->get($name);
        $this->validateModelEntity($model, $entity);

        $this->getAdapter($name)->persist($entity, $model->getAdapterOptions());
        return $entity;
    }

    /**
     * @param string $name
     * @param object $entity
     * @return object
     */
    public function remove($name, $entity)
    {
        /** @var Model\ModelInterface $model */
        $model = $this->get($name);
        $this->validateModelEntity($model, $entity);

        $this->getAdapter($name)->remove($entity, $model->getAdapterOptions());
        return $entity;
    }

    /**
     * @param Form\Annotation\AnnotationBuilder $formBuilder
     * @return $this
     */
    public function setFormBuilder($formBuilder)
    {
        $this->formBuilder = $formBuilder;
        return $this;
    }

    /**
     * @return Form\Annotation\AnnotationBuilder
     */
    public function getFormBuilder()
    {
        if (!$this->formBuilder instanceof Form\Annotation\AnnotationBuilder) {
            $this->formBuilder = new Form\Annotation\AnnotationBuilder();
        }
        return $this->formBuilder;
    }

    /**
     * @param Form\Factory $formFactory
     * @return $this
     */
    public function setFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
        return $this;
    }

    /**
     * @return Form\Factory
     */
    public function getFormFactory()
    {
        if (!$this->formFactory instanceof Form\Factory) {
            $this->formFactory = new Form\Factory($this->getFormElementManager());
        }
        return $this->formFactory;
    }

    /**
     * @param Adapter\AdapterManager $adapterManager
     * @return $this
     */
    public function setAdapterManager(Adapter\AdapterManager $adapterManager)
    {
        $this->adapterManager = $adapterManager;
        return $this;
    }

    /**
     * @return Adapter\AdapterManager
     */
    public function getAdapterManager()
    {
        if (!$this->adapterManager instanceof Adapter\AdapterManager) {
            $this->adapterManager = new Adapter\AdapterManager();
        }
        return $this->adapterManager;
    }

    /**
     * @param Form\FormElementManager $formElementManager
     * @return $this
     */
    public function setFormElementManager(Form\FormElementManager $formElementManager)
    {
        $this->formElementManager = $formElementManager;
        return $this;
    }

    /**
     * @return Form\FormElementManager
     */
    public function getFormElementManager()
    {
        if (!$this->formElementManager instanceof Form\FormElementManager) {
            $this->formElementManager = new Form\FormElementManager();
        }
        return $this->formElementManager;
    }

    /**
     * @param HydratorPluginManager $hydratorManager
     * @return $this
     */
    public function setHydratorManager(HydratorPluginManager $hydratorManager)
    {
        $this->hydratorManager = $hydratorManager;
        return $this;
    }

    /**
     * @return HydratorPluginManager
     */
    public function getHydratorManager()
    {
        if (!$this->hydratorManager instanceof HydratorPluginManager) {
            $this->hydratorManager = new HydratorPluginManager();
        }
        return $this->hydratorManager;
    }

    /**
     * @param string $defaultHydrator
     * @return $this
     */
    public function setDefaultHydrator($defaultHydrator)
    {
        $this->defaultHydrator = $defaultHydrator;
        return $this;
    }

    /**
     * @param \SpiffyCrud\Adapter\AdapterInterface $defaultAdapter
     * @return $this
     */
    public function setDefaultAdapter($defaultAdapter)
    {
        $this->defaultAdapter = $defaultAdapter;
        return $this;
    }

    /**
     * @param string $name
     * @return \Zend\Stdlib\Hydrator\HydratorInterface
     */
    public function getHydrator($name)
    {
        /** @var Model\ModelInterface $model */
        $model           = $this->get($name);
        $hydratorManager = $this->getHydratorManager();

        if ($model->getHydratorName() && $hydratorManager->has($model->getHydratorName())) {
            return $hydratorManager->get($model->getHydratorName());
        }
        return $hydratorManager->get($this->defaultHydrator);
    }

    /**
     * @param string $name
     * @return Adapter\AdapterInterface
     */
    public function getAdapter($name)
    {
        /** @var Model\ModelInterface $model */
        $model          = $this->get($name);
        $adapterManager = $this->getAdapterManager();

        if ($model->getAdapterName() && $adapterManager->has($model->getAdapterName())) {
            return $adapterManager->get($model->getAdapterName());
        }
        return $adapterManager->get($this->defaultAdapter);
    }

    /**
     * @param string $name
     * @param object $entity
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @return Form\Form
     */
    public function getForm($name, $entity = null)
    {
        /** @var Model\ModelInterface $model */
        $model = $this->get($name);

        if (is_object($entity)) {
            $this->validateModelEntity($model, $entity);
        } else {
            $entity = $this->getPrototype($name);
        }

        $form = $model->getFormSpec();
        if (is_string($form)) {
            if ($this->getFormElementManager()->has($form)) {
                $form = $this->getFormElementManager()->get($form);
            } else if (class_exists($form)) {
                $form = new $form();

                if (!$form instanceof Form\Form) {
                    $form = $this->getFormBuilder()->createForm($form);
                }
            } else {
                throw new \InvalidArgumentException(sprintf(
                    'String "%s" specified for form but could not be created.',
                    $form
                ));
            }
        } else if (is_array($form)) {
            $form = $this->getFormFactory()->createForm($form);
        } else if (is_null($form)) {
            $form = $this->getFormBuilder()->createForm($entity);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Form of type %s is invalid; must be a string, array, or null',
                (is_object($form) ? get_class($form) : gettype($form)),
                __NAMESPACE__
            ));
        }

        $form->setHydrator($this->getHydrator($name));
        $form->bind($entity);
        return $form;
    }

    /**
     * @param string $name
     * @throws Exception\InvalidEntityException
     * @return object
     */
    public function getPrototype($name)
    {
        if (isset($this->prototypes[$name])) {
            return $this->prototypes[$name];
        }

        /** @var Model\ModelInterface $model */
        $model  = $this->get($name);
        $class  = $model->getEntityClass();

        if (!class_exists($class)) {
            throw new Exception\InvalidEntityException(sprintf(
                'Entity "%s" could not be loaded for model "%s"',
                $class,
                $model
            ));
        }

        $object                  = new $class;
        $this->prototypes[$name] = $object;

        return clone $object;
    }

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof Model\ModelInterface) {
            throw new Exception\InvalidModelException(sprintf(
                'Model of type %s is invalid; must implement %s\Model\ModelInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }

    /**
     * @param Model\ModelInterface $model
     * @param object $entity
     * @throws \RuntimeException
     */
    protected function validateModelEntity(Model\ModelInterface $model, $entity)
    {
        if (get_class($entity) !== $model->getEntityClass()) {
            throw new \RuntimeException(sprintf(
                'Entity "%s" does not match model entity of "%s"',
                get_class($entity),
                $model->getEntityClass()
            ));
        }
    }
}
