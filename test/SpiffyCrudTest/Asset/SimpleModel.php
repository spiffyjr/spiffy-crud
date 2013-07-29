<?php

namespace SpiffyCrudTest\Asset;

use SpiffyCrud\Model\AbstractModel;

class SimpleModel extends AbstractModel
{
    protected $entityClass = 'SpiffyCrudTest\Asset\SimpleEntity';
}