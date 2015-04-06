<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 16.02.15
 * Time: 15:35
 */

namespace modules\event\models\form;

use kop\y2cv\ConditionalValidator;
use modules\event\models\EventDate;
use modules\event\models\EventType;
use modules\event\Module;
use yii\base\Model ;
use \modules\event\models\Event;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class EventForm1 extends Model
{

    public $event_types;
    public $event_categories;
    public $duration;
    public $duration_type = self::DURATION_TYPE_HOUR;
    public $city_name;
    public $city_id;
    public $address;
    public $address_comment;
    public $time_type;
    public $event_dates;
    public $event_dates_repeat;
    public $cost_type;
    public $event_cost_amount;
    public $event_cost_people_min;
    public $event_cost_people_max;
    public $event_costs;
    public $prepayment;
    public $email;
    public $phone;
    public $date_comment;

    const TIME_TYPE_CONCRETE = 'Concrete';
    const TIME_TYPE_REPEAT = 'Repeat';
    const TIME_TYPE_FREE = 'Free';

    const COST_TYPE_FREE = 'Free';
    const COST_TYPE_ONE = 'One';
    const COST_TYPE_MANY = 'Many';

    const DURATION_TYPE_DAY = 'Day';
    const DURATION_TYPE_HOUR = 'Hour';

    public function rules()
    {
        return [
            [['event_types', 'event_categories', 'duration_type', 'duration', 'city_name', 'email', 'phone'], 'required'],
            [['address', 'address_comment', 'event_dates', 'time_type', 'date_comment'], 'safe'],
            [['prepayment'], 'number', 'min' => 20, 'max' => 100],
            ['email', 'email'],
            [['city_id'], ConditionalValidator::className(),
                'if' => [
                    [['city_name'], 'required']
                ],
                'then' => [
                    [['city_id'], 'required', 'message' => Module::t('event', 'City is not correct')],
                ]
            ],
            ['time_type', ConditionalValidator::className(),
                'if' => [
                    [['time_type'], 'compare', 'compareValue' => self::TIME_TYPE_CONCRETE]
                ],
                'then' => [
                    [['event_dates'], 'required'],
                ]
            ],
            ['time_type', ConditionalValidator::className(),
                'if' => [
                    [['time_type'], 'compare', 'compareValue' => self::TIME_TYPE_REPEAT]
                ],
                'then' => [
                    [['event_dates_repeat'], 'required'],
                ]
            ],
            ['cost_type', ConditionalValidator::className(),
                'if' => [
                    [['cost_type'], 'compare', 'compareValue' => self::COST_TYPE_ONE]
                ],
                'then' => [
                    [['prepayment', 'event_cost_amount'], 'required'],
                ]
            ],
            ['cost_type', ConditionalValidator::className(),
                'if' => [
                    [['cost_type'], 'compare', 'compareValue' => self::COST_TYPE_MANY]
                ],
                'then' => [
                    [['prepayment'], 'required'],
                    ['event_costs', 'eventCostsValidator'],
                ]
            ],
            ['address_comment', ConditionalValidator::className(),
                'if' => [
                    [['address_comment'], 'compare', 'compareValue' => '']
                ],
                'then' => [
                    [['address'], 'required', 'message' => Module::t('event', 'Address or comment must be set')],
                ],
                'message' => 'One of 2 fields must be set',
            ],
            ['address', ConditionalValidator::className(),
                'if' => [
                    [['address'], 'compare', 'compareValue' => '']
                ],
                'then' => [
                    [['address_comment'], 'required', 'message' => Module::t('event', 'Address or comment must be set')],
                ],
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'event_types' => Module::t('event', 'EVENT_TYPES'),
            'event_categories' => Module::t('event', 'EVENT_CATEGORIES'),
            'duration' => Module::t('event', 'DURATION'),
            'duration_type' => Module::t('event', 'DURATION_TYPE'),
            'city_name' => Module::t('event', 'CITY'),
            'city_id' => Module::t('event', 'CITY'),
            'address' => Module::t('event', 'ADDRESS'),
            'address_comment' => Module::t('event', 'ADDRESS_COMMENT'),
            'time_type' => Module::t('event', 'TIME_TYPE'),
            'event_date' => Module::t('event', 'EVENT_DATE'),
            'cost_type' => Module::t('event', 'EVENT_COST_TYPE'),
            'event_cost_amount' => Module::t('event', 'EVENT_COST_AMOUNT'),
            'event_cost_people_min' => Module::t('event', 'EVENT_COST_PEOPLE_MIN'),
            'event_cost_people_max' => Module::t('event', 'EVENT_COST_PEOPLE_MAX'),
            'prepayment' => Module::t('event', 'PREPAYMENT'),
            'email' => Module::t('event', 'EMAIL'),
            'phone' => Module::t('event', 'PHONE'),
            'date_comment' => Module::t('event', 'DATE_COMMENT'),
            'event_dates_repeat' => Module::t('event', 'EVENT_DATES_REPEAT'),
        ];
    }

    public static function getTimeTypes(){
        return [
            self::TIME_TYPE_CONCRETE => Module::t('event', 'TIME_TYPE_CONCRETE'),
            self::TIME_TYPE_REPEAT => Module::t('event', 'TIME_TYPE_REPEAT'),
            self::TIME_TYPE_FREE => Module::t('event', 'TIME_TYPE_FREE'),
        ];
    }

    public static function getCostTypes(){
        return [
            self::COST_TYPE_FREE => Module::t('event', 'COST_TYPE_FREE'),
            self::COST_TYPE_ONE => Module::t('event', 'COST_TYPE_ONE'),
            self::COST_TYPE_MANY => Module::t('event', 'COST_TYPE_MANY'),
        ];
    }

    public function getDurationTypes(){
        return [
            self::DURATION_TYPE_HOUR => Module::t('event', 'DURATION_TYPE_HOUR'),
            self::DURATION_TYPE_DAY => Module::t('event', 'DURATION_TYPE_DAY'),
        ];
    }

    public function eventCostsValidator($attribute, $params){
        $costs = $this->$attribute;
        $validate = false;
        foreach($costs as $cost){
            if($cost['amount'] && $cost['people_min']){
                $validate = true;
                break;
            }
        }
        if(!$validate){
            $this->addError($attribute, 'Fields are required');
        }
        return $validate;
    }


    /**
     * @var \modules\event\models\Event $event
     */
    public function getByEvent(Event $event) {
        $this->setAttributes($event->getAttributes());

        $eventCats = [];
        $eventCategories = $event->categories;
        foreach($eventCategories as $category){
            $eventCats[] = $category->id;
        }

        $this->event_dates = ArrayHelper::map(EventDate::findAll(['event_id'=>$event->id]), 'id', 'date_start');

        $eventTypes = EventType::find()->innerJoinWith([
            'eventCategoryTypes' => function ($query) use($eventCats){
                $query->where(['category_id'=>$eventCats]);
            }
        ])->all();
        $this->event_categories = $eventCats;
        $this->event_types = $eventTypes;
    }

}