<?php

namespace SpiffyCrud\Annotation\Crud;

use SpiffyConfig\Annotation\Service;

/**
 * @Annotation
 */
class Invokable extends Service\Invokable
{
    /**
     * @var string
     */
    public $key = 'spiffy_crud|manager';
}
