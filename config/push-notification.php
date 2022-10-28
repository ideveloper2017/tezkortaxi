<?php

return array(

    'IOSUser'     => array(
        'environment' => env('IOS_USER_ENV', 'development'),
        'certificate' => app_path().'/apns/user/pushNotificationUser.pem',
        'passPhrase'  => env('IOS_USER_PUSH_PASS', '1'),
        'service'     => 'apns'
    ),
    'IOSProvider' => array(
        'environment' => env('IOS_PROVIDER_ENV', 'development'),
        'certificate' => app_path().'/apns/provider/PushDriver.pem',
        'passPhrase'  => env('IOS_PROVIDER_PUSH_PASS', '1'),
        'service'     => 'apns'
    ),
    'AndroidUser' => array(
        'environment' => env('ANDROID_USER_ENV', 'production'),
        'apiKey'      => env('ANDROID_USER_PUSH_KEY', 'AAAAp-VyPow:APA91bGcuRGB2xU2lV-4nRzo3pO8pbneUKFpMkekmH0tQ4VRnGtzOUu9pgYCJXCCSVAMwHCYaS3iaMYBIHDScMEGBb2SAGCyJeIg9_ukczNAtzPAs_c1SECt412LYR2Uo9fCYWC-qE13'),
        'service'     => 'gcm'
    ),
    'AndroidProvider' => array(
        'environment' => env('ANDROID_PROVIDER_ENV', 'production'),
        'apiKey'      => env('ANDROID_PROVIDER_PUSH_KEY', 'AAAAQEfF90w:APA91bHGFOiwuoHMy6n41frcumtnh7hTYCOrjvD5QpzxVs7cX9P9BiAKESt-vhZQY6Eu0YFZSjxBpbEA8FnIXDmHryH3I2UdUe8MKiY0axHkCEn1Kz3BWRzaJKehRRPkPqJxWpFXG2sL'),
        'service'     => 'gcm'
    )

);