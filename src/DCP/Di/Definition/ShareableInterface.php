<?php

namespace DCP\Di\Definition;

interface ShareableInterface
{
    /**
     * @return $this
     */
    public function asShared();
}
