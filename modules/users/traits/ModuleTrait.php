<?php

namespace modules\users\traits;

use modules\users\Module;
use Yii;

/**
 * Class ModuleTrait
 * @package modules\users\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait ModuleTrait
{
    /**
     * @var \modules\users\Module|null Module instance
     */
    private $_module;

    /**
     * @return \modules\users\Module|null Module instance
     */
    public function getModule()
    {
        if ($this->_module === null) {
            $module = Module::getInstance();
            if ($module instanceof Module) {
                $this->_module = $module;
            } else {
                $this->_module = Yii::$app->getModule('users');
            }
        }
        return $this->_module;
    }
}
