<?php

namespace SpiffyCrud;

use Zend\Form\Form;
use Zend\ServiceManager\ServiceManager;

class FormManager extends ServiceManager
{
    public function get($name, $usePeeringServiceManagers = true)
    {
        /** @var Form $instance */
        $instance = parent::get($name, $usePeeringServiceManagers);

        if (!is_object($instance) || !$instance instanceof Form) {
            throw new \RuntimeException('registered forms must be an instance of Zend\Form\Form');
        }

        return $instance;
    }
}
