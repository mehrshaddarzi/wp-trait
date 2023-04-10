<?php

namespace WPTrait\Abstracts;

abstract class Params
{

    /**
     * Setup Method Params
     *
     * @var array
     */
    protected array $params;

    /**
     * Get List Of Prepare Params For Use in WordPress Method
     *
     * @return array
     */
    public function toParams(): array
    {
        if (empty($this->params)) {
            $this->setParams();
        }

        return $this->params;
    }

    /**
     * Prepare Parameter For User in WordPress Method
     */
    abstract public function setParams();
}