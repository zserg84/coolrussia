<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:52
 */

namespace modules\direction\widgets;

use yii\base\Widget;

class DirectionChildrenWidget extends Widget{

    public $directionModel;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        /*if ($this->directionModel === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }*/

        $this->registerClientScript();
    }

    /**
     * Register widget client scripts.
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return \Yii::$app->controller->run('/direction/default/children');
    }
} 