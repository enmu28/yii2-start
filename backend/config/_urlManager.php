<?php
return [
    'class' => yii\web\UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        ['pattern' => 'hello/dongoc/<type:(.*)>', 'route' => 'hello/dongoc/index'],
    ]
];
