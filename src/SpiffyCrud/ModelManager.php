<?php

namespace SpiffyCrud;

use SpiffyCrud\Model\AbstractModel;
use Zend\ServiceManager\ServiceManager;

class ModelManager extends ServiceManager
{
    public function get($name, $usePeeringServiceManagers = true)
    {
        /** @var AbstractModel $instance */
        $instance = parent::get($name, $usePeeringServiceManagers);

        if (!is_object($instance) || !$instance instanceof AbstractModel) {
            throw new \RuntimeException('registered models must be an instance of SpiffyCrud\Model\AbstractModel');
        }

        return $instance;
    }
}
