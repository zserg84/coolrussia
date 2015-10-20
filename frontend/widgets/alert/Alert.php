<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 08.05.15
 * Time: 16:26
 */

namespace frontend\widgets\alert;

use common\components\Modal;
use yii\helpers\VarDumper;

class Alert extends \yii\bootstrap\Widget
{
    public $alertTypes = [
        'error' => 'danger',
        'danger' => 'danger',
        'success' => 'success',
        'info' => 'info',
        'warning' => 'warning'
    ];

    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $message) {
            /* initialize css class for each alert box */
            $this->options['class'] = 'alert-' . $this->alertTypes[$type] . $appendCss;

            /* assign unique id to each alert box */
            $this->options['id'] = $this->getId() . '-' . $type;

            /*echo \yii\bootstrap\Alert::widget(
                [
                    'body' => $message,
                    'closeButton' => $this->closeButton,
                    'options' => $this->options
                ]
            );*/
            if($message){
                echo $this->render('alert', [
                    'type' => $type,
                    'message' => $message,
                ]);
            }

            $session->removeFlash($type);
        }
    }
} 