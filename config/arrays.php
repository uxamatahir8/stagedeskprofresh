<?php

return [
    'duration_types'              => [
        'weekly'  => 'Weekly',
        'monthly' => 'Monthly',
        'yearly'  => 'Yearly',
    ],

    'social_links'                => [
        'facebook'  => 'Enter Facebook Url',
        'twitter'   => 'Enter X Url',
        'instagram' => 'Enter Instagram Url',
        'linkedin'  => 'Enter Linkedin Url',
        'pintrest'  => 'Enter Pintrest Url',
    ],
    'status_colors'               => [
        'active'   => 'primary',
        'expired'  => 'warning',
        'canceled' => 'danger',
        'paused'   => 'info',
    ],
    'registerable_roles'          => [
        '2' => 'Company',
        '5' => 'Affiliate',
    ],
    'company_admin_allowed_roles' => [
        [
            'id'   => 3,
            'name' => 'Customer',
        ],
        [
            'id'   => 4,
            'name' => 'Artist',
        ],
    ],
];
