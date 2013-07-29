<?php

namespace SpiffyCrud\View\Helper;

interface HelperInterface
{
    /**
     * @param string $name
     * @param array $data
     * @param array $options
     * @return string
     */
    public function __invoke($name, array $data, array $options = array());
}