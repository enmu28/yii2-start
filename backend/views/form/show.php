<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\DataColumn;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'vendor',
        'measurement_system',
        'price',
        'created_at',
        //'verification_token',
//        [
//            'class' => DataColumn::class, // this line is optional
//            'attribute' => 'price',
//            'format' => 'text',
//            'label' => 'Vendor',
//        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

