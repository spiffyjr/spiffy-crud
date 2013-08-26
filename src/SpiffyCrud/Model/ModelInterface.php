<?php

namespace SpiffyCrud\Model;

interface ModelInterface
{
    /**
     * @return string
     */
    public function getDisplayName();

    /**
     * @return string
     */
    public function getEntityClass();

    /**
     * @return string
     */
    public function getForm();

    /**
     * @return string
     */
    public function getAdapterName();

    /**
     * @return string
     */
    public function getHydratorName();

    /**
     * @return array
     */
    public function getAdapterOptions();

    /**
     * @return array
     */
    public function getViewOptions();
}