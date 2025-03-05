<?php



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.js"></script>
    <title>Shorten Url Script</title>
</head>
<body>
    <form>
        <label for="urlInput">Enter Url to Shorten</label>
        <input id="urlInput" type="text" name="urlInput">
        <button id="submitBtn" type="submit">Shorten Url</button>
    </form>
    <p id="feedback"></p>
    <a id="shortUrl" href="#"></a>
    <br/>
    
    <button id="getOriginalUrl" style="display: none;"> Click to get back Original Url</button>
    <p id="originalUrl"></p>

    <script>
        $(document).ready(function() {
            $('#submitBtn').click(async function(event) {
                event.preventDefault();

                $('#feedback').html('');
                var url = $('#urlInput').val();

                if (url.length === 0) {
                    $('#feedback').html('Please eneter a URL!');
                    return false;
                }

                function fetchData() {
                    return $.ajax({
                        url: "encode.php",
                        type: "POST",
                        dataType: 'json',
                        data: {url: url},
                        success: function(data) {
                            console.log(data);
                            $('#feedback').text("Encoded url: "+data.pathEncode+data.fullQueryStringEncode);
                            var shortUrl = "https://www.shorturl.com/"+data.queryStringShortener;
                            var shortUrlHref = data.pathEncode+"?"+data.queryStringEncode;
                            $('#shortUrl').html(shortUrl);
                            $('#shortUrl').attr('href', shortUrl);
                            $('#getOriginalUrl').css('display', 'block');
                        }
                    });
                }

                let ajaxData;

                fetchData().then(response => {
                    ajaxData = response;
                    console.log(ajaxData);

                    const response2 = fetch('https://shortenurl-4ae4e-default-rtdb.firebaseio.com/urls/'+ajaxData.queryStringShortener+'.json', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            'pathEncode': ajaxData.pathEncode, 
                            'queryStringEncode': ajaxData.queryStringEncode, 
                            'fullQueryStringEncode': ajaxData.fullQueryStringEncode, 
                            'queryStringShortener': ajaxData.queryStringShortener, 
                            'pathDecode': ajaxData.pathDecode, 
                            'queryStringDecode': ajaxData.queryStringDecode,
                            'fullQueryStringDecode': ajaxData.fullQueryStringDecode,
                        })
                    });

                    if (response2.ok) {
                        const responseData = response2.json();
                        console.log(responseData);
                    }
                });

                
            });

            $('#getOriginalUrl').click(function(event) {
                event.preventDefault();

                var shortUrl = $('#shortUrl').html();
                console.log(shortUrl);
                var ajaxData;
                var urlData;

                function fetchData() {
                    return $.ajax({
                        url: "decode.php",
                        type: "POST",
                        dataType: 'json',
                        data: {url: shortUrl},
                        success: function(data) {
                            console.log(data);
                        }
                    });
                }

                fetchData().then(response => {
                    ajaxData = response;
                    var queryStringToken = ajaxData.token;

                    fetch('https://shortenurl-4ae4e-default-rtdb.firebaseio.com/urls/'+queryStringToken+'.json').then(function(response2) {
                        if (response2.ok) {
                            return response2.json();
                        }
                    }).then(function(data) {
                        console.log(data);
                        const urlData = [];
                        for (const id in data) {
                            urlData.push({
                                queryStringShortener: data[id].queryStringShortener,
                                pathEncode: data[id].pathEncode,
                                queryStringEncode: data[id].queryStringEncode,
                                fullQueryStringEncode: data[id].fullQueryStringEncode,
                                pathDecode: data[id].pathDecode,
                                queryStringDecode: data[id].queryStringDecode,
                                fullQueryStringDecode: data[id].fullQueryStringDecode,
                            });

                            // Output the original url on the webpage
                            $('#originalUrl').html(data[id].pathDecode+data[id].fullQueryStringDecode);
                        }
                        this.urlData = urlData;
                        // console.log(this.urlData);
                    });
                });
            });
        });
    </script>
</body>
</html>