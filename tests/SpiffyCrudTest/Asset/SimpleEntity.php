<?php

namespace SpiffyCrudTest\Asset;

class SimpleEntity
{
    protected $foo;

    protected $bar;

    public function setFoo($foo)
    {
        $this->foo = $foo;
        return $this;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function setBar($bar)
    {
        $this->bar = $bar;
        return $this;
    }

    public function getBar()
    {
        return $this->bar;
    }
}