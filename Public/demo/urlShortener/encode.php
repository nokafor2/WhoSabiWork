<?php
    header('Content-type: text/javascript');

    $jsonData = array(
        'success' => false,
    );

    if (isset($_POST['url'])) {
        $url = $_POST['url'];

        // find the position of ? in the url
        $pos = strpos($url, '?');

        $path = substr($url, 0, $pos);
        $queryString = substr($url, $pos+1);
        $fullQueryString = substr($url, $pos);

        $pathEncode = rawurldecode($path);
        $queryStringEncode = urlencode($queryString);
        $fullQueryStringEncode = urlencode($fullQueryString);

        $queryStringShortener = generateRandomString();

        // Save the paths and query string shortener to the session
        $_SESSION["encodedpath"] = $pathEncode;
        $_SESSION[$queryStringShortener] = $queryStringEncode;

        // Send data to firebase to store
        // $databaseURL = "https://shortenurl-4ae4e-default-rtdb.firebaseio.com/urls/$queryStringShortener.json";
        // $data = [
        //     "pathEncode" => $pathEncode,
        //     "queryStringEncode" => $queryStringEncode,
        //     "queryStringShortener" => $queryStringShortener,
        // ];
        // $dataToJson = json_encode($data);

        // // Initialize cURL
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $databaseURL);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataToJson);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        // // Execute cURL request
        // $response = curl_exec($ch);
        // curl_close($ch);

        // Decode url data
        $pathDecode = rawurldecode($pathEncode);
        $queryStringDecode = urldecode($queryStringEncode);
        $fullQueryStringDecode = urldecode($fullQueryStringEncode);

        $jsonData["success"] = true;
        // $jsonData["response"] = $response;
        $jsonData["pathEncode"] = $pathEncode;
        $jsonData["queryStringEncode"] = $queryStringEncode;
        $jsonData["fullQueryStringEncode"] = $fullQueryStringEncode;
        $jsonData["queryStringShortener"] = $queryStringShortener;
        $jsonData["pathDecode"] = $pathDecode;
        $jsonData["queryStringDecode"] = $queryStringDecode;
        $jsonData["fullQueryStringDecode"] = $fullQueryStringDecode;
    }

    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($characters), 0, $length);
    }
    
    echo json_encode($jsonData);
?>