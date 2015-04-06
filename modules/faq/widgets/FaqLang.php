<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 10:52
 */

namespace modules\faq\widgets;

use yii\base\Widget;

class FaqLang extends Widget{

    public $faqModel;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->faqModel === null) {
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
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        \Yii::$app->controller->run('/faq/faqlang/index');
    }
} 