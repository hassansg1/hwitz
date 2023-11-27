<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'mnt' => [
            'driver' => 'local',
            'root' => '/mnt',
            'visibility' => 'public',
        ],

        's3_workorder' => [
            'driver' => 's3',
            'key' => 'AKIAIMCMNASOV7LS5DQA',
            'secret' => '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',
            'region' => 'us-west-2',
            'bucket' => 'musworkorder',
        ],

        's3_techsupport' => [
            'driver' => 's3',
            'key' => 'AKIAIMCMNASOV7LS5DQA',
            'secret' => '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',
            'region' => 'us-west-2',
            'bucket' => 'mustechsupport',
        ],

        's3_orientation' => [
            'driver' => 's3',
            'key' => 'AKIAIMCMNASOV7LS5DQA',
            'secret' => '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',
            'region' => 'us-west-2',
            'bucket' => 'musvideo',
        ],

        's3_docs' => [
            'driver' => 's3',
            'key' => 'AKIAIMCMNASOV7LS5DQA',
            'secret' => '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',
            'region' => 'us-west-2',
            'bucket' => 'musdocs',
        ],

        'smiota' => [
            'driver' => 'sftp',
            'host' => env('SMIOTA_HOST'),
            'username' => env('SMIOTA_USER'),
            'password' => env('SMIOTA_PASS'),
            'root' => env('SMIOTA_ROOT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
