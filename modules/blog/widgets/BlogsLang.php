<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:52
 */

namespace modules\blog\widgets;

use yii\base\Widget;

class BlogsLang extends Widget{

    public $blogModel;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->blogModel === null) {
            throw new InvalidConfigException('The "model" property must be set.');
        }

        $this->registerClientScript();
    }

    /**
     * Register widget client scripts.
     */
    protected function registerClientScript()
    {
        $view = $this->getView();
//        $options = Json::encode($this->jsOptions);
//        Asset::register($view);
//        $view->registerJs('jQuery.comments(' . $options . ');');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        \Yii::$app->controller->run('blogslang/index');
    }
} 