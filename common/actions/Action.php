<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 04.02.15
 * Time: 15:27
 */

namespace common\actions;


abstract class Action extends \yii\base\Action{

    /**
     * Модель
     * @var String
     */
    public $modelClass;

    /**
     * имя айдишника модели
     * @var Int
     */
    public $modelIdName = 'id';

    /**
     * Пусть до представления
     * @var string
     */
    public $view;

    /**
     * Параметры вьюхи
     * @var array
     */
    public $viewParams = [];

    /**
     * Сообщение при успешном выполнении
     * @var string
     * */
    public $accessMsg;

    /**
     * Сообщение при ошибке
     * @var string
     * */
    public $errorMsg;

    /**
     * Анонимная функция, которая вызывается перед действием
     * @var object
     * */
    public $beforeAction;

    /**
     * Анонимная функция, которая применяется к итоговому результату
     * @var object
     * */
    public $afterAction;

    /**
     * URL, куда редиректаем после успешного выполнения действия
     * */
    public $redirectUrl = 'index';

    public $renderType = 'render';


    /*
     * получаем модель
     * */
    public function getModel(){
        if(method_exists($this->controller, 'findModel')){
            $model = $this->controller->findModel($this->modelClass, null, $this->modelIdName);
        }
        else
            $model = self::findModel($this->modelClass, null, $this->modelIdName);
        return $model ? $model : new $this->modelClass;
    }

    /*
     * загружаем в модель данные
     * */
    abstract public function loadData($model, $post);

    /*
     * Ищем модель
     * */
    public static function findModel($modelClass, $id, $idName = null)
    {
        if (!$id) {
            $id = \Yii::$app->getRequest()->getQueryParam($idName);
        }
        if (!$id) {
            throw new NotFoundHttpException('The model ID is undefined');
        }

        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
} 