<?php
    $url = 'www.whosabiwork.com?val1=user&val2=admin';

    // Get the length of the string
    $len = strlen($url);
    
    // find the position of ? in the url
    $pos = strpos($url, '?');

    print_r([$len, $pos]) ;

    $domain = substr($url, 0, $pos);
    $domainParams = substr($url, $pos);

    $domainEncode = rawurldecode($domain);
    $domainParamsEncode = urlencode($domainParams);

    $domainDecode = rawurldecode($domainEncode);
    $domainParamsDecode = urldecode($domainParamsEncode);

    echo "\nUrl domain: ".$domain."\n";
    echo "Url params: ".$domainParams."\n";
    
    echo "\nEncoded Url: ".$domainEncode."\n";
    echo "Encoded Url params: ".$domainParamsEncode."\n";

    echo "\nDecoded Url: ".$domainDecode."\n";
    echo "Decoded Url params: ".$domainParamsDecode;

    
?>