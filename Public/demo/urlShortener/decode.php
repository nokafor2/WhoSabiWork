<?php
    header('Content-type: text/javascript');

    $jsonData = array(
        'success' => false,
    );

    if (isset($_POST['url'])) {
        $url = $_POST['url'];

        $reversedUrl = strrev($url);
        $pos = strpos($reversedUrl, '/');
        // extract the token
        $token = substr($reversedUrl, 0, $pos);
        $queryStringShortener = strrev($token);
        
        $jsonData["success"] = true;
        $jsonData["token"] = $queryStringShortener;        
    }

    echo json_encode($jsonData);
?>