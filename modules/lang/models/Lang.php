<?php

namespace modules\lang\models;

use modules\blog\models\BlogsLang;
use modules\direction\models\Directionlang;
use modules\event\models\EventLang;
use modules\faq\models\FaqLang;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "lang".
 *
 * @property integer $id
 * @property string $url
 * @property string $local
 * @property string $name
 * @property integer $default
 * @property integer $date_update
 * @property integer $date_create
 *
 * @property BlogsLang[] $blogsLangs
 * @property EventLang[] $eventLangs
 * @property FaqCategoryLang[] $faqCategoryLangs
 * @property FaqLang[] $faqLangs
 * @property Message[] $messages
 * @property Page[] $pages
 * @property Profile[] $profiles
 */
class Lang extends ActiveRecord
{
    /**
     * Переменная, для хранения текущего объекта языка
     */
    static $current = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lang';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'local', 'name'], 'required'],
            [['default', 'date_update', 'date_create'], 'integer'],
            [['url', 'local', 'name'], 'string', 'max' => 255],
            [['url', 'local', 'name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('app', 'ID'),
//            'url' => Yii::t('app', 'Url'),
//            'local' => Yii::t('app', 'Local'),
//            'name' => Yii::t('app', 'Name'),
//            'default' => Yii::t('app', 'Default'),
//            'date_update' => Yii::t('app', 'Date Update'),
//            'date_create' => Yii::t('app', 'Date Create'),
        ];
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogsLangs()
    {
        return $this->hasMany(BlogsLang::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventLangs()
    {
        return $this->hasMany(EventLang::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqCategoryLangs()
    {
        return $this->hasMany(FaqCategoryLang::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFaqLangs()
    {
        return $this->hasMany(FaqLang::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['lang_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['lang_id' => 'id']);
    }

    /**
     * Получение текущего объекта языка
     * @return Lang | ActiveRecord
     */
    static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

    /**
     * Установка текущего объекта языка и локаль пользователя
     */
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        if($language === null){
            $language = Yii::$app->session->get('language');
        }
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->session->set('language', self::$current);
        Yii::$app->language = self::$current->local;
    }

    /**
     * Получения объекта языка по умолчанию
     * @return Lang | ActiveRecord
     */
    static function getDefaultLang()
    {
        return Lang::find()->where('`default` = :default', [':default' => 1])->one();
    }

    /**
     * Получения объекта языка по буквенному идентификатору
     */
    static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            $language = Lang::find()->where('url = :url', [':url' => $url])->one();
            if ( $language === null ) {
                return null;
            }else{
                return $language;
            }
        }
    }

    /*
     * Возвращает список всех языков, которых еще нет в блоге
     * */
    public static function langForBlog($blogId, $exceptLang = null){
        $langs = BlogsLang::find()->where('blog_id = :blog', ['blog'=>$blogId])->all();
        $langArr = self::getLangArr($langs, $exceptLang);
        return $langArr;
    }

    /*
     * Возвращает список всех языков, которых еще нет в направлениях
     * */
    public static function langForDirection($directionId, $exceptLang = null){
        $langs = Directionlang::find()->where('direction_id = :direction', ['direction'=>$directionId])->all();
        $langArr = self::getLangArr($langs, $exceptLang);
        return $langArr;
    }

    /*
     * Список языков, за исключением $langs
     * */
    public static function getLangArr($langs, $exceptLang = null){
        $langArr = [];
        foreach($langs as $bl){
            if(is_a($bl, Lang::className())){
                $langArr[$bl->id] = $bl->id;
            }
            else{
                $langArr[$bl->lang_id] = $bl->lang_id;
            }
        }
        if(isset($exceptLang))
            unset($langArr[$exceptLang]);
        $langArr = self::find()->where(['NOT IN','id', $langArr])->all();
        return $langArr;
    }


    /**
     * Возвращает список всех языков
     * @param string $fieldVal
     * @param string $filedKey
     * @return array
     */
    public static function getArr($fieldVal='name', $filedKey='id') {
        $list = self::find()->all();
        return ($list) ? ArrayHelper::map($list, $filedKey, $fieldVal) : [];
    }
}
