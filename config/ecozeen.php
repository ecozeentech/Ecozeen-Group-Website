<?php

return [
    /*
     * Optional bootstrap administrator account. When both an email and
     * password are present in the environment, the roles seeder will
     * create (or ensure) this user exists with the Super Administrator
     * role. Leave unset in production after the first deployment.
     */
    'super_admin_email' => env('ECOZEEN_SUPER_ADMIN_EMAIL'),

    'media' => [
        'disk' => env('MEDIA_DISK', 'public'),
        'private_disk' => env('PRIVATE_DISK', 'local'),
        'max_image_size_kb' => 8192,
        'max_document_size_kb' => 10240,
        'allowed_image_mime_types' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'],
        'allowed_document_mime_types' => ['application/pdf'],
    ],

    'seo' => [
        'default_title_suffix' => ' | Ecozeen Group',
        'sitemap_cache_minutes' => 60,
    ],
];
