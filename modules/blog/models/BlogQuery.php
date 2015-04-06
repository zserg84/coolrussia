<?php

namespace modules\blog\models;

use modules\users\traits\ModuleTrait;
use yii\db\ActiveQuery;

/**
 * Class BlogQuery
 * @package modules\blog\models
 */
class BlogQuery extends ActiveQuery
{
    use ModuleTrait;

    /**
     * Select published posts.
     *
     * @return $this
     */
    public function published()
    {
        $this->andWhere(['status_id' => Blog::STATUS_PUBLISHED]);

        return $this;
    }

    /**
     * Select unpublished posts.
     *
     * @return $this
     */
    public function unpublished()
    {
        $this->andWhere(['status_id' => Blog::STATUS_UNPUBLISHED]);

        return $this;
    }
}
