<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 08.05.15
 * Time: 10:52
 */

namespace modules\direction\controllers\backend;

use common\actions\BatchDeleteAction;
use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use common\actions\UpdateAction;
use modules\direction\models\Direction;
use modules\direction\models\search\DirectionSearch;
use vova07\admin\components\Controller;
use yii\helpers\Url;
use yii\helpers\VarDumper;

class DefaultController extends Controller
{

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => DirectionSearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Direction::className(),
                'beforeAction' => function($action, $model){
                    $parentId = \Yii::$app->getRequest()->get('parent_id');
                    $model->parent_id = $parentId;
                    $action->redirectUrl = $parentId ? Url::toRoute(['update', 'id'=>$parentId]) : Url::toRoute(['index']);
                    return $model;
                },
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Direction::className(),
                'redirectUrl' => Url::toRoute('index'),
                'beforeAction' => function($action, $model){
                    $parentId = \Yii::$app->getRequest()->get('parent_id');
                    $model->parent_id = $parentId;
                    $action->redirectUrl = $parentId ? Url::toRoute(['update', 'id'=>$parentId]) : Url::toRoute(['index']);
                    return $model;
                },
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Direction::className(),
            ],
            'batch-delete' => [
                'class' => BatchDeleteAction::className(),
                'modelClass' => Direction::className(),
            ],
            'children' => [
                'class' => IndexAction::className(),
                'modelClass' => DirectionSearch::className(),
                'renderType' => 'renderPartial',
                'beforeAction' => function($action){
                    $parentId = \Yii::$app->getRequest()->get('id');
                    $action->searchParams = array_merge($action->searchParams, [
                        'DirectionSearch'=>['parent_id'=>$parentId],
                    ]);
                },
            ],
        ];
    }
} 