<?php

namespace modules\event;

use modules\translations\components\DbMessageSource;

class Module extends \modules\base\components\Module
{

    /**
     * @inheritdoc
     */
    public static $name = 'event';

    public function init()
    {
        self::$langNames = ['event', 'event_description'];

        parent::init();
    }
}