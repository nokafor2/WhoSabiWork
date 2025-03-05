<?php

    $decodeUrl = 'https://www.shorturl.com/91hWJw';

    $reversedUrl = strrev($decodeUrl);
    $pos = strpos($reversedUrl, '/');
    echo "The / position is: ".$pos;
    // extract the token
    $token = substr($reversedUrl, 0, $pos);
    $revToken = strrev($token);
    echo "\nThe token is: $revToken";
?>