<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 12.02.15
 * Time: 14:57
 */

namespace modules\translations\models;


use yii\db\ActiveRecord;

class MessageCategory  extends ActiveRecord
{

    public static function tableName()
    {
        $i18n = \Yii::$app->getI18n();
        if (!isset($i18n->messageCategoryTable)) {
            throw new InvalidConfigException('You should configure i18n component');
        }
        return $i18n->messageCategoryTable;
    }
} 