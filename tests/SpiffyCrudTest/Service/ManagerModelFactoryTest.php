<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Service\ManagerModelFactory;
use SpiffyTest\Module as SpiffyTest;

class ManagerModelFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ManagerModelFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ManagerModelFactory();
    }

    public function testExpectedInstanceReturned()
    {
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());
        $this->assertInstanceOf('SpiffyCrud\ModelManager', $service);
    }
    public function testExceptionThrownOnMissingConfiguration()
    {
        $sm     = clone SpiffyTest::getInstance()->getServiceManager();
        $config = $sm->get('Configuration');
        unset($config['spiffycrud']['models']);

        $sm->setAllowOverride(true);
        $sm->setService('Configuration', $config);

        $this->setExpectedException('RuntimeException', 'No model configuration given');
        $this->factory->createService($sm);
    }

    public function testManagerSetFromConfiguration()
    {
        /** @var $service \SpiffyCrud\ModelManager */
        $service = $this->factory->createService(SpiffyTest::getInstance()->getServiceManager());

        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleModel', $service->get('simpleInvokable'));
        $this->assertInstanceOf('SpiffyCrudTest\Asset\SimpleModel', $service->get('simpleFactory'));
    }
}
