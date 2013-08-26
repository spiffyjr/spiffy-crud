<?php

namespace SpiffyCrud\Annotation\Crud;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Factory extends Service\Factory
{
    /**
     * @var string
     */
    public $key = 'spiffy_crud|manager';
}
