<?php

namespace modules\comments;

use Yii;

/**
 * Comments module.
 */
class Module extends \modules\base\components\Module
{
    /**
     * @inheritdoc
     */
    public static $name = 'comments';

    public function init()
    {
        self::$langNames = [
            'comments', 'comments-models'
        ];

        parent::init();
    }
}
