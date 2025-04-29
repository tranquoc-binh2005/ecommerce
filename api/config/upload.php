<?php
return [
    'image' => [
        'disk' => 'public',
        'max_size' => 5 * 1024,
        'allowed_mime_types' => [
            'jpeg', 'jpg', 'png', 'gif', 'webp',
        ],
        'base_path' => 'uploads',
        'pipelines' => [
            'default' => [
                'generate_filename' => [
                    'enabled' => true,
                ],
                'resize' => [
                    'enabled' => false,
                ],
                'optimize' => [
                    'enabled' => true,
                    'quality' => 75,
                ],
                'encode' => [
                    'enabled' => true,
                ],
                'storage' => [
                    'enabled' => true,
                ]
            ],
        ],
    ]
];
