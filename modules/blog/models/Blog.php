<?php

namespace modules\blog\models;

use modules\lang\models\Lang;
use modules\base\behaviors\PurifierBehavior;
use modules\blog\Module;
use modules\blog\traits\ModuleTrait;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Class Blog
 * @package modules\blog\models
 * Blog model.
 *
 * @property integer $id ID
 * @property string $title Title
 * @property string $alias Alias
 * @property string $snippet Intro text
 * @property string $content Content
 * @property integer $views Views
 * @property integer $status_id Status
 * @property integer $created_at Created time
 * @property integer $updated_at Updated time
 */
class Blog extends ActiveRecord
{
    use ModuleTrait;

    /** Unpublished status **/
    const STATUS_UNPUBLISHED = 0;
    /** Published status **/
    const STATUS_PUBLISHED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%blogs}}';
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new BlogQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ],
            'sluggableBehavior' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'alias'
            ],
            'uploadBehavior' => [
                'class' => UploadBehavior::className(),
                'attributes' => [
                    'preview_url' => [
                        'path' => $this->module->previewPath,
                        'tempPath' => $this->module->imagesTempPath,
                        'url' => $this->module->previewUrl
                    ],
                    'image_url' => [
                        'path' => $this->module->imagePath,
                        'tempPath' => $this->module->imagesTempPath,
                        'url' => $this->module->imageUrl
                    ]
                ]
            ],
            'purifierBehavior' => [
                'class' => PurifierBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_VALIDATE => [
                        'snippet',
                        'content' => [
                            'HTML.AllowedElements' => '',
                            'AutoFormat.RemoveEmpty' => true
                        ]
                    ]
                ],
                'textAttributes' => [
                    self::EVENT_BEFORE_VALIDATE => ['title', 'alias']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // Required
            [['title', 'content'], 'required'],
            // Trim
            [['title', 'snippet', 'content'], 'trim'],
            // Status
            [
                'status_id',
                'default',
                'value' => $this->module->moderation ? self::STATUS_PUBLISHED : self::STATUS_UNPUBLISHED
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('blogs', 'ATTR_ID'),
            'title' => Module::t('blogs', 'ATTR_TITLE'),
            'alias' => Module::t('blogs', 'ATTR_ALIAS'),
            'snippet' => Module::t('blogs', 'ATTR_SNIPPET'),
            'content' => Module::t('blogs', 'ATTR_CONTENT'),
            'views' => Module::t('blogs', 'ATTR_VIEWS'),
            'status_id' => Module::t('blogs', 'ATTR_STATUS'),
            'created_at' => Module::t('blogs', 'ATTR_CREATED'),
            'updated_at' => Module::t('blogs', 'ATTR_UPDATED'),
            'preview_url' => Module::t('blogs', 'ATTR_PREVIEW_URL'),
            'image_url' => Module::t('blogs', 'ATTR_IMAGE_URL'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if(!BlogsLang::getBlogsLangByBlogAndLang($this->id)->one()){
            $bl = new BlogsLang();
            $bl->setAttributes($this->getAttributes());
            $bl->blog_id = $this->id;
            $bl->lang_id = Lang::getCurrent()->id;
            $bl->save();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogsLangs()
    {
        return $this->hasMany(BlogsLang::className(), ['blog_id' => 'id']);
    }

    public function getBlogsLangByLang($langId=null)
    {
        return BlogsLang::getBlogsLangByBlogAndLang($this->id, $langId)->one();
    }

    public function getTitle(){
        return $this->getBlogsLangByLang() ? $this->getBlogsLangByLang()->title : $this->title;
    }

    public function getContent(){
        return $this->getBlogsLangByLang()?  $this->getBlogsLangByLang()->content : $this->content;
    }

    public function getSnippet(){
        return $this->getBlogsLangByLang() ? $this->getBlogsLangByLang()->snippet : $this->snippet;
    }
}
