<?php

return [
    [
        'title' => 'Dashboard',
        'icon' => 'earth',
        'route' => 'dashboard',
        // roles omitted → visible to all
    ],
    [
        'title' => 'Packages',
        'icon' => 'package-open',
        'route' => 'packages',
        'roles' => ['master_admin'],
    ],
    [
        'title' => 'Companies',
        'icon' => 'building',
        'route' => 'companies',
        'roles' => ['master_admin'],
    ],
    [
        'title' => 'Testimonials',
        'icon' => 'star',
        'route' => 'testimonials',
        'roles' => ['master_admin'],
    ],
    [
        'title' => 'Users',
        'icon' => 'users',
        'route' => 'users',
        'roles' => ['master_admin', 'company_admin'],
    ],
    [
        'title' => 'Blogs CMS',
        'icon' => 'life-buoy',
        'submenu' => [
            [
                'title' => 'Blog Categories',
                'route' => 'blog-categories',
            ],
            [
                'title' => 'Blogs',
                'route' => 'blogs.list',
            ],
            [
                'title' => 'Blog Comments',
                'route' => 'testimonials', // placeholder or real route
            ],
        ],
    ],
    [
        'title' => 'Settings',
        'icon' => 'cog',
        'route' => 'settings',
        'roles' => ['master_admin'],
    ],
];
