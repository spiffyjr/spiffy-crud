<?php

namespace SpiffyCrudTest\Model;

use SpiffyCrud\Service\ModelManagerFactory;
use SpiffyTest\Module as SpiffyTest;

class ModelManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ModelManagerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ModelManagerFactory();
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
