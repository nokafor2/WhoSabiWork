<?php

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($characters), 0, $length);
}

echo generateRandomString(6);

echo "\n\n";

function generateRandomString2($length = 10) {
    return substr(bin2hex(random_bytes($length)), 0, $length);
}

echo generateRandomString2(6);

echo "\n\n";

function generateRandomString3($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

echo generateRandomString3(6);
?>