<?php
    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($characters), 0, $length);
    }
    
    $url = "https://www.whosabiwork.com?id=10&admnin=joe";

    // find the position of ? in the url
    $pos = strpos($url, '?');

    $path = substr($url, 0, $pos);
    $queryString = substr($url, $pos+1);

    $pathEncode = rawurldecode($path);
    $queryStringEncode = urlencode($queryString);

    $queryStringShortener = generateRandomString();

    // Send data to firebase to store
    $databaseURL = "https://shortenurl-4ae4e-default-rtdb.firebaseio.com/urls/$queryStringShortener.json";
    $data = [
        "pathEncode" => $pathEncode,
        "queryStringEncode" => $queryStringEncode,
        "queryStringShortener" => $queryStringShortener,
    ];
    $dataToJson = json_encode($data);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $databaseURL);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataToJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    // Execute cURL request
    $response = curl_exec($ch);
    curl_close($ch);

    // Output response
    echo $response;

?>