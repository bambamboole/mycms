<?php

return [
    'environment_indicator' => [
        'enabled' => true,
    ],
    'application_health' => [
        'enabled' => true,
    ],
    'models' => [
        'user' => 'App\Models\User',
    ],
    'settings' => [
        \Bambamboole\MyCms\Settings\GeneralSettings::class,
        \Bambamboole\MyCms\Settings\SocialSettings::class,
    ],
    'theme' => [
        'menus' => [
            'header' => 'Header',
            'footer' => 'Footer',
        ],
        'views' => [
            'page_view' => 'mycms::themes.default.pages.show',
            'post_index_view' => 'mycms::themes.default.posts.index',
            'post_view' => 'mycms::themes.default.posts.show',
            'tag_view' => 'mycms::themes.default.tags.show',
        ],
    ],
];
