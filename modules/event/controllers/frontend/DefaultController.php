<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 16.02.15
 * Time: 16:01
 */

namespace modules\event\controllers\frontend;

use modules\event\models\search\EventSearch;
use Yii;
use frontend\widgets\TimeCalendar\TimeCalendarWidget;
use modules\event\models\Event;
use modules\event\models\EventCategory;
use modules\event\models\EventCategoryEvent;
use modules\event\models\EventCost;
use modules\event\models\EventDate;
use modules\event\models\EventDescription;
use modules\event\models\EventLang;
use modules\event\models\EventQuestion;
use modules\event\models\EventTimeRepeat;
use modules\event\models\form\EventForm1;
use modules\event\models\form\EventForm2;
use modules\event\models\form\EventForm3;
use modules\lang\models\Lang;
use modules\users\models\frontend\User;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class DefaultController extends Controller {

    public function actionIndex() {
        $model = new EventSearch();

        $eventCategories = EventCategory::find()->all();
        $eventCategories = ArrayHelper::map($eventCategories, 'id', 'id');
        $model->event_categories = $eventCategories;

        $dataProvider = $model->search();

        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id) {
        if ($event = Event::find()->where(['id'=>$id])->active()->one()) {
            return $this->render('view', compact('event'));
        }
        throw new NotFoundHttpException;
    }


    public function actionEdit($id=null) {
        $step = \Yii::$app->getRequest()->get('step', 1);
        switch ($step) {
            case 1:
                $renderParams = $this->step1($id);
                break;
            case 2:
                $renderParams = $this->step2($id);
                break;
            case 3:
                $renderParams = $this->step3($id);
                break;
        }

        return $this->render('edit', $renderParams);
    }


    public function step1($id=null) {
        $form = new EventForm1();

        $user = User::getCurrent();
        $form->email = $user->email;
        if ($user->city_id) {
            $form->city_id = $user->city_id;
            $form->city_name = $user->city->getName();
        }

        $eventCategories = [];

        /** @var Event $event */
        if (($id = intval($id)) and ($event = Event::findOne($id))) {
            $form->getByEvent($event);
            $eventTypes = ArrayHelper::map($form->event_types, 'id', 'name');
            $eventCategories = EventCategory::find()->eventCategoriesByType(array_keys($eventTypes))->all();
        } else {
            $event = new Event();
        }

        if ($post = \Yii::$app->getRequest()->post()) {
            if ($form->load($post)) {
                switch ($form->time_type) {
                    case EventForm1::TIME_TYPE_CONCRETE:
                        if (isset($post['dateCalendar'])) {
                            $dateCalendar = $post['dateCalendar'];
                            $eventDates = [];
                            foreach($dateCalendar as $date=>$values) {
                                $date = date_format(date_create_from_format('d.m.Y', $date), 'Y-m-d');
                                foreach($values as $value) {
                                    $time = explode(':', $value);
                                    $min = isset($time[0]) ? $time[0]*60 : 0;
                                    $sec = isset($time[1]) ? $time[1]*60 : 0;
                                    $sec = $min*60 + $sec;
                                    $eventDates[] = strtotime($date)+$sec;
                                }
                            }
                            $form->event_dates = $eventDates;
                        }
                        break;
                    case EventForm1::TIME_TYPE_REPEAT:
                        if (isset($post['dateCalendar'])) {
                            $dateCalendar = $post['dateCalendar'];
                            $weekdays = \Yii::$app->getRequest()->post('weekday', []);
                            $event_dates_repeat = [];
                            foreach($weekdays as $weekday) {
                                if (isset($dateCalendar[$weekday])) {
                                    $event_dates_repeat[] = [
                                        'dayweek'=>$weekday,
                                        'times'=>$dateCalendar[$weekday],
                                    ];
                                }
                            }
                            $form->event_dates_repeat = $event_dates_repeat;
                        }
                        break;
                    case EventForm1::TIME_TYPE_FREE:
                        break;
                }

                switch($form->cost_type) {
                    case EventForm1::COST_TYPE_FREE:
                        break;
                    case EventForm1::COST_TYPE_ONE;
                        break;
                    case EventForm1::COST_TYPE_MANY:
                        $amount = $post['amount'];
                        $peopleMin = $post['people_min'];
                        $peopleMax = $post['people_max'];
                        $eventCosts = [];
                        foreach($amount as $k=>$amountVal) {
                            $eventCosts[$k] = [
                                'amount' => $amountVal,
                                'people_min' => $peopleMin[$k],
                                'people_max' => $peopleMax[$k],
                            ];
                        }
                        $form->event_costs = $eventCosts;
                        break;
                }

                if ($form->validate()) {
                    $event->title = '.';
                    $event->setAttributes($form->getAttributes());
                    if ($event->save()) {
                        foreach($form->event_categories as $category_id) {
                            if (!$eventCategory = EventCategoryEvent::findOne(['event_id' => $event->id, 'category_id' => $category_id])) {
                                $eventCategory = new EventCategoryEvent();
                                $eventCategory->event_id = $event->id;
                                $eventCategory->category_id = $category_id;
                                $eventCategory->save();
                            }
                        }
                        $form->event_dates = $form->event_dates ? $form->event_dates : [];
                        foreach($form->event_dates as $eventDate) {
                            $eventDateModel = new EventDate();
                            $eventDateModel->event_id = $event->id;
                            $eventDateModel->date_start = $eventDate;
                            $eventDateModel->save();
                        }

                        $form->event_dates_repeat = $form->event_dates_repeat ? $form->event_dates_repeat : [];
                        foreach($form->event_dates_repeat as $eventDateRepeat) {
                            foreach($eventDateRepeat['times'] as $time) {
                                $eventTimeRepeatModel = new EventTimeRepeat();
                                $eventTimeRepeatModel->event_id = $event->id;
                                $eventTimeRepeatModel->dayweek = $eventDateRepeat['dayweek'];
                                $eventTimeRepeatModel->time = $time;
                                $eventTimeRepeatModel->save();
                            }
                        }

                        $form->event_costs = $form->event_costs ? $form->event_costs : [];
                        foreach($form->event_costs as $cost) {
                            $eventCost = new EventCost();
                            $eventCost->event_id = $event->id;
                            $eventCost->amount = $cost['amount'];
                            $eventCost->people_min = $cost['people_min'];
                            $eventCost->people_max = $cost['people_max'];
                            $eventCost->save();
                        }
                        $this->redirect(['edit', 'id'=>$event->id, 'step'=>2]);
                    }
                } else {
                    \yii\helpers\VarDumper::dump($form->getErrors(),10,1);exit;
                }
            }
            if ($eventTypes = \Yii::$app->getRequest()->post('eventTypes')) {
                $eventTypes = $eventTypes;
            }
            $eventCategories = EventCategory::find()->eventCategoriesByType($eventTypes)->all();

            if (!$form->event_categories) {
                $form->event_categories = \Yii::$app->getRequest()->post('eventCategories');
            }
        }

        $eventCategories = ArrayHelper::map($eventCategories, 'id', 'name');

        if(\Yii::$app->getRequest()->post('costType'))
            $form->cost_type = \Yii::$app->getRequest()->post('costType');
        else
            $form->cost_type = $form->cost_type ? $form->cost_type : EventForm1::COST_TYPE_FREE;

        if(\Yii::$app->getRequest()->post('timeType'))
            $form->time_type = \Yii::$app->getRequest()->post('timeType');
        else
            $form->time_type = $form->time_type ? $form->time_type : EventForm1::TIME_TYPE_CONCRETE;

        return [
            'page' => '_step1',
            'pageParams' => [
                'model' => $form,
                'eventCategories' => $eventCategories,
                'event_id' => $event->id,
            ],
        ];
    }

    public function step2($id){
        $event = Event::findOne($id);
        if (!$event) {
            throw new NotFoundHttpException();
        }

        /*Список вопросов для данного события*/
        $questions = EventQuestion::find()->innerJoinWith([
            'type.categories.eventCategoryEvents' => function ($query) use ($id){
                $query->where(['event_id'=>$id]);
            }
        ])->all();

        /*Текущий язык*/
        $curLang = Lang::getCurrent();

        $languages = Lang::find()->all();
        $models = [];

        /*Список языков, выбранных на форме*/
        $languageSelectedList = [];

        foreach($languages as $language){
            $eventLang = EventLang::find()->where([
                'event_id' => $id,
                'lang_id' => $language->id,
            ])->one();
            $eventLang = $eventLang ? $eventLang : new EventLang();

            if($eventLang->lang_id)
                $languageSelectedList[] = $eventLang->lang;

            $eventDescription = EventDescription::find()->where([
                'event_lang_id'=>$eventLang->id,
            ])->one();
            $eventDescription = $eventDescription ? $eventDescription : new EventDescription();

            $form = new EventForm2();
            $form->setAttributes($event->getAttributes());
            $form->setAttributes($eventLang->getAttributes());
            $form->setAttributes($eventDescription->getAttributes());
            $form->questions = $questions;
            $form->language = $language;
            $models[$language->id] = $form;
        }
        
        $languageSelectedList = $languageSelectedList ? $languageSelectedList : [$curLang];

        if($post = \Yii::$app->getRequest()->post()) {
            $languageSelectedList = [];
            /*Список языков, выбранных на форме, пришедших в post*/
            $postLanguageList = \Yii::$app->getRequest()->post('hiddenLanguage');
            foreach($postLanguageList as $pl){
                $languageSelectedList[] = Lang::findOne($pl);
            }

            if(isset($post['Lang'])){
                $postLangs = [];
                foreach($post['Lang'] as $langId => $eventFormPost){
                    $postLangs[] = $langId;
                }
                /*Все существующие в БД для данного события eventLangs*/
                $existsEventLangs = EventLang::find()->where(['event_id'=>$id])->all();
                $validate = true;
                foreach($post['Lang'] as $langId => $eventFormPost){
                    $form = $models[$langId];
                    if ($form->load($eventFormPost) && $form->validate()) {
                        $event->setAttributes($form->getAttributes());

                        $eventLang = EventLang::find()->where([
                            'event_id' => $id,
                            'lang_id' => $langId,
                        ])->one();
                        $eventLang = $eventLang ? $eventLang : new EventLang();
                        $eventLang->event_id = $event->id;
                        $eventLang->lang_id = $langId;
                        $eventLang->setAttributes($form->getAttributes());
                        if($eventLang->save()){
                            $eventDescription = EventDescription::find()->where([
                                'event_lang_id'=>$eventLang->id,
                            ])->one();
                            $eventDescription = $eventDescription ? $eventDescription : new EventDescription();
                            $eventDescription->event_lang_id = $eventLang->id;
                            $eventDescription->setAttributes($form->getAttributes());
                            $eventDescription->save();
                        }
                        $validate = $validate ? $validate : false;
                    }
                    else{
                        $validate = false;
                    }
                    $models[$langId] = $form;
                }
                /*
                 * Если в post пришло меньше языков, чем в БД, то лишние удаляем из БД
                 * */
                foreach($existsEventLangs as $existsEventLang){
                    if(!in_array($existsEventLang->lang_id, $postLangs)){
                        $existsEventLang->delete();
                    }
                }
                if($validate)
                    $this->redirect(['edit', 'id'=>$event->id, 'step'=>3]);
            }
        }

        /*
         * Удаляем дубли из вопросов для каждой модели
         * */
        foreach($languages as $language) {
            $dopQuestions = [];
            $form = $models[$language->id];
            foreach ($form->questions as $questionModel) {
                if (!isset($questions[$questionModel->question]))
                    $dopQuestions[$questionModel->question] = $questionModel;
            }
            $form->questions = $dopQuestions;
            $models[$language->id] = $form;
        }

        return [
            'page' => '_step2',
            'pageParams' => [
                'models' => $models,
                'event_id' => $event->id,
                'languageSelectedList' => $languageSelectedList,
            ],
        ];
    }

    public function step3($id) {
        if (!$event = Event::findOne($id)) {
            throw new NotFoundHttpException();
        }

        $form = new EventForm3();
        $form->event = $event;

        return [
            'page' => '_step3',
            'pageParams' => [
                'model' => $form,
                'event_id' => $event->id,
            ],
        ];
    }


    public function actionUploadCover() {
        $request = Yii::$app->request;
        if ($request->isPost and ($id = intval($request->post('event_id')))) {
            if (!$event = Event::findOne($id)) {
                throw new NotFoundHttpException();
            }
            $form = new EventForm3();
            $form->event = $event;
            $form->cover = UploadedFile::getInstance($form, 'cover');
            if ($form->validate()) {
                $form->saveEventCover();
            }
        }
    }
    
    /*
     * action выбора языка (для формы step2)
     * */
    public function actionLangSelect(){
        $languageSelectedList = \Yii::$app->getRequest()->get('languageSelectedList');
        $selectedLanguage = \Yii::$app->getRequest()->get('selectedLanguage');
        if($languageSelectedList){
            $selectedLanguage = Lang::findOne($selectedLanguage);
            $languageSelectedList = Lang::find()->where(['name'=>$languageSelectedList])->all();
            $languageSelectedList = array_merge($languageSelectedList, [$selectedLanguage]);

            $languageData = ArrayHelper::map(Lang::getLangArr($languageSelectedList), 'id', 'name');

            return $this->renderAjax('_language_list', [
                'languageSelectedList' => $languageSelectedList,
                'languageData' => $languageData,
                'selectedLanguage' => $selectedLanguage->id,
            ]);
        }
    }

    /*
     * action удаления языка (для формы step2)
     * */
    public function actionLangDeselect(){
        $languageSelectedList = \Yii::$app->getRequest()->get('languageSelectedList');
        $selectedLanguage = \Yii::$app->getRequest()->get('selectedLanguage');
        $deletedLanguage = \Yii::$app->getRequest()->get('deletedLanguage');
        if($languageSelectedList){
            foreach($languageSelectedList as $k=>$language){
                if($language == $deletedLanguage)
                    unset($languageSelectedList[$k]);
                if(($deletedLanguage == $selectedLanguage || !$selectedLanguage) && isset($languageSelectedList[$k]))
                    $selectedLanguage = $languageSelectedList[$k];
            }

            $languageSelectedList = Lang::find()->where(['id'=>$languageSelectedList])->all();
            $languageData = ArrayHelper::map(Lang::getLangArr($languageSelectedList), 'id', 'name');

            return $this->renderAjax('_language_list', [
                'languageSelectedList' => $languageSelectedList,
                'languageData' => $languageData,
                'selectedLanguage' => $selectedLanguage,
            ]);
        }
    }

} 