<?php

/**
 * Top menu view.
 *
 * @var \yii\web\View $this View
 */

use modules\themes\Module;
use modules\themes\site\widgets\Menu;
use modules\users\models\User;
use yii\helpers\Url;

$username = null;
if ($user = User::GetCurrent()) {
    $username = $user->name;
}

echo Menu::widget(
    [
        'options' => [
            'class' => isset($footer) ? 'pull-right' : 'nav navbar-nav navbar-right'
        ],
        'items' => [
            [
                'label'=>'[ '.$username.' ]',
                'url' => Url::to(['/users/default/view']),
                'visible' => !Yii::$app->user->isGuest,
            ],
            [
                'label'=>Module::t('themes-site', 'Backend'),
                'url' => ['/backend'],
                'template' => '<a href="{url}" style="color:#d9534f;">{label}</a>',
                'visible' => ($user && ($user->role === 'superadmin' )),
            ],
            [
                'label' => Module::t('themes-site', 'Sign In'),
                'url' => ['/users/guest/login'],
                'visible' => Yii::$app->user->isGuest
            ],
            [
                'label' => Module::t('themes-site', 'Sign Up'),
                'url' => ['/users/guest/signup'],
                'visible' => Yii::$app->user->isGuest
            ],
            [
                'label' => Module::t('themes-site', 'Settings'),
                'url' => '#',
                'template' => '<a href="{url}" class="dropdown-toggle" data-toggle="dropdown">{label} <i class="icon-angle-down"></i></a>',
                'visible' => !Yii::$app->user->isGuest,
                'items' => [
                    [
                        'label' => Module::t('themes-site', 'Edit profile'),
                        'url' => ['/users/user/update']
                    ],
//                    [
//                        'label' => Module::t('themes-site', 'Change email'),
//                        'url' => ['/users/user/email']
//                    ],
                    [
                        'label' => Module::t('themes-site', 'Change password'),
                        'url' => ['/users/user/password']
                    ]
                ]
            ],
            [
                'label' => Module::t('themes-site', 'Sign Out'),
                'url' => ['/users/user/logout'],
                'visible' => !Yii::$app->user->isGuest
            ]
        ]
    ]
);