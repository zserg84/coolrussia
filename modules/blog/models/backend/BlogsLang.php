<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 03.02.15
 * Time: 14:43
 */

namespace modules\blog\models\backend;


class BlogsLang extends \modules\blog\models\BlogsLang{

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['admin-create'] = [
            'title',
            'snippet',
            'content',
            'lang_id',
        ];
        $scenarios['admin-update'] = [
            'title',
            'snippet',
            'content',
            'lang_id',
        ];

        return $scenarios;
    }
} 