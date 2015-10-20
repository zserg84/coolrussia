<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 11.10.15
 * Time: 18:37
 */

namespace modules\exchange\controllers\backend;

use modules\exchange\models\form\ExportForm;
use modules\exchange\models\form\ImportForm;
use modules\lang\models\Lang;
use modules\translations\models\Message;
use modules\translations\models\MessageCategory;
use modules\translations\models\SourceMessage;
use SimpleExcel\SimpleExcel;
use vova07\admin\components\Controller;
use yii\db\Query;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class DefaultController extends Controller
{

    public function actionExport()
    {
        $model = new ExportForm();
        if($_POST && $model->load($_POST)){
            $excel = new SimpleExcel('CSV');
            $excel->writer->setDelimiter(';');
            $titleRow = ['message_category', 'category'];

            $langs = Lang::find();
            if($model->lang_id){
                $langs->andWhere([
                    'id' => $model->lang_id
                ]);
            }
            $langs = $langs->all();
            $query = new Query();
            $query->select = ['sm.message', 'mc.name'];
            $query->from('message_category mc');
            $query->innerJoin('source_message sm', 'sm.category_id=mc.id');
            foreach($langs as $k=>$lang){
                $alias = 'm'.$k;
                $query->leftJoin('message '.$alias, $alias.'.source_message_id = sm.id and '.$alias.'.lang_id=:lang'.$k, ['lang'.$k=>$lang->id]);
                $query->select[] = $alias.'.translation as `'.$lang->name.'`';

                $titleRow[] = iconv('utf-8', 'cp1251', $lang->name);
            }

            if($model->category_id){
                $query->andWhere([
                    'mc.id' => $model->category_id
                ]);
            }

            $data = $query->all();

            $excel->writer->addRow($titleRow);

            foreach($data as $row){
                foreach($row as $i=>$col){
                    $row[$i] = iconv('utf-8', 'cp1251', $col);
                }
                $excel->writer->addRow($row);
            }
            $excel->writer->saveFile('export');
        }

        return $this->render('export', [
            'model' => $model,
        ]);
    }

    public function actionImport()
    {
        $model = new ImportForm();

        if($model->load($_POST)) {
            $dir = \Yii::getAlias('@statics/web/files/');
            $file = UploadedFile::getInstance($model, 'file');
            $file->saveAs($dir.$file->name);
            $model->file = $file;
            if($model->validate()) {
                $excel = new SimpleExcel('CSV');
                $excel->parser->setDelimiter(';');
                $excel->parser->loadFile($dir.$file->name);
                $i = 1;
                $parser = $excel->parser;
                $languages = [];
                $categories = [];
                $variables = [];
                $messages = [];
                while(true){
                    if($parser->isRowExists($i)){
                        $row = $parser->getRow($i);
                        if($i==1){
                            foreach($row as $k=>$r){
                                $r = iconv('cp1251', 'utf-8', $r);
                                if($k>1){
                                    $langModel = Lang::find()->where([
                                        'like', 'name', $r
                                    ])->one();
                                    if(!$langModel)
                                        continue;
                                    $languages[$k] = $langModel->id;
                                }
                            }
                        }
                        else{
                            $categories[$row[1]] = ['name' => $row[1], 'id'=>''];
                            $variables[$row[0]] = ['name' => $row[0], 'id'=>'', 'category' => $row[1]];
                            foreach($row as $k=>$r){
                                if($k>1){
                                    if(isset($languages[$k])) {
                                        $messages[$languages[$k]][] = ['category' => $row[1], 'variable' => $row[0], 'name' => $row[$k], 'id'=>''];
//                                        $messages[$row[$k]][$languages[$k]] = ['category' => $row[1], 'variable' => $row[0], 'name' => $row[$k], 'id' => ''];
                                    }
                                }
                            }

                        }
                    }
                    else{
                        break;
                    }
                    $i++;
                }

                foreach($categories as $k=>$category){
                    $categoryModel = MessageCategory::find()->where(['name' => $category['name']])->one();
                    if (!$categoryModel) {
                        $categoryModel = new MessageCategory();
                    }
                    $categoryModel->name = $category['name'];
                    if ($categoryModel->save()) {

                    }
                    $category['id'] = $categoryModel->id;
                    $categories[$k] = $category;
                }
                foreach($variables as $k=>$variable){
                    $category = $categories[$variable['category']];
                    $variableModel = SourceMessage::find()->where([
                        'message' => $variable['name'],
                        'category_id' => $category['id']
                    ])->one();
                    if (!$variableModel) {
                        $variableModel = new SourceMessage();
                    }
                    $variableModel->message = $variable['name'];
                    $variableModel->category_id = $category['id'];
                    if ($variableModel->save()) {

                    }
                    $variable['id'] = $variableModel->id;
                    $variables[$k] = $variable;
                }

                foreach($messages as $messageLangId=>$messageArr){
                    foreach($messageArr as $k=>$message){
                        if($message['name']) {
                            $category = $categories[$message['category']];
                            $variableModel = SourceMessage::find()->where([
                                'message' => $message['variable'],
                                'category_id' => $category['id']
                            ])->one();
                            if(!$variableModel)
                                continue;
                            $messageModel = Message::find()->where([
                                'source_message_id' => $variableModel->id,
                                'lang_id' => $messageLangId,
                            ])->one();
                            if (!$messageModel) {
                                $messageModel = new Message();
                            }
                            $message['name'] = iconv('cp1251', 'utf-8', $message['name']);
                            $messageModel->translation = $message['name'];
                            $messageModel->source_message_id = $variableModel->id;
                            $messageModel->lang_id = $messageLangId;
                            if ($messageModel->save()) {

                            } else {

                            }
                        }
                    }
                }
            }
            \Yii::$app->session->setFlash(
                'success',
                'Импорт успешно выполнен'
            );
        }
        return $this->render('import', [
            'model' => $model,
        ]);
    }
} 