<?php

namespace SpiffyCrud\Renderer;

use SpiffyCrud\Model\AbstractModel;

interface RendererInterface
{
    public function render(AbstractModel $model, array $input);
}