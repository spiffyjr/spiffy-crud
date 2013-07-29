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
    public function getFormSpec();

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