<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 13.02.15
 * Time: 10:54
 */

namespace modules\faq\controllers\backend;

use common\actions\BatchDeleteAction;
use common\actions\CreateAction;
use common\actions\DeleteAction;
use common\actions\IndexAction;
use common\actions\UpdateAction;
use modules\faq\models\FaqLang;
use modules\faq\models\FaqSearch;
use modules\faq\models\Faq;
use vova07\admin\components\Controller;
use yii\helpers\Url;
use vova07\fileapi\actions\UploadAction as FileAPIUpload;
use vova07\imperavi\actions\GetAction as ImperaviGet;
use vova07\imperavi\actions\UploadAction as ImperaviUpload;

class DefaultController extends Controller {

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => FaqSearch::className(),
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => Faq::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => Faq::className(),
                'redirectUrl' => Url::toRoute('index'),
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => Faq::className(),
            ],
            'batch-delete' => [
                'class' => BatchDeleteAction::className(),
                'modelClass' => Faq::className(),
            ],
            'imperavi-get' => [
                'class' => ImperaviGet::className(),
                'url' => $this->module->contentUrl,
                'path' => $this->module->contentPath
            ],
            'imperavi-image-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->contentUrl,
                'path' => $this->module->contentPath
            ],
            'imperavi-file-upload' => [
                'class' => ImperaviUpload::className(),
                'url' => $this->module->fileUrl,
                'path' => $this->module->filePath,
                'uploadOnlyImage' => false
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => $this->module->imagesTempPath
            ],
        ];
    }

}