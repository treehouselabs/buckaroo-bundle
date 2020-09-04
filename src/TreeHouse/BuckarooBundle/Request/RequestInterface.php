<?php

namespace TreeHouse\BuckarooBundle\Request;

interface RequestInterface
{
    /**
     * @return string
     */
    public function getResponseClass();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string|null
     */
    public function getOperation();
}
