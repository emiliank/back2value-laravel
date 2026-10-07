<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Every public page is served under one of these locale prefixes. The
    | first entry is the default used when a visitor arrives on a bare URL
    | or when their browser asks for a language we do not publish.
    |
    */

    'default' => 'sq',

    'fallback' => 'sq',

    'available' => [
        'sq' => [
            'name' => 'Shqip',
            'native' => 'Shqip',
            'code' => 'SQ',
            'flag' => '🇦🇱',
            'og_locale' => 'sq_AL',
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'code' => 'EN',
            'flag' => '🇬🇧',
            'og_locale' => 'en_GB',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | Where the visitor's explicit language choice is remembered, so returning
    | visitors keep their language across visits.
    |
    */

    'session_key' => 'site_locale',

];
