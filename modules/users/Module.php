<?php

namespace modules\users;

use modules\translations\components\DbMessageSource;
use Yii;

/**
 * Module [[Users]]
 * Yii2 users module.
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'modules\users\controllers\frontend';

    /**
     * @var boolean If true after registration user will be required to confirm his e-mail address.
     */
    public $requireEmailConfirmation = true;

    /**
     * @var string E-mail address from that will be sent the module messages
     */
    public $robotEmail;

    /**
     * @var string Name of e-mail sender.
     * By default is `Yii::$app->name . ' robot'`.
     */
    public $robotName;

    /**
     * @var integer The time before a sent activation token becomes invalid.
     * By default is 24 hours.
     */
    public $activationWithin = 86400; // 24 hours

    /**
     * @var integer The time before a sent recovery token becomes invalid.
     * By default is 4 hours.
     */
    public $recoveryWithin = 14400; // 4 hours

    /**
     * @var integer The time before a sent confirmation token becomes invalid.
     * By default is 4 hours.
     */
    public $emailWithin = 14400; // 4 hours

    /**
     * @var integer Users per page
     */
    public $recordsPerPage = 10;

    /**
     * @var array User roles that can access backend module.
     */
    public $adminRoles = ['superadmin', 'admin'];

    /**
     * @var string Temporary path where will be saved user's avatar
     */
    public $avatarsTempPath = '@statics/temp/users/avatars';

    /**
     * @var string Path where will be saved user's avatar
     */
    public $avatarPath = '@statics/web/users/avatars';

    /**
     * @var string Avatars path URL
     */
    public $avatarUrl = '/statics/users/avatars';

    /**
     * @var string Username regular pattern
     */
    public $patternUsername = '/^[a-zA-Z0-9_-]+$/';

    /**
     * @var string Name regular pattern
     */
    public $patternName = '/^[a-zа-яё ]+$/iu';

    /**
     * @var string Surname regular pattern
     */
    public $patternSurname = '/^[a-zа-яё]+(-[a-zа-яё]+)?$/iu';

    public $interfaceType = 'guest';

    /**
     * @var \yii\swiftmailer\Mailer Mailer instance
     */
    private $_mail;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $app = \Yii::$app;

        self::initLang();

        if ($this->interfaceType === 'backend') {
            $this->setViewPath('@modules/users/views/backend');
        }
        elseif ($this->interfaceType === 'customer') {
            $this->setViewPath('@modules/users/views/customer');
        }
        elseif ($this->interfaceType === 'performer') {
            $this->setViewPath('@modules/users/views/performer');
        }
        else {
            $this->setViewPath('@modules/users/views/frontend');
        }
    }

    public static function  initLang(){
        $langNames = ['REGISTRATION_FORM_CUSTOMER', 'REGISTRATION_FORM_PERFORMER', 'users'];
        $app = \Yii::$app;
        foreach($langNames as $langName){
            if (!isset($app->i18n->translations[$langName])) {
                $app->i18n->translations[$langName] = [
                    'class' => DbMessageSource::className(),
                    'forceTranslation' => true,
                ];
            }
        }
    }

    /**
     * @return \yii\swiftmailer\Mailer Mailer instance with predefined templates.
     */
    public function getMail()
    {
        if ($this->_mail === null) {
            $this->_mail = Yii::$app->getMailer();
            $this->_mail->htmlLayout = '@modules/users/mails/layouts/html';
            $this->_mail->textLayout = '@modules/users/mails/layouts/text';
            $this->_mail->viewPath = '@modules/users/mails/views';
            if ($this->robotEmail !== null) {
                $this->_mail->messageConfig['from'] = $this->robotName === null ? $this->robotEmail : [$this->robotEmail => $this->robotName];
            }
        }
        return $this->_mail;
    }

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
     *
     * The translation will be conducted according to the message category and the target language will be used.
     *
     * You can add parameters to a translation message that will be substituted with the corresponding value after
     * translation. The format for this is to use curly brackets around the parameter name as you can see in the following example:
     *
     * ```php
     * $username = 'Alexander';
     * echo \Yii::t('app', 'Hello, {username}!', ['username' => $username]);
     * ```
     *
     * Further formatting of message parameters is supported using the [PHP intl extensions](http://www.php.net/manual/en/intro.intl.php)
     * message formatter. See [[\yii\i18n\I18N::translate()]] for more details.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        self::initLang();
        return Yii::t($category, $message, $params, $language);
    }
}
