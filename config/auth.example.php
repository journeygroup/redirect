<?php

use Journey\Authentication;

Authentication::config([
    'users' => array(
        [
            'username' => '',           // your-username-here
            'password' => '',           // your-hash-here (to get one just: md5 -s 'your-password')
            'level'    => 1             // your-level-here
        ]
    )
]);
