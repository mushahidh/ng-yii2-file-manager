<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [        
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule', 
                    'controller' => ['v1/country','v1/software'],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],

           
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/software'],
                    'pluralize' => false,
                    'extraPatterns' => [
                      'POST create-directory' => 'create-directory', 
                    'OPTIONS create-directory' => 'options'

                      // 'xxxxx' refers to 'actionXxxxx'
                    ],
                ] ,

                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/software'],
                    'pluralize' => false,
                    'extraPatterns' => [
                      'GET list-directory' => 'list-directory', 
                    'OPTIONS list-directory' => 'options'

                      // 'xxxxx' refers to 'actionXxxxx'
                    ],
                ],
                      [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/upload'],
                    'pluralize' => false,
                    'extraPatterns' => [
                      'POST upload' => 'upload', 
                    'OPTIONS upload' => 'options'

                      // 'xxxxx' refers to 'actionXxxxx'
                    ],
                ], 
                      [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/user'],
                    'pluralize' => false,
                    'extraPatterns' => [
                      'POST login' => 'login', 
                    'OPTIONS login' => 'options'

                      // 'xxxxx' refers to 'actionXxxxx'
                    ],
                ], 
                  ],     
        ]
    ],
    'params' => $params,
];



