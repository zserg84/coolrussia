<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 27.02.15
 * Time: 12:19
 */

namespace modules\event\models\form;

use kop\y2cv\ConditionalValidator;
use modules\event\models\EventQuestion;
use modules\event\models\EventType;
use modules\event\Module;
use yii\base\Exception;
use yii\base\Model ;
use yii\helpers\VarDumper;
use yii\validators\RequiredValidator;

class EventForm2 extends Model
{

    public $title;
    public $video;
    public $questions = [];

    public $language;

    public $question_where;
    public $question_includeInPrice;
    public $question_take;
    public $question_why;
    public $question_what;
    public $question_extra;
    public $question_description;

    public function rules(){
        return [
            [['title', 'video', 'question_where', 'question_includeInPrice', 'question_take', 'question_what', 'question_description', 'question_why', 'question_extra'], 'safe'],

            [['title'], 'required'],
            /*Для типа события "Экскурсия"*/
            [['question_where', 'question_includeInPrice', 'question_take'], 'required',
                'when'=>function($model){return in_array(1, EventType::getQuestionTypes($this->questions));}, 'whenClient'=>"function(attribute, value){return false;}"],
            /*Для типа события "Приключения"*/
            [['question_where', 'question_includeInPrice', 'question_take'], 'required',
                'when'=>function($model){return in_array(2, EventType::getQuestionTypes($this->questions));}, 'whenClient'=>"function(attribute, value){return false;}"],
            /*Для типа события "Мастер-класс"*/
            [['question_what', 'question_includeInPrice', 'question_take'], 'required',
                'when'=>function($model){return in_array(3, EventType::getQuestionTypes($this->questions));}, 'whenClient'=>"function(attribute, value){return false;}"],
            /*Для типа события "Благотворительность"*/
            [['question_description'], 'required',
                'when'=>function($model){return in_array(4, EventType::getQuestionTypes($this->questions));}, 'whenClient'=>"function(attribute, value){return false;}"],
        ];
    }

    public function attributeLabels(){
        return [
            'question_where' => Module::t('event_description', 'QUESTION_WHERE'),
            'question_includeInPrice' => Module::t('event_description', 'QUESTION_INCLUDE_IN_PRICE'),
            'question_take' => Module::t('event_description', 'QUESTION_TAKE'),
            'question_why' => Module::t('event_description', 'QUESTION_WHY'),
            'question_what' => Module::t('event_description', 'QUESTION_WHAT'),
            'question_extra' => Module::t('event_description', 'QUESTION_EXTRA'),
            'question_description' => Module::t('event_description', 'QUESTION_DESCRIPTION'),
        ];
    }
} 