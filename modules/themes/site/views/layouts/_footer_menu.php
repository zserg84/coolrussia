<?php

/**
 * Footer menu view.
 *
 * @var \yii\web\View $this View
 */

use vova07\themes\site\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => 'footer_menu',
        ],
        'items' => [
            [
                'label' => Yii::t('modules/site', 'About'),
                'url' => '/page/about/',
            ],
            [
                'label' => Yii::t('modules/site', 'For organizers'),
                'url' => '/page/for_organizers/',
            ],
            [
                'label' => Yii::t('modules/site', 'Agreement'),
                'url' => '/page/agreement/',
            ],
            [
                'label' => Yii::t('modules/site', 'FAQ'),
                'url' => ['/faq/default/index/']
            ],
            [
                'label' => Yii::t('modules/site', 'Feedback'),
                'url' => ['/feedback/']
            ],
        ]
    ]
);