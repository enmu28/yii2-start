<?php
namespace app\models\elastic;
use Yii;

class Post extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return['id', 'title', 'content'];
    }
}
