<?php

/**
 * @author Denis Utkin <dizirator@gmail.com>
 * @link   https://github.com/dizirator
 */

use yii\helpers\Url;

return [
    'sys' => [
        'label' => 'Система',
        'url'   => '#',
        'items' => [
            [
                'label' => 'Домены',
                'url'   => Url::to(['/sys/backend/domain/index'])
            ],
            [
                'label' => 'Языки',
                'url'   => Url::to(['/sys/backend/language/index'])
            ],
            [
                'label' => 'Настройки',
                'url'   => Url::to(['/sys/backend/setting/index'])
            ],
            [
                'label' => 'Модули',
                'url'   => Url::to(['/sys/backend/module/index'])
            ],

        ]
    ],
    'users' => [
        'label' => 'Пользователи',
        'url'   => '#',
        'items' => [
            [
                'label' => 'Пользователи',
                'url'   => Url::to(['/sys/backend/user/index'])
            ],
            [
                'label' => 'Роли',
                'url'   => Url::to(['/sys/backend/role/index'])
            ],
            [
                'label' => 'Привилегии',
                'url'   => Url::to(['/sys/backend/permission/index'])
            ],

        ]
    ],
    'content' => [
        'label' => 'Контент',
        'url'   => '#',
        'items' => [
            [
                'label' => 'Категории',
                'url'   => Url::to(['/sys/backend/content/index'])
            ],
            [
                'label' => 'Страницы',
                'url'   => Url::to(['/sys/backend/content/index'])
            ],
            [
                'label' => 'Дополнительные поля',
                'url'   => Url::to(['/sys/backend/content/index'])
            ],

        ]
    ]
];
