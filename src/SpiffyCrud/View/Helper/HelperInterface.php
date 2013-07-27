<?php

namespace SpiffyCrud\View\Helper;

interface HelperInterface
{
    public function __invoke($name, array $data);
}