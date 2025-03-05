# Url Shorten App

### Algorithm Description
* The url shorten app takes in a long url and shortens it. It works by taking the long url which is extracted through javaScript/jQuery from the input field and then sent through a post request with Ajax to an end point called encode.php. In the encode file, the url is split between the path and the query string, so that they can be encoded properlly. A random alpha numeric character is generated for the query string of the url. These parameters are returned to Ajax and it is saved to firebase with a seperate fetch request. The new domain name and alpha numeric character is then used to generate the new short url which is displayed on the DOM.
* A button is used to retrieve the original url when clicked. This is done this way as a real url can't be used, and to avoid the difficulty of accessing the get request data when it's clicked. Hence, a button is used to retrieve the original url.
* When the retrieve url button is clicked, the generated short url is gotten from the DOM with javaScript/jQuery and a post request is sent to an end point decode.php. In the decode file, the short url token appended is extracted and sent back to Ajax where a get request is sent to the firebase using the token to retrive the saved url data. The original url is then displayed on the DOM.
* The folder called urlShortener contains the urlShortener.php, encode.php, decode.php, and readme.md file. To run the program execute the urlShortener.php file.

### How to shorten a long url
* In the url input field, enter the long url to be shorten.
* The encoded url and shortened url would be displayed below.

### How to retrieve the original url
* To retrieve the original url, click on the button "Click to get Original Url".
* The original url would be displayed below.
