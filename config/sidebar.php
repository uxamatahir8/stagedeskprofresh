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
                'title' => 'My Bookings',
                'route' => 'bookings.index',
                'roles' => ['artist', 'dj'],
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
                'roles' => ['master_admin', 'company_admin', 'artist', 'dj'],
            ],
            [
                'title' => 'My Reviews',
                'route' => 'reviews.index',
                'roles' => ['customer'],
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
                'roles' => ['master_admin', 'company_admin', 'artist', 'dj'],
            ],
            [
                'title' => 'My Payments',
                'route' => 'payments.index',
                'roles' => ['customer'],
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
        'title'   => 'Affiliate Program',
        'icon'    => 'badge-dollar-sign',
        'submenu' => [
            [
                'title' => 'My Referrals',
                'route' => 'affiliate.referrals',
                'roles' => ['affiliate'],
            ],
            [
                'title' => 'Commissions',
                'route' => 'affiliate.commissions',
                'roles' => ['affiliate'],
            ],
            [
                'title' => 'Referral Links',
                'route' => 'affiliate.referral-links',
                'roles' => ['affiliate'],
            ],
            [
                'title' => 'Performance',
                'route' => 'affiliate.performance',
                'roles' => ['affiliate'],
            ],
        ],
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
                'title' => 'My Blogs',
                'route' => 'blogs.list',
                // All authenticated users can create blogs
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
        'title' => 'Settings',
        'icon'  => 'settings',
        'route' => 'settings',
        'roles' => ['master_admin'],
    ],
];
