<?php

namespace SpiffyCrudTest\Asset;

use SpiffyCrud\Model\AbstractModel;

class IncludedFieldsModel extends AbstractModel
{
    protected $entityClass   = 'SpiffyCrudTest\Asset\AdvancedEntity';
    protected $includeFields = array('three', 'one', 'two');
}