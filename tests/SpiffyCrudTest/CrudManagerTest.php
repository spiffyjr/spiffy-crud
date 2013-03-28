<?php

namespace SpiffyCrudTest\Model;

use ArrayObject;
use SpiffyCrud\CrudManager;
use SpiffyCrud\FormManager;
use SpiffyCrud\Mapper\SimpleArray;
use SpiffyCrud\ModelManager;
use SpiffyCrudTest\Asset\AdvancedEntity;
use SpiffyCrudTest\Asset\IncludedFieldsModel;
use SpiffyCrudTest\Asset\SimpleEntity;
use SpiffyCrudTest\Asset\SimpleForm;
use SpiffyCrudTest\Asset\SimpleModel;
use SpiffyTest\Module as SpiffyTest;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Stdlib\Hydrator\ClassMethods;

class CrudManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CrudManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new CrudManager(new ModelManager(), new FormManager());
        $this->manager->setServiceLocator(SpiffyTest::getInstance()->getServiceManager());
    }

    public function testExceptionThrownWhenModelDoesNotContainAnEntity()
    {
        $model = new SimpleModel();
        $model->setEntity(new SimpleEntity());
        $this->manager->getEntityFromModel($model);

        $this->setExpectedException('RuntimeException', 'Model does not have an entity or entityClass');
        $model = new SimpleModel();
        $model->setEntityClass(null);
        $this->manager->getEntityFromModel($model);
    }

    public function testModelsHaveDefaultMapper()
    {
        $mapper = new SimpleArray();
        $this->manager->setDefaultMapper($mapper);
        $model = new SimpleModel();

        $this->assertEquals($mapper, $this->manager->getMapperFromModel($model));
    }

    public function testDefaultHydrator()
    {
        $hydrator = new ClassMethods();
        $this->manager->setDefaultHydrator($hydrator);
        $model = new SimpleModel();

        $this->assertEquals($hydrator, $this->manager->getHydratorFromModel($model));
    }

    public function testHydrateModelEntity()
    {
        $data = array(
            'foo' => 'foo',
            'bar' => 'bar',
        );
        $expected = new SimpleEntity();
        $expected->setFoo('foo')
                 ->setBar('bar');

        $this->assertEquals($expected, $this->manager->hydrateModelEntity($data, new SimpleModel()));
    }

    public function testModelEntityCreation()
    {
        $model = new SimpleModel();
        $model->setEntityClass('SpiffyCrudTest\Asset\SimpleEntity');
        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleEntity', $this->manager->getEntityFromModel($model));

        $model = new SimpleModel();
        $model->setEntity(new SimpleEntity());
        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleEntity', $this->manager->getEntityFromModel($model));
    }

    public function testExtractModelEntity()
    {
        $model  = new SimpleModel();
        $entity = new SimpleEntity();
        $entity->setFoo('foo')
               ->setBar('bar');
        $model->setEntity($entity);

        $expected = array(
            'foo' => 'foo',
            'bar' => 'bar',
        );

        $this->assertEquals($expected, $this->manager->extractModelEntity($model));
    }

    public function testRegisteredFormsMustBeAnInstanceofAbstractModel()
    {
        $service = $this->manager->getFormManager();
        $service->setService('bar', new SimpleForm());
        $this->manager->getForm('bar');

        $this->setExpectedException(
            'RuntimeException',
            'registered forms must be an instance of Zend\Form\Form'
        );
        $service->setService('foo', new ArrayObject());
        $this->manager->getForm('foo');
    }

    public function testRegisteredModelsMustBeAnInstanceofAbstractModel()
    {
        $service = $this->manager->getModelManager();
        $service->setService('bar', new SimpleModel);
        $this->manager->getModel('bar');

        $this->setExpectedException(
            'RuntimeException',
            'registered models must be an instance of SpiffyCrud\Model\AbstractModel'
        );
        $service->setService('foo', new ArrayObject());
        $this->manager->getModel('foo');
    }

    public function testModelsRequireMapper()
    {
        $model = new SimpleModel();
        $model->setMapper(new SimpleArray());

        $this->manager->create($model, array());
        $this->manager->update(0, $model, array());
        $this->manager->delete(0, $model);

        $this->setExpectedException('RuntimeException', 'Model does not have a mapper and default mapper was not registered');
        $this->manager->getModelManager()->setService('bar', new SimpleModel());
        $model = $this->manager->getModel('bar');

        $this->manager->create($model, array());
    }

    public function testCreate()
    {
        $mapper = new SimpleArray(array(
            array('foo' => 1, 'bar' => 'test'),
            array('foo' => 2, 'bar' => 'test2')
        ));

        $expected = array(
            array('foo' => 1, 'bar' => 'test'),
            array('foo' => 2, 'bar' => 'test2'),
            array('foo' => 3, 'bar' => 'test3')
        );

        $model = new SimpleModel();
        $model->setMapper($mapper);

        $this->manager->create($model, array('foo' => 3, 'bar' => 'test3'));
        $this->assertEquals($expected, $mapper->getData());
    }

    public function testUpdate()
    {
        $mapper = new SimpleArray(array(
            array('foo' => 1, 'bar' => 'test'),
            array('foo' => 2, 'bar' => 'test2')
        ));

        $expected = array(
            array('foo' => 1, 'bar' => 'test'),
            array('foo' => 2, 'bar' => 'updated'),
        );

        $model = new SimpleModel();
        $model->setMapper($mapper);

        $this->manager->update(1, $model, array('foo' => 2, 'bar' => 'updated'));
        $this->assertEquals($expected, $mapper->getData());
    }

    public function testRead()
    {
        $mapper = new SimpleArray(array(
            array('foo' => 1, 'bar' => 'test'),
            array('foo' => 2, 'bar' => 'test2')
        ));

        $expected = new SimpleEntity();
        $expected->setFoo(2)
                 ->setBar('test2');

        $model = new SimpleModel();
        $model->setMapper($mapper);

        $this->assertEquals($expected, $this->manager->read($model, 1));
    }

    public function testReadAll()
    {
        $mapper = new SimpleArray(array(
            array('foo' => 1, 'bar' => 'test'),
            array('foo' => 2, 'bar' => 'test2')
        ));

        $entity = new SimpleEntity();
        $entity->setFoo(1)
               ->setBar('test');

        $entity2 = new SimpleEntity();
        $entity2->setFoo(2)
                ->setBar('test2');

        $expected = array($entity, $entity2);

        $model = new SimpleModel();
        $model->setMapper($mapper);

        $this->assertEquals($expected, $this->manager->readAll($model));
    }

    public function testFormIsUsedDirectly()
    {
        $form  = new SimpleForm();
        $model = new SimpleModel();
        $model->setForm($form);

        $this->assertEquals($form, $this->manager->getFormFromModel($model));
    }

    public function testFormIsRetrievedFromFormManager()
    {
        $model = new SimpleModel();
        $model->setForm('foo');

        $this->manager->getFormManager()->setService('foo', new SimpleForm());
        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleForm', $this->manager->getFormFromModel($model));
    }

    public function testFormEntityMatchesModelEntityClass()
    {
        $entity = new SimpleEntity();
        $model  = new SimpleModel();

        $this->manager->getFormFromModel($model, $entity);

        $this->setExpectedException('InvalidArgumentException', 'Supplied entity does not match model entityClass');
        $this->manager->getFormFromModel($model, new AdvancedEntity());
    }

    public function testFormIsBuiltByDefaultFromFormBuilder()
    {
        $form = $this->manager->getFormFromModel(new SimpleModel());

        $this->assertInstanceOf('Zend\Form\Annotation\AnnotationBuilder', $this->manager->getFormBuilder());
        $this->assertInstanceOf('Zend\Form\Form', $form);
        $this->assertCount(2, $form->getElements());
    }
}
