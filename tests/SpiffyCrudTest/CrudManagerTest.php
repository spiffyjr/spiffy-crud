<?php

namespace SpiffyCrudTest\Model;

use ArrayObject;
use SpiffyCrud\CrudManager;
use SpiffyCrud\FormManager;
use SpiffyCrud\Mapper\SimpleArray;
use SpiffyCrud\ModelManager;
use SpiffyCrudTest\Asset\SimpleEntity;
use SpiffyCrudTest\Asset\SimpleForm;
use SpiffyCrudTest\Asset\SimpleModel;

class CrudManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CrudManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new CrudManager(new ModelManager(), new FormManager());
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

        $this->setExpectedException('RuntimeException', 'Models require a mapper');
        $this->manager->getModelManager()->setService('bar', new SimpleModel());
        $model = $this->manager->getModel('bar');

        $this->manager->create($model, array());
    }
}
