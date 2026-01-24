<?php

return [
    [
        'title' => 'Dashboard',
        'icon'  => 'earth',
        'route' => 'dashboard',
        // roles omitted → visible to all
    ],
    [
        'title'   => 'Booking Management',
        'icon'    => 'calendar-check',
        'submenu' => [
            [
                'title' => 'Booking Requests',
                'route' => 'bookings.index',
                'roles' => ['master_admin', 'company_admin', 'customer'],
            ],
            [
                'title' => 'Event Types',
                'route' => 'event-types',
                'roles' => ['master_admin'],
            ],
        ],
    ],
    [
        'title' => 'Artists',
        'icon'  => 'music',
        'route' => 'artists.index',
        'roles' => ['master_admin', 'company_admin'],
    ],
    [
        'title'   => 'Reviews & Ratings',
        'icon'    => 'star',
        'submenu' => [
            [
                'title' => 'All Reviews',
                'route' => 'reviews.index',
            ],
            [
                'title' => 'Featured Reviews',
                'route' => 'testimonials',
                'roles' => ['master_admin'],
            ],
        ],
    ],
    [
        'title'   => 'Financial',
        'icon'    => 'credit-card',
        'submenu' => [
            [
                'title' => 'Payments',
                'route' => 'payments.index',
                'roles' => ['master_admin', 'company_admin'],
            ],
            [
                'title' => 'Subscriptions',
                'route' => 'subscriptions.index',
                'roles' => ['master_admin', 'company_admin'],
            ],
            [
                'title' => 'Packages',
                'route' => 'packages',
                'roles' => ['master_admin'],
            ],
        ],
    ],
    [
        'title' => 'Companies',
        'icon'  => 'building',
        'route' => 'companies',
        'roles' => ['master_admin'],
    ],
    [
        'title' => 'Users',
        'icon'  => 'users',
        'route' => 'users',
        'roles' => ['master_admin', 'company_admin'],
    ],
    [
        'title'   => 'Content Management',
        'icon'    => 'file-text',
        'submenu' => [
            [
                'title' => 'Blog Categories',
                'route' => 'blog-categories',
                'roles' => ['master_admin'],
            ],
            [
                'title' => 'Blogs',
                'route' => 'blogs.list',
                'roles' => ['master_admin'],
            ],
        ],
    ],
    [
        'title' => 'Support Center',
        'icon'  => 'headset',
        'route' => 'support.tickets',
    ],
    [
        'title' => 'Activity Logs',
        'icon'  => 'activity',
        'route' => 'activity-logs.index',
        'roles' => ['master_admin', 'company_admin'],
    ],
    [
        'title' => 'Notifications',
        'icon'  => 'bell',
        'route' => 'notifications.index',
    ],
    [
        'title' => 'Settings',
        'icon'  => 'settings',
        'route' => 'settings',
        'roles' => ['master_admin'],
    ],
];
