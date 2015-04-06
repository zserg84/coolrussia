<?php

namespace modules\themes;

use yii\base\BootstrapInterface;

/**
 * Themes bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add themes I18N category.
        if (!isset($app->i18n->translations['modules/themes/admin*']) && !isset($app->i18n->translations['modules/themes/*']) && !isset($app->i18n->translations['modules/*'])) {
            $app->i18n->translations['modules/themes/admin*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@modules/themes/admin/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'admin' => 'admin.php',
                    'widgets/box' => 'box.php'
                ]
            ];
        }
        if (!isset($app->i18n->translations['modules/themes/site*']) && !isset($app->i18n->translations['modules/themes/*']) && !isset($app->i18n->translations['modules/*'])) {
            $app->i18n->translations['modules/themes/site*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@modules/themes/site/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'site' => 'site.php',
                ]
            ];
        }
    }
}
