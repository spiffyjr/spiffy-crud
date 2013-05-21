<?php

namespace SpiffyCrudTest\Asset;

use Zend\Form\Annotation as Form;

/**
 * @Form\Name("user")
 */
class AdvancedEntity
{
    /**
     * @Form\Name("one")
     */
    protected $one;

    /**
     * @Form\Name("two")
     */
    protected $two;

    /**
     * @Form\Name("three")
     */
    protected $three;

    /**
     * @Form\Name("four")
     */
    protected $four;

    /**
     * @Form\Name("five")
     */
    protected $five;

    public function setFive($five)
    {
        $this->five = $five;
        return $this;
    }

    public function getFive()
    {
        return $this->five;
    }

    public function setFour($four)
    {
        $this->four = $four;
        return $this;
    }

    public function getFour()
    {
        return $this->four;
    }

    public function setOne($one)
    {
        $this->one = $one;
        return $this;
    }

    public function getOne()
    {
        return $this->one;
    }

    public function setThree($three)
    {
        $this->three = $three;
        return $this;
    }

    public function getThree()
    {
        return $this->three;
    }

    public function setTwo($two)
    {
        $this->two = $two;
        return $this;
    }

    public function getTwo()
    {
        return $this->two;
    }


}