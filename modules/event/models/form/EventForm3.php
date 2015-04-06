<?php

namespace modules\event\models\form;

use modules\event\Module;
use modules\image\models\Image;
use yii\base\Model;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class EventForm3 extends Model {

    /**
     * @var \modules\event\models\Event Event
     */
    public $event;

    /**
     * @var UploadedFile|Null file attribute
     */
    public $cover = null;

    public $images = [];


    public function rules() {
        return [
            ['cover', 'file', 'mimeTypes'=> ['image/png', 'image/jpeg', 'image/gif'], 'wrongMimeType'=>Module::t('image', 'IMAGE_MESSAGE_FILE_TYPES').' jpg, png, gif'],
        ];
    }


    public function saveEventCover() {
        if (is_null($this->cover)) return;
        if (($tmpName = $this->cover->tempName) and ($ext = $this->cover->extension)) {
            if ($image = Image::GetByFile($tmpName, $ext)) {
                $this->event->image_id = $image->id;
                $this->event->save();
            }
        }
    }


    public function attributeLabels()
    {
        return [
            'cover' => Module::t('event', 'EVENT_COVER'),
        ];
    }
}