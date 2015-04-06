<?php

namespace modules\users\models;

use modules\image\models\Image;
use modules\lang\models\Lang;
use modules\users\helpers\Security;
use modules\users\Module;
use modules\users\traits\ModuleTrait;
use modules\geo\models\GeoCity;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use Yii;

/**
 * Class User
 * @package modules\users\models
 * User model.
 *
 * @property integer $id ID
 * @property string $name Name
 * @property string $email E-mail
 * @property string $password_hash Password hash
 * @property string $auth_key Authentication key
 * @property string $role Role
 * @property integer $city_id
 * @property string $birthday
 * @property double $rate
 * @property integer $status_id Status
 * @property integer $created_at Created time
 * @property integer $updated_at Updated time
 *
 * @property Profile[] $profiles
 * @property Profile $profile
 * @property GeoCity $city
 * @property Image $image
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    use ModuleTrait;

    /** Inactive status */
    const STATUS_INACTIVE = 0;
    /** Active status */
    const STATUS_ACTIVE = 1;
    /** Banned status */
    const STATUS_BANNED = 2;
    /** Deleted status */
    const STATUS_DELETED = 3;

    /**
     * Default role
     */
    const ROLE_DEFAULT = 'user';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Find users by IDs.
     *
     * @param $ids Users IDs
     * @param null $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] Users
     */
    public static function findIdentities($ids, $scope = null)
    {
        $query = static::find()->where(['id' => $ids]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Find model by email.
     *
     * @param string $email Email
     * @param string $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] User
     */
    public static function findByEmail($email, $scope = null)
    {
        $query = static::find()->where(['email' => $email]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->one();
    }

    /**
     * Find model by token.
     *
     * @param string $token Token
     * @param string $scope Scope
     *
     * @return array|\yii\db\ActiveRecord[] User
     */
    public static function findByToken($token, $scope = null)
    {
        $query = static::find()->where(['token' => $token]);
        if ($scope !== null) {
            if (is_array($scope)) {
                foreach ($scope as $value) {
                    $query->$value();
                }
            } else {
                $query->$scope();
            }
        }
        return $query->one();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }


    public static function GetCurrent() {
        if ($id = Yii::$app->getUser()->getId()) {
            return User::find()->where(['id'=>$id])->one();
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Auth Key validation.
     *
     * @param string $authKey
     *
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Password validation.
     *
     * @param string $password
     *
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @return string Human readable created date
     */
    public function getCreated()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password_hash', 'auth_key', 'token', 'created_at', 'updated_at'], 'required'],
            [['status_id', 'created_at', 'updated_at', 'city_id'], 'integer'],
            [['birthday'], 'safe'],
            [['rate'], 'number'],
            [['name', 'email'], 'string', 'max' => 100],
            [['password_hash'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['token'], 'string', 'max' => 53],
            [['role'], 'string', 'max' => 64],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Module::t('users', 'ATTR_NAME'),
            'email' => Module::t('users', 'ATTR_EMAIL'),
            'role' => Module::t('users', 'ATTR_ROLE'),
            'status_id' => Module::t('users', 'ATTR_STATUS'),
            'created_at' => Module::t('users', 'ATTR_CREATED'),
            'updated_at' => Module::t('users', 'ATTR_UPDATED'),
            'city_id' => Module::t('users', 'ATTR_CITY'),
            'birthday' => Module::t('users', 'ATTR_BIRTHDAY'),
            'rate' => Module::t('users', 'ATTR_RATE'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Set default status
                if (!$this->status_id) {
                    $this->status_id = $this->module->requireEmailConfirmation ? self::STATUS_INACTIVE : self::STATUS_ACTIVE;
                }
                // Set default role
                if (!$this->role) {
                    $this->role = self::ROLE_DEFAULT;
                }
                // Generate auth and secure keys
                $this->generateAuthKey();
                $this->generateToken();
            }
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            Yii::$app->authManager->revokeAll($this->id);
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes){
        if (parent::afterSave($insert, $changedAttributes)) {
            if($insert){
                $profile = new Profile();
                $profile->user_id = $this->id;
                $profile->lang_id = Lang::getCurrent()->id;
                $profile->name = $this->name;
                $profile->save();
            }

            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @param int|null $langId
     * @return array|null|ActiveRecord
     */
    public function getProfile($langId=null)
    {
        $userId = $this->id ?: Yii::$app->getUser()->getId();
        $where = ['user_id'=>$userId];
        $where['lang_id'] = (!is_null($langId)) ? $langId : Lang::getCurrent()->id;
        if (!$profile = Profile::find()->where($where)->one()) {
            $where['lang_id'] = Lang::getDefaultLang()->id;
            $profile = Profile::find()->where($where)->one();
        }
        return $profile;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(GeoCity::className(), ['id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * Generates "remember me" authentication key.
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates secure key.
     */
    public function generateToken()
    {
        $this->token = Security::generateExpiringRandomString();
    }

    /**
     * Activates user account.
     *
     * @return boolean true if account was successfully activated
     */
    public function activation()
    {
        $this->status_id = self::STATUS_ACTIVE;
        $this->generateToken();
        return $this->save(false);
    }

    /**
     * Recover password.
     *
     * @param string $password New Password
     *
     * @return boolean true if password was successfully recovered
     */
    public function recovery($password)
    {
        $this->setPassword($password);
        $this->generateToken();
        return $this->save(false);
    }

    /**
     * Generates password hash from password and sets it to the model.
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Change user password.
     *
     * @param string $password Password
     *
     * @return boolean true if password was successfully changed
     */
    public function password($password)
    {
        $this->setPassword($password);
        return $this->save(false);
    }

    public function getName(){
        $profile = $this->getProfile();
        return $profile ? $profile->name : $this->name;
    }


    public function getViewUrl() {
        if (!$this->id) return false;
        return Url::toRoute(['/users/default/view', 'id'=>$this->id]);
    }

}
