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
                'url'   =>['/sys/backend/domain/index']
            ],
            [
                'label' => 'Языки',
                'url'   => ['/sys/backend/language/index']
            ],
            [
                'label' => 'Настройки',
                'url'   => ['/sys/backend/setting/index']
            ],
            [
                'label' => 'Модули',
                'url'   => ['/sys/backend/module/index']
            ],

        ]
    ],
    'users' => [
        'label' => 'Пользователи',
        'url'   => '#',
        'items' => [
            [
                'label' => 'Пользователи',
                'url'   => ['/sys/backend/user/index']
            ],
            [
                'label' => 'Роли',
                'url'   => ['/sys/backend/rbac-role/index']
            ],
            [
                'label' => 'Привилегии',
                'url'   => ['/sys/backend/rbac-permission/index']
            ],

        ]
    ],
    'content' => [
        'label' => 'Контент',
        'url'   => '#',
        'items' => [
            [
                'label' => 'Категории',
                'url'   => ['/sys/backend/content/index']
            ],
            [
                'label' => 'Страницы',
                'url'   => ['/sys/backend/content/index']
            ],
            [
                'label' => 'Дополнительные поля',
                'url'   => ['/sys/backend/content/index']
            ],

        ]
    ]
];
