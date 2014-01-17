<?php

namespace DCP\Di\Definition;

trait ShareableTrait
{
    protected $shared = false;

    /**
     * @return $this
     */
    public function asShared()
    {
        $this->shared = true;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShared()
    {
        return $this->shared;
    }
}