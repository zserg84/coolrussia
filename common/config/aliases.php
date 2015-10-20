<?php

/**
 * Set all application aliases.
 */

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@customer', dirname(dirname(__DIR__)) . '/customer');
Yii::setAlias('@performer', dirname(dirname(__DIR__)) . '/performer');
Yii::setAlias('@statics', dirname(dirname(__DIR__)) . '/statics');
Yii::setAlias('@modules', dirname(dirname(__DIR__)) . '/modules');
Yii::setAlias('@root', dirname(dirname(__DIR__)));