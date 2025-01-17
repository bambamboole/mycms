<?php

return [

    'item' => [
        'custom_text' => 'Custom Text',
        'custom_link' => 'Custom Link',
    ],
    'resource' => [
        'navigation-group' => 'Admin',
        'navigation-icon' => 'heroicon-o-users',
        'navigation-label' => 'Menus',
        'fields' => [
            'name' => [
                'label' => 'Name',
            ],
            'is_visible' => [
                'label' => 'Visible',
                'visible' => 'Visible',
                'hidden' => 'Hidden',
            ],
            'locations' => [
                'label' => 'Locations',
                'empty' => 'Empty',
            ],
            'items' => [
                'label' => 'Items',
            ],
        ],
        'actions' => [
            'add' => [
                'label' => 'Add to Menu',
            ],
            'locations' => [
                'label' => 'Locations',
                'heading' => 'Manage Locations',
                'description' => 'Choose which menu appears at each location.',
                'submit' => 'Update',
                'form' => [
                    'location' => [
                        'label' => 'Location',
                    ],
                    'menu' => [
                        'label' => 'Assigned Menu',
                    ],
                ],
                'empty' => [
                    'heading' => 'No locations registered',
                ],
            ],
        ],
    ],
];
