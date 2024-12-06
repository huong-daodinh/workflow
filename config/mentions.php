<?php

use App\Models\User;

return [
    'pools' => [
        'users' => [
            // Model that will be mentioned.
            'model' => User::class,

            // The column that will be used to search the model by the parser.
            'column' => 'id',

            // The route used to generate the user link.
            'route' => '/user/profile/',

            // Notification class to use when this model is mentioned.
            // 'notification' => App\Notifications\MentionNotification::class,
        ]
    ]
];
