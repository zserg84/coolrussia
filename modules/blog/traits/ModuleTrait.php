<?php

namespace modules\blog\traits;

use modules\blog\Module;

/**
 * Class ModuleTrait
 * @package modules\blog\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \modules\blog\Module|null Module instance
     */
    private $_module;

    /**
     * @return \modules\blog\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $this->_module = Module::getInstance();
        }
        return $this->_module;
    }
}
