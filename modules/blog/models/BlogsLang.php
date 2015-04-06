<?php

namespace modules\blog\models;

use modules\lang\models\Lang;
use Yii;

/**
 * This is the model class for table "blogs_lang".
 *
 * @property integer $id
 * @property integer $blog_id
 * @property integer $lang_id
 * @property string $title
 * @property string $snippet
 * @property string $content
 */
class BlogsLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blogs_lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['blog_id', 'lang_id', 'title', 'snippet', 'content'], 'required'],
            [['blog_id', 'lang_id'], 'integer'],
            [['snippet', 'content'], 'string'],
            [['title'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'blog_id' => Yii::t('app', 'Blog ID'),
            'lang_id' => Yii::t('app', 'Lang ID'),
            'title' => Yii::t('app', 'Title'),
            'snippet' => Yii::t('app', 'Snippet'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlog()
    {
        return $this->hasOne(Blog::className(), ['id' => 'blog_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLang()
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public static function getBlogsLangByBlogAndLang($blogId, $langId=null){
        $langId = $langId ? $langId : Lang::getCurrent()->id;
        return BlogsLang::find()->where('blog_id=:blog AND lang_id=:lang', [
            'blog'=>$blogId,
            'lang'=>$langId
        ]);
    }
}
