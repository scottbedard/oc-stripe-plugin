<?php

return [
    'navigation' => [
        'label' => 'Services',
        'plans' => 'Plans',
        'schedules' => 'Schedules',
    ],
    'permissions' => [
        'access_plans' => 'Manage plans',
        'access_schedules' => 'Manage billing schedules',
        'tab'          => 'Software services',
    ],
    'plans' => [
        'form' => [
            'description'       => 'Description',
            'general_tab'       => 'General',
            'is_active'         => 'Status',
            'is_active_comment' => 'When off, this plan cannot be purchased',
            'name'              => 'Name',
            'slug'              => 'Slug',
        ],
        'list' => [
            'created_at'       => 'Created',
            'id'               => 'ID',
            'is_active'        => 'Status',
            'is_active_filter' => 'Hide disabled',
            'is_active_off'    => 'Disabled',
            'is_active_on'     => 'Active',
            'name'             => 'Name',
            'slug'             => 'Slug',
            'updated_at'       => 'Last Updated',
        ],
        'list_title' => 'Manage Plans',
        'model'      => 'Plan',
    ],
];
