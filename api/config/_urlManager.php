<?php
return [
    'class' => yii\web\UrlManager::class,
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
        ['pattern' => 'cms/resource/<type:(.*)>', 'route' => 'cms/resource/index'],
        ['pattern' => 'cms/newspaper/<type:(.*)>', 'route' => 'cms/newspaper/index'],
        ['pattern' => 'cms/article/<type:(.*)>', 'route' => 'cms/article/index'],
        ['pattern' => 'cms/user/<type:(.*)>', 'route' => 'cms/user/index'],
    ]
];