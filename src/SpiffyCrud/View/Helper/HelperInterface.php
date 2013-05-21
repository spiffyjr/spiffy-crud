<?php

namespace SpiffyCrud\View\Helper;

use SpiffyCrud\Model\AbstractModel;

interface HelperInterface
{
    public function __invoke(AbstractModel $model, $name, array $data);
}