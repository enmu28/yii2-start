<?php

namespace api\modules\cms\models;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

//    public function rules()
//    {
//        return [
//            [['file'], 'file'],
//        ];
//    }

//    public function rules()
//    {
//        return [
//            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
//        ];
//    }
//
//    public function upload()
//    {
//        if ($this->validate()) {
//            $this->file->saveAs('uploads/' . $this->file->baseName . '.' . $this->file->extension);
//            return true;
//        } else {
//            return false;
//        }
//    }
}