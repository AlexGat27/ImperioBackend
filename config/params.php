<?php

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'jwt' => [
        'issuer' => 'imperio',  //name of your project (for information only)
        'audience' => 'imperio_audience',  //description of the audience, eg. the website using the authentication (for info only)
        'id' => 'Shurikgat2704',  //a unique identifier for the JWT, typically a random string
        'key' => 'Imperio-Secret-Key',
        'access_expire' => 3600 * 24,
        'refresh_expire' => 3600 * 24 * 30//the short-lived JWT token is here set to expire after 5 min.
    ],
];
