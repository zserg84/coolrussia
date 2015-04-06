<?php

namespace modules\translations\controllers\console;

use modules\lang\models\Lang;
use modules\translations\models\MessageCategory;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\FileHelper;
use modules\translations\models\Message;
use modules\translations\models\SourceMessage;

class I18nController extends Controller
{
    /**
     * @param string $sourcePath
     * @throws Exception
     */
    public function actionImport($sourcePath = null)
    {
        if (!$sourcePath) {
            $sourcePath = $this->prompt('Enter a source path');
        }
        $sourcePath = realpath(Yii::getAlias($sourcePath));
        if (!is_dir($sourcePath)) {
            throw new Exception('The source path ' . $sourcePath . ' is not a valid directory.');
        }

        $translationsFiles = FileHelper::findFiles($sourcePath, ['only' => ['*.php']]);

        foreach ($translationsFiles as $translationsFile) {
            $relativePath = trim(str_replace([$sourcePath, '.php'], '', $translationsFile), '/,\\');
            $relativePath = FileHelper::normalizePath($relativePath, '/');
            $relativePath = explode('/', $relativePath, 2);
            if (count($relativePath) > 1) {
                $language = $this->prompt('Enter language.', ['default' => $relativePath[0]]);
                $category = $this->prompt('Enter category.', ['default' => $relativePath[1]]);
                $categoryId = $this->addCategory($category);

                $translations = require_once $translationsFile;
                if (is_array($translations)) {
                    foreach ($translations as $sourceMessage => $translation) {
                        if (!empty($translation)) {
                            $sourceMessage = $this->getSourceMessage($categoryId, $sourceMessage);
                            $this->setTranslation($sourceMessage, $language, $translation);
                        }
                    }
                }
            }
        }
        echo PHP_EOL . 'Done.' . PHP_EOL;
    }

    private function addCategory($category){
        $catModel = MessageCategory::find()->where(['name'=>$category])->one();
        if(!$catModel){
            $catModel = new MessageCategory();
            $catModel->name = $category;
            $catModel->save();
        }
        return $catModel->id;
    }
    /**
     * @param string $category
     * @param string $message
     * @return SourceMessage
     */
    private function getSourceMessage($category, $message)
    {
        $params = [
            'category_id' => $category,
            'message' => $message
        ];
        $sourceMessage = SourceMessage::find()
            ->where($params)
            ->with('messages')
            ->one();
        if (!$sourceMessage) {
            $sourceMessage = new SourceMessage;
            $sourceMessage->setAttributes($params, false);
            $sourceMessage->save(false);
        }
        return $sourceMessage;
    }

    /**
     * @param SourceMessage $sourceMessage
     * @param string $language
     * @param string $translation
     */
    private function setTranslation($sourceMessage, $language, $translation)
    {
        /** @var Message[] $messages */
        $messages = $sourceMessage->messages;
        $lang = Lang::find()->where(['url'=>$language])->one();
        if(!$lang)
            return;
        $langId = $lang->id;
        if (isset($messages[$langId]) /*&& $messages[$langId]->translation === null*/) {
            $messages[$langId]->translation = $translation;
            $messages[$langId]->save(false);
        } elseif (!isset($messages[$langId])) {
            $message = new Message;
            $message->setAttributes([
                'lang_id' => $langId,
                'translation' => $translation
            ], false);
            $sourceMessage->link('messages', $message);
        }
    }

    public function actionFlush()
    {
        $tableNames = [
            Message::tableName(),
            SourceMessage::tableName()
        ];
        $db = Yii::$app->getDb();
        foreach ($tableNames as $tableName) {
            $db->createCommand()
                ->delete($tableName)
                ->execute();
        }
        echo PHP_EOL . 'Done.' . PHP_EOL;
    }
}