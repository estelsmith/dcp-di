<?php

namespace DCP\Di\Definition;

interface ShareableGetterInterface
{
    /**
     * @return bool
     */
    public function isShared();
}
