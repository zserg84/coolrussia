<?php

namespace modules\image\controllers;

use Yii;
use modules\image\Module;
use modules\image\models\Image;
use \WideImage;
use yii\helpers\VarDumper;

class DefaultController extends \yii\base\Controller {

    public function actionIndex($id, $width, $ext) {
        $module = $this->module;
        $allowedSized = $module->sizeArray;
        if (!in_array($width, $allowedSized)) {
            header("HTTP/1.1 404 Not Found");
            exit;
        }

        $groupFolder = ceil($id / 1000);
        $path = Yii::getAlias('@web').$module->dir.$groupFolder.'/';
        $file = $path.$id.'.'.$ext;

        // ищем ближайший подходящий размер
        foreach ($allowedSized as $w) {
            if ($w <= $width) continue;
            $nextBigFile = $path.$id.'-'.$w.'.'.$ext;
            if (file_exists($nextBigFile)) {
                $file = $nextBigFile;
                break;
            }
        }

        // чистим кэш
        clearstatcache();

        if (file_exists($file)) {
            $wideImage = WideImage::load($file);
            $newFile = $path.$id.'-'.$width.'.'.$ext;
            $wideImage = $wideImage->resizeDown($width);
            $wideImage->saveToFile($newFile);
            chmod($newFile, 0755);
            $wideImage->output($ext);
        } else {
            header("HTTP/1.1 404 Not Found");
        }

        exit;
    }

}