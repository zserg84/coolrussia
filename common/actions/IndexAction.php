<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 04.02.15
 * Time: 12:09
 */

namespace common\actions;

class IndexAction extends Action {

    /**
     * вьюха, которую рендерим
     */
    public $view = 'index';

    public $searchParams = [];

    /**
     * модель поиска (для грида)
     */
    private $_searchModel;

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]].
     */
    public $prepareDataProvider;

    public function run(){
        $this->_searchModel = new $this->modelClass();
        $dataProvider = $this->prepareDataProvider();

        $viewParams = array_merge($this->viewParams, [
            'dataProvider' => $dataProvider,
            'searchModel' => $this->_searchModel,
        ]);

        $renderType = $this->renderType;

        return $this->controller->$renderType($this->view, $viewParams);
    }

    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        $searchParams = $this->searchParams;
        $searchParams = array_merge($searchParams, \Yii::$app->request->get());

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $dataProvider = $this->_searchModel->search($searchParams);
        if(!$dataProvider){
            return new ActiveDataProvider([
                'query' => $modelClass::find(),
            ]);
        }
        return $dataProvider;
    }

    public function loadData($model, $post){

    }
} 