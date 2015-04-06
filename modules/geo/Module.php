<?php

namespace modules\geo;

use Yii;

class Module extends \modules\base\components\Module
{

    public static $name = 'geo';

    const TABLE_COUNTRY_EUROPE = 'geo_ip_country_europe';

    const TABLE_COUNTRY_WORLD = 'geo_ip_country_world';

    public function init()
    {
        self::$langNames = [
            'geo', 'geo-country', 'geo-region', 'geo-city',
        ];

        parent::init();
    }
}