<?php
//
//namespace api\modules\cms\controllers;
//
//use api\modules\cms\models\UploadForm;
//use api\modules\cms\traits\ApiTraits;
//use yii\rest\Controller;
//use yii\web\UploadedFile;
//use Yii;
//
//
//class UploadController extends Controller
//{
//    public function actionUploadFile()
//    {
////        @chmod('uploads/', 0777);
//        $file = UploadedFile::getInstanceByName('file');
//
////        var_dump($file->type); exit();          //type ~ image/jpeg
////        var_dump($file->getBaseName()); exit();         //name file ~ kjsadkfjkf
////        var_dump($file->getExtension()); exit();        // extension file ~ jpg
//        $file->saveAs('uploads/' . $file->name);
//    }
//
////    public function action
//}
