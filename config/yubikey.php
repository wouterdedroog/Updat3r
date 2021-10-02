<?php

/*
 * You will need an API Key from the official Yubikey Website
 * https://upgrade.yubico.com/getapikey/
 * */

return [
    'CLIENT_ID'       => env('YUBICO_CLIENT_ID'),
    'SECRET_KEY'      => env('YUBICO_SECRET_KEY'),
    'URL_LIST'        => [
        'api.yubico.com/wsapi/2.0/verify',
        'api2.yubico.com/wsapi/2.0/verify',
        'api3.yubico.com/wsapi/2.0/verify',
        'api4.yubico.com/wsapi/2.0/verify',
        'api5.yubico.com/wsapi/2.0/verify',
    ],
    'USER_AGENT'      => 'Updat3r Laravel',
    'HTTPS_VERIFY'    => true,
    'CAINFO_LOCATION' => '',
    'HTTP_PROXY'      => '',
];
