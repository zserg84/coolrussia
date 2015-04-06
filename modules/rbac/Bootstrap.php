<?php

namespace modules\rbac;

use yii\base\BootstrapInterface;

/**
 * Blogs module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        // Add module I18N category.
        if (!isset($app->i18n->translations['modules/rbac']) && !isset($app->i18n->translations['modules/*'])) {
            $app->i18n->translations['modules/rbac'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => 'rbac/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'modules/rbac' => 'rbac.php',
                ]
            ];
        }
    }
}
