<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Service\ManagerCrudFactory;
use SpiffyTest\Module as SpiffyTest;

class ManagerCrudFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ManagerCrudFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ManagerCrudFactory();
    }

    public function testDefaultMapperSetWithString()
    {
        $sm     = SpiffyTest::getInstance()->getServiceManager();
        $config = $sm->get('Configuration');
        $config['spiffy-crud']['default_mapper'] = 'SpiffyCrud\Mapper\SimpleArray';
        $sm->setAllowOverride(true);
        $sm->setService('Configuration', $config);

        $service = $this->factory->createService($sm);
        $this->assertInstanceOf('SpiffyCrud\Mapper\SimpleArray', $service->getDefaultMapper());
    }

    public function testDefaultMapperIsSetWithSmAlias()
    {
        $sm     = SpiffyTest::getInstance()->getServiceManager();
        $config = $sm->get('Configuration');
        $config['spiffy-crud']['default_mapper'] = 'SpiffyCrudMapperDoctrineObject';
        $sm->setAllowOverride(true);
        $sm->setService('Configuration', $config);

        $service = $this->factory->createService($sm);
        $this->assertInstanceOf('SpiffyCrud\Mapper\DoctrineObject', $service->getDefaultMapper());
    }

    public function testExpectedInstanceReturned()
    {
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());
        $this->assertInstanceOf('SpiffyCrud\CrudManager', $service);
    }
}
